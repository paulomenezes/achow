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

Route::group(['prefix' => 'users'], function () {
	Route::get('/', 'UserController@index');
	Route::post('/login', 'UserController@login');
	Route::post('/login/facebook', 'UserController@loginFacebook');
	Route::get('/notification/{id}/{msg}', 'UserController@notification');
	Route::get('/{id}', 'UserController@show');
	Route::get('/{id}/and/{id2}', 'UserController@areFriend');
	Route::get('/{id}/friends', 'UserController@friends');
	Route::get('/{id}/friends2', 'UserController@friends2');
	Route::get('/friends/{id}', 'UserController@friends2');
	Route::get('/{id}/requests', 'UserController@friendRequests');
	Route::post('/{id}/requests/aceitar', 'UserController@friendRequestsAccept');
	Route::get('/{id}/requests/recusar', 'UserController@friendRequestsDelete');
	Route::get('/{id}/checkins', 'UserController@checkins');
	Route::get('/find/{search}/{id}', 'UserController@find');
	Route::get('/password/{id}/{current}/{new}', 'UserController@password');
	Route::post('/', 'UserController@store');
	Route::post('/requests', 'UserController@storeFriendRequests');
	Route::post('/update/{id}', 'UserController@update');
	Route::delete('/{id}', 'UserController@destroy');
	Route::get('/{id}/delete/friend', 'UserController@destroyFriend');
});

Route::group(['prefix' => 'notifications'], function () {
	Route::get('/{id}', 'NotificationController@show');
	Route::get('/{id}/read', 'NotificationController@showRead');
	Route::post('/', 'NotificationController@store');
});

Route::group(['prefix' => 'chat'], function () {
	Route::get('/{id}', 'ChatController@index');
	Route::get('/room/{id}', 'ChatController@room');
	Route::get('/delete/{id}', 'ChatController@destroy');
	Route::post('/', 'ChatController@store');
});

Route::group(['prefix' => 'sub_types'], function () {
	Route::get('/', 'StoreSubTypeController@index');
});

Route::group(['prefix' => 'ads'], function () {
	Route::get('/{type}', 'AdsController@index');
});

Route::group(['prefix' => 'order'], function () {
	Route::get('/{user}', 'StoreOrderController@index');
	Route::post('/', 'StoreOrderController@store');
});

Route::group(['prefix' => 'stores'], function () {
	Route::get('/{type}/{city}/{sub}', 'StoreController@index');
	Route::get('/visited', 'StoreController@visited');
	Route::get('/{store}/extras', 'StoreController@extras');
	Route::get('/{store}/schedule', 'StoreController@schedule');
	Route::get('/{store}/menu', 'StoreController@menu');
	Route::get('/find/{search}', 'StoreController@find');
	Route::post('/checkin', 'StoreController@checkinStore');
});

Route::group(['prefix' => 'store_visited'], function () {
	Route::get('/{user}/{type}/{store}', 'StoreVisitedController@index');
	Route::get('/shows/{user}/{type}/{store}', 'StoreVisitedController@shows');
	Route::get('/{user}/favorite', 'StoreVisitedController@favorite');
	Route::post('/', 'StoreVisitedController@store');
});

Route::group(['prefix' => 'store_comment'], function () {
	Route::get('/{store}', 'StoreCommentController@index');
	Route::get('/shows/{store}', 'StoreCommentController@shows');
	Route::post('/', 'StoreCommentController@store');
});