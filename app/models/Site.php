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
		'group',
		'key',
		'value'
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
			'site'        => static::config('general'),
			'error'       => Session::get('messages.error'),
			'success'     => Session::get('messages.success'),
			'auth'        => Auth::user(),
			'role'        => User::getRoles(),
		);
	}

	/**
	 * Gets or sets the site configuration data
	 *
	 * @access public
	 * @param  string  $group
	 * @param  array   $newData
	 * @return stdClass|bool
	 */
	public static function config($group, $newData = array())
	{
		// Get a config value
		if (count($newData) == 0)
		{
			// We first look up in the local cache
			// If it isn't found there, we fetch it from the database
			if ( ! isset(static::$data[$group]))
			{
				static::$data[$group] = new stdClass();
				$config = static::where('group', $group)->get();

				if ($config != NULL)
				{
					foreach ($config as $item)
					{
						static::$data[$group]->$item['key'] = $item['value'];
					}
				}
			}

			return static::$data[$group];
		}

		// Set config values
		else
		{
			foreach ($newData as $key => $value)
			{
				$config = static::query();

				$config->where('group', $group);
				$config->where('key', $key);

				if ($config->count() == 1)
				{
					$config->update(array('value' => $value));
				}
			}

			return TRUE;
		}
	}

	/**
	 * Generates the site navigation menu
	 *
	 * @access public
	 * @param  string   $group
	 * @return string
	 */
	public static function getMenu($group)
	{
		$output = '';
		$icon = NULL;
		$path = Request::path();

		// Grab and parse all the menus
		$menus = Config::get('menus');
		$group = $menus[$group];

		foreach ($group as $key => $item)
		{
			if ( ! str_contains($key, '_'))
			{
				$label = Lang::get($item['label']);
				$current = FALSE;

				// Check if a role restriction is set
				if (isset($item['role']) AND ! User::getRoles()->$item['role'])
				{
					continue;
				}

				// Determine whether this is the active link
				if ($group['_exact'] AND $key === $path)
				{
					$current = TRUE;
				}
				else if ( ! $group['_exact'] AND starts_with($path, $key))
				{
					$current = TRUE;
				}

				// Highlight the active item
				if ($current)
				{
					$active = ' class="active"';
					$href = '';
				}
				else
				{
					$active = '';
					$href = 'href="'.url($key).'"';
				}

				// Set the entry icon
				if (isset($item['icon']))
				{
					$icon = '<span class="glyphicon glyphicon-'.$item['icon'].'"></span>';
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
				$label = Lang::get('global.logout');
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
