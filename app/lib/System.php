<?php namespace StickyNotes;

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

use File;
use Input;
use Lang;
use Request;
use Requests;
use Route;
use Schema;
use Session;
use Site;

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
		$site = Site::config('general')->fqdn;

		$host = getenv('SERVER_NAME');

		if ($site != $host)
		{
			return trim(str_replace($site, '', $host), '.');
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
	 * Gets the name of the current action.
	 * We don't return anything if we are in an error flow
	 *
	 * @static
	 * @return string
	 */
	public static function action()
	{
		if (is_null(static::$route) AND ! Input::has('e'))
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
		// Send / mask the site's URL
		$fqdn = Config::get('app.fullStats') ? Site::config('general')->fqdn : Lang::get('global.anonymous');

		// Populate the data to be send
		$data =  array(
			'fqdn'     => $fqdn,
			'action'   => Request::segment(2),
			'version'  => Config::get('app.version'),
		);

		// Send the stats to the REST stats service
		Requests::post(Site::config('services')->statsUrl, array(), $data);
	}

}
