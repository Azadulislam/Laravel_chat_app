<?php

namespace App\Observers;

use App\Models\Conversation;
use App\Models\Group;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Get or create the team group
        $group = Group::firstOrCreate(
            ['name' => 'Team'],
            [
                'description' => 'Default team group for all users',
                'created_by' => $user->id,
            ]
        );

        // Get or create the conversation for this group
        $conversation = Conversation::firstOrCreate(
            ['group_id' => $group->id],
            ['type' => 'group']
        );

        // Add the new user to the group
        $group->members()->syncWithoutDetaching([$user->id => ['role' => 'member']]);
        
        // Add the new user to the conversation
        $conversation->participants()->syncWithoutDetaching([$user->id]);
        
        // If the new user is an admin, add them to all groups and conversations
        if (config('chat.auto_admin_groups') && $user->isAdmin()) {
            $this->makeUserAdminOfAllGroups($user);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if (config('chat.auto_admin_groups') && $user->isAdmin()) {
            $this->makeUserAdminOfAllGroups($user);
        }
    }
    
    private function makeUserAdminOfAllGroups(User $user): void
    {
        // Add admin to all groups
        $allGroups = Group::all();
        foreach ($allGroups as $group) {
            $group->members()->syncWithoutDetaching([$user->id => ['role' => 'admin']]);
        }
        
        // Add admin to all conversations
        $allConversations = Conversation::all();
        foreach ($allConversations as $conv) {
            $conv->participants()->syncWithoutDetaching([$user->id]);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
