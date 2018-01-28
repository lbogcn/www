<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class UserController extends Controller
{

    const PERMISSION = array(
        'title' => '用户管理',
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
     * @param Admin $user
     * @return ApiResponse
     */
    public function index(Request $request, Admin $user)
    {
        $keys = ['username'];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($user, $request, $keys)
            ->with('roles')
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
            'username' => ['required', 'min:4', 'max:16', 'unique:admins'],
            'name' => ['required', 'min:1', 'max:12'],
            'password' => ['required', 'min:6', 'max:32'],
        ));

        $data = $request->only(['username', 'name', 'password']);
        $data['password'] = bcrypt($data['password']);

        Admin::create($data);

        return ApiResponse::success(null);
    }

    /**
     * 更新
     * @param Request $request
     * @param Admin $user
     * @return ApiResponse
     */
    public function update(Request $request, Admin $user)
    {
        $this->validate($request, array(
            'name' => ['required', 'min:1', 'max:12'],
            'password' => ['min:6', 'max:32'],
        ));
        $data = $request->only(['username', 'name']);

        // 判断是否需要修改密码
        if ($request->input('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }

        $user->update($data);

        return ApiResponse::success(null);
    }

    /**
     * 删除
     * @param Admin $user
     * @return ApiResponse
     * @throws \Exception
     */
    public function destroy(Admin $user)
    {
        $user->delete();

        return ApiResponse::success(null);
    }

}