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

Route::group(array(
    'domain' => env('DOMAIN_ADMIN'),
    'namespace' => 'Admin',
), function() {

    Route::get('test', 'HomeController@test');

    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');

    Route::get('', 'HomeController@index');

    // 获取菜单
    Route::get('menu', 'HomeController@menu')->middleware(['auth:admin']);

    Route::group(array(
        'middleware' => ['admin'],
    ), function() {

        Route::get('permission/node/init', 'Permission\NodeController@init');
        Route::get('permission/node/sync', 'Permission\NodeController@sync');

        Route::resources([
            'permission/user' => 'Permission\UserController',
            'permission/role' => 'Permission\RoleController',
            'permission/node' => 'Permission\NodeController',
            'permission/menu' => 'Permission\MenuController',
        ]);
    });
});