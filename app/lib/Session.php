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
 * Session class
 *
 * Abstraction over \Illuminate\Support\Facades\Session to enable caching
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Session extends \Illuminate\Support\Facades\Session {

	/**
	 * Configuration cache
	 *
	 * @var array
	 */
	private static $cache = array();

	/**
	 * Returns session data
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
	 * Flushes session data
	 *
	 * @static
	 * @return void
	 */
	public static function flush()
	{
		static::$cache = array();

		parent::flush();
	}

}
