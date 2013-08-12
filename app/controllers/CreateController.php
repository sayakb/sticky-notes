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
			'languages'	=> Highlighter::languages(),
			'error'		=> Session::get('error'),
			'success'	=> Session::get('success'),
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
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'title'		=> 'alpha_dash|max:30',
			'data'		=> 'required',
			'language'	=> 'in:'.Highlighter::languages(TRUE),
			'expire'	=> 'in:'.implode(',', array_keys(Config::get('expire')))
		));

		// Run the validator
		if ($validator->passes())
		{
			// Generate a unique key for the paste
			$urlkey = Paste::getUrlKey();
			$hash = rand(100000, 999999);

			// Insert the new paste
			Paste::create(array(
				'title'		=> Input::get('title'),
				'data'		=> Input::get('data'),
				'language'	=> Input::get('language'),
				'password'	=> Input::get('password'),
				'salt'		=> str_random(5),
				'private'	=> Input::has('password') OR Input::has('private') ? 1 : 0,
				'hash'		=> $hash,
				'urlkey'	=> $urlkey,
				'timestamp'	=> time(),
				'expire'	=> intval(Input::get('expire')) + time(),
				'ip'		=> Request::getClientIp(),
				'hits'		=> 0
			));

			// Redirect to paste if there's no password
			// Otherwise, just show a link
			if (Input::has('password'))
			{
				$url = link_to("view/{$urlkey}/{$hash}");
				$message = sprintf(Lang::get('create.click_for_paste', $url));

				Session::flash('success', $message);
			}
			else if (Input::has('private'))
			{
				return Redirect::to("show/{$urlkey}/{$hash}");
			}
			else
			{
				return Redirect::to("show/{$urlkey}");
			}
		}
		else
		{
			// Set the error message as flashdata
			Session::flash('error', $validator->messages()->all('<p>:message</p>'));
		}

		return Redirect::to('new');
	}

}
