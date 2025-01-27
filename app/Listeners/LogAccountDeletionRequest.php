<?php

namespace App\Listeners;

use App\Enums\DeletionLogStatus;
use App\Events\AccountDeletionRequested;
use App\Models\DeletionLog;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogAccountDeletionRequest implements ShouldQueue
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
    public function handle(AccountDeletionRequested $event): void
    {
        DeletionLog::create([
            'user_id' => $event->user->getKey(),
            'action' => DeletionLogStatus::REQUESTED->value,
            'requested_at' => now(),
            'status' => DeletionLogStatus::PENDING->value,
        ]);

    }
}
