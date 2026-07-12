<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    \Log::info("Broadcasting auth attempt", [
        'user_id' => $user->id,
        'conversation_id' => $conversationId
    ]);
    
    $conversation = Conversation::find($conversationId);
    
    if (!$conversation) {
        \Log::warning("Conversation not found", ['id' => $conversationId]);
        return false;
    }

    $isParticipant = $conversation->participants()->where('user_id', $user->id)->exists();
    
    \Log::info("Authorization result", ['is_participant' => $isParticipant]);
    
    return $isParticipant;
});
