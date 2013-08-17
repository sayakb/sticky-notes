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
 * This controller handles displaying of a paste lists
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class ListController extends BaseController {

	/**
	 * Displays the default list page
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getIndex()
	{
		// Restrict paste access
		$perPage = Site::config('general')->perPage;
		$pastes = Paste::where('private', '<>', 1)->paginate($perPage);

		// Check if no pastes were found
		if ($pastes == NULL)
		{
			App:abort(418); // No pastes found
		}

		// Output the view
		$data = array(
			'pastes'   => $pastes,
			'pages'    => $pastes->links(),
		);

		return View::make('site/list', $data, Site::defaults());
	}

}
