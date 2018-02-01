<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Components\Rbac;
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
        $rbac = new Rbac($admin);

        $data = array(
            'menus' => $rbac->getMenu(),
            'user' => array(
                'name' => $admin->name,
            ),
        );

        return ApiResponse::success($data);
    }

}