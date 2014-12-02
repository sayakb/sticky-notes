<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Immutable antispam services
	|--------------------------------------------------------------------------
	|
	| Antispam configuration is managed from the database. This option allows
	| developers to add service configurations that are not editable by the
	| pastebin admins.
	|
	| Immutable services are always run, even if they are not set by the admin
	|
	*/

	'immutable'  => array('ipban'),

	/*
	|--------------------------------------------------------------------------
	| Scope declaration
	|--------------------------------------------------------------------------
	|
	| This value defines the scope of the antispam plugins.
	|
	*/

	'scopes'  => array(

		'paste'    => array('ipban', 'censor', 'noflood', 'php', 'stealth'),

		'comment'  => array('ipban', 'censor', 'noflood', 'php', 'akismet'),

		'search'   => array('ipban', 'noflood'),

		'api_call' => array('ipban', 'censor', 'noflood', 'php'),

	),

);
