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

    public function createOrUpdate(User $user, ?Post $post = null): bool
    {
        if ($post == null)
        {
            // 因为无论角色，都可以发帖子
            return true;
        } else {
            // 如果不为null，则说明是编辑帖子
            if ($post->owner_id == $user->id)
            {
                // 谁都可以修改自己的帖子
                return true;
            }

            if ($user->role == Role::ADMIN->value)
            {
                // 超管可以修改任何人的帖子
                return true;
            }
        }

        return false;
    }
}
