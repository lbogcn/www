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

Route::get('test', 'TestController@index');

require __DIR__ . '/admin.php';

Route::group(array(
    'domain' => config('global.domain.web'),
    'namespace' => 'Web',
), function() {

    Route::get('', 'HomeController@index');

    Route::get('article/{article}.html', 'ArticleController@index');

    Route::get('{alias}.html', 'CategoryController@index');
    Route::get('{alias}/p_{page}.html', 'CategoryController@paginate');
});
