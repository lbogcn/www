<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    
    return view('welcome');
});

Route::group(array(
    'domain' => 'admin-l.lbog.cn',
    'namespace' => 'Admin',
), function() {

    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');

    // 获取菜单
    Route::get('menu', 'HomeController@menu')->middleware(['auth:admin']);

    Route::group(array(
        'middleware' => ['admin'],
    ), function() {

        Route::resources([
            'permission/user' => 'Permission\UserController'
        ]);
    });
});