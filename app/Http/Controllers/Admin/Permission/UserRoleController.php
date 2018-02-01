<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use DB;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{

    const PERMISSION = array(
        'title' => '用户角色管理',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'store', 'name' => '保存'],
        ],
    );

    /**
     * 列表
     * @param Admin $user
     * @return ApiResponse
     */
    public function index(Admin $user)
    {
        $data = array(
            'roles' => AdminRole::get(),
            'role_id' => array_column($user->roles->toArray(), 'id'),
        );

        return ApiResponse::success($data);
    }

    /**
     * 保存
     * @param Request $request
     * @param Admin $user
     * @return ApiResponse
     * @throws \Exception
     */
    public function store(Request $request, Admin $user)
    {
        $this->validate($request, array(
            'role_id' => ['array']
        ));

        DB::beginTransaction();
        $user->roles()->detach();
        $user->roles()->attach($request->input('role_id'));
        DB::commit();

        return ApiResponse::success(null);
    }

}