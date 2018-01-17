<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Auth;

class HomeController extends Controller
{

    public function menu()
    {
        /** @var Admin $admin */
        $admin = Auth::guard(Admin::GUARD)->user();
        $data = array(
            $admin->load('roles.permissions.menus'),
            $admin->roles
        );

        $menus = json_decode('{"home":{"name":"home","title":"首页","childs":{"dashboard":{"name":"dashboard","title":"仪表盘","is_link":true}}},"process_center":{"name":"process_center","title":"处理中心","childs":{"user":{"name":"user","title":"用户中心","icon":"el-icon-menu","childs":{"server":{"name":"server","title":"客户服务"},"manage":{"name":"manage","title":"用户管理"},"test":{"name":"test","title":"这是一个测试"}}},"operation":{"name":"operation","title":"运营中心","childs":{"banner":{"name":"banner","title":"轮播图管理"},"push":{"name":"push","title":"推送管理"}}},"test":{"name":"test","title":"测试","is_link":true}}},"order":{"name":"order","title":"订单管理","childs":{}}}', true);
        $data = array(
            'menus' => $menus,
            'data' => $data
        );
        return ApiResponse::success($data);
    }

}