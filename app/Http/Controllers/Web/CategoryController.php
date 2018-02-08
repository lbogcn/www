<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
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
     * 获取第一页数据
     * @param ArticleCategory $alias
     * @return string
     * @throws \Throwable
     */
    public function index(ArticleCategory $alias)
    {
        return $this->paginate($alias, 1);
    }

    /**
     * 获取分页数据
     * @param ArticleCategory $alias
     * @param $page
     * @return string
     * @throws \Throwable
     */
    public function paginate(ArticleCategory $alias, $page)
    {
        return $alias->staticRender($page);
    }

}