<?php

namespace App\Http\Services;
use App\Http\Repositories\PostRepository;

class PostService {
    protected $postRepository;

    public function __construct(PostRepository $postRepository) {
        $this->postRepository = $postRepository;
    }

    public function list($keyword = '', $orderBy = 'created_at', $orderDirection = 'desc') {
        return $this->postRepository->list($keyword, $orderBy, $orderDirection);
    }

    public function createOrUpdate($title, $content, $id = null) {
        if ($id) {
            return $this->postRepository->update($id, $title, $content);
        }
        return $this->postRepository->create($title, $content);
    }

    public function delete($id) {
        return $this->postRepository->delete($id);
    }

    // 点查
    public function get($id) {
        return $this->postRepository->get($id);
    }
}