<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $email
 * @property string $nickname
 * @property string $ip
 * @property int $display
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class Message extends Eloquent
{

    use SoftDeletes;

    const DISPLAY_SHOW = 1;

    public $fillable = [
        'email', 'nickname', 'ip', 'display', 'content'
    ];
}