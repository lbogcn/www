<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;

class HomeController extends Controller
{

    public function index()
    {
        $categories = ArticleCategory::where('display', ArticleCategory::DISPLAY_SHOW)
            ->orderBy('weight', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        /** @var \Illuminate\Pagination\Paginator $articles */
        $articles = Article::where('display', Article::DISPLAY_SHOW)
            ->orderBy('weight', 'desc')
            ->orderBy('id', 'desc')
            ->simplePaginate(5);
        $data = array(
            'main_title' => config('app.name'),
            'sub_title' => '林博的博客',
            'intro' => 'large eagle narrate bygone opus.',
            'categories' => $categories,
            'articles' => $articles,
            'cover' => '/images/03.jpg',
        );

        return view('web.welcome', $data);
    }

}