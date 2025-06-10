<?php

namespace App\Services\EventReaction;

use App\Models\AppEvent;
use App\Models\Rule;
use App\Models\SecurityResponse;
use App\Notifications\SecurityEventTriggered;
use App\Services\Notifier\Interfaces\NotifierInterface;
use App\Services\RuleEval\Interfaces\RuleEvaluatorInterface;

final readonly class EventReactionService
{
    public function __construct(
        private NotifierInterface $notifier
    ) {
    }

    public function react(
        AppEvent $event,
        Rule $triggeredRule,
        array $metadata,
    ): void
    {


        SecurityResponse::create([
            'rule_id' => $triggeredRule->id,
            'metadata' => $metadata
        ]);

        $this->notifier->notify(new SecurityEventTriggered($event));
    }

}
