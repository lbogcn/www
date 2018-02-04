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
        return config('menu');
    }

}