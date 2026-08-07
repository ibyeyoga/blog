<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\LogService;

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
        $logs = $this->logService->getLogs();
        return view('logs.list', compact('logs'));
    }
}
