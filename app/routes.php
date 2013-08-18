<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Defines the various routes used by Sticky Notes
|
*/

// Create paste route
Route::get('/', 'CreateController@getCreate');
Route::post('create', 'CreateController@postCreate');

// Show paste routes
Route::get('{key}/{hash?}/{mode?}', 'ShowController@getPaste')->where('key', 'p[a-zA-Z0-9]+|[0-9]+');
Route::post('{key}/{hash?}', 'ShowController@postPassword')->where('key', 'p[a-zA-Z0-9]+|[0-9]+');

// List paste route
Route::get('all', 'ListController@getAll');
Route::get('trending/{age?}', 'ListController@getTrending');

// User public routes
Route::get('user/login', 'UserController@getLogin');
Route::post('user/login', 'UserController@postLogin');
Route::get('user/logout', 'UserController@getLogout');

// Protected routes
Route::group(array('before' => 'auth'), function()
{
	// User protected routes
	Route::get('user/pastes', 'ListController@getUserPastes');

	// Admin only routes
	Route::group(array('before' => 'admin'), function()
	{
		// Admin routes
		Route::controller('admin', 'AdminController');
	});
});

// CSRF protection for all forms
Route::when('*', 'csrf', array('post'));
