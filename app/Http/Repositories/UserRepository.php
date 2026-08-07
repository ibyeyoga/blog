<?php
namespace App\Http\Repositories;
use App\Models\User;

class UserRepository {
    public function getUserByIds($ids) {
        return User::whereIn('id', $ids)->get();
    }
}