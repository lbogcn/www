<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function index()
    {
        $data = array(
            'main_title' => config('app.name'),
            'sub_title' => '林博的博客',
            'intro' => 'large eagle narrate bygone opus.',
        );

        return view('web.welcome', $data);
    }

}