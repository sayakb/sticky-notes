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
 * @since       Version 1.8
 * @filesource
 */

/**
 * Cookie class
 *
 * Abstraction over \Illuminate\Support\Facades\Cookie to add manual serialization
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Cookie extends \Illuminate\Support\Facades\Cookie {

	/**
	 * Get the value of the given cookie
	 *
	 * @static
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public static function get($key = NULL, $default = NULL)
	{
		// First, we get the raw cookie data
		$raw = parent::get($key, $default);

		// Then we attempt to unserialize it
		$unserialized = @unserialize($raw);

		// If unserialization succeeded, we return the unserialized data
		// otherwise, we return the original raw data
		return $unserialized !== FALSE ? $unserialized : $raw;
	}

	/**
	 * Creates a new cookie instance
	 *
	 * @static
	 * @param  string  $name
	 * @param  mixed   $value
	 * @param  int     $minutes
	 * @param  string  $path
	 * @param  string  $domain
	 * @param  bool    $secure
	 * @param  bool    $httpOnly
	 * @return \Symfony\Component\HttpFoundation\Cookie
	 */
	public static function make($name, $value, $minutes = 0, $path = NULL, $domain = NULL, $secure = FALSE, $httpOnly = TRUE)
	{
		// Serialize the value
		$value = @serialize($value);

		// Create the cookie
		return parent::make($name, $value, $minutes, $path, $domain, $secure, $httpOnly);
	}

	/**
	 * Create a cookie that lasts "forever" (five years)
	 *
	 * @static
	 * @param  string  $name
	 * @param  mixed   $value
	 * @param  string  $path
	 * @param  string  $domain
	 * @param  bool    $secure
	 * @param  bool    $httpOnly
	 * @return \Symfony\Component\HttpFoundation\Cookie
	 */
	public static function forever($name, $value, $path = NULL, $domain = NULL, $secure = FALSE, $httpOnly = TRUE)
	{
		// Serialize the value
		$value = @serialize($value);

		// Create the cookie
		return parent::forever($name, $value, $path, $domain, $secure, $httpOnly);
	}

}
