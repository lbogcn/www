<?php

namespace App\Components\Upload;

class UploadMgr
{

    /**
     * @return Contracts
     */
    public function resolve()
    {
        $driver = config('upload.driver');
        $config = config("upload.services.{$driver}");
        $driverMethod = 'create'.ucfirst($driver).'Driver';

        return $this->{$driverMethod}($config);
    }

    /**
     * 本地存储
     * @param $config
     * @return Local
     */
    public function createLocalDriver($config)
    {
        return new Local($config);
    }

    /**
     * 七牛存储
     * @param $config
     * @return Qiniu
     */
    public function createQiniuDriver($config)
    {
        return new Qiniu($config);
    }
}