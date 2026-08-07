<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enum\Role;

class PostPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        // 超管可以看到所有帖子
        if ($user->role === Role::ADMIN->value) {
            return true;
        }

        // 用户只能看到自己的帖子
        return $user->id === $post->owner_id;
    }
}
