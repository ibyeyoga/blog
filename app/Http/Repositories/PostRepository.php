<?php

namespace App\Http\Repositories;
use App\Models\Post;

class PostRepository {
    public function create($title, $content){
        return Post::create([
            'title' => $title,
            'content' => $content,
            'owner_id' => auth()->id()
        ]);
    }

    public function update($id, $title, $content){
        $post = Post::find($id);
        // 获取当前用户的字段，看是否为超管，如果不是，需要看owner_id是否为当前用户的id 
        $currentUser = auth()->user();
        $isAuthorized = $currentUser->role == 1 || $post->owner_id == $currentUser->id;
        if (!$post) {
            return false;
        }
        $post->title = $title;
        $post->content = $content;
        return $post->save();
    }

    public function delete($post){
        return $post->delete();
    }

    public function list($keyword = '', $orderBy = 'created_at', $orderDirection = 'desc'){
        $query = Post::query();
        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('content', 'like', '%' . $keyword . '%');
        }
        return $query->orderBy($orderBy, $orderDirection)->paginate(10);
    }

    public function get($id){
        return Post::find($id);
    }
}