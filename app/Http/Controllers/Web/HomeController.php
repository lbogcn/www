<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Cover;

class HomeController extends Controller
{

    public function index()
    {
        /** @var \Illuminate\Pagination\Paginator $articles */
        $articles = Article::where('display', Article::DISPLAY_SHOW)
            ->orderBy('weight', 'desc')
            ->orderBy('id', 'desc')
            ->simplePaginate(10);
        $data = array(
            'articles' => $articles,
        );

        return view('web.article.list', $data);
    }

    /**
     * 获取封面列表
     * @param Cover $cover
     * @return Cover[]|\Illuminate\Database\Eloquent\Collection
     */
    public function covers(Cover $cover)
    {
        return $cover->cache();
    }

}