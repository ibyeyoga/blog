<?php

namespace App\Http\Controllers;

use App\Http\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->middleware('auth');
        $this->postService = $postService;
    }

    public function list(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $posts = $this->postService->list($keyword);
        return view('posts.list', compact('posts', 'keyword'));
    }

    public function edit(Request $request, $id = null)
    {
        $post = null;
        if ($id) {
            $post = $this->postService->get($id);
        }
        return view('posts.edit', compact('post', 'id'));
    }

    public function post(Request $request)
    {
        $id = $request->post('id', null);
        $title = $request->post('title', '');
        $content = $request->post('content', '');

        $flag = $this->postService->createOrUpdate($title, $content, $id);

        return redirect()->route('post.list')->with('message', $flag ? '操作成功' : '操作失败');
    }

    public function del($post)
    {
        $flag = $this->postService->delete($post);
        return redirect()->route('post.list')->with('message', $flag ? '删除成功' : '删除失败');
    }
}
