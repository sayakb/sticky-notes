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
			'services'    => explode(',', static::config('antispam')->services),
			'user'        => Auth::user(),
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
			if ( ! Cache::has('site.config'))
			{
				// Load the default configuration data
				$config = Config::get('default');

				// We try to access the site config table here, but it may
				// fail due to many reasons, one of them being a bad connection
				// If it fails, we just return the default values
				try
				{
					if (php_sapi_name() != 'cli')
					{
						$siteConfig = static::all();

						if ( ! is_null($siteConfig))
						{
							foreach ($siteConfig as $item)
							{
								$config[$item['group']]->$item['key'] = $item['value'];
							}
						}
					}
				}
				catch(Exception $e)
				{
					// Suppress the exception here
				}

				// Save the fetched config data to cache
				Cache::forever('site.config', $config);
			}
			else
			{
				// Read config data from cache
				$config = Cache::get('site.config');
			}

			return $config[$group];
		}

		// Set config values
		else
		{
			// Update the new config values in the DB
			foreach ($newData as $key => $value)
			{
				if ( ! starts_with($key, '_'))
				{
					$key = camel_case($key);

					// Get the existing value of the config
					$config = static::query();

					$config->where('group', $group);

					$config->where('key', $key);

					// Do an UPSERT, i.e. if the value exists, update it.
					// If it doesn't, insert it.
					if ($config->count() > 0)
					{
						$config->update(array('value' => $value));
					}
					else
					{
						$config->insert(array(
							'group'  => $group,
							'key'    => $key,
							'value'  => $value,
						));
					}
				}
			}

			// Remove the config from cache
			Cache::forget('site.config');

			return TRUE;
		}
	}

	/**
	 * Generates the site navigation menu
	 *
	 * @access public
	 * @param  string  $menu
	 * @return string
	 */
	public static function getMenu($menu)
	{
		// Current path - will be used to highlight menu item
		$path = Request::path();

		// Current user ID for role based menus
		$user = Auth::check() ? Auth::user()->id : 0;

		// Grab and parse all the menus
		$menus = Config::get('menus');

		$group = $menus[$menu];

		// Build the menu items
		if ( ! Cache::has("site.menu.{$menu}.{$path}.{$user}"))
		{
			$output = NULL;

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
						$active = 'class="active"';

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
					else
					{
						$icon = NULL;
					}

					// Generate the item markup
					$output .= "<li {$active}><a {$href}>{$icon} {$label}</a></li>";
				}
			}

			// Add login/logout link if menu is set for that
			if ($group['_showLogin'])
			{
				if ($user)
				{
					$label = Lang::get('global.logout');

					$href = 'href="'.url('user/logout').'"';
				}
				else
				{
					$label = Lang::get('global.login');

					$href = 'href="'.url('user/login').'"';
				}

				// Are we on the login screen?
				$active = $path == 'user/login' ? 'class="active"' : '';

				$icon = '<span class="glyphicon glyphicon-user"></span>';

				// Generate the markup
				$output .= "<li {$active}><a {$href}>{$icon} {$label}</a></li>";
			}

			// Save the parsed menu to cache for 1 day
			Cache::put("site.menu.{$menu}.{$path}.{$user}", $output, 1440);
		}
		else
		{
			$output = Cache::get("site.menu.{$menu}.{$path}.{$user}");
		}

		return $output;
	}

	/**
	 * Retrieves a list of languages supported by the site
	 *
	 * @static
	 * @param  bool  $csv
	 * @return array|string
	 */
	public static function getLanguages($csv = FALSE)
	{
		$list = array();

		$langs = scandir(app_path().'/lang');

		foreach ($langs as $lang)
		{
			if ( ! starts_with($lang, '.'))
			{
				$list[$lang] = $lang;
			}
		}

		if ($csv)
		{
			$list = implode(',', $list);
		}

		return $list;
	}

	/**
	 * Gets the server load. On windows systems, it fetches the
	 * current CPU utilization.
	 *
	 * @static
	 * @return string
	 */
	public static function getSystemLoad()
	{
		$sysload = NULL;

		// Get the system's load based on the OS
		$os = strtolower(PHP_OS);

		if (strpos($os, 'win') === FALSE)
		{
			if (file_exists('/proc/loadavg'))
			{
				$load = file_get_contents('/proc/loadavg');

				$load = explode(' ', $load);

				$sysload = $load[0];
			}
			else if (function_exists('shell_exec'))
			{
				$load = explode(' ', `uptime`);

				$sysload = $load[count($load) - 1];
			}
		}
		else
		{
			if (function_exists('exec'))
			{
				$load = array();

				exec('wmic cpu get loadpercentage', $load);

				if ( ! empty($load[1]))
				{
					$sysload = "{$load[1]}%";
				}
			}
		}

		return empty($sysload) ? Lang::get('global.not_available') : $sysload;
	}

	/**
	 * Gets a version number from a version string
	 *
	 * @static
	 * @param  string  $version
	 * @return int
	 */
	public static function versionNbr($version)
	{
		$version = ! empty($version) ? $version : '0.0';

		// Remove decimals
		$version = str_replace('.', '', $version);

		// Convert it to an integer
		return intval($version);
	}

}
