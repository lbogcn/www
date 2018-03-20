<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\KeyValue;
use Illuminate\Http\Request;

class KeyValueController extends Controller
{
    const PERMISSION = array(
        'title' => '通用配置',
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
     * @param KeyValue $keyValue
     * @return ApiResponse
     */
    public function index(Request $request, KeyValue $keyValue)
    {
        $keys = [];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($keyValue, $request, $keys)
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
            'key' => ['required', 'max:30', 'unique:key_values'],
            'value' => ['required'],
            'describe' => ['required'],
        ));

        $data = $request->only(['key', 'value', 'describe']);
        KeyValue::create($data);

        return ApiResponse::success(null);
    }

    /**
     * 更新
     * @param Request $request
     * @param KeyValue $keyValue
     * @return ApiResponse
     */
    public function update(Request $request, KeyValue $keyValue)
    {
        $this->validate($request, array(
            'value' => ['required'],
            'describe' => ['required'],
        ));

        $data = $request->only(['value', 'describe']);
        $keyValue->update($data);

        return ApiResponse::success(null);
    }

    /**
     * 删除
     * @param KeyValue $keyValue
     * @return ApiResponse
     * @throws \Exception
     */
    public function destroy(KeyValue $keyValue)
    {
        $keyValue->delete();

        return ApiResponse::success(null);
    }

    /**
     * 静态化
     * @param KeyValue $keyValue
     * @return ApiResponse
     */
    public function cache(KeyValue $keyValue)
    {
        $keyValue->cache();

        return ApiResponse::success(null);
    }

}