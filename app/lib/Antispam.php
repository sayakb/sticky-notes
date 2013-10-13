<?php namespace StickyNotes;

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

use Akismet;
use Auth;
use Config;
use Input;
use IPBan;
use Lang;
use Object;
use Request;
use Session;
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
	 * Antispam configuration
	 *
	 * @static
	 * @var array
	 */
	private $config;

	/**
	 * Stores the antispam error message
	 *
	 * @var string
	 */
	private $message = NULL;

	/**
	 * Custom messages to be displayed if validation fails
	 *
	 * @var array
	 */
	private $customMessages;

	/**
	 * Creates a new instance of antispam class
	 *
	 * @static
	 * @param  array  $messages
	 * @return void
	 */
	public static function make($messages = array())
	{
		$antispam = new Antispam();

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
		$flags = new stdClass();

		$services = static::services();

		// Fetching all enabled filters. This value can be defined
		// from the antispam screen in the admin panel
		$enabled = explode(',', Site::config('antispam')->services);

		foreach ($services as $service)
		{
			$flags->$service = in_array($service, $enabled);
		}

		return $flags;
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

		$methods = get_class_methods(static::make());

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
	 * Processes antispam filters
	 *
	 * @access public
	 * @return bool
	 */
	public function passes()
	{
		$this->config = Site::config('antispam');

		// Run only if data was POSTed
		if (Input::has('data'))
		{
			// We get the enabled services
			// Then we iterate through each of them to see if there is a
			// handler available for the service. If found, we run the handler
			$services = explode(',', $this->config->services);

			// Immutable services are always executed even if they are not
			// set explicitly from the admin panel. These services ideally
			// require no configuration and therefore, do not appear in the
			// antispam section of the admin panel
			$services = array_merge($services, Config::get('antispam.immutable'));

			// Run the spam filters
			foreach ($services as $service)
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
			$words = explode("\n", $this->config->censor);

			// Traverse through all blocked words
			foreach ($words as $word)
			{
				if (str_is($word, Input::get('data')))
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
		$data = strtolower(Input::get('data'));

		$language = Input::get('language');

		return ! (preg_match('/https?:\/\//', $data) AND $language == 'text');
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
		$posted = Session::get('paste.posted');

		$threshold = Site::config('antispam')->floodThreshold;

		if (time() - $posted >= $threshold)
		{
			Session::put('paste.posted', time());

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
		$akismet->setCommentContent(Input::get('data'));

		// Return the Akismet analysis
		return $akismet->isCommentSpam();
	}

}
