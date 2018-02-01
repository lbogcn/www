<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Components\ApiResponse;
use App\Components\RbacMenu;
use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use App\Models\AdminVisibleMenu;
use DB;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    const PERMISSION = array(
        'title' => '菜单管理',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'store', 'name' => '保存'],
        ],
    );

    /**
     * 列表
     * @param RbacMenu $rbacMenu
     * @return ApiResponse
     */
    public function index(RbacMenu $rbacMenu)
    {
        $menu = $rbacMenu->getAll();
        $roles = AdminRole::select(['id', 'role'])->get();

        $data = array(
            'menu' => $menu,
            'roles' => $roles
        );

        return ApiResponse::success($data);
    }

    /**
     * 更新
     * @param Request $request
     * @return ApiResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'role_id' => ['array'],
            'route' => ['required']
        ));

        $route = $request->input('route');
        $roleIds = (array)$request->input('role_id');
        $routeIndex = substr(md5($route), 8, 16);
        $menus = AdminVisibleMenu::where('route_index', $routeIndex)->get();

        $deleteIds = [];
        foreach ($menus as $menu) {
            if (!in_array($menu->role_id, $roleIds)) {
                $deleteIds[] = $menu->id;
            } else {
                $index = array_search($menu->role_id, $roleIds);
                if (isset($roleIds[$index])) {
                    unset($roleIds[$index]);
                }
            }
        }

        DB::beginTransaction();
        if (count($roleIds) > 0) {
            $rows = array();
            foreach ($roleIds as $roleId) {
                $rows[] = array(
                    'role_id' => $roleId,
                    'route_index' => $routeIndex,
                    'route' => $route
                );
            }
            AdminVisibleMenu::insert($rows);
        }
        if (count($deleteIds) > 0) {
            AdminVisibleMenu::whereIn('id', $deleteIds)->delete();
        }
        DB::commit();

        return ApiResponse::success(null);
    }

}