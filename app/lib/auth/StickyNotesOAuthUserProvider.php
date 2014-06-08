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

use App;
use Auth;
use Cache;
use Config;
use Cookie;
use Input;
use Redirect;
use Site;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Database\Connection;

use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Storage\Session;
use OAuth\OAuth2\Service\Google;
use OAuth\ServiceFactory;

/**
 * StickyNotesOAuthUserProvider Class
 *
 * This class handles oAuth authentication.
 *
 * @package     StickyNotes
 * @subpackage  Drivers
 * @author      Sayak Banerjee
 */
class StickyNotesOAuthUserProvider implements UserProviderInterface {

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
		require_once base_path().'/vendor/lusitanian/OAuth/bootstrap.php';

		$url = url('/');

		if ( ! empty($this->auth->oauthGoogleId) AND ! empty($this->auth->oauthGoogleSecret))
		{
			// Setup the credentials for the requests
			$credentials = new Credentials(
				$this->auth->oauthGoogleId,
				$this->auth->oauthGoogleSecret,
				url('user/login')
			);

			// Session storage
			$storage = new Session();

			// Instantiate the Google service using the credentials, http client and storage mechanism for the token
			$service = new ServiceFactory();

			$google = $service->createService('google', $credentials, $storage, array('userinfo_email', 'groups_provisioning'));

			// Google responded with a code
			if (Input::has('code'))
			{
				 // This was a callback request from google, get the token
				$google->requestAccessToken(Input::get('code'));

				// Send a request with it
				$result = json_decode($google->request(Site::config('services')->googleUrlOAuth), TRUE);

				// Process user
				if (is_string($result['id']) AND is_string($result['email']) AND isset($result['verified_email']))
				{
					if ($result['verified_email'])
					{
						// First we will add each credential element to the query as a where clause.
						// Then we can execute the query and, if we found a user, return it in a
						// Eloquent User "model" that will be utilized by the Guard instances.
						$query = $this->createModel()->newQuery();

						// We search by email and user type. A filter for type=oauth is added to avoid
						// getting users created by other auth methods
						$query->where('email', $result['email'])->where('type', 'oauth');

						// If a user is not found, we need to create one automagically
						// Thats why even if count is 0, we return a new model instance
						$user = $query->count() > 0 ? $query->first() : $this->createModel();

						// Determine if user is an admin
						$googleAdmins = explode("\n", $this->auth->oauthGoogleAdmins);

						$isAdmin = in_array($result['email'], $googleAdmins);

						// We extract the username from the email address of the user
						$parts = explode('@', $result['email']);

						// Insert/Update user info
						$user->username = $parts[0];
						$user->password = '';
						$user->salt     = '';
						$user->email    = $result['email'];
						$user->type     = 'oauth';
						$user->active   = 1;
						$user->admin    = $isAdmin;

						$user->save();

						// Log the user in. We need to do it manually because we don't have an username
						// that we can 'attempt' to log in.
						Auth::login($user);

						return $user;
					}
				}

				App::abort(401); // Unauthorized
			}

			// We redirect the user to Google
			else
			{
				$url = $google->getAuthorizationUri()->getAbsoluteUri();
			}
		}

		App::after(function($request, $response) use ($url)
		{
			$response->headers->set('Location', $url);
		});

		return NULL;
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
		return FALSE;
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

}
