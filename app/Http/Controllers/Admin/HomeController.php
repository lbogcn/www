<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Components\Rbac;
use App\Components\Upload\Upload;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    const PERMISSION = array(
        'title' => '公共',
        'nodes' => [
            ['action' => 'uploadToken', 'name' => '获取上传配置'],
            ['action' => 'upload', 'name' => '上传'],
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
     * @param Upload $upload
     * @return ApiResponse
     */
    public function uploadToken(Upload $upload)
    {
        return ApiResponse::success($upload->uploadConfig());
    }

    /**
     * 上传（本地）
     * @param Request $request
     * @param Upload $upload
     * @return ApiResponse
     */
    public function upload(Request $request, Upload $upload)
    {
         return ApiResponse::success($upload->storage($request));
    }

}