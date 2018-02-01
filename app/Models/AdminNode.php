<?php

namespace App\Models;

use Eloquent;

/**
 * @property int id
 * @property string $group
 * @property string $node
 * @property string $route
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminNode extends Eloquent
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'node', 'route', 'group'
    ];

}