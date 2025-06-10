<?php

namespace App\Services\Notifier\Interfaces;

use Illuminate\Notifications\Notification;

interface NotifierInterface
{
    public function notify(Notification $notification): void;
}
