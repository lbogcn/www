<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 构建搜索模型
     * @param \Eloquent|\Illuminate\Database\Eloquent\Model $model
     * @param Request $request
     * @param array $keys
     * @return \Eloquent|\Illuminate\Database\Eloquent\Model
     */
    protected function buildSearch($model, Request $request, array $keys)
    {
        return $model->where(function(Builder $query) use ($request, $keys) {
            foreach ($keys as $key) {
                $val = $request->input($key, '');
                if ($val === '') {
                    continue;
                }

                if (is_array($val)) {
                    $start = $val['start'] ?? '';
                    $end = $val['end'] ?? '';

                    // 若有包含start 或 end，认为是日期查询
                    if ($start || $end) {
                        $start = $start ? date('Y-m-d 00:00:00', $start) : '';
                        $end = $end ? date('Y-m-d 23:59:59', $end) : '';

                        if ($start && $end) {
                            $query->whereBetween($key, [$start, $end]);
                        } elseif ($start) {
                            $query->where($key, '>=', $start);
                        } elseif ($end) {
                            $query->where($key, '<=', $end);
                        }
                    }

                    // 有效长度认为是in查询
                    if (count($val) > 0) {
                        $query->whereIn($key, $val);
                    }
                } else {
                    $query->where($key, $val);
                }
            }
        });
    }
}
