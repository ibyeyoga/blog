<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostDeleteRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->middleware('auth');
        $this->middleware('operationCatch')->only(['post', 'del']);
    }

    public function list(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $posts = $this->postService->list();
        return view('posts.list', compact('posts', 'keyword'));
    }

    public function edit($id = null)
    {
        // 这里公用一个接口，id是否为null判断是编辑还是新建
        // 编辑需要鉴权
        if ($id === null)
        {
            $post = [];
        } else {
            $post = $this->postService->get($id);
            $this->authorize('view', $post);
        }

        return view('posts.edit', compact('post', 'id'));
    }

    public function post(Request $request)
    {
        $post = null;
        $postData = $request->post();
        if($postData['id'] != null)

        {
            $post = $this->postService->get($postData['id']);
        } else {
            $post = Post::class;
        }

        $this->authorize('createOrUpdate', $post);
        $flag = $this->postService->createOrUpdate($post, $request->post());

        return redirect()->route('post.list')->with('message', $flag ? '操作成功' : '操作失败');
    }

    public function del(PostDeleteRequest $request, Post $post)
    {
        $flag = $this->postService->delete($post);
        return redirect()->route('post.list')->with('message', $flag ? '删除成功' : '删除失败');
    }
}
