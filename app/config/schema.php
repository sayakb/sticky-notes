<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Table schemas
	|--------------------------------------------------------------------------
	|
	| This file defines the table schema to be used for installs / updates.
	|
	*/

	'install' => array(

		'tables' => array(

			'config' => array(

				(object) array(
					'name'     => 'id',
					'type'     => 'increments',
				),

				(object) array(
					'name'     => 'group',
					'type'     => 'string',
					'length'   => 30,
				),

				(object) array(
					'name'     => 'key',
					'type'     => 'string',
					'length'   => 30,
				),

				(object) array(
					'name'     => 'value',
					'type'     => 'text',
					'nullable' => TRUE,
				),

			),

			'cron' => array(

				(object) array(
					'name'     => 'timestamp',
					'type'     => 'integer',
					'default'  => 0,
				),

				(object) array(
					'name'     => 'locked',
					'type'     => 'boolean',
					'default'  => 0,
				),

			),

			'ipbans' => array(

				(object) array(
					'name'     => 'ip',
					'type'     => 'string',
					'length'   => 50,
				),

				(object) array(
					'name'     => 'ip',
					'type'     => 'primary',
				),

			),

			'main' => array(

				(object) array(
					'name'     => 'id',
					'type'     => 'increments',
				),

				(object) array(
					'name'     => 'urlkey',
					'type'     => 'string',
					'length'   => 9,
				),

				(object) array(
					'name'     => 'urlkey',
					'type'     => 'index',
				),

				(object) array(
					'name'     => 'author_id',
					'type'     => 'integer',
					'nullable' => TRUE,
					'default'  => NULL,
				),

				(object) array(
					'name'     => 'author',
					'type'     => 'string',
					'length'   => 50,
					'nullable' => TRUE,
					'default'  => '',
				),

				(object) array(
					'name'     => 'author',
					'type'     => 'index',
				),

				(object) array(
					'name'     => 'project',
					'type'     => 'string',
					'length'   => 50,
					'nullable' => TRUE,
					'default'  => '',
				),

				(object) array(
					'name'     => 'project',
					'type'     => 'index',
				),

				(object) array(
					'name'     => 'timestamp',
					'type'     => 'integer',
				),

				(object) array(
					'name'     => 'expire',
					'type'     => 'integer',
				),

				(object) array(
					'name'     => 'title',
					'type'     => 'string',
					'length'   => 25,
					'nullable' => TRUE,
					'default'  => '',
				),

				(object) array(
					'name'     => 'data',
					'type'     => 'text',
				),

				(object) array(
					'name'     => 'language',
					'type'     => 'string',
					'length'   => 50,
					'default'  => 'text',
				),

				(object) array(
					'name'     => 'password',
					'type'     => 'string',
					'length'   => 60,
				),

				(object) array(
					'name'     => 'salt',
					'type'     => 'string',
					'length'   => 5,
				),

				(object) array(
					'name'     => 'private',
					'type'     => 'boolean',
					'default'  => 0,
				),

				(object) array(
					'name'     => 'private',
					'type'     => 'index',
				),

				(object) array(
					'name'     => 'hash',
					'type'     => 'string',
					'length'   => 12,
				),

				(object) array(
					'name'     => 'ip',
					'type'     => 'string',
					'length'   => 50,
				),

				(object) array(
					'name'     => 'hits',
					'type'     => 'integer',
					'default'  => 0,
				),

			),

			'revisions' => array(

				(object) array(
					'name'     => 'id',
					'type'     => 'increments',
				),

				(object) array(
					'name'     => 'paste_id',
					'type'     => 'integer',
				),

				(object) array(
					'name'     => 'urlkey',
					'type'     => 'string',
					'length'   => 9,
				),

				(object) array(
					'name'     => 'author',
					'type'     => 'string',
					'length'   => 50,
					'nullable' => TRUE,
					'default'  => NULL,
				),

				(object) array(
					'name'     => 'timestamp',
					'type'     => 'integer',
				),

			),

			'users' => array(

				(object) array(
					'name'     => 'id',
					'type'     => 'increments',
				),

				(object) array(
					'name'     => 'username',
					'type'     => 'string',
					'length'   => 50,
				),

				(object) array(
					'name'     => 'username',
					'type'     => 'index',
				),

				(object) array(
					'name'     => 'password',
					'type'     => 'string',
					'length'   => 60,
				),

				(object) array(
					'name'     => 'salt',
					'type'     => 'string',
					'length'   => 5,
				),

				(object) array(
					'name'     => 'email',
					'type'     => 'string',
					'length'   => 100,
				),

				(object) array(
					'name'     => 'dispname',
					'type'     => 'string',
					'length'   => 100,
					'nullable' => TRUE,
					'default'  => '',
				),

				(object) array(
					'name'     => 'admin',
					'type'     => 'boolean',
					'default'  => 0,
				),

				(object) array(
					'name'     => 'type',
					'type'     => 'string',
					'length'   => 10,
					'default'  => 'db',
				),

				(object) array(
					'name'     => 'active',
					'type'     => 'boolean',
					'default'  => 1,
				),

			),

		),

		'closure' => function()
		{
			// Get the FQDN for the server
			$fqdn = getenv('SERVER_NAME');

			// Get the app configuration
			$app = Config::get('app');

			// Generate user credentials
			$username = 'admin';

			$password = str_random(8);

			// Save the user info to session
			Session::put('install.username', $username);

			Session::put('install.password', $password);

			// Create the admin user
			$user = new User;

			$user->username = $username;
			$user->email    = $username.'@'.$fqdn;
			$user->salt     = str_random(5);
			$user->password = PHPass::make()->create($password, $user->salt);
			$user->admin    = 1;

			$user->save();

			// Insert fqdn and app version to site config
			Site::config('general', array(
				'fqdn'     => $fqdn,
				'version'  => $app['version'],
			));
		},

	),

);
