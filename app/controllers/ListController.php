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

		return $this->getList($pastes, TRUE);
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

		return $this->getList($pastes, FALSE, TRUE);
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
	 * Searches for a paste by its content
	 *
	 * @access public
	 * @param  string  $term
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getSearch()
	{
		$term = Input::get('q');

		// Initialize the antispam filters
		$antispam = Antispam::make('q');

		// Run the antispam validation
		if (strlen($term) >= 5 AND ($antispam->passes() OR Session::has('search.exempt')))
		{
			// Show all pastes to admins
			if (Auth::check() AND Auth::user()->admin)
			{
				$query = Paste::query();
			}
			else
			{
				$query = Paste::where('private', '<>', 1);
			}

			// Append the search term
			$query = $query->where('data', 'like', "%{$term}%");

			// Filter by project
			if ( ! empty($this->project))
			{
				$query = $query->where('project', $this->project);
			}

			// Get number of results to show per page
			$perPage = Site::config('general')->perPage;

			// Query the search results
			$pastes = $query->orderBy('id', 'desc')->paginate($perPage);

			// Append the search term to pagination URLs
			$pastes->appends('q', $term);

			// We will not run antispam if it passed once and there are
			// multiple pages. But we exempt it only for the next request.
			Session::flash('search.exempt', $perPage > $pastes->count());

			return $this->getList($pastes, TRUE);
		}
		else
		{
			Session::flash('messages.error', $antispam->message());

			return Redirect::to('all')->withInput();
		}
	}

	/**
	 * Searches for a paste by its content
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\View
	 */
	public function postSearch()
	{
		// Initialize the validator
		$validator = Validator::make(Input::all(), array(
			'search' => 'required|min:5|max:500'
		));

		// Run the validation rules
		if ($validator->passes())
		{
			return Redirect::to('search?q='.Input::get('search'));
		}
		else
		{
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));

			return Redirect::to('all')->withInput();
		}
	}

	/**
	 * Parses and displays a list
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $pastes
	 * @param  bool                                 $showFilters
	 * @param  bool                                 $showSearch
	 * @return \Illuminate\Support\Facades\View
	 */
	private function getList($pastes, $showSearch = FALSE, $showFilters = FALSE)
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
			'search'   => $showSearch,
		);

		return View::make('site/list', $data);
	}

}
