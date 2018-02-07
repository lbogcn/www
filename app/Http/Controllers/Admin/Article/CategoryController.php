<?php

namespace App\Http\Controllers\Admin\Article;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    const PERMISSION = array(
        'title' => '文章管理-类目管理',
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
     * @param ArticleCategory $category
     * @return ApiResponse
     */
    public function index(Request $request, ArticleCategory $category)
    {
        $keys = [];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($category, $request, $keys)
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
     * @param ArticleCategory $category
     * @return ApiResponse
     */
    public function store(Request $request, ArticleCategory $category)
    {
        $this->validate($request, array(
            'alias' => ['required', 'max:10', "unique:{$category->getTable()},alias"],
            'title' => ['required', 'max:10'],
            'weight' => ['required', 'integer', 'min:0', 'max:100'],
            'display' => ['required', 'in:1,2'],
            'type' => ['required', 'in:1,2'],
        ));
        if ($request->input('type') == 2) {
            $this->validate($request, array(
                'url' => ['url', 'max:500'],
            ));
        }

        $data = $request->only(['alias', 'title', 'weight', 'display', 'type', 'url']);
        $data['url'] = $category->genUrl($data);
        $category->create($data);

        return ApiResponse::success(null);
    }

    /**
     * 更新
     * @param Request $request
     * @param ArticleCategory $category
     * @return ApiResponse
     */
    public function update(Request $request, ArticleCategory $category)
    {
        $this->validate($request, array(
            'title' => ['required', 'max:10'],
            'weight' => ['required', 'integer', 'min:0', 'max:100'],
            'display' => ['required', 'in:1,2'],
            'type' => ['required', 'in:1,2'],
        ));
        if ($request->input('type') == 2) {
            $this->validate($request, array(
                'url' => ['url', 'max:500'],
            ));
        }

        $urlData = $data = $request->only(['title', 'weight', 'display', 'type', 'url']);
        $urlData['alias'] = $category->alias;
        $data['url'] = $category->genUrl($urlData);
        $category->update($data);

        return ApiResponse::success(null);
    }

    /**
     * 删除
     * @param ArticleCategory $category
     * @return ApiResponse
     * @throws \Exception
     */
    public function destroy(ArticleCategory $category)
    {
        $category->delete();

        return ApiResponse::success(null);
    }

}