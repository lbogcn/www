<?php

namespace App\Http\Middleware;

use App\Components\ApiResponse;
use App\Models\AdminOperationLog;
use Auth;
use Closure;

class OperationLog
{

    /**
     * 验证权限
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param $guard
     * @return ApiResponse|mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        /** @var \App\Models\Admin $user */
        $user = Auth::guard($guard)->user();

        $data = array(
            'username' => "{$user->username}",
            'url' => "{$request->getUri()}",
            'method' => "{$request->method()}",
            'query_string' => "{$request->getQueryString()}",
            'input_content' => "{$request->getContent()}"
        );
        AdminOperationLog::create($data);

        return $next($request);
    }
}