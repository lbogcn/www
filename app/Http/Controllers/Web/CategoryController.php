<?php

namespace App\Http\Controllers\Web;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Route;

class CategoryController extends Controller
{

    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        Route::bind('alias', function ($value) {
            return ArticleCategory::where('alias', $value)
                ->where('display', ArticleCategory::DISPLAY_SHOW)
                ->where('type', ArticleCategory::TYPE_MODULE)
                ->first();
        });
    }

    /**
     * 列表
     * @param Request $request
     * @param ArticleCategory $alias
     * @return string
     * @throws \Throwable
     */
    public function index(Request $request, ArticleCategory $alias)
    {
        $articles = Article::select('articles.*')
            ->join('article_category', 'article_id', '=', 'id')
            ->where('display', Article::DISPLAY_SHOW)
            ->where('category_id', $alias->id)
            ->simplePaginate(10);

        $data = array(
            'articles' => $articles,
            'title' => $alias->title,
            'type' => 'list',
        );

        if ($request->ajax()) {
            $ajaxData = array(
                'view' => view('web.article.list', $data)->render(),
                'title' => $data['title'] . ' - ' . config('app.name'),
            );

            return ApiResponse::success($ajaxData);
        } else {
            return view('web.article.layout', $data);
        }
    }


}