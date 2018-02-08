<?php

namespace App\Models;

use Eloquent;

/**
 * @property int $id
 * @property string $title
 * @property string $url
 * @property int $weight
 * @property int $display
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Cover extends Eloquent
{

    const DISPLAY_SHOW = 1;

    public $fillable = [
        'title', 'url', 'weight', 'display'
    ];


}