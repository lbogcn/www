<?php

namespace App\Models;

use Eloquent;

/**
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminOperationLog extends Eloquent
{

    public $fillable = [
        'username','url','method','query_string','input_content',
    ];

}