<?php

Route::group(array(
    'domain' => config('global.domain.admin'),
    'namespace' => 'Admin',
), function() {

    // 入口
    Route::get('', 'HomeController@index');

    // 登入登出
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');
    Route::post('modify-password', 'AuthController@modifyPassword')->middleware(['auth:admin', 'admin_operation_log:admin']);

    // 获取菜单
    Route::get('init', 'HomeController@init')->middleware(['auth:admin']);

    Route::group(array(
        'middleware' => ['admin'],
    ), function() {

        Route::get('stat', 'StatController@index');

        Route::get('upload-token', 'HomeController@uploadToken');
        Route::post('upload', 'HomeController@upload');

        Route::get('permission/node/init', 'Permission\NodeController@init');
        Route::post('permission/node/sync', 'Permission\NodeController@sync');

        Route::get('article-categories', 'Article\ArticleController@categories');

        Route::get('article/preview/{article}', 'Article\ArticleController@preview');

        Route::post('cover/static', 'CoverController@cache');
        Route::post('key-value/static', 'KeyValueController@cache');

        Route::get('log/error/init', 'Log\ErrorController@init');

        Route::resources([
            'permission/user' => 'Permission\UserController',
            'permission/user.role' => 'Permission\UserRoleController',
            'permission/role' => 'Permission\RoleController',
            'permission/role.permission' => 'Permission\RolePermissionController',
            'permission/node' => 'Permission\NodeController',
            'permission/menu' => 'Permission\MenuController',
            'log/operation' => 'Log\OperationController',
            'log/error' => 'Log\ErrorController',
            'article/category' => 'Article\CategoryController',
            'article' => 'Article\ArticleController',
            'questionnaires' => 'QuestionnairesController',
            'message' => 'MessageController',
            'cover' => 'CoverController',
            'key-value' => 'KeyValueController',
        ]);
    });
});

