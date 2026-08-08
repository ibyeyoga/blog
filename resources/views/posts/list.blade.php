@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Post List') }}</div>

                <div class="card-body">
                    @if(session('message'))
                        <div class="alert alert-info">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form method="GET" action="{{ route('post.list') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="keyword" class="form-control" placeholder="搜索标题或内容" value="{{ $keyword }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">{{ __('查找帖子') }}</button>
                                <a href="{{ route('post.edit') }}" class="btn btn-success">{{ __('发贴') }}</a>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>标题</th>
                                <th>内容</th>
                                @if($isAdmin)
                                    <th>作者</th>
                                @endif
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($posts as $post)
                                <tr>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($post->content, 50) }}</td>
                                    @if($isAdmin)
                                        <td>{{ $post->owner_name }}</td>
                                    @endif
                                    <td>{{ $post->created_at }}</td>
                                    <td>
                                        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-sm btn-primary">编辑</a>
                                        <form method="POST" action="{{ route('post.del', $post->id) }}" style="display: inline;" onsubmit="return confirm('确定删除吗？');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">删除</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
