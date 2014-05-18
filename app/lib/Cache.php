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

/**
 * Cache class
 *
 * Abstraction over \Illuminate\Support\Facades\Cache to enable local caching
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Cache extends \Illuminate\Support\Facades\Cache {

	/**
	 * Local cache
	 *
	 * @var array
	 */
	private static $cache = array();

	/**
	 * Returns cached data
	 *
	 * @param  string  $key
	 * @return mixed
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
	 * Returns cached data after updating the cached value if expired
	 *
	 * @param  string  $key
	 * @param  int     $ttl
	 * @param  closure $closure
	 * @return mixed
	 */
	public static function remember($key, $ttl, $closure)
	{
		if ( ! isset(static::$cache[$key]))
		{
			static::$cache[$key] = parent::remember($key, $ttl, $closure);
		}

		return static::$cache[$key];
	}

	/**
	 * Returns cached data after updating the cached value if expired
	 *
	 * @param  string  $key
	 * @param  closure $closure
	 * @return mixed
	 */
	public static function rememberForever($key, $closure)
	{
		if ( ! isset(static::$cache[$key]))
		{
			static::$cache[$key] = parent::rememberForever($key, $closure);
		}

		return static::$cache[$key];
	}

	/**
	 * Remove all items from the cache.
	 *
	 * @return void
	 */
	public static function flush()
	{
		static::$cache = array();

		parent::flush();
	}

}
