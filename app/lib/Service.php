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

use Lang;
use Request;
use Site;

use UnitedPrototype\GoogleAnalytics\Page;
use UnitedPrototype\GoogleAnalytics\Session;
use UnitedPrototype\GoogleAnalytics\Tracker;
use UnitedPrototype\GoogleAnalytics\Visitor;

/**
 * Services class
 *
 * Layer for accessing third party services
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Service {

	/**
	 * goo.gl URL shortener service
	 *
	 * @static
	 * @param  string  $longUrl
	 * @return string
	 */
	public static function urlShortener($longUrl)
	{
		$services = Site::config('services');

		if ( ! empty($services->googleApiKey))
		{
			$url = sprintf($services->googleUrlShortener, $services->googleApiKey);

			$ch = curl_init();

			// Set the API url to connect to
			curl_setopt($ch, CURLOPT_URL, $url);

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
	 * Google analytics tracker
	 *
	 * @static
	 * @return void
	 */
	public static function analytics()
	{
		$site = Site::config('general');

		$services = Site::config('services');

		// Run analytics if a tracking code is set
		if ( ! empty($services->googleAnalyticsId))
		{
			// Initilize GA Tracker
			$tracker = new Tracker($services->googleAnalyticsId, $site->fqdn);

			// Gather visitor information
			$visitor = new Visitor();

			$visitor->setIpAddress(Request::getClientIp());

			$visitor->setUserAgent(Request::server('HTTP_USER_AGENT'));

			// Gather session information
			$session = new Session();

			// Gather page information
			$path = Request::path();

			$page = new Page($path == '/' ? $path : "/{$path}");

			$page->setTitle($site->title);

			// Track page view
			$tracker->trackPageview($page, $session, $visitor);
		}
	}

}
