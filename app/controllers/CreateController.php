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
 * CreateController
 *
 * This is the default homepage of the site and allows the user to create a new
 * paste.
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class CreateController extends BaseController {

	/**
	 * Displays the new paste form
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getIndex()
	{
		$data = array('languages' => Highlighter::languages());

		return View::make('site/create', $data, Site::defaults());
	}

	/**
	 * Creates a new paste item
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postIndex()
	{
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'title'     => 'alpha_dash|max:30',
			'data'      => 'required',
			'language'  => 'in:'.Highlighter::languages(TRUE),
			'expire'    => 'in:'.implode(',', array_keys(Config::get('expire')))
		));

		// Run the validator
		if ($validator->passes())
		{
			// Password and private flags
			$is_protected = Input::has('password');
			$is_private = Input::has('private');

			// Unique key and secure hash for the paste
			$urlkey = Paste::getUrlKey();
			$hash = Paste::getHash();

			// Set the paste author
			if (Auth::check())
			{
				$author = Auth::user()->username;
			}
			else
			{
				$author = NULL;
			}

			// Insert the new paste
			Paste::create(array(
				'project'     => $this->project,
				'title'       => Input::get('title'),
				'data'        => Input::get('data'),
				'language'    => Input::get('language'),
				'password'    => Input::get('password'),
				'salt'        => str_random(5),
				'private'     => $is_protected OR $is_private ? 1 : 0,
				'hash'        => $hash,
				'urlkey'      => $urlkey,
				'author'      => $author,
				'timestamp'   => time(),
				'expire'      => intval(Input::get('expire')) + time(),
				'ip'          => Request::getClientIp(),
				'hits'        => 0
			));

			// Redirect to paste if there's no password
			// Otherwise, just show a link
			if ($is_protected)
			{
				$url = link_to("view/{$urlkey}/{$hash}");
				$message = sprintf(Lang::get('create.click_for_paste', $url));

				Session::flash('success', $message);
			}
			else if ($is_private)
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

		return Redirect::to('new')->withInput();
	}

}
