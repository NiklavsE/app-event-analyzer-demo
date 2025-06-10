<?php

namespace App\Services\RuleEval\Interfaces;

use App\Models\AppEvent;

interface RuleEvaluatorInterface
{
    public function evaluate(AppEvent $event): void;
}
