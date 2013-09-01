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
 * AjaxController
 *
 * This controller handles AJAX requests
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class AjaxController extends BaseController {

	/**
	 * Fetches the latest available sticky notes version
	 *
	 * @return string
	 */
	public function getVersion()
	{

	}

	/**
	 * Gets the system load
	 *
	 * @return string
	 */
	public function getSysload()
	{
		return Site::getSystemLoad();
	}

}
