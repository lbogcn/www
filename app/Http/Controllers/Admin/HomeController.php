<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Components\RbacNode;
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
            $admin->load('roles.permissions.menus'),
            $admin->roles
        );

        $menus = json_decode('{"home":{"name":"home","title":"首页","childs":{"dashboard":{"name":"dashboard","title":"仪表盘","is_link":true}}},"process_center":{"name":"process_center","title":"处理中心","childs":{"user":{"name":"user","title":"用户中心","icon":"el-icon-menu","childs":{"server":{"name":"server","title":"客户服务"},"manage":{"name":"manage","title":"用户管理"},"test":{"name":"test","title":"这是一个测试"}}},"operation":{"name":"operation","title":"运营中心","childs":{"banner":{"name":"banner","title":"轮播图管理"},"push":{"name":"push","title":"推送管理"}}},"test":{"name":"test","title":"测试","is_link":true}}},"control":{"name":"control","title":"控制中心","childs":{"permission":{"name":"permission","title":"权限管理","childs":{"user":{"name":"user","title":"用户管理"},"role":{"name":"role","title":"角色管理"},"node":{"name":"node","title":"节点管理"},"menu":{"name":"menu","title":"菜单管理"}}}}}}', true);
        $data = array(
            'menus' => $menus,
            'user' => $user,
            'data' => $data
        );
        return ApiResponse::success($data);
    }

    public function test(RbacNode $rbacNode)
    {
        if (!config('app.debug')) {
            abort(404);
        }

        return $rbacNode->update();
    }

}