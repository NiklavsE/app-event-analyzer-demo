<?php

namespace App\Livewire;

use App\Models\SecurityResponse;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class RecentTriggeredRulesTable extends Component
{
    public Collection $triggeredRules;

    public function mount()
    {
        $this->triggeredRules = SecurityResponse::limit(10)->orderBy('id', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.recent-triggered-rules-table');
    }
}
