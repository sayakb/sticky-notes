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
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getDashboard()
	{
		// Get all stats for the last 1 month
		$duration = Site::config('general')->statsDisplay;

		$date = date('Y-m-d', strtotime($duration));

		$stats = Statistics::where('date', '>', $date)->orderBy('date')->get()->toArray();

		// Build the view data
		$data = array(
			'users'         => User::count(),
			'pastes'        => Paste::count(),
			'php_version'   => phpversion(),
			'sn_version'    => Config::get('app.version'),
			'db_driver'     => Config::get('database.default'),
			'stats'         => $stats,
		);

		return View::make('admin/dashboard', $data);
	}

	/**
	 * Search, edit and delete pastes
	 *
	 * @param  string  $urlkey
	 * @param  string  $action
	 * @return \Illuminate\Support\Facades\View|\Illuminate\Support\Facades\Redirect
	 */
	public function getPaste($urlkey = '', $action = '')
	{
		$paste = NULL;

		if ( ! empty($urlkey))
		{
			$paste = Paste::where('urlkey', $urlkey)->first();

			// Paste was not found
			if (is_null($paste))
			{
				Session::flash('messages.error', Lang::get('admin.paste_404'));
			}

			// Perform requested action
			switch ($action)
			{
				case 'rempass':

					$paste->password = '';

					$paste->save();

					return Redirect::to(URL::previous());

				case 'toggle':

					Revision::where('urlkey', $paste->urlkey)->delete();

					$paste->private = $paste->private ? 0 : 1;

					$paste->password = '';

					$paste->save();

					return Redirect::to(URL::previous());

				case 'remattach':

					$attachment = storage_path()."/uploads/{$paste->urlkey}";

					if ($paste->attachment AND File::exists($attachment))
					{
						File::delete($attachment);

						$paste->attachment = 0;

						$paste->save();
					}

					Session::flash('messages.success', Lang::get('admin.attachment_deleted'));

					return Redirect::to(URL::previous());

				case 'delete':

					Revision::where('urlkey', $paste->urlkey)->delete();

					$paste->comments()->delete();

					$attachment = storage_path()."/uploads/{$paste->urlkey}";

					if ($paste->attachment AND File::exists($attachment))
					{
						File::delete($attachment);
					}

					$paste->delete();

					Session::flash('messages.success', Lang::get('global.paste_deleted'));

					return Redirect::to('admin/paste');
			}
		}

		return View::make('admin/paste', array('paste' => $paste));
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

			return Redirect::to('admin/paste/'.urlencode($key));
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
	 * @return \Illuminate\Support\Facades\View|\Illuminate\Support\Facades\Redirect
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

				$data = array(
					'user'     => new User,
					'founder'  => FALSE,
				);

				return View::make('admin/user', $data);

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

		// Render the view. The founder flag here makes sure that the first
		// user cannot be blocked or removed from admin status.
		$data = array(
			'user'     => $user,
			'users'    => $users,
			'pages'    => $pages,
			'founder'  => is_null($user) ? FALSE : $user->id == User::min('id'),
		);

		return View::make('admin/user', $data);
	}

	/**
	 * Handles POST actions for the user module
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postUser()
	{
		if (Input::has('_save'))
		{
			$id = Input::get('id');

			// Define validation rules
			$validator = Validator::make(Input::all(), array(
				'username'    => 'required|max:50|alpha_dash|unique:users,username,'.$id.',id,type,db',
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

					$origUsername = $user->username;
				}
				else
				{
					$user = new User;

					$origUsername = NULL;
				}

				$user->username = Input::get('username');
				$user->email    = Input::get('email');
				$user->dispname = Input::get('dispname');
				$user->salt     = $user->salt ?: str_random(5);

				// The first user is always immutable
				$isFounder = $user->id == User::min('id');

				$user->admin = $isFounder ?: Input::has('admin');
				$user->active = $isFounder ?: Input::has('active');

				if (Input::has('password'))
				{
					$user->password = PHPass::make()->create(Input::get('password'), $user->salt);
				}

				$user->save();

				// Username is cached in the main, comment and revision tables, update them too
				if ( ! empty($id))
				{
					Paste::where('author_id', $id)->update(array(
						'author' => $user->username,
					));

					Revision::where('author', $origUsername)->update(array(
						'author' => $user->username,
					));

					Comment::where('author', $origUsername)->update(array(
						'author' => $user->username,
					));
				}

				Cache::flush();

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
	 * @param  string  $action
	 * @param  string  $ip
	 * @return \Illuminate\Support\Facades\View
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

		return View::make('admin/ban', array('bans' => IPBan::all()));
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
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getMail()
	{
		return View::make('admin/mail');
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
			// Save button click
			if (Input::has('_save'))
			{
				Site::config('mail', Input::all());

				Session::flash('messages.success', Lang::get('admin.mail_updated'));
			}

			// Test settings button click
			else if (Input::has('_test'))
			{
				// Backup the existing mail settings
				$original = (array) Site::config('mail');

				// Temporarily apply the new mail settings
				Site::config('mail', Input::all());

				// Test the mail settings
				$result = Mail::test();

				if ($result === TRUE)
				{
					Session::flash('messages.success', Lang::get('admin.test_mail_success'));
				}
				else
				{
					Session::flash('messages.error', $result);
				}

				// Revert back to original mail settings
				Site::config('mail', $original);
			}
		}
		else
		{
			Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));
		}

		return Redirect::to('admin/mail')->withInput();
	}

	/**
	 * Display the spam filter configuration screen
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getAntispam()
	{
		return View::make('admin/antispam', array('flags' => Antispam::flags()));
	}

	/**
	 * Handles POST requests to the antispam config form
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postAntispam()
	{
		// Define Akismet key validation logic
		Validator::extend('akismet_key', function($attribute, $value, $parameters)
		{
			$akismet = new Akismet(Request::url(), $value);

			return $akismet->isKeyValid();
		});

		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'php_key'           => 'required_if:flag_php,1',
			'php_days'          => 'required_if:flag_php,1|integer|between:0,255',
			'php_score'         => 'required_if:flag_php,1|integer|between:0,255',
			'php_type'          => 'required_if:flag_php,1|integer|between:0,255',
			'flood_threshold'   => 'required_if:flag_noflood,1|integer|between:0,60',
			'akismet_key'       => 'required_if:flag_akismet,1|akismet_key',
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
				'services' => implode('|', $flags)
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
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getAuth()
	{
		return View::make('admin/auth');
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
			'method'          => 'required|in:db,ldap,oauth',
			'db_allow_reg'    => 'required|in:0,1',
			'ldap_server'     => 'required_if:method,ldap',
			'ldap_base_dn'    => 'required_if:method,ldap',
			'ldap_uid'        => 'required_if:method,ldap',
			'ldap_admin'      => 'required_if:method,ldap',
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
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getSite()
	{
		return View::make('admin/site', array('langs' => System::directories('lang')));
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
			'lang'          => 'required|in:'.System::directories('lang', TRUE),
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

	/**
	 * Displays the skin chooser
	 *
	 * @access public
	 * @param  string  $action
	 * @param  string  $skin
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getSkin($action = 'list', $skin = '')
	{
		$version = System::version(Site::config('general')->version);

		$skins = System::directories('views/skins');

		$list = array();

		// Output the response based on the action
		switch ($action)
		{
			case 'list':

				foreach ($skins as $skin)
				{
					if (File::exists(app_path()."/views/skins/{$skin}/{$skin}.info"))
					{
						$info = @json_decode(File::get(app_path()."/views/skins/{$skin}/{$skin}.info"), TRUE);

						$data = array(
							'key'          => $skin,
							'name'         => isset($info['name']) ? $info['name'] : $skin,
							'version'      => isset($info['themeVersion']) ? $info['themeVersion'] : '1.0',
							'description'  => isset($info['description']) ? $info['description'] : NULL,
							'author'       => NULL,
						);

						if (isset($info['author']))
						{
							if (isset($info['authorWebsite']))
							{
								$data['author'] = link_to($info['authorWebsite'], $info['author']);
							}
							else
							{
								$data['author'] = $info['author'];
							}
						}

						$list[] = (object) $data;
					}
				}

				return View::make('admin/skin', array('skins' => $list));

			case 'set':

				if (File::exists(app_path()."/views/skins/{$skin}/{$skin}.info"))
				{
					$info = @json_decode(File::get(app_path()."/views/skins/{$skin}/{$skin}.info"), TRUE);

					// The theme info 'minCoreVersion' tells us the minimum version needed for
					// the theme to work. So we check if the system version is newer
					// than the core version before setting the theme
					if (isset($info['minCoreVersion']) AND $version >= System::version($info['minCoreVersion']))
					{
						Site::config('general', array('skin' => $skin));

						Cache::flush();

						Session::flash('messages.success', Lang::get('admin.skin_applied'));
					}
					else
					{
						Session::flash('messages.error', Lang::get('admin.skin_version'));
					}

					return Redirect::to('admin/skin');
				}

				Session::flash('messages.error', Lang::get('admin.skin_error'));

				return Redirect::to('admin/skin');

			case 'preview':

				if (File::exists(app_path()."/views/skins/{$skin}/{$skin}.png"))
				{
					$preview = File::get(app_path()."/views/skins/{$skin}/{$skin}.png");
				}
				else
				{
					$preview = File::get(public_path().'/assets/img/no-preview.png');
				}

				$response = Response::make($preview);

				$response->header('Content-Type', 'image/png');

				return $response;
		}
	}

	/**
	 * Displays services configuration screen
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getServices()
	{
		return View::make('admin/services');
	}

	/**
	 * Handles POST requests to the servics config form
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postServices()
	{
		Site::config('services', Input::all());

		Session::flash('messages.success', Lang::get('admin.services_updated'));

		return Redirect::to('admin/services');
	}

}
