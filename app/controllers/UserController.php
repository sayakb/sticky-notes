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
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getLogin()
	{
		$auth = Site::config('auth');

		// Directly attempt auth if a method is selected that does not support
		// the login form
		$noForm = preg_split('/\||,/', $auth->noForm);

		if (in_array($auth->method, $noForm))
		{
			Auth::attempt();

			return Redirect::to('/');
		}
		else
		{
			return View::make('user/login');
		}
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
			'username'   => 'required',
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
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getRegister()
	{
		return View::make('user/register');
	}

	/**
	 * Handles POST requests on the registration screen
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postRegister()
	{
		// Define validation rules
		$rules = array(
			'username'    => 'required|max:50|alpha_dash|unique:users,username,-1,id,type,db',
			'email'       => 'required|max:100|email|unique:users,email,-1,id,type,db',
			'dispname'    => 'max:100',
			'password'    => 'required|min:5',
		);

		// Check if captcha is enabled, and if it is, validate it
		if (Site::config('auth')->dbShowCaptcha)
		{
			$rules['captcha'] = 'required|captcha';
		}

		$validator = Validator::make(Input::all(), $rules);

		// Run the validator
		if ($validator->passes())
		{
			$user = new User;

			$user->username = Input::get('username');
			$user->email    = Input::get('email');
			$user->dispname = Input::get('dispname');
			$user->salt     = str_random(5);
			$user->password = PHPass::make()->create(Input::get('password'), $user->salt);
			$user->admin    = 0;

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
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getForgot()
	{
		return View::make('user/forgot');
	}

	/**
	 * Handles POST requests to the password reset form
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postForgot()
	{
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'username'    => 'required|exists:users,username,type,db',
		));

		// Run the validator
		if ($validator->passes())
		{
			// Generate a random password
			$password = str_random(8);

			// Now we update the password in the database
			$user = User::where('username', Input::get('username'))->where('type', 'db')->first();

			$user->password = PHPass::make()->create($password, $user->salt);

			$user->save();

			// Build the email template
			$data = array_merge(View::defaults(), array(
				'name'     => $user->dispname ?: $user->username,
				'password' => $password,
			));

			// Send the notification mail
			Mail::queue('templates/email/forgot', $data, function($message) use ($user)
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

	/**
	 * Displays the user profile screen
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getProfile()
	{
		return View::make('user/profile');
	}

	/**
	 * Handles POST requests on the user profile
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postProfile()
	{
		$user = Auth::user();

		// Define validation rules
		$rules = array(
			'username'    => 'max:50|alpha_dash|unique:users,username,'.$user->id.',id,type,db',
			'email'       => 'required|max:100|email|unique:users,email,'.$user->id.',id,type,db',
			'dispname'    => 'max:100',
			'password'    => 'min:5',
		);

		$validator = Validator::make(Input::all(), $rules);

		// Run the validator
		if ($validator->passes())
		{
			$origUsername = $user->username;

			$user->username = $user->admin ? Input::get('username') : $user->username;
			$user->email    = Input::get('email');
			$user->dispname = Input::get('dispname');

			if (Input::has('password'))
			{
				$user->password = PHPass::make()->create(Input::get('password'), $user->salt);
			}

			$user->save();

			// Update cached username in the main table
			Paste::where('author_id', $user->id)->update(array(
				'author' => $user->username,
			));

			// Update cached username in the revisions table
			Revision::where('author', $origUsername)->update(array(
				'author' => $user->username,
			));

			// Update cached username in the comments table
			Comment::where('author', $origUsername)->update(array(
				'author' => $user->username,
			));

			Session::flash('messages.success', Lang::get('user.profile_saved'));

			return Redirect::to('user/profile');
		}
		else
		{
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));

			return Redirect::to('user/profile')->withInput();
		}
	}

}
