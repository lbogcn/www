<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Components\Errors;
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

    /**
     * 修改密码
     * @param Request $request
     * @return ApiResponse
     * @throws \Throwable
     */
    public function modifyPassword(Request $request)
    {
        $this->validate($request, array(
            'old_password' => ['required'],
            'password' => ['required', 'min:6', 'max:16', 'confirmed']
        ));

        /** @var Admin $user */
        $user = $this->guard()->user();

        $validate = $this->guard()->validate(array(
            'username' => $user->username,
            'password' => $request->input('old_password'),
        ));

        if (!$validate) {
            return ApiResponse::fail(Errors::passwordError());
        }

        $user->password = bcrypt($request->input('password'));
        $user->saveOrFail();
        $this->guard()->logout();

        return ApiResponse::success(null);
    }
}