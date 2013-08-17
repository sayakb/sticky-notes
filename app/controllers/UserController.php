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
 * UserController
 *
 * This controller handles users and their sessions
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class UserController extends BaseController {

	/**
	 * Displays the user login page
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getLogin()
	{
		return View::make('common/login', array(), Site::defaults());
	}

	/**
	 * Handles user authentication requests
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postLogin()
	{
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'username'   => 'required|alpha_dash|max:50',
			'password'   => 'required'
		));

		// Run the validator
		if ($validator->passes())
		{
			$remember = Input::has('remember');

			$success = Auth::attempt(array(
				'username'   => Input::get('username'),
				'password'   => Input::get('password')
			), $remember);

			if ($success)
			{
				return Redirect::intended('new');
			}
			else
			{
				// Auth failed, show error message
				Session::flash('error', Lang::get('global.auth_fail'));
			}
		}
		else
		{
			// Set the error message as flashdata
			Session::flash('error', $validator->messages()->all('<p>:message</p>'));
		}

		return Redirect::to('user/login')->withInput();
	}

	/**
	 * Handles user logout
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function getLogout()
	{
		Auth::logout();

		return Redirect::to('new');
	}

}
