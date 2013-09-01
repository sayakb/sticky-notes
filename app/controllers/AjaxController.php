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
 * AjaxController
 *
 * This controller handles AJAX requests
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class AjaxController extends BaseController {

	/**
	 * Fetches the latest available sticky notes version
	 *
	 * @return \Illuminate\View\View
	 */
	public function getVersion()
	{
		// Get app configuration
		$app = Config::get('app');

		// Parse the version number to unified numeral format
		$localVersion = str_replace('.', '', $app['version']);

		// Get the remote version number
		$remoteVersion = @file_get_contents($app['updateUrl']);

		// Compare the versions and return the appropriate response
		$view = intval($remoteVersion) > intval($localVersion) ? 'old' : 'ok';

		return View::make("ajax/version/{$view}");
	}

	/**
	 * Gets the system load
	 *
	 * @return string
	 */
	public function getSysload()
	{
		return Site::getSystemLoad();
	}

	/**
	 * Generates a short URL for a paste
	 *
	 * @param  string  $urlkey
	 * @param  string  $hash = ''
	 * @return \Illuminate\View\View|string
	 */
	public function getShorten($urlkey, $hash = '')
	{
		// We need to validate the paste first
		$paste = Paste::where('urlkey', $urlkey)->first();

		// Paste was not found
		if (is_null($paste))
		{
			return Lang::get('ajax.error');
		}

		// If it is a private paste, we need the hash
		if ($paste->private AND $paste->hash != $hash)
		{
			return Lang::get('ajax.error');
		}

		// Shorten and return the paste URL
		$longUrl = url("{$urlkey}/{$hash}");

		return GoogleSvc::urlshortener($longUrl);
	}

}
