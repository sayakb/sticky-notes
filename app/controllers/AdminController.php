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
 * AdminController
 *
 * This controller handles site administration
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class AdminController extends BaseController {

	/**
	 * Redirects to the administration dashboard
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function getIndex()
	{
		return Redirect::to('admin/dashboard');
	}

	/**
	 * Displays the administration dashboard
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getDashboard()
	{
		return View::make('admin/layout', array(), Site::defaults());
	}

	/**
	 * Search, edit and delete pastes
	 *
	 * @param  string  $action
	 * @param  string  $key
	 * @return \Illuminate\View\View|\Illuminate\Support\Facades\Redirect
	 */
	public function getPaste($action = 'show', $key = '')
	{
		$paste = NULL;

		if ( ! empty($key))
		{
			$paste = Paste::getByKey($key);

			// Paste was not found
			if ($paste == NULL)
			{
				Session::flash('messages.error', Lang::get('admin.paste_404'));
			}

			// Perform requested action
			switch ($action)
			{
				case 'rempass':
					$paste->password = NULL;

					$paste->save();

					return Redirect::to(URL::previous());

				case 'toggle':
					$paste->private = $paste->private ? 0 : 1;
					$paste->password = NULL;

					$paste->save();

					return Redirect::to(URL::previous());

				case 'delete':
					$paste->delete();

					Session::flash('messages.success', Lang::get('admin.paste_deleted'));

					return Redirect::to('admin/paste');
			}
		}

		return View::make('admin/paste', array('paste' => $paste), Site::defaults());
	}

	/**
	 * Handles POST requests to the paste module
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postPaste()
	{
		if (Input::has('search'))
		{
			$key = Input::get('search');

			return Redirect::to('admin/paste/show/'.urlencode($key));
		}
		else
		{
			return Redirect::to('admin/paste');
		}
	}

	/**
	 * Search, create, edit or delete users
	 *
	 * @param  string  $action
	 * @param  string  $username
	 * @return \Illuminate\View\View|\Illuminate\Support\Facades\Redirect
	 */
	public function getUser($action = '', $username = '')
	{
		$user = NULL;
		$users = NULL;
		$pages = NULL;

		if ( ! empty($username))
		{
			$user = User::where('username', $username)->first();

			// User was not found
			if ($user == NULL)
			{
				Session::flash('messages.error', Lang::get('admin.user_404'));
			}

			// Perform user specific actions
			switch ($action)
			{
				case 'delete':
					$user->delete();

					Session::flash('messages.success', Lang::get('admin.user_deleted'));

					return Redirect::to('admin/user');
			}
		}
		else
		{
			// Perform non-user specific actions
			switch ($action)
			{
				case 'create':
					return View::make('admin/user', array('user' => new User), Site::defaults());

				default:
					$perPage = Site::config('general')->perPage;
					$users = User::orderBy('username')->paginate($perPage);
					$pages = $users->links();
					break;
			}
		}

		// Render the view
		$data = array(
			'user'     => $user,
			'users'    => $users,
			'pages'    => $pages,
		);

		return View::make('admin/user', $data, Site::defaults());
	}

	/**
	 * Handles POST actions for the user module
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postUser()
	{
		if (Input::has('save'))
		{
			$id = Input::get('id');

			// Define validation rules
			$validator = Validator::make(Input::all(), array(
				'username'    => 'required|max:50|alpha_num|unique:users,username,'.$id,
				'email'       => 'required|max:100|email|unique:users,email,'.$id,
				'dispname'    => 'max:100',
				'password'    => empty($id) ? 'required' : ''
			));

			// Run the validator
			if ($validator->passes())
			{
				// If ID is there, it is an update operation
				if ( ! empty($id))
				{
					$user = User::findOrFail($id);
				}
				else
				{
					$user = new User;
				}

				$user->username = Input::get('username');
				$user->email = Input::get('email');
				$user->dispname = Input::get('dispname');
				$user->salt = $user->salt ?: str_random(5);
				$user->admin = $user->id != 1 ? Input::has('admin') : 1;

				if (Input::has('password'))
				{
					$user->password = PHPass::make()->create(Input::get('password'), $user->salt);
				}

				$user->save();

				Session::flash('messages.success', Lang::get('admin.user_saved'));
			}
			else
			{
				Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));
			}

			return Redirect::to(URL::previous())->withInput();
		}
		else if (Input::has('search'))
		{
			$username = Input::get('search');

			return Redirect::to('admin/user/edit/'.urlencode($username));
		}
		else
		{
			return Redirect::to('admin/user');
		}
	}

	/**
	 * Displays the IP banning module
	 *
	 * @param  string $action
	 * @param  string $ip
	 * @return \Illuminate\View\View
	 */
	public function getBan($action = '', $ip = '')
	{
		// Perform IP address actions
		switch ($action)
		{
			case 'remove':
				$ipban = IPBan::findOrFail($ip);

				$ipban->delete();

				Session::flash('messages.success', Lang::get('admin.ip_unbanned'));

				return Redirect::to('admin/ban');
		}

		return View::make('admin/ban', array('bans' => IPBan::all()), Site::defaults());
	}

	/**
	 * Processes POST requests for the IP banning module
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postBan()
	{
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'ip'   => 'required|ip',
		));

		// Run the validator
		if ($validator->passes())
		{
			$ipban = new IPBan;

			$ipban->ip = Input::get('ip');

			$ipban->save();

			Session::flash('messages.success', Lang::get('admin.ip_banned'));

			return Redirect::to('admin/ban');
		}
		else
		{
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));

			return Redirect::to('admin/ban')->withInput();
		}
	}

	/**
	 * Displays site configuration screen
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getSite()
	{
		return View::make('admin/site', array(), Site::defaults());
	}

}
