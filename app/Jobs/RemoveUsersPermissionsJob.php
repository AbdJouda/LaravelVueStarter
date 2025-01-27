<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveUsersPermissionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Role $role, protected bool $isRemovingRole = false)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var User $user */
        $permissions = $this->role->permissions;

        $users = $this->role->users()->cursor();

        foreach ($users as $user) {
            $user->removeRole($this->role);
            $user->revokePermissionTo($permissions);
        }

        $this->role->delete();

    }
}
