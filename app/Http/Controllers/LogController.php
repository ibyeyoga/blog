<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LogService;
use Illuminate\Support\Facades\Gate;

class LogController extends Controller
{
    protected $logService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LogService $logService)
    {
        $this->middleware('auth');
        $this->logService = $logService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list()
    {
        if(Gate::denies('super'))
        {
            // 没权限重定向
            return redirect()->route('post.list')->with('message', '没有权限');
        }

        $logs = $this->logService->list();
        return view('logs.list', compact('logs'));
    }
}
