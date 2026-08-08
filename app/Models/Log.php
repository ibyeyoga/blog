<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'operation', 'target_id', 'target'])]
class Log extends Model
{
    public function scopeUserName($query){
        return $query
            ->leftJoin('users', 'logs.user_id', '=', 'users.id')
            ->select('logs.*')
            ->addSelect('users.name as user_name');
    }
}
