<?php namespace StickyNotes;

/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package     StickyNotes
 * @author      Sayak Banerjee
 * @copyright   (c) 2014 Sayak Banerjee <mail@sayakbanerjee.com>
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @link        http://sayakbanerjee.com/sticky-notes
 * @since       Version 1.0
 * @filesource
 */

use File;
use Lang;
use Request;
use Requests;
use Requests_Exception;
use Route;
use Schema;
use Site;
use URL;

/**
 * System class
 *
 * Provides system information to views and controllers.
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class System {

	/**
	 * Caches the current route
	 *
	 * @var string
	 */
	private static $route = NULL;

	/**
	 * Defines whether the system is in an error state
	 *
	 * @var bool
	 */
	private static $errorState = FALSE;

	/**
	 * Retrieves a list of directory names from a given path
	 *
	 * @static
	 * @param  string  $path
	 * @param  bool    $csv
	 * @return array|string
	 */
	public static function directories($path, $csv = FALSE)
	{
		$list = array();

		$items = scandir(app_path()."/{$path}");

		foreach ($items as $item)
		{
			if ( ! starts_with($item, '.'))
			{
				$list[$item] = $item;
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
	public static function load()
	{
		$sysload = NULL;

		// Get the system's load based on the OS
		$os = strtolower(PHP_OS);

		if (strpos($os, 'win') === FALSE)
		{
			if (File::exists('/proc/loadavg'))
			{
				$load = File::get('/proc/loadavg');

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
	public static function version($version)
	{
		$version = ! empty($version) ? $version : '0.0';

		// Remove decimals
		$version = str_replace('.', '', $version);

		// Convert it to an integer
		return intval($version);
	}

	/**
	 * Returns the current project name
	 *
	 * @static
	 * @return string|null
	 */
	public static function project()
	{
		$fqdn = explode('.', Site::config('general')->fqdn);

		$host = explode('.', getenv('SERVER_NAME'));

		if (count($host) > count($fqdn))
		{
			return $host[0];
		}
	}

	/**
	 * Determines the installed state of the system
	 *
	 * @static
	 * @return bool
	 */
	public static function installed()
	{
		return Cache::rememberForever('site.installed', function()
		{
			return Schema::hasTable('main');
		});
	}

	/**
	 * Determines whether the latest version of Sticky Notes
	 * is installed.
	 *
	 *  - If local version is same as remote, return 0
	 *  - If remote version is newer, return negative integer
	 *  - If local version is newer, return positive integer
	 *
	 * @static
	 * @return int
	 */
	public static function updated()
	{
		try
		{
			// Get the local (installed) version number
			$localVersion = static::version(Config::get('app.version'));

			// Get the remote version number
			$response = Requests::get(Site::config('services')->updateUrl);

			$remoteVersion = static::version($response->body);

			// Return the version difference
			return $localVersion - $remoteVersion;
		}
		catch (Requests_Exception $e)
		{
			// HTTP GET failed
			return 0;
		}
	}

	/**
	 * Gets the name of the current action.
	 * We don't return anything if we are in an error flow
	 *
	 * @static
	 * @return string
	 */
	public static function action()
	{
		if (is_null(static::$route) AND ! static::error())
		{
			$action = Route::currentRouteAction();

			static::$route = head(explode('@', $action));
		}

		return static::$route;
	}

	/**
	 * Submits statistics to Sticky Notes server
	 *
	 * @return void
	 */
	public static function submitStats()
	{
		try
		{
			// Send / mask the site's URL
			$url = Config::get('app.fullStats') ? URL::current() : Lang::get('global.anonymous');

			// Populate the data to be send
			$data =  array(
				'url'      => $url,
				'action'   => Request::segment(2),
				'version'  => Config::get('app.version'),
			);

			// Send the stats to the REST stats service
			Requests::post(Site::config('services')->statsUrl, array(), $data);
		}
		catch (Requests_Exception $e)
		{
			// HTTP POST failed. Suppress this exception
		}
	}

	/**
	 * Sets the system in an error state
	 *
	 * @param  mixed  $state
	 * @return bool
	 */
	public static function error($state = NULL)
	{
		if (is_bool($state))
		{
			static::$errorState = $state;
		}
		else
		{
			return static::$errorState;
		}
	}

}
