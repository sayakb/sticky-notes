<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Antispam Configuration
	|--------------------------------------------------------------------------
	|
	| Antispam configuration is managed from the database. This file allows
	| developers to add service configurations that are not editable by the
	| pastebin admins.
	|
	| Immutable services are always run, even if they are not set by the admin
	|
	*/

	'immutable'  => array('ipban'),

);
