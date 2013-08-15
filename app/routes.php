<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Defines the various routes used by Sticky Notes
|
*/

// Redirect homepage to /new
Route::get('/', function()
{
	return Redirect::to('new');
});

// Show paste route
Route::controller('show/{urlkey}/{hash?}', 'ShowController');

// List paste route
Route::controller('all', 'ListController');

// User routes
Route::controller('user', 'UserController');

// Create paste route
Route::controller('new', 'CreateController');

// Protected routes
Route::group(array('before' => 'auth|admin'), function()
{
	// Admin routes
	Route::controller('admin', 'AdminController');
});

// CSRF protection for all forms
Route::when('*', 'csrf', array('post'));
