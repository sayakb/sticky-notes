<?php

/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package		StickyNotes
 * @author		Sayak Banerjee
 * @copyright	(c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @link		http://sayakbanerjee.com/sticky-notes
 * @since		Version 1.0
 * @filesource
 */

/**
 * BaseController
 *
 * @package		StickyNotes
 * @subpackage	Controllers
 * @author		Sayak Banerjee
 */
class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @access	protected
	 * @return	void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
