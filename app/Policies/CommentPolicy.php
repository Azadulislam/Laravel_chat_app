<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function moderate(User $user, Comment $comment): bool
    {
        return $user->isAdmin();
    }
}
