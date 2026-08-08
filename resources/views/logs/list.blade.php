@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('日志列表') }}</div>

                <div class="card-body">
                    @if(session('message'))
                        <div class="alert alert-info">
                            {{ session('message') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>操作类型</th>
                                <th>用户ID</th>
                                <th>用户名</th>
                                <th>目标类型</th>
                                <th>目标ID</th>
                                <th>操作时间</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->operation }}</td>
                                    <td>{{ $log->user_id }}</td>
                                    <td>{{ $log->user_name }}</td>
                                    <td>{{ $log->target }}</td>
                                    <td>{{ $log->target_id }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
