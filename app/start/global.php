<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a rotating log file setup which creates a new file each day.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenace mode is in effect for this application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/*
|--------------------------------------------------------------------------
| Set the configured site locale
|--------------------------------------------------------------------------
|
| Set the active site locale as defined in the general config data.
|
*/

App::setLocale(Site::config('general')->lang);

/*
|--------------------------------------------------------------------------
| Sticky Notes auth methods
|--------------------------------------------------------------------------
|
| Define the handlers for sticky-notes authentication requests.
|
*/

use Illuminate\Auth\Guard;
use Illuminate\Auth\StickyNotesDBUserProvider;
use Illuminate\Auth\StickyNotesLDAPUserProvider;

Auth::extend('stickynotesdb', function()
{
	$model = Config::get('auth.model');
	$crypt = PHPass::make();

	return new Guard(
		new StickyNotesDBUserProvider($model, $crypt),
		App::make('session')
	);
});

Auth::extend('stickynotesldap', function()
{
	$model = Config::get('auth.model');
	$auth = Site::config('auth');

	return new Guard(
		new StickyNotesLDAPUserProvider($model, $auth),
		App::make('session')
	);
});

/*
|--------------------------------------------------------------------------
| Handle application errors
|--------------------------------------------------------------------------
|
| Shows custom screens for app errors. This is mainly done to show a
| friendly error message and to throw errors with ease from the view.
|
*/

App::error(function($exception, $code)
{
	$data = array('site' => Site::config('general'));
	$type = get_class($exception);

	// Set code based on exception
	switch ($type)
	{
		case 'Illuminate\Session\TokenMismatchException':
			$code = 403;
			break;

		case 'Illuminate\Database\Eloquent\ModelNotFoundException':
		case 'InvalidArgumentException':
			$code = 404;
			break;
	}

	// Set message based on code
	switch ($code)
	{
		case 401:
		case 403:
		case 404:
		case 405:
		case 418:
			$data['errCode'] = $code;
			break;

		default:
			if (Config::get('app.debug'))
			{
				return;
			}
			else
			{
				$data['errCode'] = 'default';
			}
			break;
	}

	// For regular requests, we show a nice and pretty error screen
	// When in the API, just die on the user
	if (Request::segment(1) == 'api')
	{
		$message = Lang::get('errors.'.$data['errCode']);

		return Response::make($message, $code);
	}
	else
	{
		return Response::view('common/error', $data, $code);
	}
});
