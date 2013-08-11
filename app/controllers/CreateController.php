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
	 * Displays the new paste form
	 *
	 * @access	public
	 * @return	object	the parsed view
	 */
	public function getIndex()
	{
		// Set up the view
		$data = array(
			'site'		=> Site::config('general'),
			'languages'	=> Highlighter::languages()
		);

		return View::make('site/create', $data);
	}

	/**
	 * Creates a new paste item
	 *
	 * @return	object	the parsed view
	 */
	public function postIndex()
	{
		// Insert the new paste
		Paste::create(array(
			'title'		=> Input::get('title'),
			'data'		=> Input::get('data'),
			'language'	=> Input::get('language'),
			'password'	=> Input::get('password'),
			'salt'		=> str_random(5),
			'private'	=> Input::has('password') OR Input::has('private') ? 1 : 0,
			'hash'		=> rand(100000, 999999),
			'timestamp'	=> time(),
			'expire'	=> intval(Input::get('expire')) + time(),
			'ip'		=> Request::getClientIp(),
			'urlkey'	=> Paste::getUrlKey(),
			'hits'		=> 0
		));

		return Redirect::to('new');
	}

}
