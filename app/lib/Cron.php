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

use File;
use Paste;
use Revision;
use Schema;
use Site;
use Statistics;

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
		// We run the cron tasks once every 5 minutes
		Cache::remember('site.cron', 5, function()
		{
			$expired = array();

			$storage = storage_path();

			// Retrieve expired pastes
			$pastes = Paste::where('expire', '>', 0)->where('expire', '<', time())->get();

			if ($pastes->count() > 0)
			{
				// Check if the comments table exists
				$hasComments = Schema::hasTable('comments');

				// Build the expired pastes array
				// Also delete associated comments and attachments
				foreach($pastes as $paste)
				{
					$expired[] = $paste->urlkey;

					$paste->comments()->delete();

					$attachment = "{$storage}/uploads/{$paste->urlkey}";

					if ($paste->attachment AND File::exists($attachment))
					{
						File::delete($attachment);
					}
				}

				// Remove expired pastes
				Paste::whereIn('urlkey', $expired)->delete();

				// Remove expired revisions
				Revision::whereIn('urlkey', $expired)->delete();
			}

			// Delete paste statistics older than configured age
			$ttl = Site::config('general')->statsTTL;

			$date = date('Y-m-d', strtotime($ttl));

			Statistics::where('date', '<', $date)->delete();

			// Crun run successfully
			return TRUE;
		});
	}

}
