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
use Lang;

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

}
