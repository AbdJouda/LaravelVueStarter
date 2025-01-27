<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PurgeSoftDeletedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete records older than the grace period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::onlyTrashed()
            ->whereDate('deleted_at', '<', now()->subDays(30))
            ->get();

        foreach ($users as $user) {
            $user->forceDelete();
        }

        $this->info('Old soft-deleted users purged.');

    }
}
