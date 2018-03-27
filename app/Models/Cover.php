<?php

namespace App\Models;

use Eloquent;

/**
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $source
 * @property int $weight
 * @property int $display
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Cover extends Eloquent
{

    public $fillable = [
        'title', 'url', 'weight', 'display', 'source'
    ];

    /**
     * 写入缓存
     */
    public function cache()
    {
        $covers = self::select(['url', 'source', 'title'])
            ->where('display', config('enum.display.show.code'))
            ->orderBy('weight', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        $json = json_encode($covers, JSON_UNESCAPED_UNICODE);
        $filename = \App::publicPath() . '/static/covers.json';
        file_put_contents($filename, $json);

        return $covers;
    }


}