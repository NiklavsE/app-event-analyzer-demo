<?php

namespace App\Services\Notifier;

use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Notifications\Notification;

class SlackNotifier implements Interfaces\NotifierInterface
{
    public function __construct(
        private readonly string $slackWebhookUrl
    ) {

    }

    public function notify(Notification $notification): void
    {
        NotificationFacade::route('slack', $this->slackWebhookUrl)
            ->notify($notification);
    }
}
