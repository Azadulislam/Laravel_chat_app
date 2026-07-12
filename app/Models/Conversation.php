<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    protected $fillable = ['type', 'group_id'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withTimestamps()
            ->withPivot('last_read_at');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function getUnreadCountForUser(User $user): int
    {
        $participant = $this->participants()->where('user_id', $user->id)->first();
        if (!$participant) {
            return 0;
        }

        return $this->messages()
            ->where('user_id', '!=', $user->id)
            ->where('created_at', '>', $participant->pivot->last_read_at ?? now()->subYears(10))
            ->count();
    }
}
