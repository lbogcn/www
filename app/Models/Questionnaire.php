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
class Questionnaire extends Eloquent
{
    use SoftDeletes;

    const QUESTION_TYPE_RADIO = 1;
    const QUESTION_TYPE_CHECKBOX = 2;
    const QUESTION_TYPE_FILL = 3;

    const QUESTION_TYPES = [
        [self::QUESTION_TYPE_RADIO, '单选'],
        [self::QUESTION_TYPE_CHECKBOX, '多选'],
        [self::QUESTION_TYPE_FILL, '填空'],
    ];

    public $fillable = [
        'title', 'config', 'stat'
    ];

}