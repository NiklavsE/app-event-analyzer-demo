<?php

namespace App\Notifications;

use App\Models\AppEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SecurityEventTriggered extends Notification
{
    use Queueable;

    use Queueable;

    public function __construct(
        private AppEvent $event
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->error()
            ->content('Threshold Reached Alert!')
            ->attachment(function ($attachment) {
                $attachment
                    ->title('Event: ' . $this->event->name)
                    ->content('The threshold has been reached for this event.')
                    ->timestamp($this->event->timestamp);
            });
    }
}
