<?php

namespace App\Http\Middleware;

use App\Components\ApiResponse;
use App\Components\Errors;
use App\Components\Rbac;
use Auth;
use Closure;

class Permission
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
        $rbac = new Rbac($user);

        if (!$rbac->check($request->route())) {
            return ApiResponse::fail(Errors::permissionDenied());
        }

        return $next($request);
    }
}