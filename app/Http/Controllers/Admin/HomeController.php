<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Components\Qiniu;
use App\Components\Rbac;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Auth;

class HomeController extends Controller
{
    const PERMISSION = array(
        'title' => '公共',
        'nodes' => [
            ['action' => 'uploadToken', 'name' => '上传资源'],
        ],
    );

    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.welcome');
    }

    /**
     * 获取初始化
     * @return ApiResponse
     */
    public function init()
    {
        /** @var Admin $admin */
        $admin = Auth::guard(Admin::GUARD)->user();
        $rbac = new Rbac($admin);

        $data = array(
            'menus' => $rbac->getMenu(),
            'user' => array(
                'name' => $admin->name,
            ),
            'app_name' => config('app.name')
        );

        return ApiResponse::success($data);
    }

    /**
     * 获取上传token
     * @param Qiniu $qiniu
     * @return ApiResponse
     */
    public function uploadToken(Qiniu $qiniu)
    {
        $token = $qiniu->token(config('global.qiniu.callback'));

        return ApiResponse::success(compact('token'));
    }

}