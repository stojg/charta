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

Route::get('/', 'WelcomeController@index');
Route::get('home', 'DocumentController@index');

Route::get('signin', 'Auth\AuthController@signin');
Route::get('authorize/{provider?}', 'Auth\AuthController@authorize');
Route::get('login/{provider?}', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');


Route::controllers([
	'document' => 'DocumentController'
]);
