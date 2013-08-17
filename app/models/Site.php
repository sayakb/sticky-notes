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
	 * Table name for the model
	 *
	 * @var string
	 */
	protected $table = 'config';

	/**
	 * Disable timestamps for the model
	 *
	 * @var bool
	 */
	public $timestamps = FALSE;

	/**
	 * Define fillable properties
	 *
	 * @var array
	 */
	protected $fillable = array(
		'config_group',
		'config_key',
		'config_value'
	);

	/**
	 * Defines an instance cache of config data
	 *
	 * @var array
	 */
	private static $data = array();

	/**
	 * Returns site defaults that can be used in templates
	 *
	 * @static
	 * @return array
	 */
	public static function defaults()
	{
		return array(
			'site'    => self::config('general'),
			'error'   => Session::get('error')
		);
	}

	/**
	 * Fetches the site configuration data
	 *
	 * @access public
	 * @param  string  $section
	 * @return stdClass
	 */
	public static function config($section)
	{
		if ( ! isset(self::$data[$section]))
		{
			self::$data[$section] = new stdClass();
			$config = self::where('config_group', $section)->get();

			if ($config != NULL)
			{
				foreach ($config as $item)
				{
					self::$data[$section]->$item['config_key'] = $item['config_value'];
				}
			}
		}

		return self::$data[$section];
	}

	/**
	 * Generates the site navigation menu
	 *
	 * @access public
	 * @return string
	 */
	public static function getMenu($group)
	{
		$output = '';
		$path = Request::path();

		// Grab and parse all the menus
		$menus = Config::get('menus');
		$group = $menus[$group];

		foreach ($group as $key => $item)
		{
			if (strpos($key, '_') !== 0)
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
		}

		// Add login/logout link if menu is set for that
		if ($group['_showLogin'])
		{
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
		}

		return $output;
	}

}
