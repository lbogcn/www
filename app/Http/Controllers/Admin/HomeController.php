<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Http\Controllers\Admin\Permission\MenuController;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Auth;

class HomeController extends Controller
{

    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.welcome');
    }

    /**
     * 获取菜单
     * @return ApiResponse
     */
    public function menu()
    {
        /** @var Admin $admin */
        $admin = Auth::guard(Admin::GUARD)->user();
        $user = $admin->toArray();

        $data = array(
            $admin->roles
        );

        $menus = config('menu');
        $data = array(
            'menus' => $menus,
            'user' => $user,
            'data' => $data
        );
        return ApiResponse::success($data);
    }

}