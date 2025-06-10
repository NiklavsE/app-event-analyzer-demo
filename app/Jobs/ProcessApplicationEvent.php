<?php

namespace App\Jobs;

use App\Models\AppEvent;
use App\Services\EventProcessor\EventProcessorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;

class ProcessApplicationEvent implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public function __construct(public array $event)
    {
    }

    public function handle(EventProcessorService $service): void
    {
        $service->execute(new AppEvent($this->event));
    }
}
