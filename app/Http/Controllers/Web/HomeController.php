<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;

class HomeController extends Controller
{

    public function index()
    {
        $categories = ArticleCategory::where('display', ArticleCategory::DISPLAY_SHOW)
            ->orderBy('weight', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        $data = array(
            'main_title' => config('app.name'),
            'sub_title' => '林博的博客',
            'intro' => 'large eagle narrate bygone opus.',
            'categories' => $categories,
            'cover' => '/images/03.jpg',
        );

        return view('web.welcome', $data);
    }

}