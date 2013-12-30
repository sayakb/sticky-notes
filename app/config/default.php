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

	'general'                 => (object) array(
		'fqdn'                => 'localhost',
		'title'               => 'Sticky Notes',
		'perPage'             => '15',
		'copyright'           => '',
		'lang'                => 'en',
		'version'             => '0.0',
		'preMigrate'          => '0',
		'pasteAge'            => '1800',
		'skin'                => 'bootstrap',
		'proxy'               => '0',
		'privateSite'         => '0',
		'pasteSearch'         => '1',
		'comments'            => '1',
		'noExpire'            => '1',
	),

	'antispam'                => (object) array(
		'services'            => 'censor|noflood',
		'phpKey'              => '',
		'phpDays'             => '90',
		'phpScore'            => '50',
		'phpType'             => '2',
		'censor'              => '',
		'floodThreshold'      => '5',
		'akismetKey'          => '',
	),

	'mail'                    => (object) array(
		'driver'              => 'smtp',
		'host'                => 'localhost',
		'port'                => '25',
		'address'             => 'webmaster@sticky.notes',
		'name'                => 'Webmaster',
		'encryption'          => 'ssl',
		'username'            => '',
		'password'            => '',
		'sendmail'            => '',
		'pretend'             => '0',
	),

	'auth'                    => (object) array(
		'method'              => 'db',
		'noForm'              => 'oauth',
		'bannerText'          => '',
		'infoUrl'             => '',
		'infoUrlText'         => '',
		'dbAllowReg'          => '1',
		'dbShowCaptcha'       => '1',
		'ldapServer'          => '127.0.0.1',
		'ldapPort'            => '389',
		'ldapBaseDn'          => '',
		'ldapUid'             => '',
		'ldapFilter'          => '',
		'ldapAdmin'           => '',
		'ldapUserDn'          => '',
		'ldapPassword'        => '',
		'oauthGoogleId'       => '',
		'oauthGoogleSecret'   => '',
		'oauthGoogleAdmins'   => '',
	),

	'services'                => (object) array(
		'googleApiKey'        => '',
		'googleAnalyticsId'   => '',
		'googleUrlShortener'  => 'https://www.googleapis.com/urlshortener/v1/url?key=%s',
		'googleUrlOAuth'      => 'https://www.googleapis.com/oauth2/v1/userinfo',
		'statsUrl'            => 'http://sites.sayakbanerjee.com/sn-stats/?a=submit',
		'docsUrl'             => 'http://sayakbanerjee.com/sticky-notes',
		'updateUrl'           => 'http://sayakb.github.io/sticky-notes/version/',
	),

);
