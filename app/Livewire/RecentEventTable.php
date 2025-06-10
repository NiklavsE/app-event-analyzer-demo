<?php

namespace App\Livewire;

use App\Models\AppEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RecentEventTable extends Component
{
    public Collection $events;
    public ?AppEvent $selectedEvent = null;

    public function mount()
    {
        $this->events = AppEvent::limit(20)->orderBy('id', 'desc')->get();
    }

    public function viewEvent($eventId)
    {
        $this->selectedEvent = AppEvent::find($eventId);
    }


    public function render()
    {
        return view('livewire.recent-event-table');
    }
}
