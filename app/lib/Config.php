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
 * @since       Version 1.3
 * @filesource
 */

/**
 * Config class
 *
 * Abstraction over \Illuminate\Support\Facades\Config to enable config caching
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Config extends \Illuminate\Support\Facades\Config {

	/**
	 * Configuration cache
	 *
	 * @var array
	 */
	private static $cache = array();

	/**
	 * Returns configuration data
	 *
	 * @static
	 * @param  string  $key
	 * @return array
	 */
	public static function get($key)
	{
		if ( ! isset(static::$cache[$key]))
		{
			static::$cache[$key] = parent::get($key);
		}

		return static::$cache[$key];
	}

	/**
	 * Flushes the config cache
	 *
	 * @static
	 * @return void
	 */
	public static function flush()
	{
		static::$cache = array();
	}

}
