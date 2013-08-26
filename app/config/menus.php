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
	|  * role       : If set as 'user' or 'admin', the item will not be showed
	|                 if the user does not belong to that role
	|
	*/

	'navigation'            => array(

		'_showLogin'        => TRUE,

		'_exact'            => FALSE,

		'all'               => array(
			'label'         => 'global.archives',
			'icon'          => 'list'
		),

		'trending'          => array(
			'label'         => 'global.trending',
			'icon'          => 'fire'
		),

		'docs'              => array(
			'label'         => 'global.docs',
			'icon'          => 'book'
		),

		'mypastes'          => array(
			'label'         => 'global.my_pastes',
			'icon'          => 'flag',
			'role'          => 'user'
		),

		'admin'             => array(
			'label'         => 'global.siteadmin',
			'icon'          => 'cog',
			'role'          => 'admin'
		),

	),

	'filters'               => array(

		'_showLogin'        => FALSE,

		'_exact'            => TRUE,

		'trending'          => array(
			'label'         => 'list.filter_now'
		),

		'trending/week'     => array(
			'label'         => 'list.filter_week'
		),

		'trending/month'    => array(
			'label'         => 'list.filter_month'
		),

		'trending/year'     => array(
			'label'         => 'list.filter_year'
		),

		'trending/all'      => array(
			'label'         => 'list.filter_all'
		),

	),

	'admin'                 => array(

		'_showLogin'        => FALSE,

		'_exact'            => FALSE,

		'admin/dashboard'   => array(
			'label'         => 'admin.dashboard',
			'icon'          => 'dashboard',
			'role'          => 'admin'
		),

		'admin/paste'       => array(
			'label'         => 'admin.manage_pastes',
			'icon'          => 'pushpin',
			'role'          => 'admin'
		),

		'admin/user'        => array(
			'label'         => 'admin.manage_users',
			'icon'          => 'user',
			'role'          => 'admin'
		),

		'admin/ban'         => array(
			'label'         => 'admin.ban_an_ip',
			'icon'          => 'ban-circle',
			'role'          => 'admin'
		),

		'admin/mail'        => array(
			'label'         => 'admin.mail_settings',
			'icon'          => 'envelope',
			'role'          => 'admin'
		),

		'admin/auth'        => array(
			'label'         => 'admin.authentication',
			'icon'          => 'lock',
			'role'          => 'admin'
		),

		'admin/antispam'    => array(
			'label'         => 'admin.spam_filters',
			'icon'          => 'screenshot',
			'role'          => 'admin'
		),

		'admin/site'        => array(
			'label'         => 'admin.site_settings',
			'icon'          => 'wrench',
			'role'          => 'admin'
		),

		'admin/theme'       => array(
			'label'         => 'admin.theme_builder',
			'icon'          => 'picture',
			'role'          => 'admin'
		),

	),

);
