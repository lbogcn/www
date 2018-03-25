<?php

namespace App\Models;

use Eloquent;

/**
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Stat extends Eloquent
{

    protected $fillable = [
        'date', 'type', 'count', 'value'
    ];

}