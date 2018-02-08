<?php

namespace App\Models;

use App;
use App\Components\Paginator;
use App\Http\Controllers\Web\CategoryController;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $alias
 * @property string $title
 * @property int $weight
 * @property int $display
 * @property int $type
 * @property string $url
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ArticleCategory extends Eloquent
{

    const DISPLAY_SHOW = 1;

    const TYPE_MODULE = 1;

    public $fillable = [
        'alias', 'title', 'weight', 'display', 'type', 'url'
    ];

    /**
     * 静态输出
     * @param $page
     * @return string
     * @throws \Throwable
     */
    public function staticRender($page)
    {
        if ($this->display == self::DISPLAY_SHOW) {
            /** @var Builder $builder */
            $builder = Article::select('articles.*')
                ->join('article_category', 'article_id', '=', 'id')
                ->where('display', Article::DISPLAY_SHOW)
                ->where('category_id', $this->id);
            $articles = $this->customPaginate($builder, $page, $this->alias);

            $data = array(
                'articles' => $articles,
                'sub_title' => $this->title,
                'cover' => '/images/03.jpg',
            );
            $view = view('web.article.list', $data)->render();

            if (!config('app.debug')) {
                file_put_contents($this->staticFilename($page), $view);
                if ($page == 1) {
                    file_put_contents($this->staticFilename(), $view);
                }
            }

            return $view;
        } else {
            if (file_exists($this->staticFilename($page))) {
                unlink($this->staticFilename($page));
            }

            if ($page == 1 && file_exists($this->staticFilename())) {
                unlink($this->staticFilename());
            }

            return '';
        }
    }

    /**
     * 生成url
     * @param array $data
     * @return string
     */
    public function genUrl(array $data)
    {
        if ($data['type'] == self::TYPE_MODULE) {
            return action("\\" . CategoryController::class . '@index', $data['alias']);
        }

        return $data['url'];
    }

    /**
     * 静态文件目录
     * @param null $page
     * @return string
     */
    private function staticFilename($page = null): string
    {
        if ($page) {
            $filename = App::publicPath() . "/static/{$this->alias}/p_{$page}.html";
        } else {
            $filename = App::publicPath() . "/static/{$this->alias}.html";
        }

        if (!is_dir(dirname($filename))) {
            mkdir(dirname($filename), 755, true);
        }

        return $filename;
    }

    /**
     * 自定义分页
     * @param Builder $query
     * @param $page
     * @param $alias
     * @return Paginator
     */
    private function customPaginate(Builder $query, $page, $alias): Paginator
    {
        $perPage = 10;
        $items = $query->skip(($page - 1) * $perPage)->take($perPage + 1)->get();
        $paginate = new Paginator($items, $perPage, $page, ['query' => compact('alias')]);

        return $paginate->setAction('\\' . CategoryController::class . '@paginate');
    }

}