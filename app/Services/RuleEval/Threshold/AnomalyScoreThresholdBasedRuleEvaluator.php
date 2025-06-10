<?php

namespace App\Services\RuleEval\Threshold;

use App\Models\AppEvent;
use App\Models\Rule;
use App\Models\SecurityResponse;
use App\Services\EventReaction\EventReactionService;
use App\Services\RuleEval\Interfaces\RuleEvaluatorInterface;
use Aws\Exception\AwsException;
use Aws\SageMakerRuntime\SageMakerRuntimeClient;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class AnomalyScoreThresholdBasedRuleEvaluator implements RuleEvaluatorInterface
{
    public function __construct(
        private EventReactionService $eventReactionService,
        private SageMakerRuntimeClient $sageMakerRuntimeClient,
        private string $inferenceEndpointName,
        private int $minimumThresholdCount,
        private float $anomalyScoreThreshold,
        private array $userDiscriminatorMapper,
    ) {
    }

    public function evaluate(AppEvent $event): void
    {
        try {
            $rule = Rule::where('event_name', $event->name)->firstOrFail();

            $count = DB::table('app_events')
                ->where('name', $event->name)
                ->where('created_at', '>=', Carbon::now()->subMinutes(10))
                ->count();

            if ($count < $this->minimumThresholdCount) {
                return;
            }

            // Check for existing security response in last 10 minutes
            $existingResponse = SecurityResponse::where('rule_id', $rule->id)
                ->where('created_at', '>=', Carbon::now()->subMinutes(10))
                ->exists();

            if ($existingResponse) {
                Log::info('Skipping evaluation - recent security response exists', [
                    'event_name' => $event->name,
                    'rule_id' => $rule->id
                ]);

                return;
            }

            try {
                $result = $this->sageMakerRuntimeClient->invokeEndpoint([
                    'EndpointName' => $this->inferenceEndpointName,
                    // or JSON, however, CSV is easier to parse
                    'ContentType'  => 'text/csv',
                    'Body'         => $this->constructPayload($event, $count)
                ]);

                $response = json_decode($result['Body']->getContents(), true);
                $anomalyScore = $response['scores'][0]['score'] ?? null;

                Log::info('Invoked SageMaker endpoint', [
                    'input' => $this->constructPayload($event, $count),
                    'score' => $response,
                ]);

                if ($anomalyScore === null) {
                    Log::error('Anomaly score not found in response', ['response' => $response]);
                    return;
                }

                if ($this->anomalyScoreThreshold <= $anomalyScore) {
                    $this->eventReactionService->react($event, $rule, $response);
                }
            } catch (AwsException $e) {
                Log::error('SageMaker endpoint invocation failed', [
                    'error'    => $e->getMessage(),
                    'event_id' => $event->id,
                ]);
                throw $e;
            }

        } catch (ModelNotFoundException) {
            // no rule found for this event, skipping evaluation
            return;
        } catch (\Exception $e) {
            Log::error('Error evaluating rule', [
                'error'    => $e->getMessage(),
                'event_id' => $event->id
            ]);

            throw $e;
        }
    }

    private function constructPayload(AppEvent $event, int $count): string
    {
        $now = Carbon::now();

        $payload = [
            (int) $now->format('G'), // hour_of_day
            (int) $now->format('N'), // day_of_week
            $this->isBusinessHour($now), // is_business_hour
            !$now->isWeekend(), // is_workday
            $count, // event_count_last_10m
            $this->userDiscriminatorMapper[$event->user_id] ?? 5, // discriminator
        ];

        return implode(',', $payload);
    }

    private function isBusinessHour(Carbon $time): bool
    {
        $hour = (int) $time->format('G');
        return $hour >= 9 && $hour < 17;
    }

}
