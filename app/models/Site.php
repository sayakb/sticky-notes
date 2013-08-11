<?php

/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package		StickyNotes
 * @author		Sayak Banerjee
 * @copyright	(c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @link		http://sayakbanerjee.com/sticky-notes
 * @since		Version 1.0
 * @filesource
 */

/**
 * Config class
 *
 * Manages and fetches site configuration data
 *
 * @package		StickyNotes
 * @subpackage	Models
 * @author		Sayak Banerjee
 */
class Site extends Eloquent {

	/**
	 * Fetches the site configuration data
	 *
	 * @access	public
	 * @param	string	config section to fetch
	 * @return	object	site configuration data
	 */
	public static function config($section)
	{
		$config = new stdClass();

		switch ($section)
		{
			case 'general':
				$config->title = Lang::get('global.sticky_notes');
				break;
		}

		return $config;
	}

	/**
	 * Generates the site navigation menu
	 *
	 * @access	public
	 * @return	string	menu markup
	 */
	public static function getMenu()
	{
		$output = '';
		$path = Request::path();

		// Grab and parse all the menus
		$menus = Config::get('menus');

		foreach ($menus as $key => $item)
		{
			$label = Lang::get($item['label']);
			$icon = '<span class="glyphicon glyphicon-'.$item['icon'].'"></span>';

			if ($key == $path)
			{
				$active = ' class="active"';
				$href = '';
			}
			else
			{
				$active = '';
				$href = 'href="'.url($key).'"';
			}

			// Generate the item
			$output .= "<li{$active}><a {$href}>{$icon} {$label}</a></li>";
		}

		return $output;
	}

}
