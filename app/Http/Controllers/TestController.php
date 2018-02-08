<?php

namespace App\Http\Controllers;


class TestController extends Controller
{

    public function index()
    {
        if (!config('app.debug')) {
            abort(404);
        }

        return $this->a();
    }

    private function a()
    {
        return '<form action="http://up-z2.qiniu.com/" method="post" enctype="multipart/form-data">
    <input type="text" name="token" value="{{(new \App\Components\Qiniu())->token(\'http://callback.lbog.cn/qiniu/ueditor\')}}">
    <input type="file" name="file">
    <input type="submit" value="submit">
</form>';
    }


}