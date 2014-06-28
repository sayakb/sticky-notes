<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Menu Configuration
	|--------------------------------------------------------------------------
	|
	| Sticky Notes global menu configuration. Each entry has the following
	| format:
	|
	| 'url'      => array(
	| 	'label'  => 'global.lang_key',
	| 	'icon'   => 'glyphicon' // Icon is optional
	| )
	|
	| Properties to be added for each menu:
	|  * _showLogin : Whether or not to append login/logout link
	|  * _exact     : Whether an exact match should be done for determining the
	|                 currently active link
	|
	| Optional properties in each item:
	|  * icon       : Determines the glyphicon for that item
	|  * visible    : This can be used to bind the visibility of an item to a
	|                 user role or a site config. You can use ! to invert a
	|                 flag. Multiple flags with 'OR' relationships should be
	|                 separated by a |
	|
	*/

	'navigation'            => array(

		'_showLogin'        => TRUE,

		'_exact'            => FALSE,

		'all'               => array(
			'label'         => 'global.archives',
			'icon'          => 'list',
			'visible'       => '!config.pasteVisibility=private|role.admin'
		),

		'trending'          => array(
			'label'         => 'global.trending',
			'icon'          => 'fire',
			'visible'       => '!config.pasteVisibility=private|role.admin'
		),

		'docs'              => array(
			'label'         => 'global.docs',
			'icon'          => 'book'
		),

		'user/profile'      => array(
			'label'         => 'global.my_profile',
			'icon'          => 'flag',
			'visible'       => 'role.user'
		),

		'admin'             => array(
			'label'         => 'global.siteadmin',
			'icon'          => 'cog',
			'visible'       => 'role.admin'
		),

	),

	'filters'               => array(

		'_showLogin'        => FALSE,

		'_exact'            => TRUE,

		'trending'          => array(
			'label'         => 'list.filter_now',
			'visible'       => '!config.pasteVisibility=private|role.admin'
		),

		'trending/week'     => array(
			'label'         => 'list.filter_week',
			'visible'       => '!config.pasteVisibility=private|role.admin'
		),

		'trending/month'    => array(
			'label'         => 'list.filter_month',
			'visible'       => '!config.pasteVisibility=private|role.admin'
		),

		'trending/year'     => array(
			'label'         => 'list.filter_year',
			'visible'       => '!config.pasteVisibility=private|role.admin'
		),

		'trending/all'      => array(
			'label'         => 'list.filter_all',
			'visible'       => '!config.pasteVisibility=private|role.admin'
		),

	),

	'admin'                 => array(

		'_showLogin'        => FALSE,

		'_exact'            => FALSE,

		'admin/dashboard'   => array(
			'label'         => 'admin.dashboard',
			'icon'          => 'home',
			'visible'       => 'role.admin'
		),

		'admin/paste'       => array(
			'label'         => 'admin.manage_pastes',
			'icon'          => 'file',
			'visible'       => 'role.admin'
		),

		'admin/user'        => array(
			'label'         => 'admin.manage_users',
			'icon'          => 'user',
			'visible'       => 'role.admin'
		),

		'admin/ban'         => array(
			'label'         => 'admin.ban_an_ip',
			'icon'          => 'ban-circle',
			'visible'       => 'role.admin'
		),

		'admin/mail'        => array(
			'label'         => 'admin.mail_settings',
			'icon'          => 'envelope',
			'visible'       => 'role.admin'
		),

		'admin/auth'        => array(
			'label'         => 'admin.authentication',
			'icon'          => 'lock',
			'visible'       => 'role.admin'
		),

		'admin/antispam'    => array(
			'label'         => 'admin.spam_filters',
			'icon'          => 'screenshot',
			'visible'       => 'role.admin'
		),

		'admin/skin'        => array(
			'label'         => 'admin.skin_chooser',
			'icon'          => 'picture',
			'visible'       => 'role.admin'
		),

		'admin/services'    => array(
			'label'         => 'admin.services',
			'icon'          => 'briefcase',
			'visible'       => 'role.admin'
		),

		'admin/site'        => array(
			'label'         => 'admin.site_settings',
			'icon'          => 'wrench',
			'visible'       => 'role.admin'
		),

	),

);
