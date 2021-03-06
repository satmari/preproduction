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

// Route::get('/', 'WelcomeController@index');
Route::get('/', 'HomeController@index');

// LoginOperatorController
Route::get('operator_login', 'LoginOperatorController@index');
Route::post('login_operator','LoginOperatorController@login_operator');
Route::get('table_operator', 'LoginOperatorController@table_operator');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
