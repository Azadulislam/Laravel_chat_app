<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\Group;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:sync-admin-groups')]
#[Description('Add all site admins to all groups and conversations')]
class SyncAdminGroups extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!config('chat.auto_admin_groups')) {
            $this->warn('Auto admin groups feature is disabled!');
            return Command::SUCCESS;
        }
        
        $admins = User::whereIn('role', ['moderator', 'super-admin'])->get();
        
        $this->info('Found ' . $admins->count() . ' admin(s):');
        
        foreach ($admins as $admin) {
            $this->line("- " . ($admin->username ?? $admin->name));
            
            // Add admin to all groups
            $allGroups = Group::all();
            foreach ($allGroups as $group) {
                $group->members()->syncWithoutDetaching([$admin->id => ['role' => 'admin']]);
                $this->line("  → Added to group: " . $group->name);
            }
            
            // Add admin to all conversations
            $allConversations = Conversation::all();
            foreach ($allConversations as $conv) {
                $conv->participants()->syncWithoutDetaching([$admin->id]);
                $this->line("  → Added to conversation: " . $conv->id);
            }
        }
        
        $this->info('All admins synced!');
        
        return Command::SUCCESS;
    }
}
