<?php

namespace App\Http\Controllers\Web;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Cover;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * 首页
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        /** @var \Illuminate\Pagination\Paginator $articles */
        $articles = Article::where('display', config('enum.display.show.code'))
            ->orderBy('weight', 'desc')
            ->orderBy('id', 'desc')
            ->simplePaginate(10);
        $data = array(
            'articles' => $articles,
            'type' => 'list',
        );

        if ($request->ajax()) {
            $ajaxData = array(
                'view' => view('web.article.list', $data)->render(),
                'title' =>  config('app.name'),
            );

            return ApiResponse::success($ajaxData);
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