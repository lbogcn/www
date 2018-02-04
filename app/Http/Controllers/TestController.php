<?php

namespace App\Http\Controllers;


class TestController extends Controller
{

    public function index()
    {
        if (!config('app.debug')) {
            abort(404);
        }

        return $this->b();
    }

    private function b()
    {
        return bcrypt('123456');
    }

    private function a()
    {
        return config('menu');
    }

}