<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;

class ArticleController extends Controller
{

    /**
     * 文章
     * @param Article $article
     * @return string
     * @throws \Throwable
     */
    public function index(Article $article)
    {
        if ($article->display != Article::DISPLAY_SHOW) {
            return abort(404);
        }

        return $article->staticRender();
    }

}