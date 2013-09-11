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

			), // config

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

			), // ipbans

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

			), // main

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

			), // revisions

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

			), // users

		), // tables

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

		}, // closure

	), // install

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

				), // config

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

				), // revisions

			), // newTables

			'modifyTables' => array(

				'main' => array(

					(object) array(
						'name'     => 'author_id',
						'type'     => 'integer',
						'nullable' => TRUE,
						'default'  => NULL,
					),

				), // main

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

				), // users

			), // modifyTables

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

				// Generate URL keys for all pastes
				$pastes = Paste::where('urlkey', '')->get();

				foreach ($pastes as $paste)
				{
					$paste->urlkey = Paste::makeUrlKey();

					$paste->save();
				}

				// Get the FQDN for the server
				$fqdn = getenv('SERVER_NAME');

				// Get the app configuration
				$app = Config::get('app');

				// Insert fqdn, app version and migration ID to site config
				// The migration ID is nothing but the max paste ID while updating
				// This will be used to allow/deny access to old pastes by their IDs
				Site::config('general', array(
					'fqdn'        => $fqdn,
					'version'     => $app['version'],
					'preMigrate'  => Paste::max('id'),
				));

				// Now we migrate the old config data
				if (File::exists(app_path().'/config/config.php'))
				{
					include app_path().'/config/config.php';

					Site::config('general', array(
						'title'          => html_entity_decode($site_name),
						'copyright'      => html_entity_decode($site_copyright),
					));

					Site::config('antispam', array(
						'services'       => html_entity_decode($sg_services),
						'phpKey'         => html_entity_decode($sg_php_key),
						'phpDays'        => html_entity_decode($sg_php_days),
						'phpScore'       => html_entity_decode($sg_php_score),
						'phpType'        => html_entity_decode($sg_php_type),
						'censor'         => html_entity_decode($sg_censor),
					));

					Site::config('auth', array(
						'method'         => html_entity_decode($auth_method),
						'ldapServer'     => html_entity_decode($ldap_server),
						'ldapPort'       => html_entity_decode($ldap_port),
						'ldapBaseDn'     => html_entity_decode($ldap_base_dn),
						'ldapUid'        => html_entity_decode($ldap_uid),
						'ldapFilter'     => html_entity_decode($ldap_filter),
						'ldapUserDn'     => html_entity_decode($ldap_user_dn),
						'ldapPassword'   => html_entity_decode($ldap_password),
					));

					Site::config('mail', array(
						'host'           => html_entity_decode($smtp_host),
						'port'           => html_entity_decode($smtp_port),
						'encryption'     => html_entity_decode($smtp_crypt),
						'username'       => html_entity_decode($smtp_username),
						'password'       => html_entity_decode($smtp_password),
						'address'        => html_entity_decode($smtp_from),
					));

					// If auth method is LDAP, notify the user to set
					// an admin filter.
					if ($auth_method == 'ldap')
					{
						Setup::messages('0.4', Lang::get('setup.ldap_update_warn'));
					}
				}

				// All done, flush the cache!
				Cache::flush();

			}, // closure

		), // 0.4

	), // update

);
