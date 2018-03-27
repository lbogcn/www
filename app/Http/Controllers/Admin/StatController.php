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

        if ($daily > 60) {
            return ApiResponse::fail('只允许查询60天内数据');
        }

        $method = 'stat' . studly_case($type);
        if (!method_exists($this, $method)) {
            return ApiResponse::fail('无效类型');
        }

        $data = array(
            $type => $this->$method($daily),
        );

        return ApiResponse::success($data);
    }

    /**
     * 移动流量
     * @param $daily
     * @return array
     */
    protected function statMobileTraffic($daily)
    {
        $enums = [
            config('enum.stat.aos_count'),
            config('enum.stat.ios_count'),
            config('enum.stat.wechat_count'),
        ];
        $keys = array_column($enums, 'code');

        $date = date('Y-m-d', strtotime("-{$daily} days"));
        $stats = Stat::select(['type', \DB::raw('count(*) as count')])
            ->where('date', '>=', $date)
            ->whereIn('type', $keys)
            ->groupBy('type')
            ->get();
        $rows = [];

        foreach ($enums as $enum) {
            /** @var Stat $row */
            $row = $stats->where('type', $enum['code'])
                ->first();

            $value = empty($row) ? 0 : $row->count;
            $rows[] = array(
                'name' => $enum['label'],
                'value' => intval($value),
            );
        }

        return $rows;
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