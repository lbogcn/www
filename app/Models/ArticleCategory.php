<?php

namespace App\Models;

use App\Http\Controllers\Web\CategoryController;
use Eloquent;

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

    const TYPE_MODULE = 1;

    public $fillable = [
        'alias', 'title', 'weight', 'display', 'type', 'url'
    ];

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


}