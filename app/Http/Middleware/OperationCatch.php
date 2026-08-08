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
        $response = $next($request);
        $userId = Auth::id();
        if ($userId) {
            $this->doLog($request, $userId);
        }
        return $response;
    }

    private function doLog(Request $request, int $userId): void
    {
        $route = $request->route();
        $operation = $route->getActionMethod();
        $targetId = $route->parameter('id');
        if ($targetId === null) {
            $targetId = $request->post('id');
        }
        $target = $route->getName();

        if ($target === 'post.post') {
            if ($targetId !== null) {
                $target = 'post_update';
            } else {
                $target = 'post_create';
            }
        }

        $this->logService->addLog($userId, $operation, $target ?? '', $targetId);
    }
}
