<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\LogService;

class OperationCatch
{
    protected $logService;
    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        // 获取当前登录用户的 ID（未登录时为 null）
        $userId = Auth::id();


        if ($userId) {
            $operation = $route->getActionMethod();
            $targetId = $route->parameter('id');
            $target = $route->getName();
            
            if ($target === 'post.post') {
                if($targetId !== null){
                    $target = 'post_update';
                } else {
                    $target = 'post_create';
                }
            }

            $this->logService->addLog($userId, $operation, $target, $targetId);
        }

        return $next($request);
    }
}
