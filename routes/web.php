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

    // 入口
    Route::get('', 'HomeController@index');

    // 登入登出
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');

    // 获取菜单
    Route::get('menu', 'HomeController@menu')->middleware(['auth:admin']);

    Route::group(array(
        'middleware' => ['admin'],
    ), function() {

        Route::get('permission/node/init', 'Permission\NodeController@init');
        Route::get('permission/node/sync', 'Permission\NodeController@sync');

        Route::resources([
            'permission/user' => 'Permission\UserController',
            'permission/user.role' => 'Permission\UserRoleController',
            'permission/role' => 'Permission\RoleController',
            'permission/role.permission' => 'Permission\RolePermissionController',
            'permission/node' => 'Permission\NodeController',
            'permission/menu' => 'Permission\MenuController',
        ]);
    });
});