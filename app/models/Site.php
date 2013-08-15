<?php

/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package     StickyNotes
 * @author      Sayak Banerjee
 * @copyright   (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @link        http://sayakbanerjee.com/sticky-notes
 * @since       Version 1.0
 * @filesource
 */

/**
 * Config class
 *
 * Manages and fetches site configuration data
 *
 * @package     StickyNotes
 * @subpackage  Models
 * @author      Sayak Banerjee
 */
class Site extends Eloquent {

	/**
	 * Fetches the site configuration data
	 *
	 * @access public
	 * @param  string  $section
	 * @return stdClass
	 */
	public static function config($section)
	{
		$config = new stdClass();

		switch ($section)
		{
			case 'general':
				$config->hostname = 'local.kde.org';
				$config->title = Lang::get('global.sticky_notes');
				$config->perPage = 15;
				break;
		}

		return $config;
	}

	/**
	 * Generates the site navigation menu
	 *
	 * @access public
	 * @return string
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

			// Highlight active entry
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

		// Add login/logout link
		if (Auth::check())
		{
			$username = Auth::user()->username;

			if (strlen($username) > 10)
			{
				$username = substr($username, 0, 10).'&hellip;';
			}

			$label = sprintf(Lang::get('global.logout'), $username);
			$href = 'href="'.url('user/logout').'"';
		}
		else
		{
			$label = Lang::get('global.login');
			$href = 'href="'.url('user/login').'"';
		}

		$icon = '<span class="glyphicon glyphicon-user"></span>';
		$output .= "<li><a {$href}>{$icon} {$label}</a></li>";

		return $output;
	}

}
