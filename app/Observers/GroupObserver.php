<?php

namespace App\Observers;

use App\Models\Group;
use App\Models\User;

class GroupObserver
{
    /**
     * Handle the Group "created" event.
     */
    public function created(Group $group): void
    {
        if (config('chat.auto_admin_groups')) {
            // Get all site admins
            $admins = User::whereIn('role', ['moderator', 'super-admin'])->get();
            
            foreach ($admins as $admin) {
                // Check if admin is not already a member
                if (!$group->members()->where('user_id', $admin->id)->exists()) {
                    $group->members()->attach($admin->id, ['role' => 'admin']);
                }
            }
        }
    }

    /**
     * Handle the Group "updated" event.
     */
    public function updated(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "deleted" event.
     */
    public function deleted(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "restored" event.
     */
    public function restored(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "force deleted" event.
     */
    public function forceDeleted(Group $group): void
    {
        //
    }
}
