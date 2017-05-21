<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('welcome');
    });

//    Route::any('admin/crypt', 'Admin\LoginController@crypt');
    Route::get('admin/login', 'Admin\LoginController@login');
    Route::post('admin/store', 'Admin\LoginController@store');
    Route::get('admin/code', 'Admin\LoginController@code');

//});

Route::group(['middleware' => ['web', 'admin.login'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/', 'IndexController@index');
    Route::get('index', 'IndexController@index');
    Route::get('info', 'IndexController@info');
    Route::get('quit', 'IndexController@quit');


});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('pass', 'IndexController@pass');
    Route::post('repass', 'IndexController@repass');

//    Route::get('category/create', 'CategoryController@create');
//    Route::post('category/store', 'CategoryController@store');
    Route::any('upload', 'CommonController@upload');
});

//web中间件从laravel 5.2.27版本以后默认全局加载，不需要自己手动载入，如果自己手动重复载入，会导致session无法加载的情况,因而去除web中间件即可使用
Route::group(['middleware' => 'admin.login', 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
//    Route::get('category/create', 'CategoryController@create');
//    Route::post('category/store', 'CategoryController@store');
    Route::post('cate/changeorder', 'CategoryController@changeOrder');
    Route::resource('category', 'CategoryController');

    Route::resource('article', 'ArticleController');

    Route::get('config/putfile', 'ConfigController@putFile');
    Route::post('config/changecontent', 'ConfigController@changeContent');
    Route::post('config/changeorder', 'ConfigController@changeOrder');
    route::resource('config', 'ConfigController');
});