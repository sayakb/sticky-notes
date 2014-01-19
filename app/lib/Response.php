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
 * Response class
 *
 * Abstraction over \Illuminate\Support\Facades\Response to enable skin support
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Response extends \Illuminate\Support\Facades\Response {

	/**
	 * This abstraction over the base method injects the skin name
	 * and default view data.
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @param  int     $status
	 * @param  array   $headers
	 * @return \Illuminate\View\View
	 */
	public static function view($view, $data = array(), $status = 200, array $headers = array())
	{
		$data = array_merge(View::defaults(), $data);

		return parent::view(View::inject($view), $data, $status, $headers);
	}

}
