<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use App\Events\UserAuthenticated;
use Illuminate\Support\Facades\Log;

class ProcessUserAuthentication
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserAuthenticated $event): void
    {
        $userId = $event->userId;
        Log::info("UserAuthenticated event received for user ID: " . $event->userId);
        Cache::put("user_auth_status_{$userId}", true, 60);
    }
}
