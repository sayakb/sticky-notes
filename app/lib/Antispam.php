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
	 * Creates a new instance of antispam class
	 *
	 * @static
	 * @return void
	 */
	public static function make()
	{
		return new Antispam();
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

			foreach ($services as $service)
			{
				$handler = array($this, 'run'.studly_case($service));

				if (is_callable($handler))
				{
					if ( ! call_user_func($handler))
					{
						$this->message = Lang::get('antispam.'.$service);

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
	 * @static
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
				$word = trim($word);
				$word = str_replace('*', '.*?', $word);
				$word = "/^{$word}$/i";

				// Check if the string exists in the post
				if (preg_match($word, Input::get('data')))
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
	 * @static
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
	 * @static
	 * @access private
	 * @return bool
	 */
	private function runStealth()
	{
		$data = strtolower(Input::get('data'));
		$language = Input::get('language');

		return ! (str_contains($data, '<a href') AND $language == 'text');
	}

	/**
	 * Flood control for Sticky Notes.
	 * This disallowes a user to create pastes in less than 5 second intervals.
	 *
	 * @static
	 * @access private
	 * @return bool
	 */
	private function runNoflood()
	{
		$posted = Session::get('posted');

		if (time() - $posted >= 5)
		{
			Session::put('posted', time());

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
	 * @static
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
			$sections = explode('.', $ip);
			$revIp = "{$sections[3]}.{$sections[2]}.{$sections[1]}.{$sections[0]}";

			// Query Project Honey Pot
			$response = dns_get_record("{$this->config->phpKey}.{$revIp}.dnsbl.httpbl.org");

			// Exit if NXDOMAIN is returned
			if (empty($response[0]['ip']))
			{
				return TRUE;
			}

			// Extract the info
			$result = explode('.', $response[0]['ip']);
			$days = $result[1];
			$score = $result[2];
			$type = $result[3];

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

}
