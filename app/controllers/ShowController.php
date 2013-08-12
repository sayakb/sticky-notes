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
 * ShowController
 *
 * This controller handles displaying of a paste
 *
 * @package		StickyNotes
 * @subpackage	Controllers
 * @author		Sayak Banerjee
 */
class ShowController extends BaseController {

	/**
	 * Displays the default view page
	 *
	 * @access	public
	 * @return	object	the parsed view
	 */
	public function getIndex($urlkey, $hash = "")
	{
		// Restrict paste access
		$paste = Paste::where('urlkey', $urlkey)->first();

		if ($paste->private AND $paste->hash != $hash)
		{
			App::abort(401);
		}

		$data = array(
			'site'		=> Site::config('general'),
			'paste'		=> $paste
		);

		return View::make('site/show', $data);
	}

}
