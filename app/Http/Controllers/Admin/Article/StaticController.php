<?php

namespace App\Http\Controllers\Admin\Article;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class StaticController extends Controller
{
    const PERMISSION = array(
        'title' => '文章管理-静态化',
        'nodes' => [
            ['action' => 'index', 'name' => '检测'],
            ['action' => 'store', 'name' => '批量静态化'],
            ['action' => 'update', 'name' => '单个静态化'],
        ],
    );

    /**
     * 检测
     * @return ApiResponse
     */
    public function index()
    {
        $data = array(
            'count' => Article::withTrashed()->count(),
        );

        return ApiResponse::success($data);
    }

    /**
     * 批量静态化
     * @param Request $request
     * @return ApiResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $lastId = intval($request->input('last_id'));
        /** @var Article $article */
        $article = Article::withTrashed()->where('id', '>', $lastId)->first();
        $data = array(
            'count' => 0,
            'last_id' => 0
        );

        if (empty($article)) {
            return ApiResponse::success($data);
        }

        $article->staticRender();
        $data['count'] = Article::where('id', '>', $article->id)->count();
        $data['last_id'] = $article->id;

        return ApiResponse::success($data);
    }

    /**
     * 单个静态化
     * @param Article $static
     * @return ApiResponse
     * @throws \Throwable
     */
    public function update(Article $static)
    {
        $static->staticRender();

        return ApiResponse::success(null);
    }

}