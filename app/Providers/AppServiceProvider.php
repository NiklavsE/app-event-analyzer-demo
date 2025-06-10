<?php

namespace App\Providers;

use App\Services\EventReaction\EventReactionService;
use App\Services\Notifier\Interfaces\NotifierInterface;
use App\Services\Notifier\SlackNotifier;
use App\Services\RuleEval\Interfaces\RuleEvaluatorInterface;
use App\Services\RuleEval\Threshold\AnomalyScoreThresholdBasedRuleEvaluator;
use Aws\SageMakerRuntime\SageMakerRuntimeClient;
use Aws\Sdk;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Sdk::class, function () {
            return new Sdk(config('services.aws'));
        });

         $this->app->singleton(SageMakerRuntimeClient::class, function ($app) {
             $sdk = $app->make(Sdk::class);
             return $sdk->createSageMakerRuntime();
         });

        $this->app->bind(NotifierInterface::class, function () {
            return new SlackNotifier(
                config('services.slack.notifications.test_webhook_url')
            );
        });

        $this->app->bind(EventReactionService::class, function ($app) {
            return new EventReactionService(
                $app->make(NotifierInterface::class),
            );
        });

        $this->app->bind(RuleEvaluatorInterface::class, function ($app) {
            return new AnomalyScoreThresholdBasedRuleEvaluator(
                $app->make(EventReactionService::class),
                $app->make(SageMakerRuntimeClient::class),
                config('anomaly.inference_endpoint_name'),
                config('anomaly.event_threshold'),
                config('anomaly.anomaly_score_threshold'),
                config('user_activity_levels.static_mapping')
            );
        });
    }
}
