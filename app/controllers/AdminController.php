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
			if (is_null($paste))
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
		$perPage = Site::config('general')->perPage;

		$user = User::where('username', $username)->where('type', 'db')->first();

		$users = User::where('type', 'db')->orderBy('username')->paginate($perPage);

		$pages = $users->links();

		// User not found
		if ( ! empty($username) AND is_null($user))
		{
			Session::flash('messages.error', Lang::get('admin.user_404'));

			return Redirect::to('admin/user');
		}

		// Perform the specified action
		switch ($action)
		{
			case 'create':
				return View::make('admin/user', array('user' => new User), Site::defaults());

			case 'delete':
				// Cannot delete founder user or own account
				if ($user->id != 1 AND $user->id != Auth::user()->id)
				{
					$user->delete();

					Session::flash('messages.success', Lang::get('admin.user_deleted'));

					return Redirect::to('admin/user');
				}
				else
				{
					Session::flash('messages.error', Lang::get('admin.user_del_fail'));
				}
		}

		// Render the view
		$data = array(
			'user'     => $user,
			'users'    => $users,
			'pages'    => $pages,
			'auth'     => Site::config('auth'),
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
				'username'    => 'required|max:50|alpha_num|unique:users,username,'.$id.',id,type,db',
				'email'       => 'required|max:100|email|unique:users,email,'.$id.',id,type,db',
				'dispname'    => 'max:100',
				'password'    => empty($id) ? 'required|min:5' : 'min:5'
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
				$user->email    = Input::get('email');
				$user->dispname = Input::get('dispname');
				$user->salt     = $user->salt ?: str_random(5);

				// The first user is always immutable
				$user->admin = $user->id != 1 ? Input::has('admin') : 1;

				if (Input::has('password'))
				{
					$user->password = PHPass::make()->create(Input::get('password'), $user->salt);
				}

				$user->save();

				// Username is cached in the main table, update that too
				if ( ! empty($id))
				{
					Paste::where('authorid', $id)->update(array(
						'author' => Input::get('username')
					));
				}

				Session::flash('messages.success', Lang::get('admin.user_saved'));

				return Redirect::to('admin/user');
			}
			else
			{
				Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));

				return Redirect::to(URL::previous())->withInput();
			}
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
		// Remove a specific IP address
		if ($action == 'remove' AND ! empty($ip))
		{
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
			'ip' => 'required|ip',
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
	 * Displays the email configuration module
	 *
	 * @return \Illuminate\View\View
	 */
	public function getMail()
	{
		return View::make('admin/mail', array('mail' => Site::config('mail')), Site::defaults());
	}

	/**
	 * Handles POST requests to the email config form
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postMail()
	{
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'driver'        => 'required|in:smtp,mail,sendmail',
			'host'          => 'required_if:driver,smtp',
			'port'          => 'required_if:driver,smtp',
			'address'       => 'required',
			'sendmail'      => 'required_if:driver,sendmail',
		));

		// Run the validator
		if ($validator->passes())
		{
			Site::config('mail', Input::all());

			Session::flash('messages.success', Lang::get('admin.mail_updated'));

			return Redirect::to('admin/mail');
		}
		else
		{
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));

			return Redirect::to('admin/mail')->withInput();
		}
	}

	/**
	 * Display the spam filter configuration screen
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getAntispam()
	{
		// Build the view data
		$data = array(
			'flags'     => Antispam::flags(),
			'antispam'  => Site::config('antispam'),
		);

		return View::make('admin/antispam', $data, Site::defaults());
	}

	/**
	 * Handles POST requests to the antispam config form
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postAntispam()
	{
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'php_key'           => 'required_if:flag_php,1',
			'php_days'          => 'required_if:flag_php,1|integer|between:0,255',
			'php_score'         => 'required_if:flag_php,1|integer|between:0,255',
			'php_type'          => 'required_if:flag_php,1|integer|between:0,255',
			'flood_threshold'   => 'required_if:flag_noflood,1|integer|between:0,60',
		));

		// Run the validator
		if ($validator->passes())
		{
			$services = Antispam::services();
			$flags = array();

			// Convert the service flags to CSV
			foreach ($services as $service)
			{
				if (Input::has('flag_'.$service))
				{
					$flags[] = $service;
				}
			}

			// Inject flag data to the configuration
			$config = array_merge(Input::all(), array(
				'services' => implode(',', $flags)
			));

			Site::config('antispam', $config);

			Session::flash('messages.success', Lang::get('admin.antispam_updated'));

			return Redirect::to('admin/antispam');
		}
		else
		{
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));

			return Redirect::to('admin/antispam')->withInput();
		}
	}

	/**
	 * Displays user authentication configuration screen
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getAuth()
	{
		return View::make('admin/auth', array('auth' => Site::config('auth')), Site::defaults());
	}

	/**
	 * Handles POST requests to the user auth config form
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postAuth()
	{
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'method'          => 'required|in:db,ldap',
			'db_allow_reg'    => 'required|in:0,1',
			'ldap_server'     => 'required_if:method,ldap',
			'ldap_base_dn'    => 'required_if:method,ldap',
			'ldap_uid'        => 'required_if:method,ldap',
		));

		// Run the validator
		if ($validator->passes())
		{
			Site::config('auth', Input::all());

			Session::flash('messages.success', Lang::get('admin.auth_updated'));

			return Redirect::to('admin/auth');
		}
		else
		{
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));

			return Redirect::to('admin/auth')->withInput();
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
		return View::make('admin/site', array('langs' => Site::getLanguages()), Site::defaults());
	}

	/**
	 * Handles POST requests to the site config form
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postSite()
	{
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'fqdn'          => 'required',
			'title'         => 'required|max:20',
			'per_page'      => 'required|integer|between:5,200',
			'lang'          => 'required|in:'.Site::getLanguages(TRUE),
		));

		// Run the validator
		if ($validator->passes())
		{
			Site::config('general', Input::all());

			Session::flash('messages.success', Lang::get('admin.site_updated'));

			return Redirect::to('admin/site');
		}
		else
		{
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));

			return Redirect::to('admin/site')->withInput();
		}
	}

}
