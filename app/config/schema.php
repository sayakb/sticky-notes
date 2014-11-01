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
					'default'  => '',
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
					'type'     => 'longText',
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

				(object) array(
					'name'     => 'flagged',
					'type'     => 'boolean',
					'default'  => 0,
				),

				(object) array(
					'name'     => 'attachment',
					'type'     => 'boolean',
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

			'comments' => array(

				(object) array(
					'name'     => 'id',
					'type'     => 'increments',
				),

				(object) array(
					'name'     => 'paste_id',
					'type'     => 'integer',
				),

				(object) array(
					'name'     => 'data',
					'type'     => 'text',
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
					'name'     => 'remember_token',
					'type'     => 'string',
					'length'   => 60,
					'default'  => '',
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

			'statistics' => array(

				(object) array(
					'name'     => 'id',
					'type'     => 'increments',
				),

				(object) array(
					'name'     => 'date',
					'type'     => 'date',
				),

				(object) array(
					'name'     => 'web',
					'type'     => 'integer',
					'default'  => 0,
				),

				(object) array(
					'name'     => 'api',
					'type'     => 'integer',
					'default'  => 0,
				),

			),

		),

		'closure' => function()
		{

			// Get the FQDN for the server
			$fqdn = getenv('SERVER_NAME');

			// Generate user credentials
			$username = 'admin';

			$password = str_random(8);

			// Save the user info to session
			Session::put('install.username', $username);

			Session::put('install.password', $password);

			// Create the admin user
			$user = new User;

			$user->username       = $username;
			$user->email          = $username.'@'.$fqdn;
			$user->salt           = str_random(5);
			$user->password       = PHPass::make()->create($password, $user->salt);
			$user->admin          = 1;

			$user->save();

			// Insert fqdn and app version to site config
			Site::config('general', array(
				'fqdn'     => $fqdn,
				'version'  => Config::get('app.version'),
			));

		},

	),

	'update' => array(

		'0.4' => array(

			'newTables' => array(

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

			),

			'modifyTables' => array(

				'main' => array(

					(object) array(
						'name'     => 'author_id',
						'type'     => 'integer',
						'nullable' => TRUE,
						'default'  => NULL,
					),

				),

				'users' => array(

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

					(object) array(
						'name'     => 'sid',
						'type'     => 'dropColumn',
					),

					(object) array(
						'name'     => 'lastlogin',
						'type'     => 'dropColumn',
					),

				),

			),

			'closure' => function()
			{

				// Get the table prefix
				$dbPrefix = DB::getTablePrefix();

				// Change the hash datatype to VARCHAR(12)
				// A raw query is fine here as 0.4 supported MySQL only
				DB::update("ALTER TABLE {$dbPrefix}main MODIFY COLUMN hash VARCHAR(12) NOT NULL");

				// Change the urlkey to VARCHAR(9), as we prepent 'p' now
				DB::update("ALTER TABLE {$dbPrefix}main MODIFY COLUMN urlkey VARCHAR(9) NOT NULL DEFAULT ''");

				// Prepend 'p' to non-empty URL keys
				DB::update("UPDATE {$dbPrefix}main SET urlkey = CONCAT('p', urlkey) WHERE urlkey <> ''");

				// Setup admin = true for all users because
				// for 0.4, only admins could log in
				DB::update("UPDATE {$dbPrefix}users SET admin = 1");

				// Set user type = ldap for users without passwords
				DB::update("UPDATE {$dbPrefix}users SET type = 'ldap' WHERE password = ''");

				// Drop the session table, we no longer need it
				DB::update("DROP TABLE {$dbPrefix}session");

				// Drop the cron table, we use cache to handle that now
				DB::update("DROP TABLE {$dbPrefix}cron");

				// Generate URL keys for pastes that do not have a key.
				// We process the pastes in batches of 1000 to avoid running
				// out of memory.
				while (TRUE)
				{
					$pastes = Paste::where('urlkey', '')->take(1000)->get(array('id', 'urlkey'));

					if ($pastes->count() > 0)
					{
						foreach ($pastes as $paste)
						{
							$paste->urlkey = Paste::makeUrlKey();

							$paste->save();
						}
					}
					else
					{
						break;
					}
				}

				// Get the FQDN for the server
				$fqdn = getenv('SERVER_NAME');

				// Insert fqdn, app version and migration ID to site config
				// The migration ID is nothing but the max paste ID while updating
				// This will be used to allow/deny access to old pastes by their IDs
				Site::config('general', array(
					'fqdn'        => $fqdn,
					'preMigrate'  => Paste::max('id'),
				));

				// This is the v0.4 config file
				$configFile = app_path().'/config/config.php';

				// Now we migrate the old config data
				if (File::exists($configFile))
				{
					include $configFile;

					// Import site settings
					Site::config('general', array_map('html_entity_decode', array(
						'title'          => $site_name,
						'copyright'      => $site_copyright,
						'googleApi'      => $google_api_key,
					)));

					// Import antispam settings
					Site::config('antispam', array_map('html_entity_decode', array(
						'services'       => $sg_services,
						'phpKey'         => $sg_php_key,
						'phpDays'        => $sg_php_days,
						'phpScore'       => $sg_php_score,
						'phpType'        => $sg_php_type,
						'censor'         => $sg_censor,
					)));

					// Import authentication settings
					Site::config('auth', array_map('html_entity_decode', array(
						'method'         => $auth_method,
						'ldapServer'     => $ldap_server,
						'ldapPort'       => $ldap_port,
						'ldapBaseDn'     => $ldap_base_dn,
						'ldapUid'        => $ldap_uid,
						'ldapFilter'     => $ldap_filter,
						'ldapUserDn'     => $ldap_user_dn,
						'ldapPassword'   => $ldap_password,
					)));

					// Import SMTP settings
					Site::config('mail', array_map('html_entity_decode', array(
						'host'           => $smtp_host,
						'port'           => $smtp_port,
						'encryption'     => $smtp_crypt,
						'username'       => $smtp_username,
						'password'       => $smtp_password,
						'address'        => $smtp_from,
					)));

					// If auth method is LDAP, notify the user to set
					// an admin filter.
					if ($auth_method == 'ldap')
					{
						Setup::messages('0.4', Lang::get('setup.ldap_update_warn'));
					}

					// Remove the old config file
					File::delete($configFile);
				}

			},

		),

		'1.0' => array(),

		'1.1' => array(

			'closure' => function()
			{

				$config = Site::config('general');

				// Modify config values
				if (isset($config->googleApi))
				{
					Site::config('services', array(
						'googleApiKey' => $config->googleApi,
					));
				}

			},

		),

		'1.2' => array(

			'newTables' => array(

				'comments' => array(

					(object) array(
						'name'     => 'id',
						'type'     => 'increments',
					),

					(object) array(
						'name'     => 'paste_id',
						'type'     => 'integer',
					),

					(object) array(
						'name'     => 'data',
						'type'     => 'text',
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

			),

		),

		'1.3' => array(

			'newTables' => array(

				'statistics' => array(

					(object) array(
						'name'     => 'id',
						'type'     => 'increments',
					),

					(object) array(
						'name'     => 'date',
						'type'     => 'date',
					),

					(object) array(
						'name'     => 'web',
						'type'     => 'integer',
						'default'  => 0,
					),

					(object) array(
						'name'     => 'api',
						'type'     => 'integer',
						'default'  => 0,
					),

				),

			),

		),

		'1.4' => array(),

		'1.5' => array(),

		'1.6' => array(

			'modifyTables' => array(

				'main' => array(

					(object) array(
						'name'     => 'flagged',
						'type'     => 'boolean',
						'default'  => 0,
					),

					(object) array(
						'name'     => 'attachment',
						'type'     => 'boolean',
						'default'  => 0,
					),

				),

				'users' => array(

					(object) array(
						'name'     => 'remember_token',
						'type'     => 'string',
						'length'   => 60,
						'default'  => '',
					),

				),

			),

		),

		'1.7' => array(

			'closure' => function()
			{

				$config = Site::config('general');

				// Modify config values
				if (isset($config->privateSite) AND $config->privateSite)
				{
					Site::config('general', array(
						'pasteVisibility' => 'private',
					));
				}

			},

		),

		'1.8' => array(

			'closure' => function()
			{

				$config = Site::config('general');

				$noExpire = isset($config->noExpire) AND ! $config->noExpire ? 'none' : 'all';

				Site::config('general', array(
					'noExpire' => $noExpire,
				));

			},

		),

	),

);
