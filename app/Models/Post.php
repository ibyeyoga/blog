<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

#[Fillable(['title', 'content', 'owner_id'])]
class Post extends Model implements Transformable
{
    use TransformableTrait;

    public function scopeOwner($query, $ownerId){
        return $query->where('owner_id', $ownerId);
    }

    public function scopeAllWithOwnerName($query){
        return $query
            ->leftJoin('users', 'posts.owner_id', '=', 'users.id')
            ->select('posts.*')
            ->addSelect('users.name as owner_name');
    }

    public function scopeKeyword($query, $keyword){
        return $query->where(function($q) use ($keyword){
            $q->where('title', 'like', '%' . $keyword . '%')
              ->orWhere('content', 'like', '%' . $keyword . '%');
        });
    }
}
