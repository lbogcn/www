<?php

namespace App\Models;

use App;
use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string $cover
 * @property int $cover_width
 * @property int $cover_height
 * @property string $excerpt
 * @property string $markdown
 * @property string $content
 * @property int $weight
 * @property int $pv
 * @property int $display
 * @property bool $has_static
 * @property string $url
 * @property string $release_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property int $first_category_id
 * @property ArticleCategory|\Illuminate\Database\Eloquent\Collection $categories
 */
class Article extends Eloquent
{

    use SoftDeletes;

    const DISPLAY_SHOW = 1;

    public $fillable = [
        'title', 'author', 'cover', 'cover_width', 'cover_height', 'excerpt', 'weight', 'pv', 'display', 'content', 'markdown'
    ];

    public $appends = [
        'has_static', 'url', 'release_time', 'first_category_id'
    ];

    /**
     * 获取所属类目
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(ArticleCategory::class, 'article_category', 'article_id', 'category_id');
    }

    /**
     * 静态化并渲染输出
     * @return string
     * @throws \Throwable
     */
    public function staticRender()
    {
        if ($this->display == self::DISPLAY_SHOW) {
            $data = array(
                'model' => $this,
            );
            $page = view('web.article.article', $data)->render();

            if (!config('app.debug')) {
                file_put_contents($this->staticFilename(), $page);
            }

            return $page;
        } else {
            if (file_exists($this->staticFilename())) {
                unlink($this->staticFilename());
            }

            return '';
        }
    }

    /**
     * 获取访问地址
     * @return string
     */
    public function getUrlAttribute()
    {
        return action("\\" . App\Http\Controllers\Web\ArticleController::class . '@index', $this->id);
    }

    /**
     * 发布时间
     * @return string
     */
    public function getReleaseTimeAttribute()
    {
        $createdAt = strtotime($this->created_at);
        $s = time() - $createdAt;

        if ($s < 60) {
            $val = $s;
            $unit = '秒';
        } elseif ($s < 60 * 60) {
            $val = intval($s / 60);
            $unit = '分钟';
        } elseif ($s < 24 * 3600) {
            $val = intval($s / 3600);
            $unit = '小时';
        } elseif ($s < 7 * 24 * 3600) {
            $val = intval($s / 24 / 3600);
            $unit = '天';
        } elseif ($s < 30 * 24 * 3600) {
            $val = intval($s / 7 / 24 / 3600);
            $unit = '周';
        } elseif ($s < 365 * 24 * 3600) {
            $val = intval($s / 30 / 24 / 3600);
            $unit = '个月';
        } else {
            return date('Y-m-d', $createdAt) . '发布';
        }

        return "{$val}{$unit}前发布";
    }

    /**
     * 是否已静态化
     * @return bool
     */
    public function getHasStaticAttribute()
    {
        return file_exists($this->staticFilename());
    }

    /**
     * 获取类目ID列表
     * @return int
     */
    public function getFirstCategoryIdAttribute()
    {
        $categories = $this->categories->toArray();
        $ids = array_column($categories, 'id');
        if (count($ids) == 0) {
            return null;
        }

        return current($ids);
    }

    /**
     * 获取静态化目录
     * @return string
     */
    private function staticFilename(): string
    {
        $filename = App::publicPath() . "/static/article/{$this->id}.html";
        if (!is_dir(dirname($filename))) {
            mkdir(dirname($filename), 755, true);
        }

        return $filename;
    }

}