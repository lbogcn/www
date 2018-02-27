<?php

namespace App\Http\Controllers\Admin\Log;

use App;
use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SplFileObject;

class ErrorController extends Controller
{

    const PERMISSION = array(
        'title' => '错误日志',
        'nodes' => [
            ['action' => 'init', 'name' => '初始化'],
            ['action' => 'index', 'name' => '读取'],
        ],
    );

    /**
     * 初始化
     * @return ApiResponse
     */
    public function init()
    {
        $files = glob(App::storagePath() . '/logs/*.log');
        foreach ($files as &$file) {
            $file = basename($file);
        }

        return ApiResponse::success(compact('files'));
    }

    /**
     * 列表
     * @param Request $request
     * @return ApiResponse
     */
    public function index(Request $request)
    {
        $file = App::storagePath() . "/logs/{$request->input('search.file')}";
        if (substr($file, -4) != '.log' || !file_exists($file)) {
            return ApiResponse::fail('日志文件不存在');
        }

        $data = array(
            'paginate' => $this->paginate($file, $request->input('page')),
        );

        return ApiResponse::success($data);
    }

    /**
     * 分页获取
     * @param $file
     * @param $page
     * @return array
     */
    private function paginate($file, $page)
    {
        $data = [];
        $total = 0;
        $handle = new SplFileObject($file);

        // 统计总行数
        while (!$handle->eof()) {
            $block = $handle->fread(4096);
            $total += substr_count($block, "\n");
        }

        $current_page = intval((is_numeric($page) && $page >= 1) ? $page : 1);
        $per_page = 50;
        $range = [
            $total - $current_page * $per_page,
            $total - ($current_page - 1) * $per_page,
        ];

        foreach ($handle as $index => $line) {
            if ($index > $range[0] && $index <= $range[1]) {
                $currentLine = $index + 1;

                $data[] = array(
                    'line' => intval($currentLine),
                    'content' => substr($line, -1) === "\n" ? substr($line, 0, strlen($line) - 1) : $line,
                );
            }

            if ($index > $range[1]) {
                break;
            }
        }

        $data = array_reverse($data);

        return compact('data', 'total', 'current_page', 'per_page');
    }

}