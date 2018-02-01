<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\AdminNode;
use App\Models\AdminRole;
use DB;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{

    const PERMISSION = array(
        'title' => '角色权限管理',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'store', 'name' => '保存'],
        ],
    );

    /**
     * 列表
     * @param AdminRole $role
     * @return ApiResponse
     */
    public function index(AdminRole $role)
    {
        $permissionsGroups = array();
        /** @var AdminNode $adminNode */
        foreach (AdminNode::get() as $adminNode) {
            if (!isset($permissionsGroups[$adminNode->group])) {
                $permissionsGroups[$adminNode->group] = [];
            }

            $permissionsGroups[$adminNode->group][] = $adminNode;
        }

        $data = array(
            'groups' => $permissionsGroups,
            'role_permission_id' => array_column($role->permissions->toArray(), 'id'),
        );

        return ApiResponse::success($data);
    }

    /**
     * 保存
     * @param Request $request
     * @param AdminRole $role
     * @return ApiResponse
     * @throws \Exception
     */
    public function store(Request $request, AdminRole $role)
    {
        $this->validate($request, array(
            'node_id' => ['array']
        ));

        DB::beginTransaction();
        $role->permissions()->detach();
        $role->permissions()->attach($request->input('node_id'));
        DB::commit();

        return ApiResponse::success(null);
    }

}