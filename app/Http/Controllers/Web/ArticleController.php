<?php

namespace App\Http\Controllers\Web;

use App\Components\ApiResponse;
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
     * @throws \Throwable
     */
    public function index(Request $request, Article $article)
    {
        if ($article->display != config('enum.display.show.code')) {
            return abort(404);
        }

        $data = array(
            'article' => $article,
            'title' => $article->title,
            'type' => 'post',
        );

        $article->incrPv();

        if ($request->ajax()) {
            $ajaxData = array(
                'view' => view('web.article.post', $data)->render(),
                'title' => $data['title'] . ' - ' . config('app.name'),
            );

            return ApiResponse::success($ajaxData);
        } else {
            return view('web.article.layout', $data);
        }
    }

}