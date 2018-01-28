<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    const PERMISSION = array(
        'title' => '角色管理',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'store', 'name' => '保存'],
            ['action' => 'update', 'name' => '更新'],
            ['action' => 'destroy', 'name' => '删除'],
        ],
    );

    /**
     * 列表
     * @param Request $request
     * @param AdminRole $role
     * @return ApiResponse
     */
    public function index(Request $request, AdminRole $role)
    {
        $keys = [];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($role, $request, $keys)
            ->orderBy('id', 'desc')
            ->paginate();

        $data = array(
            'paginate' => $paginate->toArray()
        );

        return ApiResponse::success($data);
    }

    /**
     * 保存
     * @param Request $request
     * @return ApiResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'role' => ['required', 'min:2', 'max:16', 'unique:admin_roles'],
            'name' => ['required', 'min:1', 'max:12'],
        ));

        $data = $request->only(['role', 'name']);

        AdminRole::create($data);

        return ApiResponse::success(null);
    }

    /**
     * 更新
     * @param Request $request
     * @param AdminRole $role
     * @return ApiResponse
     */
    public function update(Request $request, AdminRole $role)
    {
        $this->validate($request, array(
            'name' => ['required', 'min:1', 'max:12'],
        ));
        $data = $request->only(['name']);

        $role->update($data);

        return ApiResponse::success(null);
    }

    /**
     * 删除
     * @param AdminRole $role
     * @return ApiResponse
     * @throws \Exception
     */
    public function destroy(AdminRole $role)
    {
        $role->delete();

        return ApiResponse::success(null);
    }

}