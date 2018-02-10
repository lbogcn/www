<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Cover;
use Illuminate\Http\Request;

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
     * 统计pv
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function pv(Request $request)
    {
        $id = $request->input('id');
        /** @var Article $article */
        $article = Article::select(['id', 'pv'])->findOrFail($id);
        if ($request->input('read') == '1') {
            $pv = $article->readPv();
        } else {
            $pv = $article->incrPv();
        }

        $pv = intval($pv);
        $js = "document.querySelector('{$request->input('dom')}').innerHTML = '{$pv}人浏览过'";

        return $js;
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