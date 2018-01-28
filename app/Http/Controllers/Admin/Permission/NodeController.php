<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Components\ApiResponse;
use App\Components\RbacNode;
use App\Http\Controllers\Controller;
use App\Models\AdminNode;
use Illuminate\Http\Request;

class NodeController extends Controller
{

    const PERMISSION = array(
        'title' => '节点管理',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'store', 'name' => '保存'],
            ['action' => 'update', 'name' => '更新'],
            ['action' => 'destroy', 'name' => '删除'],
            ['action' => 'init', 'name' => '初始化数据'],
            ['action' => 'sync', 'name' => '一键同步'],
        ],
    );

    /**
     * 列表
     * @param Request $request
     * @param AdminNode $node
     * @return ApiResponse
     */
    public function index(Request $request, AdminNode $node)
    {
        $keys = ['group'];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($node, $request, $keys)
            ->orderBy('id', 'desc')
            ->paginate();

        $data = array(
            'paginate' => $paginate->toArray()
        );

        return ApiResponse::success($data);
    }

    /**
     * 获取初始化数据
     * @return ApiResponse
     */
    public function init()
    {
        $groups = AdminNode::select(['group'])->groupBy('group')->get()->toArray();

        $data = array(
            'groups' => $groups
        );
        return ApiResponse::success($data);
    }

    /**
     * 一键同步
     * @param RbacNode $rbacNode
     * @return ApiResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function sync(RbacNode $rbacNode)
    {
        $data = $rbacNode->update();

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
            'group' => ['min:1', 'max:16'],
            'node' => ['required', 'min:1', 'max:12'],
            'route' => ['max:255'],
        ));
        $data = $request->only(['group', 'node', 'route']);

        AdminNode::create($data);

        return ApiResponse::success(null);
    }

    /**
     * 更新
     * @param Request $request
     * @param AdminNode $node
     * @return ApiResponse
     */
    public function update(Request $request, AdminNode $node)
    {
        $this->validate($request, array(
            'group' => ['min:1', 'max:16'],
            'node' => ['required', 'min:1', 'max:12'],
            'route' => ['max:255'],
        ));
        $data = $request->only(['group', 'node', 'route']);

        $node->update($data);

        return ApiResponse::success(null);
    }

    /**
     * 删除
     * @param AdminNode $node
     * @return ApiResponse
     * @throws \Exception
     */
    public function destroy(AdminNode $node)
    {
        $node->delete();

        return ApiResponse::success(null);
    }

}