<?php

namespace App\Http\Controllers\Admin\Article;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    const PERMISSION = array(
        'title' => '文章管理',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'store', 'name' => '保存'],
            ['action' => 'destroy', 'name' => '删除'],
        ],
    );

    /**
     * 列表
     * @param Request $request
     * @param Article $article
     * @return ApiResponse
     */
    public function index(Request $request, Article $article)
    {
        $keys = ['author'];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($article, $request, $keys)
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
            'title' => ['required', 'min:1', 'max:60'],
            'author' => ['required', 'min:1', 'max:10'],
            'image' => ['required', 'url'],
            'weight' => ['required', 'min:1', 'max:100', 'integer'],
            'display' => ['required', 'integer'],
            'content' => ['required'],
        ));
        $data = $request->only(['title', 'author', 'image', 'weight', 'display', 'content']);
        $data['excerpt'] = '';
        Article::create($data);

        return ApiResponse::success(null);
    }

    /**
     * 删除
     * @param Article $article
     * @return ApiResponse
     * @throws \Exception
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return ApiResponse::success(null);
    }

}