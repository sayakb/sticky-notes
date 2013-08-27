<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Site Configuration
	|--------------------------------------------------------------------------
	|
	| This file defines the default site configuration. These are the values
	| that are used as a fallback if the database configuration provider is
	| unavailable.
	|
	| IMPORTANT: Please do not modify this file!!
	|
	*/

	'general'               => (object) array(
		'fqdn'              => 'localhost',
		'title'             => 'Sticky Notes',
		'perPage'           => '15',
		'copyright'         => '',
		'lang'              => 'en',
		'googleApi'         => '',
	),

	'antispam'              => (object) array(
		'services'          => 'censor,stealth,noflood,php',
		'phpKey'            => '',
		'phpDays'           => '90',
		'phpScore'          => '50',
		'phpType'           => '2',
		'censor'            => '',
		'floodThreshold'    => '5',
	),

	'mail'                  => (object) array(
		'driver'            => 'smtp',
		'host'              => 'smtp.mailgun.org',
		'port'              => '587',
		'address'           => 'webmaster@localhost',
		'name'              => 'Webmaster',
		'encryption'        => 'ssl',
		'username'          => '',
		'password'          => '',
		'sendmail'          => '',
		'pretend'           => '0',
	),

	'auth'                  => (object) array(
		'method'            => 'db',
		'dbAllowReg'        => '1',
		'ldapServer'        => '127.0.0.1',
		'ldapPort'          => '389',
		'ldapBaseDn'        => '',
		'ldapUid'           => '',
		'ldapFilter'        => '',
		'ldapUserDn'        => '',
		'ldapPassword'      => '',
	),

);
