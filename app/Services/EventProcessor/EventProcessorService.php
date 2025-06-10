<?php

namespace App\Services\EventProcessor;

use App\Models\AppEvent;
use App\Services\RuleEval\Interfaces\RuleEvaluatorInterface;

final readonly class EventProcessorService
{
    public function __construct(
        private RuleEvaluatorInterface $evaluator
    ) {
    }

    public function execute(AppEvent $event): void
    {
        $event->save();

        $this->evaluator->evaluate($event);
    }

}
