<?php

namespace App\Components\Upload;


interface Contracts
{

    /**
     * 存储
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function storage($request);

    /**
     * 获取 upload token
     * @return string
     */
    public function getToken();

    /**
     * 获取上传 action
     * @return string
     */
    public function getAction();

}