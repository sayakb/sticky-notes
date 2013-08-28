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
	 * Show a paste by its ID or key
	 *
	 * @access public
	 * @param  string  $mode
	 * @param  string  $key
	 * @param  string  $hash
	 * @param  string  $password
	 * @return \Illuminate\View\View
	 */
	public function getShow($mode, $key, $hash = '', $password = '')
	{
		$api = API::make($mode);

		$paste = Paste::getByKey($key);

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
			else if ($paste->password != $password)
			{
				return $api->error('invalid_password', 403);
			}
		}

		// Build the API data
		$data = $paste->toArray();

		$data['key'] = Paste::getUrlKey($paste);

		return $api->out('show', $data);
	}

	/**
	 * Gets a paste list in the specified mode
	 *
	 * @param  string  $mode
	 * @param  string  $page
	 * @return \Illuminate\View\View
	 */
	public function getList($mode, $page = '')
	{
		$api = API::make($mode);

		$perPage = Site::config('general')->perPage;

		// Only the public pastes are accessible via the API
		$query = Paste::where('private', '<>', 1);

		$pastes = $query->orderBy('id', 'desc')->paginate($perPage);

		// We populate the data manually here as there is some
		// per item processing to be done
		$list = array();

		// Get the key for each paste item
		foreach ($pastes as $paste)
		{
			$data = $paste->toArray();

			$data['key'] = Paste::getUrlKey($paste);

			$list[] = $data;
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
	 * @return \Illuminate\View\View
	 */
	public function postCreate($mode)
	{
		$api = API::make($mode);

		// Set custom messages for validation module
		$custom = array(
			'title.max'           => 'title_max_30',
			'data.required'       => 'data_required',
			'language.required'   => 'lang_required',
			'language.in'         => 'lang_invalid',
			'expire.required'     => 'expire_required',
			'expire.in'           => 'expire_invalid',
		);

		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'title'     => 'max:30',
			'data'      => 'required',
			'language'  => 'required|in:'.Highlighter::make()->languages(TRUE),
			'expire'    => 'required|in:'.implode(',', array_keys(Config::get('expire'))),
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
		$antispam = Antispam::make($custom);

		// Run the anti-spam modules
		if ($antispam->fails())
		{
			return $api->error($antispam->message());
		}

		// Create the paste like a boss!
		$paste = Paste::createNew(Input::all());

		// All done! Now we need to output the urlkey and hash
		$data = array(
			'key'    => 'p'.$paste['urlkey'],
			'hash'   => ($paste['is_protected'] OR $paste['is_private']) ? $paste['hash'] : '',
		);

		// Return the output
		return $api->out('create', $data);
	}

}
