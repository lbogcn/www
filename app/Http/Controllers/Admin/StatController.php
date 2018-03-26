<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Stat;
use Illuminate\Http\Request;

class StatController extends Controller
{
    const PERMISSION = array(
        'title' => '统计',
        'nodes' => [
            ['action' => 'index', 'name' => '获取数据'],
        ],
    );

    /**
     * 获取数据
     * @param Request $request
     * @return ApiResponse
     */
    public function index(Request $request)
    {
        $daily = intval($request->input('daily')) - 1;
        $type = $request->input('type');

        $statsConfig = config('enum.stat');

        if ($daily > 60) {
            return ApiResponse::fail('只允许查询60天内数据');
        }

        if (!isset($statsConfig[$type]) && $type != '') {
            return ApiResponse::fail('无效类型');
        }

        $rows = array(
            $type => $this->{'stat' . studly_case($type)}($daily),
        );

        return ApiResponse::success($rows);
    }

    /**
     * 统计PV
     * @param $daily
     * @return array
     */
    protected function statPv($daily)
    {
        $date = date('Y-m-d', strtotime("-{$daily} days"));
        $stats = Stat::where('date', '>=', $date)
            ->where('type', config('enum.stat.pv.code'))
            ->get();
        $rows = [];

        for ($i = $daily - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            /** @var Stat $row */
            $row = $stats->where('date', $date)
                ->first();
            if (empty($row)) {
                $rows[$date] = 0;
            } else {
                $rows[$date] = $row->count;
            }
        }

        return $rows;
    }

    /**
     * 百度蜘蛛抓取次数
     * @param $daily
     * @return array
     */
    protected function statBaiduSpider($daily)
    {
        $date = date('Y-m-d', strtotime("-{$daily} days"));
        $stats = Stat::where('date', '>=', $date)
            ->where('type', config('enum.stat.baidu_spider.code'))
            ->get();
        $rows = [];

        for ($i = $daily - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            /** @var Stat $row */
            $row = $stats->where('date', $date)
                ->first();
            if (empty($row)) {
                $rows[$date] = 0;
            } else {
                $rows[$date] = $row->count;
            }
        }

        return $rows;
    }

}