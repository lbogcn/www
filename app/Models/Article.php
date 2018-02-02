<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class Article extends Eloquent
{

    use SoftDeletes;

    public $fillable = [
        'title', 'author', 'image', 'excerpt', 'weight', 'pv', 'display', 'content'
    ];

}