<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// placeholder route for demo purposes
Route::post('/demo/event', function (Request $request) {
    $validationRules = [
        'name'      => 'required|string',
        'context'   => 'required|array',
    ];

    $eventData = $request->validate($validationRules);

    $event = (new \App\Models\AppEvent())->fill($eventData);
    app(\App\Services\EventProcessor\EventProcessorService::class)->execute($event);

    return response()->json(['status' => 'event queued']);
});
