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
Route::get('{key}/{hash?}/{action?}', 'ShowController@getPaste')->where('key', 'p[a-zA-Z0-9]+|[0-9]+');
Route::post('{key}/{hash?}', 'ShowController@postPassword')->where('key', 'p[a-zA-Z0-9]+|[0-9]+');

// List paste routes
Route::get('all', 'ListController@getAll');
Route::get('trending/{age?}', 'ListController@getTrending');

// User operation routes
Route::controller('user', 'UserController');

// Protected routes
Route::group(array('before' => 'auth'), function()
{
	// User pastes route
	Route::get('mypastes', 'ListController@getUserPastes');

	// Admin only routes
	Route::group(array('before' => 'admin'), function()
	{
		// Admin routes
		Route::controller('admin', 'AdminController');
	});
});

// CSRF protection for all forms
Route::when('*', 'csrf', array('post'));
