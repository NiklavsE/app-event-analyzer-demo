<?php

namespace App\Livewire;

use App\Models\AppEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TotalEventGraph extends Component
{
    public array $eventData;

    public function mount()
    {

        $this->eventData = AppEvent::select(DB::raw("strftime('%Y-%m-%d %H:%M:00', created_at) as minute"), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subMinutes(1440))
            ->groupBy('minute')
            ->orderBy('minute')
            ->get()
            ->mapWithKeys(fn($item) => [$item->minute => $item->count])->toArray();
    }

    public function render()
    {
        return view('livewire.total-event-graph');
    }
}
