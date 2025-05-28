<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'PagesController@index')->name('home');

Route::get('/list', 'PagesController@list')->name('list');

Route::get('/images', 'PagesController@root')->name('root');

Auth::routes(['verify' => true]);

Route::get('/change/password/reset', 'UserController@showRequestForm')->name('change.password.request');

Route::post('/change/password/reset', 'UserController@passwordrest')->name('change.password.update');

Route::get('/profile/', 'UserController@showProfileForm')->name('profile.request');

Route::post('/profile/', 'UserController@profile')->name('profile.update');

Route::get('/hjh-workflow/', 'HjhController@showWorkflowForm')->name('workflow.request');
Route::get('/hjh-workflowimage/', 'HjhController@showWorkflowImageForm')->name('workflow.image.upload');
Route::post('/hjh-workflow/', 'HjhController@create')->name('workflow.create');
Route::get('/hjh-image/', 'HjhController@image')->name('hjh.image');
Route::post('/hjh-callback', 'HjhController@callback')->name('workflow.callback');

Route::get('/image/', 'UserController@showImageForm')->name('image.request');

Route::post('/image/', 'UserController@image')->name('image.update');

Route::get('/music/{id}', 'PagesController@music')->name('music');

Route::get('/music/', 'UserController@showMusicForm')->name('music.request');

Route::post('/music/', 'UserController@music')->name('music.update');

Route::get('/photo/{id}', 'PagesController@show')->name('image.show');

Route::get('/license/', 'PagesController@license')->name('image.license');

Route::get('/play/', 'PagesController@play')->name('image.play');

Route::get('/user/{id}', 'PagesController@user')->name('image.user');

Route::get('/favorite', 'UserController@favorites')->name('image.favorite');

Route::get('/tag/{name}', 'PagesController@tag')->name('image.tag');

Route::post('/favorite', 'UserController@favorite');

Route::post('/unfavorite', 'UserController@unfavorite');

Route::get('/find/{query?}', 'PagesController@find')->name('image.find');
