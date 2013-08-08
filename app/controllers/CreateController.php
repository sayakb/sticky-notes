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
 * CreateController
 *
 * This is the default homepage of the site and allows the user to create a new
 * paste.
 *
 * @package		StickyNotes
 * @subpackage	Controllers
 * @author		Sayak Banerjee
 */
class CreateController extends BaseController {

	/**
	 * Creates a new paste item
	 *
	 * @access	public
	 * @return	void
	 */
	public function newPaste()
	{
		// Set up the view
		$data = array(
			'site'		=> Site::config('general'),
			'languages'	=> Highlighter::languages()
		);

		return View::make('site/create', $data);
	}

}
