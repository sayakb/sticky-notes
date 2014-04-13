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
 * Redirect class
 *
 * Abstraction over \Illuminate\Support\Facades\Redirect to enable AJAX support
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Redirect extends \Illuminate\Support\Facades\Redirect {

	/**
	 * Create a new redirect response, while putting the current URL in the session.
	 *
	 * @param  string  $path
	 * @param  int     $status
	 * @param  array   $headers
	 * @param  bool    $secure
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public static function guest($path, $status = 302, $headers = array(), $secure = null)
	{
		$url = str_replace('ajax=1', '', parent::getUrlGenerator()->full());

		Session::put('url.intended', $url);

		return parent::to($path, $status, $headers, $secure);
	}

	/**
	 * Create a new redirect response to the previously intended location.
	 *
	 * @param  string  $default
	 * @param  int     $status
	 * @param  array   $headers
	 * @param  bool    $secure
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public static function intended($default, $status = 302, $headers = array(), $secure = null)
	{
		$path = Session::get('url.intended', $default);

		Session::forget('url.intended');

		return parent::to($path, $status, $headers, $secure);
	}

}
