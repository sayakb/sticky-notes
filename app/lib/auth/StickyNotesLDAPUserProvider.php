<?php namespace StickyNotes\Auth;

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

use Cache;
use Config;
use Session;
use Site;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Database\Connection;
use Illuminate\Hashing\HasherInterface;

/**
 * StickyNotesLDAPUserProvider Class
 *
 * This class handles LDAP authentication.
 *
 * @package     StickyNotes
 * @subpackage  Drivers
 * @author      Sayak Banerjee
 */
class StickyNotesLDAPUserProvider implements UserProviderInterface {

	/**
	 * The Eloquent user model.
	 *
	 * @var Illuminate\Database\Eloquent\Model
	 */
	protected $model;

	/**
	 * Authentication configuration.
	 *
	 * @var array
	 */
	protected $auth;

	/**
	 * Contains the retrieved user details
	 *
	 * @var object
	 */
	protected $user;

	/**
	 * Initializes the provider and sets the model instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->model = Config::get('auth.model');

		$this->auth = Site::config('auth');
	}

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed  $identifier
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveById($identifier)
	{
		return $this->createModel()->newQuery()->find($identifier);
	}

	/**
	 * Retrieve a user by by their unique identifier and "remember me" token.
	 *
	 * @param  mixed   $identifier
	 * @param  string  $token
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByToken($identifier, $token)
	{
		$model = $this->createModel();

		return $model->newQuery()
		             ->where($model->getKeyName(), $identifier)
		             ->where($model->getRememberTokenName(), $token)
		             ->first();
	}

	/**
	 * Update the "remember me" token for the given user in storage.
	 *
	 * @param  \Illuminate\Auth\UserInterface  $user
	 * @param  string                          $token
	 * @return void
	 */
	public function updateRememberToken(UserInterface $user, $token)
	{
		$user->setAttribute($user->getRememberTokenName(), $token);

		$user->save();
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array  $credentials
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByCredentials(array $credentials)
	{
		// First we will add each credential element to the query as a where clause.
		// Then we can execute the query and, if we found a user, return it in a
		// Eloquent User "model" that will be utilized by the Guard instances.
		$query = $this->createModel()->newQuery();

		foreach ($credentials as $key => $value)
		{
			if ( ! str_contains($key, 'password'))
			{
				$query->where($key, $value);
			}
		}

		// A filter for type=ldap is added to avoid getting users created by
		// other auth methods
		$query->where('type', 'ldap');

		// We store it locally as we need to access the data later
		// If a user is not found, we need to create one automagically
		// Thats why even if count is 0, we return a new model instance
		$this->user = $query->count() > 0 ? $query->first() : $this->createModel();

		return $this->user;
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Auth\UserInterface  $user
	 * @param  array                           $credentials
	 * @return bool
	 */
	public function validateCredentials(UserInterface $user, array $credentials)
	{
		$ldap = FALSE;

		$valid = FALSE;

		// Connect to the LDAP server
		if ( ! empty($this->auth->ldapPort))
		{
			$ldap = @ldap_connect($this->auth->ldapServer, (int)$this->auth->ldapPort);
		}
		else
		{
			$ldap = @ldap_connect($this->auth->ldapServer);
		}

		// Check if connection failed
		if ( ! $ldap)
		{
			return FALSE;
		}

		@ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);

		@ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

		// Try to bind with the user DN and password, if provided
		if ($this->auth->ldapUserDn OR $this->auth->ldapPassword)
		{
			if ( ! @ldap_bind($ldap, $this->auth->ldapUserDn, $this->auth->ldapPassword))
			{
				return FALSE;
			}
		}

		// Generate the user key (filter)
		$username = $this->ldapEscape($credentials['username']);

		$key = "({$this->auth->ldapUid}={$username})";

		// Get the user password
		$password = $credentials['password'];

		// Check if an additional filter is set
		if ($this->auth->ldapFilter)
		{
			if ($this->auth->ldapFilter[0] == '(' AND substr($this->auth->ldapFilter, -1) == ')')
			{
				$filter = $this->auth->ldapFilter;
			}
			else
			{
				$filter = "({$this->auth->ldapFilter})";
			}

			$key = "(&{$key}{$filter})";
		}

		// Look up for the user's details
		$search = @ldap_search($ldap, $this->auth->ldapBaseDn, $key);

		$entry = @ldap_first_entry($ldap, $search);

		if ( ! empty($entry))
		{
			$dn = @ldap_get_dn($ldap, $entry);

			// Validate credentials by binding with user's password
			if (@ldap_bind($ldap, $dn, $password))
			{
				// If the admin filter is not there, being a mandatory field,
				// this can only mean that the site was updated from an older
				// Sticky Notes. Therefore, we set isAdmin always 1.
				if ( ! empty($this->auth->ldapAdmin))
				{
					$ldapAdmin = array_map('trim', explode('=', $this->auth->ldapAdmin));

					$groups = @ldap_get_values($ldap, $entry, $ldapAdmin[0]);

					$isAdmin = (is_array($groups) AND in_array($ldapAdmin[1], $groups)) ? 1 : 0;
				}
				else
				{
					$isAdmin = 1;
				}

				// We need to flush the cache as the menus need to be parsed
				// again for this user.
				if ($this->user->admin != $isAdmin)
				{
					Cache::flush();
				}

				// Now if this is a new user, retrieveByCredentials would have
				// returned a new model. If it is an existing user, $this->user
				// has an instance of that user. Either way, we update the user info.
				if (is_null($this->user->id) OR $this->user->admin != $isAdmin)
				{
					$this->user->username = $credentials['username'];
					$this->user->password = '';
					$this->user->salt     = '';
					$this->user->email    = '';
					$this->user->type     = 'ldap';
					$this->user->active   = 1;
					$this->user->admin    = $isAdmin;

					$this->user->save();
				}

				$valid = TRUE;
			}
		}

		@ldap_close($ldap);

		return $valid;
	}

	/**
	 * Create a new instance of the model.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	private function createModel()
	{
		$class = '\\'.ltrim($this->model, '\\');

		return new $class;
	}

	/**
	 * Escapes auth string needed for plugins like LDAP
	 *
	 * @param  string  $string
	 * @return string
	 */
	private function ldapEscape($string)
	{
		return str_replace(
			array('*', '\\', '(', ')'),
			array('\\*', '\\\\', '\\(', '\\)'),
			$string
		);
	}

}
