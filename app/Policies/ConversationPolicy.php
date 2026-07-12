<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    public function view(User $user, Conversation $conversation): bool
    {
        // Allow site admins to view all conversations if feature is enabled
        if (config('chat.auto_admin_groups') && $user->isAdmin()) {
            return true;
        }
        
        return $conversation->participants()->where('user_id', $user->id)->exists();
    }
}
