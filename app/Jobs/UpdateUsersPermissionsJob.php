<?php

namespace App\Jobs;

use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUsersPermissionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Role $role)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $permissions = $this->role->permissions;

        $users = $this->role->users()->cursor();

        foreach ($users as $user) {
            $user->syncPermissions($permissions);
        }

    }
}
