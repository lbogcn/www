<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Cover;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        /** @var \Illuminate\Pagination\Paginator $articles */
        $articles = Article::where('display', Article::DISPLAY_SHOW)
            ->orderBy('weight', 'desc')
            ->orderBy('id', 'desc')
            ->simplePaginate(10);
        $data = array(
            'articles' => $articles,
            'type' => 'list',
        );

        if ($request->ajax()) {
            return view('web.article.list', $data);
        } else {
            return view('web.article.layout', $data);
        }

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