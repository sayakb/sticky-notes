<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

// Reads a Sticky Notes config value and displays it
Artisan::add(new ConfigReader);

// Sets a Sticky Notes config value
Artisan::add(new ConfigWriter);
