<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'role', 'onboarding_completed'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'onboarding_completed' => 'boolean',
        ];
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function isAdmin(): bool
    {
        return in_array($this->role, ['moderator', 'super-admin']);
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants')
            ->withTimestamps()
            ->withPivot('last_read_at');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->withTimestamps()
            ->withPivot('role');
    }

    public function getTotalUnreadMessagesAttribute(): int
    {
        return $this->conversations()->get()->sum(function ($conversation) {
            return $conversation->getUnreadCountForUser($this);
        });
    }
}
