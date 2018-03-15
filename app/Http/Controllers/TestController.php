<?php

namespace App\Http\Controllers;


class TestController extends Controller
{

    public function index()
    {
        if (!config('app.debug')) {
            abort(404);
        }
    }

}