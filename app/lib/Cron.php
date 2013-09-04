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
 * Cron class
 *
 * Offers scheduled execution functionality
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Cron {

	/**
	 * Cron execution interval
	 *
	 * @static
	 * @var int
	 */
	private static $interval = 1800;

	/**
	 * Run cron tasks. This is a simple implementation without
	 * any bells and whistles.
	 *
	 * @static
	 * @return void
	 */
	public static function run()
	{
		try
		{
			if (php_sapi_name() != 'cli')
			{
				if ( ! Cache::has('cron.schedule') OR Cache::get('cron.schedule') > time())
				{
					// Schedule run one hour from now
					Cache::forever('cron.schedule', time() + static::$interval);

					// Remove expired pastes
					Paste::where('expire', '>', 0)->where('expire', '<', time())->delete();

					// Add more cron tasks here..
				}
			}
		}
		catch(Exception $e)
		{
			// Suppress the exception here
		}
	}

}
