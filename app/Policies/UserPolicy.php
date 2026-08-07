<?php

namespace App\Policies;

use App\Models\User;
use App\Enum\Role;

class UserPolicy
{
    public function isSuper(User $user): bool
    {
        return $user->role === Role::ADMIN->value;
    }
}
