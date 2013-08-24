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
			'icon'          => 'flag'
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
			'icon'          => 'dashboard'
		),

		'admin/paste'       => array(
			'label'         => 'admin.manage_pastes',
			'icon'          => 'pushpin'
		),

		'admin/user'        => array(
			'label'         => 'admin.manage_users',
			'icon'          => 'user'
		),

		'admin/ban'         => array(
			'label'         => 'admin.ban_an_ip',
			'icon'          => 'ban-circle'
		),

		'admin/mail'        => array(
			'label'         => 'admin.mail_settings',
			'icon'          => 'envelope'
		),

		'admin/auth'        => array(
			'label'         => 'admin.auth_settings',
			'icon'          => 'lock'
		),

		'admin/theme'       => array(
			'label'         => 'admin.theme_builder',
			'icon'          => 'picture'
		),

		'admin/site'        => array(
			'label'         => 'admin.site_settings',
			'icon'          => 'wrench'
		),

	),

);
