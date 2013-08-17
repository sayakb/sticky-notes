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
 * ShowController
 *
 * This controller handles displaying of a paste
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class ShowController extends BaseController {

	/**
	 * Displays the default view page
	 *
	 * @access public
	 * @param  string  $urlkey
	 * @param  string  $hash
	 * @return \Illuminate\View\View
	 */
	public function getIndex($urlkey, $hash = "")
	{
		// Restrict paste access
		$paste = Paste::where('urlkey', $urlkey)->firstOrFail();

		// Require hash to be passed for private pastes
		if ($paste->private AND $paste->hash != $hash)
		{
			App::abort(401); // Unauthorized
		}

		// Increment the hit counter
		$viewed = Session::get('viewed');

		if ( ! is_array($viewed) OR ! in_array($paste->id, $viewed))
		{
			$paste->hits++;
			$paste->save();

			$viewed[] = $paste->id;
			Session::put('viewed', $viewed);
		}

		$data = array('paste' => $paste);

		return View::make('site/show', $data, Site::defaults());
	}

}
