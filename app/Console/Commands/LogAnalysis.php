<?php

namespace App\Console\Commands;

use App\Models\Stat;
use DB;
use Exception;
use Illuminate\Console\Command;

class LogAnalysis extends Command
{

    protected $signature = 'log-analysis
                            {--file= : 日志路径}
                            {--daily=1 : 统计daily天前的数据}';

    protected $description = '访问日志分析统计';

    private $commands = [];

    /**
     * LogAnalysis constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->commands = $this->createCommands();
    }

    /**
     * 处理
     * @throws Exception
     * @throws \Throwable
     */
    public function handle()
    {
        $file = $this->option('file');
        if (!file_exists($file)) {
            $this->error("文件 {$file} 不存在！");
            return;
        }

        $time = strtotime("-{$this->option('daily')} day");

        $this->info('------ count date: ' . date('Y-m-d', $time) . '------' . PHP_EOL);

        $this->stat($file, $time);
    }

    /**
     * 创建命令
     * @return array
     */
    private function createCommands()
    {
        return array(
            config('enum.stat.top_url.code') => "cat :file | grep ':date' | awk '{print $7}' | sort | uniq -c | sort -nr | head -n 10",
            config('enum.stat.top_ip.code') => "cat :file | grep ':date' | awk '{print $1}' | sort | uniq -c | sort -nr | head -n 10",
            config('enum.stat.baidu_spider.code') => "cat :file | grep ':date' | grep 'Baiduspider' | awk '{print \"value\"}' |uniq -c | head -n 1",
            config('enum.stat.aos_count.code') => "cat :file | grep ':date' | grep 'Android' | awk '{print \"value\"}' |uniq -c | head -n 1",
            config('enum.stat.ios_count.code') => "cat :file | grep ':date' | grep 'iPhone' | awk '{print \"value\"}' |uniq -c | head -n 1",
            config('enum.stat.wechat_count.code') => "cat :file | grep ':date' | grep 'MicroMessenger' | awk '{print \"value\"}' |uniq -c | head -n 1",
            config('enum.stat.pv.code') => "cat :file | grep ':date' | awk '{print \"value\"}' | sort | uniq -c | sort -nr | head -n 10",
        );
    }

    /**
     * 执行统计
     * @param $file
     * @param $time
     * @throws Exception
     */
    private function stat($file, $time)
    {
        $date = date('d/M/Y', $time);

        foreach ($this->commands as $type => $command) {
            $command = str_replace([':file', ':date'], [$file, $date], $command);
            $output = null;
            $status = 1;

            $this->info("before exec command: {$command}");

            exec($command, $output, $status);

            if ($status == 0) {
                $this->info("before exec insert");

                $this->insert($time, $type, $output);

                $this->info('insert success');
            } else {
                $this->error("exec {$command} failed! status code = {$status}");
            }

            $this->info('');
        }
    }

    /**
     * 插入DB
     * @param $time
     * @param $type
     * @param array $output
     * @throws Exception
     */
    private function insert($time, $type, array $output)
    {
        $rows = array();
        $date = date('Y-m-d', $time);

        foreach ($output as $line) {
            $line = explode(' ', $line);
            $len = count($line);
            if ($len < 2) {
                continue;
            }

            $value = $line[$len - 1];
            $count = $line[$len - 2];

            $rows[] = compact('date', 'type', 'count', 'value');
        }

        DB::beginTransaction();
        try {
            Stat::where('date', $date)->where('type', $type)->delete();
            Stat::insert($rows);
            DB::commit();
        } catch (Exception $e) {
            $this->error("exception {$e->getMessage()}");

            DB::rollBack();
        }
    }


}