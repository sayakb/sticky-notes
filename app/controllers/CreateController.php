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
	public function getCreate()
	{
		$data = array(
			'languages'  => Highlighter::make()->languages()
		);

		return View::make('site/create', $data, Site::defaults());
	}

	/**
	 * Creates a new paste item
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postCreate()
	{
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'title'     => 'max:30',
			'data'      => 'required',
			'language'  => 'in:'.Highlighter::make()->languages(TRUE),
			'expire'    => 'in:'.implode(',', array_keys(Config::get('expire')))
		));

		// Generate anti-spam modules
		$antispam = Antispam::make();

		// Run validations
		$resultValidation = $validator->passes();
		$resultAntispam = $antispam->passes();

		// Run validations
		if ($resultValidation AND $resultAntispam)
		{
			// Password and private flags
			$is_protected = Input::has('password');
			$is_private = Input::has('private');

			// Unique key and secure hash for the paste
			$urlkey = Paste::makeUrlKey();
			$hash = Paste::getHash();

			// Set the paste author
			$author = Auth::check() ? Auth::user()->username : NULL;

			// Encrypt the password with a salt
			$password = '';
			$salt = str_random(5);

			if (Input::has('password'))
			{
				$password = PHPass::make()->create(Input::get('password'), $salt);
			}

			// Insert the new paste
			Paste::create(array(
				'project'     => $this->project,
				'title'       => Input::get('title'),
				'data'        => Input::get('data'),
				'language'    => Input::get('language'),
				'private'     => $is_protected OR $is_private ? 1 : 0,
				'password'    => $password,
				'salt'        => $salt,
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
				$url = link_to("p{$urlkey}/{$hash}");
				$message = sprintf(Lang::get('create.click_for_paste'), $url);

				Session::flash('messages.success', $message);
			}
			else if ($is_private)
			{
				return Redirect::to("p{$urlkey}/{$hash}");
			}
			else
			{
				return Redirect::to("p{$urlkey}");
			}
		}
		else
		{
			// Set the error message as flashdata
			if ( ! $resultValidation)
			{
				Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));
			}
			else if ( ! $resultAntispam)
			{
				Session::flash('messages.error', $antispam->message());
			}
		}

		return Redirect::to('/')->withInput();
	}

}
