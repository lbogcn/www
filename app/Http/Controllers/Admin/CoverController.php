<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Cover;
use Illuminate\Http\Request;

class CoverController extends Controller
{
    const PERMISSION = array(
        'title' => '封面管理',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'store', 'name' => '保存'],
            ['action' => 'update', 'name' => '更新'],
            ['action' => 'destroy', 'name' => '删除'],
            ['action' => 'cache', 'name' => '静态化'],
        ],
    );

    /**
     * 列表
     * @param Request $request
     * @param Cover $cover
     * @return ApiResponse
     */
    public function index(Request $request, Cover $cover)
    {
        $keys = [];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($cover, $request, $keys)
            ->orderBy('weight', 'desc')
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
            'title' => ['required', 'max:20'],
            'url' => ['required', 'url', 'max:500'],
            'source' => ['required', 'url', 'max:500'],
            'weight' => ['required', 'min:1', 'max:100', 'integer'],
            'display' => ['required', 'in:1,2'],
        ));

        $data = $request->only(['title', 'url', 'source', 'weight', 'display']);
        Cover::create($data);

        return ApiResponse::success(null);
    }

    /**
     * 更新
     * @param Request $request
     * @param Cover $cover
     * @return ApiResponse
     */
    public function update(Request $request, Cover $cover)
    {
        $this->validate($request, array(
            'title' => ['required', 'max:20'],
            'url' => ['required', 'url', 'max:500'],
            'source' => ['required', 'url', 'max:500'],
            'weight' => ['required', 'min:1', 'max:100', 'integer'],
            'display' => ['required', 'in:1,2'],
        ));

        $data = $request->only(['title', 'url', 'source', 'weight', 'display']);
        $cover->update($data);

        return ApiResponse::success(null);
    }

    /**
     * 删除
     * @param Cover $cover
     * @return ApiResponse
     * @throws \Exception
     */
    public function destroy(Cover $cover)
    {
        $cover->delete();

        return ApiResponse::success(null);
    }

    /**
     * 静态化
     * @param Cover $cover
     * @return ApiResponse
     */
    public function cache(Cover $cover)
    {
        $cover->cache();

        return ApiResponse::success(null);
    }
}