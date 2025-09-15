<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('/image', 'ImageController');
    $router->resource('/video', 'VideoController');
    $router->resource('/task', 'TaskController');
    $router->resource('/music', 'MusicController');
    $router->resource('/user', 'UserController');
});
