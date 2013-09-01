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
 * GoogleSvc class
 *
 * Layer for accessing Google APIs
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class GoogleSvc {

	/**
	 * goo.gl URL shortener service
	 *
	 * @static
	 * @param  string  $longUrl
	 * @return string
	 */
	public static function urlshortener($longUrl)
	{
		$apiUrl = static::getServiceUrl('urlshortener');

		if ( ! is_null($apiUrl))
		{
			$ch = curl_init();

			// Set the API url to connect to
			curl_setopt($ch, CURLOPT_URL, $apiUrl);

			// We will be making a POST request
			curl_setopt($ch, CURLOPT_POST, TRUE);

			// Set the URL that we want to shorten
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("longUrl" => $longUrl)));

			// Indicate that we want the response in JSON format
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			// Indicate that we want a text response back
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			// This is just in case the SSL certificate is not valid
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			// Execute the POST request
			$result = curl_exec($ch);

			curl_close($ch);

			// Parse and return the response
			$response = json_decode($result, TRUE);

			if (isset($response['id']))
			{
				return $response['id'];
			}
		}

		return Lang::get('ajax.error');
	}

	/**
	 * Returns the service URL for a specific service
	 *
	 * @static
	 * @param  string  $service
	 * @return string
	 */
	private static function getServiceUrl($service)
	{
		// Get the service URL
		$urls = Config::get('googlesvc');

		// Get the site configuration
		$site = Site::config('general');

		// Get the google API key
		$apiKey = $site->googleApi;

		if ( ! empty($apiKey))
		{
			return sprintf($urls[$service], $apiKey);
		}
	}

}
