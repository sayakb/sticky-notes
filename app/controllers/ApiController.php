<?php

/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package     StickyNotes
 * @author      Sayak Banerjee
 * @copyright   (c) 2014 Sayak Banerjee <mail@sayakbanerjee.com>
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
	 * The constructor here validates the API mode
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
	 * Fetches allowed values for a certain parameter
	 *
	 * @param  string  $mode
	 * @param  string  $param
	 * @return void
	 */
	public function getParameter($mode, $param)
	{
		$api = API::make($mode);

		switch ($param)
		{
			case 'language':

				$languages = Highlighter::make()->languages();

				$values = array_keys($languages);

				break;

			case 'expire':

				$expire = Paste::getExpiration();

				$values = array_keys($expire);

				break;

			case 'version':

				$values = array(Config::get('app.version'));

				break;

			case 'theme':

				$values = array(studly_case(Site::config('general')->skin));

				break;

			default:

				return $api->error('invalid_param', 404);
		}

		// Build the API data
		$data = array(
			'param'     => $param,
			'values'    => $values,
		);

		return $api->out('param', $data);
	}

	/**
	 * Show a paste by its ID or key
	 *
	 * @access public
	 * @param  string  $mode
	 * @param  string  $urlkey
	 * @param  string  $hash
	 * @param  string  $password
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getShow($mode, $urlkey, $hash = '', $password = '')
	{
		$api = API::make($mode);

		$paste = Paste::where('urlkey', $urlkey)->first();

		// The paste was not found
		if (is_null($paste))
		{
			return $api->error('not_found', 404);
		}

		// Validate the hash for private pastes
		if ($paste->private AND $paste->hash != $hash)
		{
			return $api->error('invalid_hash', 403);
		}

		// Validate the password for protected pastes
		if ($paste->password)
		{
			if (empty($password))
			{
				return $api->error('password_required', 403);
			}
			else if ( ! PHPass::make()->check('Paste', $password, $paste->salt, $paste->password))
			{
				return $api->error('invalid_password', 403);
			}
		}

		// Build the API data
		$data = $paste->toArray();

		return $api->out('show', $data);
	}

	/**
	 * Gets a paste list in the specified mode
	 *
	 * @param  string  $mode
	 * @param  int     $page
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getList($mode, $page = 1)
	{
		$api = API::make($mode);

		$perPage = Site::config('general')->perPage;

		// As laravel reads the page GET parameter, we need to
		// manually set it to use this page.
		DB::getPaginator()->setCurrentPage($page);

		// Only the public pastes are accessible via the API
		$query = Paste::where('private', '<>', 1);

		$pastes = $query->orderBy('id', 'desc')->paginate($perPage);

		// Check if no pastes were found
		if ($pastes->count() === 0)
		{
			return $api->error('no_pastes', 418);
		}

		// We populate the data manually here as there is some
		// per item processing to be done
		$list = array();

		// Get the key for each paste item
		foreach ($pastes as $paste)
		{
			$list[] = $paste->toArray();
		}

		// Build the API data and make the output
		$data = array(
			'pastes'  => $list,
			'count'   => $pastes->count(),
			'pages'   => $pastes->getLastPage(),
		);

		return $api->out('list', $data);
	}

	/**
	 * Creates a new paste via the API
	 *
	 * @param  string  $mode
	 * @return \Illuminate\Support\Facades\View
	 */
	public function postCreate($mode)
	{
		$api = API::make($mode);

		// Set custom messages for validation module
		$custom = array(
			'title.max'           => 'title_max_30',
			'data.required'       => 'data_required',
			'data.auth'           => 'cannot_post',
			'data.mbmax'          => 'data_too_big',
			'language.required'   => 'lang_required',
			'language.in'         => 'lang_invalid',
			'expire.integer'      => 'expire_integer',
			'expire.in'           => 'expire_invalid',
		);

		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'title'     => 'max:30',
			'data'      => 'required|auth|mbmax:'.Site::config('general')->maxPasteSize,
			'language'  => 'required|in:'.Highlighter::make()->languages(TRUE),
			'expire'    => 'integer|in:'.Paste::getExpiration('create', TRUE),
		), $custom);

		// Run validations
		if ($validator->fails())
		{
			return $api->error($validator->messages()->first());
		}

		// Set custom messages for the antispam module
		$custom = array(
			'ipban'    => 'antispam_ipban',
			'stealth'  => 'antispam_stealth',
			'censor'   => 'antispam_censor',
			'noflood'  => 'antispam_noflood',
			'php'      => 'antispam_php',
		);

		// Instantiate the antispam module
		$antispam = Antispam::make('api_call', 'data', $custom);

		// Run the anti-spam modules
		if ($antispam->fails())
		{
			return $api->error($antispam->message());
		}

		// Create the paste like a boss!
		$paste = Paste::createNew('api', Input::all());

		// All done! Now we need to output the urlkey and hash
		$data = array(
			'urlkey'  => $paste->urlkey,
			'hash'    => $paste->hash,
		);

		// Return the output
		return $api->out('create', $data);
	}

}
