<?php

namespace App\Models;

use App;
use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string $image
 * @property string $excerpt
 * @property string $content
 * @property int $weight
 * @property int $pv
 * @property int $display
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property bool $has_static
 */
class Article extends Eloquent
{

    use SoftDeletes;

    const DISPLAY_SHOW = 1;

    public $fillable = [
        'title', 'author', 'image', 'excerpt', 'weight', 'pv', 'display', 'content'
    ];

    public $appends = [
        'has_static'
    ];

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
            file_put_contents($this->staticFilename(), $page);

            return $page;
        } else {
            if (file_exists($this->staticFilename())) {
                unlink($this->staticFilename());
            }

            return '';
        }
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
     * 获取静态化目录
     * @return string
     */
    private function staticFilename()
    {
        return App::publicPath() . "/article/{$this->id}.html";
    }

}