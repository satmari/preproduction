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

// Operator Login new
Route::get('operator_login_new', 'OperatorLoginController@login');
Route::post('operator_login_new_post','OperatorLoginController@operator_login_new_post');

Route::get('operator_change_new', 'OperatorLoginController@change');
Route::post('operator_change_new_post','OperatorLoginController@operator_change_new_post');

Route::get('operator_logout_new', 'OperatorLoginController@logout');
Route::post('operator_logout_new_post','OperatorLoginController@operator_logout_new_post');

Route::get('table_operator_new', 'OperatorLoginController@table_operator_new');

// LoginPreproductionController
Route::get('preproduction_login', 'LoginPreproductionController@preproduction_login');
Route::post('preproduction_login_post','LoginPreproductionController@preproduction_login_post');
Route::post('preproduction_login_post2','LoginPreproductionController@preproduction_login_post2');
Route::get('table_preproduction', 'LoginPreproductionController@table_preproduction');

Route::get('preproduction_logout', 'LoginPreproductionController@preproduction_logout');
Route::post('preproduction_logout_post','LoginPreproductionController@preproduction_logout_post');
Route::get('edit_line/{id}', 'LoginPreproductionController@edit_line');
Route::post('edit_line_post','LoginPreproductionController@edit_line_post');
Route::post('edit_line_set','LoginPreproductionController@edit_line_set');

Route::post('remove_line','LoginPreproductionController@remove_line');
Route::post('remove_line_post','LoginPreproductionController@remove_line_post');

// Activity
Route::get('activity', 'ActivityController@index');
Route::get('add_activity','ActivityController@add_activity');
Route::post('insert_activity', 'ActivityController@insert_activity');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
