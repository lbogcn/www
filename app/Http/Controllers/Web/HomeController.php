<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;

class HomeController extends Controller
{

    public function index()
    {
        /** @var \Illuminate\Pagination\Paginator $articles */
        $articles = Article::where('display', Article::DISPLAY_SHOW)
            ->orderBy('weight', 'desc')
            ->orderBy('id', 'desc')
            ->simplePaginate(5);
        $data = array(
            'articles' => $articles,
        );

        return view('web.article.list', $data);
    }

}