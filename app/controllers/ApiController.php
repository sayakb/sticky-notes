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
 * ApiController
 *
 * This controller handles all API operations
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class ApiController extends BaseController {

	/**
	 * The constructor here validates and sets the API mode
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$mode = Request::segment(2);

		switch ($mode)
		{
			case 'xml':
			case 'json':
				break;

			default:
				header('HTTP/1.1 400 Bad Request', TRUE, 400);
				exit;
		}
	}

	/**
	 * Show a paste by its ID or key
	 *
	 * @access public
	 * @param  string  $key
	 * @param  string  $hash
	 * @return \Illuminate\View\View
	 */
	public function getShow($key, $hash = '')
	{
		$paste = Paste::getByKey($key);

		return View::make('site/show', array('paste' => $paste), Site::defaults());
	}

}
