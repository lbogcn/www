<?php

namespace App\Http\Controllers\Admin\Log;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\AdminOperationLog;
use DB;
use Illuminate\Http\Request;

class OperationController extends Controller
{

    const PERMISSION = array(
        'title' => '操作日志',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'destroy', 'name' => '删除'],
        ],
    );

    /**
     * 列表
     * @param Request $request
     * @param AdminOperationLog $model
     * @return ApiResponse
     */
    public function index(Request $request, AdminOperationLog $model)
    {
        $keys = ['username', 'method'];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($model, $request, $keys)
            ->orderBy('id', 'desc')
            ->paginate(100);

        $data = array(
            'paginate' => $paginate->toArray()
        );

        return ApiResponse::success($data);
    }

    /**
     * 删除
     * @param AdminOperationLog $model
     * @return ApiResponse
     */
    public function destroy(AdminOperationLog $model)
    {
        $outDaily = 10;
        $date = date('Y-m-d 00:00:00', strtotime("-{$outDaily} days"));

        $query = DB::table($model->getTable())->where('created_at', '<', $date);
        $queryDel = clone $query;
        $clearCount = $queryDel->limit(10000)->delete();
        $total = $query->count();

        $data = array(
            'done' => $total == 0,
            'clear_count' => $clearCount,
            'total' => $total,
        );

        return ApiResponse::success($data);
    }

}