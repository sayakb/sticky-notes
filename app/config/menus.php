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
	| 	'icon'   => 'glyphicon'
	| )
	|
	| Each menu group must have a _showLogin member indicating whether a
	| login/logout link will be appended or not
	|
	*/

	'navigation'         => array(

		'_showLogin'     => true,

		'new'            => array(
			'label'      => 'global.new_paste',
			'icon'       => 'pencil'
		),

		'all'            => array(
			'label'      => 'global.archives',
			'icon'       => 'list'
		),

		'trending'       => array(
			'label'      => 'global.trending',
			'icon'       => 'heart'
		),

		'rss'            => array(
			'label'      => 'global.feed',
			'icon'       => 'asterisk'
		),

		'docs'           => array(
			'label'      => 'global.docs',
			'icon'       => 'book'
		),

	),

	'admin'              => array(

		'_showLogin'     => false,

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
