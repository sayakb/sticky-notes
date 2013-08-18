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
	public function getAll()
	{
		$perPage = Site::config('general')->perPage;
		$pastes = Paste::where('private', '<>', 1)->orderBy('id', 'desc')->paginate($perPage);

		return $this->getList($pastes);
	}

	/**
	 * Fetches the top N trending pastes, where N = perPage from site config
	 *
	 * @access public
	 * @param  string  $age
	 * @return \Illuminate\View\View
	 */
	public function getTrending($age = 'now')
	{
		$perPage = Site::config('general')->perPage;
		$pastes = Paste::getTrending($age, $perPage)->paginate($perPage);

		return $this->getList($pastes, TRUE);
	}

	/**
	 * Gets user's own pastes
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getUserPastes()
	{
		$perPage = Site::config('general')->perPage;
		$user = Auth::user()->username;
		$pastes = Paste::where('author', $user)->orderBy('id', 'desc')->paginate($perPage);

		return $this->getList($pastes);
	}

	/**
	 * Parses and displays a list
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $pastes
	 * @param  bool                                 $showFilters
	 * @return \Illuminate\View\View
	 */
	private function getList($pastes, $showFilters = FALSE)
	{
		// Check if no pastes were found
		if ($pastes->count() === 0)
		{
			App::abort(418); // No pastes found
		}

		// Output the view
		$data = array(
			'pastes'   => $pastes,
			'pages'    => $pastes->links(),
			'filters'  => $showFilters
		);

		return View::make('site/list', $data, Site::defaults());
	}

}
