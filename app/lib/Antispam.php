<?php namespace StickyNotes;

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

use Akismet;
use Input;
use IPBan;
use Lang;
use Object;
use Request;
use Site;
use stdClass;

/**
 * Antispam class
 *
 * Provides protection over spambots
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Antispam {

	/**
	 * Site's antispam configuration
	 *
	 * @var array
	 */
	public $config;

	/**
	 * Scope of the antispam operation
	 *
	 * @var string
	 */
	public $scope = NULL;

	/**
	 * Data to run antispam validation on
	 *
	 * @var string
	 */
	public $data = NULL;

	/**
	 * Stores the antispam error message
	 *
	 * @var string
	 */
	public $message = NULL;

	/**
	 * Custom messages to be displayed if validation fails
	 *
	 * @var array
	 */
	public $customMessages;

	/**
	 * Creates a new instance of antispam class
	 *
	 * @static
	 * @param  string  $scope
	 * @param  string  $dataKey
	 * @param  array   $messages
	 * @return void
	 */
	public static function make($scope, $dataKey, $messages = array())
	{
		$antispam = new Antispam();

		// Set the scope of operation
		$antispam->scope = $scope;

		// Set the data to be validated
		$antispam->data = Input::get($dataKey);

		// Set the current configuration
		$antispam->config = Site::config('antispam');

		// Set custom messages to return
		$antispam->customMessages = $messages;

		return $antispam;
	}

	/**
	 * Return flags indicating whether each filter is
	 * enabled or disabled
	 *
	 * @static
	 * @return object
	 */
	public static function flags()
	{
		return Cache::rememberForever('antispam.flags', function()
		{
			$flags = new stdClass();

			$services = Antispam::services();

			// Fetching all enabled filters. This value can be defined
			// from the antispam screen in the admin panel
			$enabled = preg_split('/\||,/', Site::config('antispam')->services);

			foreach ($services as $service)
			{
				$flags->$service = in_array($service, $enabled);
			}

			return $flags;
		});
	}

	/**
	 * Returns a list of antispam services
	 *
	 * @static
	 * @return array
	 */
	public static function services()
	{
		$services = array();

		$methods = get_class_methods(static::make(null, null));

		foreach ($methods as $method)
		{
			if (starts_with($method, 'run'))
			{
				$services[] = strtolower(substr($method, 3));
			}
		}

		return $services;
	}

	/**
	 * Returns the scopes for a specific service
	 *
	 * @static
	 * @param  string  $service
	 * @return string
	 */
	public static function scopes($service)
	{
		$scopes = Config::get('antispam.scopes');

		$inScopes = array();

		// Iterate through each scope and check if this service is
		// in that scope
		foreach ($scopes as $scope => $services)
		{
			if (in_array($service, $services))
			{
				$inScopes[] = studly_case(str_plural($scope));
			}
		}

		// Now that we collected all scopes, return a merged list of
		// scope names wherein the service exists
		return implode(', ', $inScopes);
	}

	/**
	 * Processes antispam filters
	 *
	 * @access public
	 * @return bool
	 */
	public function passes()
	{
		if ( ! empty($this->data))
		{
			// Load the antispam configuration
			// This is not same as the site configuration
			$antispam = Config::get('antispam');

			// We get the enabled services
			// Then we iterate through each of them to see if there is a
			// handler available for the service. If found, we run the handler
			$services = preg_split('/\||,/', $this->config->services);

			// Immutable services are always executed even if they are not
			// set explicitly from the admin panel. These services ideally
			// require no configuration and therefore, do not appear in the
			// antispam section of the admin panel
			$services = array_merge($services, $antispam['immutable']);

			// Remove leading/trailing spaces from service names
			$services = array_map('trim', $services);

			// Run the spam filters
			foreach ($services as $service)
			{
				// Check if this service is available for the current scope
				// This helps us decide whether or not to run this service
				if (in_array($service, $antispam['scopes'][$this->scope]))
				{
					$handler = array($this, 'run'.studly_case($service));

					if (is_callable($handler))
					{
						if ( ! call_user_func($handler))
						{
							if (isset($this->customMessages[$service]))
							{
								$this->message = $this->customMessages[$service];
							}
							else
							{
								$this->message = Lang::get('antispam.'.$service);
							}

							return FALSE;
						}
					}
				}
			}
		}

		return TRUE;
	}

	/**
	 * Inverse of Antispam::passes()
	 *
	 * @access public
	 * @return bool
	 */
	public function fails()
	{
		return ! $this->passes();
	}

	/**
	 * Fetches the antispam message
	 *
	 * @return string
	 */
	public function message()
	{
		return $this->message;
	}

	/**
	 * Word censor for sticky notes.
	 * This plugin checks if specific words are contained within the POSTed
	 * paste body
	 *
	 * @access private
	 * @return bool
	 */
	private function runCensor()
	{
		if ( ! empty($this->config->censor))
		{
			// Get array of blocked words
			$words = array_map('trim', explode("\n", $this->config->censor));

			// Traverse through all blocked words
			foreach ($words as $word)
			{
				if (str_is($word, $this->data))
				{
					return FALSE;
				}
			}
		}

		return TRUE;
	}

	/**
	 * IP ban access control.
	 * This plugin checks if the current user has been banned from creating
	 * new pastes.
	 *
	 * @access private
	 * @return bool
	 */
	private function runIpban()
	{
		$banned = IPBan::where('ip', Request::getClientIp())->count();

		return $banned == 0;
	}

	/**
	 * Sticky Notes' in-build HTML filter.
	 *
	 * @access private
	 * @return bool
	 */
	private function runStealth()
	{
		$data = strtolower($this->data);

		$language = Input::get('language');

		// Get the number of links in the paste
		preg_match_all('/https?:\/\//', $data, $matches);

		// Disallow if number of links are more than configured threshold
		return ! (isset($matches[0]) AND count($matches[0]) > $this->config->stealthCount AND $language == 'text');
	}

	/**
	 * Flood control for Sticky Notes.
	 * This disallowes a user to create pastes in less than 5 second intervals.
	 *
	 * @access private
	 * @return bool
	 */
	private function runNoflood()
	{
		$posted = Session::get('form.posted');

		$threshold = Site::config('antispam')->floodThreshold;

		if (time() - $posted >= $threshold)
		{
			Session::put('form.posted', time());

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Project Honeypot integration allows sticky-notes to check if an IP
	 * address is flagged as abusive in the honeypot database.
	 *
	 * For details, see: http://www.projecthoneypot.org/
	 *
	 * @access private
	 * @return bool
	 */
	private function runPhp()
	{
		try
		{
			$ip = Request::getClientIp();

			// Skip validation is no key is specified in config.php
			if (empty($this->config->phpKey))
			{
				return TRUE;
			}

			// We cannot process an IPv6 address
			if( ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
			{
				return TRUE;
			}

			// Convert IP address to reversed octet format
			// So for example, 127.0.0.1 becomes 1.0.0.127
			$sections = explode('.', $ip);

			$revIp = "{$sections[3]}.{$sections[2]}.{$sections[1]}.{$sections[0]}";

			// Query Project Honey Pot BL
			// The URI of the query for 127.0.0.1 would be:
			//  - phpkey.1.0.0.127.dnsbl.httpbl.org
			$response = dns_get_record("{$this->config->phpKey}.{$revIp}.dnsbl.httpbl.org");

			// Exit if NXDOMAIN is returned
			if (empty($response[0]['ip']))
			{
				return TRUE;
			}

			// The information returns is:
			//  - The age of the IP address in the honeypot database (0 - 255)
			//  - The threat score of the IP address (0 - 255)
			//  - The type of the threat (0 - 255)
			$result = explode('.', $response[0]['ip']);

			$days  = $result[1];
			$score = $result[2];
			$type  = $result[3];

			// Perform PHP validation
			if ($days <= $this->config->phpDays AND ($type >= $this->config->phpType OR $score >= $this->config->phpScore))
			{
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		catch (Exception $e)
		{
			return TRUE;
		}
	}

	/**
	 * Akismet automatic spam filter. See http://akismet.com
	 *
	 * @access private
	 * @return void
	 */
	private function runAkismet()
	{
		// Create the Akismet instance
		$akismet = new Akismet(Request::url(), $this->config->akismetKey);

		// Set the author info if the user is logged in
		if (Auth::check())
		{
			$user = Auth::user();

			$akismet->setCommentAuthor($user->username);

			$akismet->setCommentAuthorEmail($user->email);
		}

		// Set the content to validate
		$akismet->setCommentContent($this->data);

		// Return the Akismet analysis
		return ! $akismet->isCommentSpam();
	}

}
