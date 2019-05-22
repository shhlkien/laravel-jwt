<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
// 	return $request->user();
// });

Route::group([

	'middleware' => ['api'],
	'prefix' => 'v1/auth'

], function ($router) {

	Route::post('register', 'AuthController@register');
	Route::post('login', 'AuthController@login');

	Route::group([
		'middleware' => ['jwt.verify']
	], function() {

		Route::post('logout', 'AuthController@logout');
		Route::post('refresh', 'AuthController@refresh');
		Route::post('reset-password', 'AuthController@resetPassword');
		Route::get('me', 'AuthController@me');
	});

});