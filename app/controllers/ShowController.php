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
	 * @param  string  $action
	 * @return \Illuminate\View\View|\Illuminate\Support\Facades\Redirect|null
	 */
	public function getPaste($key, $hash = '', $action = '')
	{
		$paste = Paste::getByKey($key);
		$owner = Auth::check() AND (Auth::user()->admin OR Auth::user()->username == $paste->author);

		// Paste was not found
		if ($paste == NULL)
		{
			App::abort(404);
		}

		// We do not make password prompt mandatory for owners
		if ( ! $owner)
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

		// Let's do some action!
		switch ($action)
		{
			case 'toggle':
				$paste->private = $paste->private ? 0 : 1;
				$paste->password = NULL;
				$paste->save();
				break;

			case 'shorten':
				die("Short url here");

			case 'raw':
				die($paste->data);

			default:
				return View::make('site/show', array('paste' => $paste), Site::defaults());
		}

		// If we are here, we should get outta here quickly!
		return Redirect::to(URL::previous());
	}

	/**
	 * Handles the paste password submission
	 *
	 * @param  string  $key
	 * @param  string  $hash
	 * @return \Illuminate\Support\Facades\Redirect|null
	 */
	public function postPassword($key, $hash = '')
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
