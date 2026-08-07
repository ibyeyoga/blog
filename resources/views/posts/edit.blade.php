@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ $post ? '编辑帖子' : '新建帖子' }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('post.post') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="title" class="col-md-2 col-form-label text-md-end">标题</label>

                            <div class="col-md-8">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $post ? $post->title : '') }}" required autocomplete="博客标题" autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div style="display: none">
                            <input type="text" name="id" value="{{ $post ? $post->id : $id }}">
                        </div>
                        <div class="row mb-3">
                            <label for="content" class="col-md-2 col-form-label text-md-end">内容</label>

                            <div class="col-md-8">
                                <textarea id="content" style="height: 400px;" class="form-control @error('content') is-invalid @enderror" name="content" required autocomplete="博客内容">{{ old('content', $post ? $post->content : '') }}</textarea>

                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-3 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    提交
                                </button>
                            </div>

                            <div class="col-md-3">
                                <a href="{{ route('post.list') }}" class="btn btn-secondary">
                                    返回
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
