<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Menu Configuration
	|--------------------------------------------------------------------------
	|
	| Sticky Notes global menu congiguration. Each entry has the following
	| format:
	|
	| 'url'		=> array(
	| 	'label'	=> 'global.lang_key',
	| 	'icon'	=> 'glyphicon'
	| )
	|
	*/

	'/'			=> array(
		'label'	=> 'global.new_paste',
		'icon'	=> 'pencil'
	),

	'all'		=> array(
		'label'	=> 'global.archives',
		'icon'	=> 'list'
	),

	'trending'	=> array(
		'label'	=> 'global.trending',
		'icon'	=> 'heart'
	),

	'rss'		=> array(
		'label'	=> 'global.feed',
		'icon'	=> 'book'
	),

	'doc/api'	=> array(
		'label'	=> 'global.api',
		'icon'	=> 'tags'
	),

	'doc/help'	=> array(
		'label'	=> 'global.help',
		'icon'	=> 'question-sign'
	),

	'doc/about'	=> array(
		'label'	=> 'global.about',
		'icon'	=> 'info-sign'
	),

);
