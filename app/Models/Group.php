<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    protected $fillable = ['name', 'avatar_path', 'description', 'created_by'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->withTimestamps()
            ->withPivot('role');
    }

    public function isUserAdmin(User $user): bool
    {
        if (config('chat.auto_admin_groups') && $user->isAdmin()) {
            return true;
        }
        
        return $this->members()->where('user_id', $user->id)->wherePivot('role', 'admin')->exists();
    }

    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class);
    }
}
