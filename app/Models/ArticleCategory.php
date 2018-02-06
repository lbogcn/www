<?php

namespace App\Models;

use Eloquent;

/**
 * @property int $id
 * @property string $alias
 * @property string $title
 * @property int $weight
 * @property int $display
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ArticleCategory extends Eloquent
{

    public $fillable = [
        'alias', 'title', 'weight', 'display'
    ];

}