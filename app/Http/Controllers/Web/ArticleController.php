<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    /**
     * 文章
     * @param Request $request
     * @param Article $article
     * @return mixed
     */
    public function index(Request $request, Article $article)
    {
        if ($article->display != Article::DISPLAY_SHOW) {
            return abort(404);
        }

        $data = array(
            'article' => $article,
            'title' => $article->title,
            'type' => 'post',
        );

        $article->incrPv();

        if ($request->ajax()) {
            return view('web.article.post', $data);
        } else {
            return view('web.article.layout', $data);
        }
    }

}