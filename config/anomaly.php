<?php

return [
    'event_threshold'         => env('MINIMUM_EVENT_COUNT_THRESHOLD'),
    'anomaly_score_threshold' => env('MINIMUM_ANOMALY_SCORE_THRESHOLD'),
    'inference_endpoint_name' => env('INFERENCE_ENDPOINT_NAME'),
];
