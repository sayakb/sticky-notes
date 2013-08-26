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
		return View::make('user/login', array('auth' => Site::config('auth')), Site::defaults());
	}

	/**
	 * Handles user authentication requests
	 *
	 * @access public
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
				return Redirect::intended('/');
			}
			else
			{
				// Auth failed, show error message
				Session::flash('messages.error', Lang::get('user.auth_fail'));
			}
		}
		else
		{
			// Set the error message as flashdata
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));
		}

		return Redirect::to('user/login')->withInput();
	}

	/**
	 * Shows the user registration screen
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getRegister()
	{
		// Show error if registration is not allowed
		$auth = Site::config('auth');

		if ($auth->method != 'db' OR ! $auth->dbAllowReg)
		{
			Session::flash('messages.error', Lang::get('user.reg_disabled'));
		}

		return View::make('user/register', array(), Site::defaults());
	}

	/**
	 * Handles POST requests on the registration screen
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postRegister()
	{
		// Check if registration is allowed
		$auth = Site::config('auth');

		if ($auth->method != 'db' OR ! $auth->dbAllowReg)
		{
			App::abort(401);
		}

		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'username'    => 'required|max:50|alpha_num|unique:users,username',
			'email'       => 'required|max:100|email|unique:users,email',
			'dispname'    => 'max:100',
			'password'    => 'required|min:5',
			'captcha'     => 'required|captcha'
		));

		// Run the validator
		if ($validator->passes())
		{
			$user = new User;

			$user->username = Input::get('username');
			$user->email = Input::get('email');
			$user->dispname = Input::get('dispname');
			$user->salt = str_random(5);
			$user->password = PHPass::make()->create(Input::get('password'), $user->salt);
			$user->admin = 0;

			$user->save();

			Session::flash('messages.success', Lang::get('user.register_done'));

			return Redirect::to('user/login');
		}
		else
		{
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));

			return Redirect::to('user/register')->withInput();
		}
	}

	/**
	 * Handles user logout
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function getLogout()
	{
		Auth::logout();

		return Redirect::to('/');
	}

	/**
	 * Displays the password reset screen
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getForgot()
	{
		// Show error if resetting is not allowed
		$auth = Site::config('auth');

		if ($auth->method != 'db')
		{
			Session::flash('messages.error', Lang::get('user.forgot_disabled'));
		}

		return View::make('user/forgot', array(), Site::defaults());
	}

	/**
	 * Handles POST requests to the password reset form
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postForgot()
	{
		// Check if resetting password is allowed
		$auth = Site::config('auth');

		if ($auth->method != 'db')
		{
			App::abort(401);
		}

		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'username'    => 'required|exists:users,username',
		));

		// Run the validator
		if ($validator->passes())
		{
			// Generate a random password
			$password = str_random(8);

			// Now we update the password in the database
			$user = User::where('username', Input::get('username'))->first();

			$user->password = PHPass::make()->create($password, $user->salt);

			$user->save();

			// Build the email template
			$data = array_merge(Site::defaults(), array(
				'dispname'   => $user->dispname,
				'password'   => $password,
			));

			// Send the notification mail
			Mail::send('mail.forgot', $data, function($message) use ($user)
			{
				$message->to($user->email)->subject(Lang::get('mail.forgot_subject'));
			});

			// All done!
			Session::flash('messages.success', Lang::get('user.reset_done'));

			return Redirect::to('user/login');
		}
		else
		{
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));

			return Redirect::to('user/forgot')->withInput();
		}
	}

}
