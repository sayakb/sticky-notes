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
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getAll()
	{
		$perPage = Site::config('general')->perPage;

		// Show all pastes to admins
		if (Auth::check() AND Auth::user()->admin)
		{
			$query = Paste::query();
		}
		else
		{
			$query = Paste::where('private', '<>', 1);
		}

		// Filter by project
		if ( ! empty($this->project))
		{
			$query = $query->where('project', $this->project);
		}

		$pastes = $query->orderBy('id', 'desc')->paginate($perPage);

		return $this->getList($pastes);
	}

	/**
	 * Fetches the top N trending pastes, where N = perPage from site config
	 *
	 * @access public
	 * @param  string  $age
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getTrending($age = 'now')
	{
		$perPage = Site::config('general')->perPage;

		$time = time();

		$filter = $time - 259200;

		// Calculate age based on filter
		switch ($age)
		{
			case 'week':
				$filter = $time - 1814400;
				break;

			case 'month':
				$filter = $time - 7776000;
				break;

			case 'year':
				$filter = $time - 94608000;
				break;

			case 'all':
				$filter = 0;
				break;
		}

		// Get all pastes matching the age filter
		$query = Paste::where('timestamp', '>=', $filter);

		// Hide private pastes from non-admins
		if (Auth::guest() OR ! Auth::user()->admin)
		{
			$query = $query->where('private', '<>', 1);
		}

		// Filter by project
		if ( ! empty($this->project))
		{
			$query = $query->where('project', $this->project);
		}

		// We do not really need paginate() here, however the generic method
		// we are using here depends on it.
		$pastes = $query->orderBy('hits', 'desc')->take($perPage)->paginate($perPage);

		return $this->getList($pastes, TRUE);
	}

	/**
	 * Gets user's own pastes
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getUserPastes()
	{
		$perPage = Site::config('general')->perPage;

		$userId = Auth::user()->id;

		$pastes = Paste::where('author_id', $userId)->orderBy('id', 'desc')->paginate($perPage);

		return $this->getList($pastes);
	}

	/**
	 * Parses and displays a list
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $pastes
	 * @param  bool                                 $showFilters
	 * @return \Illuminate\Support\Facades\View
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
			'filters'  => $showFilters,
		);

		return View::make('site/list', $data);
	}

}
