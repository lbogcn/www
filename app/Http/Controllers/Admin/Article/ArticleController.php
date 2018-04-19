<?php

namespace App\Http\Controllers\Admin\Article;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    const PERMISSION = array(
        'title' => '文章管理',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'show', 'name' => '详情'],
            ['action' => 'store', 'name' => '保存'],
            ['action' => 'update', 'name' => '更新'],
            ['action' => 'updateMeta', 'name' => '更新元字段'],
            ['action' => 'destroy', 'name' => '删除'],
            ['action' => 'categories', 'name' => '获取可用类目'],
            ['action' => 'preview', 'name' => '预览'],
        ],
    );

    /**
     * 列表
     * @param Request $request
     * @param Article $article
     * @return ApiResponse
     */
    public function index(Request $request, Article $article)
    {
        $keys = ['author'];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($article, $request, $keys)
            ->orderBy('id', 'desc')
            ->paginate();

        $data = array(
            'paginate' => $paginate->toArray()
        );

        return ApiResponse::success($data);
    }

    /**
     * 保存
     * @param Request $request
     * @return ApiResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'title' => ['required', 'min:1', 'max:60'],
            'cover' => ['required'],
            'excerpt' => ['required', 'max:500'],
            'weight' => ['required', 'min:1', 'max:100', 'integer'],
            'display' => ['required', 'in:1,2'],
            'first_category_id' => ['required', 'integer'],
            'markdown' => ['required'],
            'content' => ['required'],
        ));
        $data = $request->only(['title', 'cover', 'cover_width', 'cover_height', 'excerpt', 'weight', 'display', 'markdown', 'content']);
        $data['author'] = 'lenbo';

        $article = Article::create($data);
        $article->categories()->attach([$request->input('first_category_id')]);

        return ApiResponse::success(null);
    }

    /**
     * 详情
     * @param Article $article
     * @return ApiResponse
     */
    public function show(Article $article)
    {
        $data = array(
            'article' => $article
        );

        return ApiResponse::success($data);
    }

    /**
     * 更新
     * @param Request $request
     * @param Article $article
     * @return ApiResponse
     */
    public function update(Request $request, Article $article)
    {
        if ($request->method() == Request::METHOD_PATCH) {
            return $this->patch($request, $article);
        }

        $this->validate($request, array(
            'title' => ['required', 'min:1', 'max:60'],
            'cover' => ['required'],
            'excerpt' => ['required', 'max:500'],
            'weight' => ['required', 'min:1', 'max:100', 'integer'],
            'display' => ['required', 'in:1,2'],
            'first_category_id' => ['required', 'integer'],
            'markdown' => ['required'],
            'content' => ['required'],
        ));
        $data = $request->only(['title', 'cover', 'cover_width', 'cover_height', 'excerpt', 'weight', 'display', 'markdown', 'content']);
        $article->update($data);
        $article->categories()->detach();
        $article->categories()->attach([$request->input('first_category_id')]);

        return ApiResponse::success(null);
    }

    /**
     * 更新元字段
     * @param Request $request
     * @param Article $article
     * @return ApiResponse
     */
    public function patch(Request $request, Article $article)
    {
        $keys = ['display'];
        $data = $request->only($keys);
        $article->update($data);

        return ApiResponse::success(null);
    }

    /**
     * 删除
     * @param Article $article
     * @return ApiResponse
     * @throws \Exception
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return ApiResponse::success(null);
    }

    /**
     * 获取可用类目
     * @return ApiResponse
     */
    public function categories()
    {
        $rows = ArticleCategory::where('display', config('enum.display.show.code'))
            ->where('type', ArticleCategory::TYPE_MODULE)
            ->orderBy('weight', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        $data = array(
            'categories' => $rows,
        );

        return ApiResponse::success($data);
    }

    /**
     * 预览
     * @param Article $article
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function preview(Article $article)
    {
        $data = array(
            'article' => $article,
            'title' => $article->title,
            'type' => 'post',
        );

        return view('web.article.layout', $data);
    }
}