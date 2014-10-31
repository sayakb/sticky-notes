<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Defines the various routes used by Sticky Notes
|
*/

// Create and revise paste routes
Route::group(array('before' => 'auth.enforce'), function()
{
	Route::get('/', 'CreateController@getCreate');

	Route::get('rev/{urlkey}', 'CreateController@getRevision')->where('urlkey', 'p[a-zA-Z0-9]+');
});

Route::post('create', 'CreateController@postCreate');

Route::post('revise', 'CreateController@postRevision');

// Show paste routes
Route::group(array('before' => 'numeric'), function()
{
	Route::get('{urlkey}/{hash?}/{action?}/{extra?}', 'ShowController@getPaste')->where('urlkey', 'p[a-zA-Z0-9]+|[0-9]+');
});

Route::get('attachment/{urlkey}/{hash?}', 'ShowController@getAttachment');

Route::get('diff/{oldkey}/{newkey}', 'ShowController@getDiff');

Route::post('{urlkey}/{hash?}', 'ShowController@postPassword')->where('urlkey', 'p[a-zA-Z0-9]+');

Route::post('comment', 'ShowController@postComment');

// List paste routes
Route::group(array('before' => 'private'), function()
{
	Route::get('all', 'ListController@getAll');

	Route::get('trending/{age?}', 'ListController@getTrending');

	Route::get('search', 'ListController@getSearch');

	Route::post('search', 'ListController@postSearch');

	// Admin-only lists
	Route::group(array('before' => 'admin'), function()
	{
		Route::get('flagged', 'ListController@getFlagged');
	});
});

// API routes
Route::get('api/{mode}/parameter/{param}', 'ApiController@getParameter');

Route::get('api/{mode}/show/{urlkey}/{hash?}/{password?}', 'ApiController@getShow');

Route::get('api/{mode}/list/{page?}', 'ApiController@getList');

Route::post('api/{mode}/create', 'ApiController@postCreate');

// Feed routes
Route::get('feed/{type?}', 'FeedController@getFeed')->where('type', 'rss');

// AJAX routes
Route::controller('ajax', 'AjaxController');

// Application setup routes
Route::controller('setup', 'SetupController');

// Documentation routes
Route::get('docs', function()
{
	return Redirect::to(Site::config('services')->docsUrl);
});

// User operation routes
Route::get('user/login', 'UserController@getLogin');

Route::post('user/login', 'UserController@postLogin');

Route::get('user/logout', 'UserController@getLogout');

Route::get('user/register', 'UserController@getRegister');

Route::get('user/forgot', 'UserController@getForgot');

// DB-only user operations
Route::group(array('before' => 'auth.db'), function()
{
	// Submit user registration
	Route::post('user/register', 'UserController@postRegister');

	// Submit forgot password
	Route::post('user/forgot', 'UserController@postForgot');

	// Submit user profile
	Route::group(array('before' => 'auth'), function()
	{
		Route::post('user/profile', 'UserController@postProfile');
	});
});

// Protected routes
Route::group(array('before' => 'auth'), function()
{
	// User pastes route
	Route::get('user/{userid}/pastes', 'ListController@getUserPastes')->where('userid', 'u[0-9]+');

	// User profile route
	Route::get('user/profile', 'UserController@getProfile');

	// Admin only routes
	Route::group(array('before' => 'admin'), function()
	{
		Route::controller('admin', 'AdminController');
	});
});

// Installed state check for everything
Route::when('*', 'installed', array('get', 'post'));

// Global message population
Route::when('*', 'global', array('get'));

// CSRF protection for all forms
Route::when('*', 'csrf', array('post'));
