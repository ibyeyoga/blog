<?php

namespace App\Http\Services;
use App\Http\Repositories\PostRepository;
use App\Http\Repositories\UserRepository;
use App\Enum\Role;

class PostService {
    protected $postRepository;
    protected $userRepository;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository) {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function list($keyword = '', $orderBy = 'created_at', $orderDirection = 'desc') {
        if (auth()->user()->role === Role::ADMIN->value) {
            return $this->postRepository->list($keyword, $orderBy, $orderDirection);
        } else {
            return $this->postRepository->listSelf(auth()->id(), $keyword, $orderBy, $orderDirection);
        }
    }

    public function createOrUpdate($title, $content, $id = null) {
        if ($id) {
            return $this->postRepository->update($id, $title, $content);
        }
        return $this->postRepository->create($title, $content);
    }

    public function delete($post) {
        return $this->postRepository->delete($post);
    }

    // 点查
    public function get($id) {
        return $this->postRepository->get($id);
    }
}