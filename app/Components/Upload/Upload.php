<?php

namespace App\Components\Upload;


class Upload
{

    /** @var Contracts */
    private $driver;

    /**
     * Upload constructor.
     * @param UploadMgr $mgr
     */
    public function __construct(UploadMgr $mgr)
    {
        $this->driver = $mgr->resolve();
    }

    /**
     * 存储
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function storage($request)
    {
        return $this->driver->storage($request);
    }

    /**
     * 获取上传配置
     * @return array
     */
    public function uploadConfig(): array
    {
        $token = $this->driver->getToken();
        $action = $this->driver->getAction();

        return compact('token', 'action');
    }

    /**
     * 获取驱动对象
     * @return Contracts
     */
    public function getDriver()
    {
        return $this->driver;
    }

}