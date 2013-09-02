<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		return Redirect::guest('user/login');
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check())
	{
		return Redirect::to('/');
	}
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
| CSRF protection is not applied to API POST requests.
|
*/

Route::filter('csrf', function()
{
	if (Request::segment(1) != 'api')
	{
		if (Session::token() != Input::get('_token'))
		{
			throw new Illuminate\Session\TokenMismatchException;
		}
	}
});

/*
|--------------------------------------------------------------------------
| Admin only filter
|--------------------------------------------------------------------------
|
| This filter validates that the logged in user is an administrator.
|
*/

Route::filter('admin', function()
{
	if ( ! Auth::check() OR ! Auth::user()->admin)
	{
		App::abort(401);
	}
});

/*
|--------------------------------------------------------------------------
| Setup validation filter
|--------------------------------------------------------------------------
|
| This filter checks if Sticky Notes is marked as installed.
|
| The following checks are done:
|  - If the main table does not exist, it is a fresh install
|  - If the main table is there, but versions mismatch, it is an update
|  - If main table is there and versions match, we should get out of setup
|
*/

Route::filter('installed', function()
{
	try
	{
		$installed = Schema::hasTable('main');
	}
	catch (Exception $e)
	{
		$installed = FALSE;
	}

	// Set installed state to cache
	Session::put('global.installed', $installed);

	// Now we get the app and DB versions
	// If there is no version data in the DB, the function will return 0
	$app = Config::get('app');

	$db = Site::config('general');

	// Derive app and db version numbers
	$appVersion = Site::versionNbr($app['version']);

	$dbVersion = Site::versionNbr($db->version);

	// Now down to business: do the checks
	if (Request::segment(1) != 'setup')
	{
		Session::forget('install.stage');

		if ( ! $installed)
		{
			return Redirect::to('setup/install');
		}
		else if ($appVersion > $dbVersion)
		{
			return Redirect::to('setup/update');
		}
	}
	else if ($installed AND $appVersion == $dbVersion AND ! Session::has('install.stage'))
	{
		return Redirect::to('/');
	}
});
