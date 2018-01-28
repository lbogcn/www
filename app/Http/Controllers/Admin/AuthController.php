<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard(Admin::GUARD);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $data = array(
            'user' => $user
        );

        return ApiResponse::success($data);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  Request $request
     * @return ApiResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return ApiResponse::fail('密码错误');
    }

    /**
     * 退出登录
     * @param Request $request
     * @return ApiResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return ApiResponse::success(null);
    }
}