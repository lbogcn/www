<?php

namespace App\Models;

use App\Http\Controllers\Web\ArticleController;
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

    public $fillable = [
        'title', 'author', 'cover', 'cover_width', 'cover_height', 'excerpt', 'weight', 'pv', 'display', 'content', 'markdown'
    ];

    public $appends = [
        'url', 'release_time', 'first_category_id'
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
     * 获取访问地址
     * @return string
     */
    public function getUrlAttribute()
    {
        return action("\\" . ArticleController::class . '@index', $this->id);
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
     * 递增pv
     * @return bool
     */
    public function incrPv(): bool
    {
        if (!$this->id) {
            return false;
        }

        $pv = $this->pv;

        // 执行update后会导致pv变为字符串"pv + 1"，需要手动转回来
        $this->update(array(
            'pv' => \DB::raw('pv + 1'),
        ));

        $this->pv = $pv + 1;

        return true;
    }

}