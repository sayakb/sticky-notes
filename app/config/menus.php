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

	'navigation'         => array(

		'_showLogin'     => TRUE,

		'_exact'         => FALSE,

		'/'              => array(
			'label'      => 'global.new_paste',
			'icon'       => 'pencil'
		),

		'all'            => array(
			'label'      => 'global.archives',
			'icon'       => 'list'
		),

		'trending'       => array(
			'label'      => 'global.trending',
			'icon'       => 'fire'
		),

		'docs'           => array(
			'label'      => 'global.docs',
			'icon'       => 'book'
		),

		'user/pastes'    => array(
			'label'      => 'global.my_pastes',
			'icon'       => 'flag'
		),

	),

	'filters'            => array(

		'_showLogin'     => FALSE,

		'_exact'         => TRUE,

		'trending'       => array(
			'label'      => 'list.filter_now'
		),

		'trending/week'  => array(
			'label'      => 'list.filter_week'
		),

		'trending/month' => array(
			'label'      => 'list.filter_month'
		),

		'trending/year'  => array(
			'label'      => 'list.filter_year'
		),

		'trending/all'   => array(
			'label'      => 'list.filter_all'
		),

	),

	'admin'              => array(

		'_showLogin'     => FALSE,

		'_exact'         => TRUE,

		'admin'          => array(
			'label'      => 'admin.dashboard',
			'icon'       => 'home'
		),

		'admin/site'     => array(
			'label'      => 'admin.site_config',
			'icon'       => 'globe'
		),

	),

);
