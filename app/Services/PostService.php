<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryEloquent;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    /**
     * @var PostRepository 
     */
    protected $postRepository;

    public function __construct(PostRepositoryEloquent $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function list()
    {
        return $this->postRepository->paginate();
    }

    public function createOrUpdate(Post|string $post, $data = [])
    {
        if(is_string($post))
        {
            $post = new Post();
            $post->owner_id = Auth::user()->id;
        }

        $post->title = $data['title'];
        $post->content = $data['content'];

        return $post->save();
    }

    public function get(int|string $id)
    {
        return $this->postRepository->find($id);
    }

    public function delete($post)
    {
        return $this->postRepository->delete($post->id);
    }
}