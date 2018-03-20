<?php

namespace App\Models;

use Cache;
use Eloquent;

/**
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class KeyValue extends Eloquent
{

    const CACHE_KEY = 'key-value';

    protected $fillable = [
        'key', 'value', 'describe'
    ];

    /**
     * 静态化
     */
    public function cache()
    {
        $rows = self::select(['key', 'value'])
            ->get()
            ->toArray();

        $data = array_column($rows, 'value', 'key');

        Cache::forever(self::CACHE_KEY, $data);
    }

    /**
     * 获取value
     * @param $key
     * @param null $default
     * @return null
     */
    public static function getValue($key, $default = null)
    {
        if (Cache::has(self::CACHE_KEY)) {
            $keyValues = Cache::get(self::CACHE_KEY);

            if (array_key_exists($key, $keyValues)) {
                return $keyValues[$key];
            }
        }

        return $default;
    }

}