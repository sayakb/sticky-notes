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

// Create paste route
Route::controller('new', 'CreateController');
