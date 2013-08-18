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
 * This controller handles displaying of a paste
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class ShowController extends BaseController {

	/**
	 * Displays the default view page
	 *
	 * @access public
	 * @param  string  $key
	 * @param  string  $hash
	 * @return \Illuminate\View\View
	 */
	public function getPaste($key, $hash = "", $mode = "")
	{
		$paste = Paste::getByKey($key);

		// Paste was not found
		if ($paste == NULL)
		{
			App::abort(404);
		}

		// User can view his own private and protected pastes
		if ( ! Auth::check() OR Auth::user()->username != $paste->author)
		{
			// Require hash to be passed for private pastes
			if ($paste->private AND $paste->hash != $hash)
			{
				App::abort(401); // Unauthorized
			}

			// Check if paste is password protected and user hasn't entered
			// the password yet
			if ($paste->password AND ! Session::has('paste.password'.$paste->id))
			{
				return View::make('site/password', array(), Site::defaults());
			}
		}

		// Increment the hit counter
		if ( ! Session::has('paste.viewed'.$paste->id))
		{
			$paste->hits++;
			$paste->save();

			Session::put('paste.viewed'.$paste->id, TRUE);
		}

		$data = array('paste' => $paste);

		return View::make('site/show', $data, Site::defaults());
	}

	/**
	 * Handles the paste password submission
	 *
	 * @param  string  $key
	 * @param  string  $hash
	 * @return \Illuminate\Support\Facades\Redirect|null
	 */
	public function postPassword($key, $hash = "")
	{
		$paste = Paste::getByKey($key);

		if ($paste != NULL AND Input::has('password'))
		{
			$entered = Input::get('password');

			if (PHPass::make()->check('Paste', $entered, $paste->salt, $paste->password))
			{
				Session::put('paste.password'.$paste->id, TRUE);

				return Redirect::to("{$key}/{$hash}");
			}
		}

		// Something wrong here
		App::abort(401);
	}

}
