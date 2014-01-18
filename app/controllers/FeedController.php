<?php

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
 * @since       Version 1.1
 * @filesource
 */

/**
 * FeedController
 *
 * This controller handles feed operations
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class FeedController extends BaseController {

	/**
	 * Gets the news feed for the site
	 *
	 * @param  string  $type
	 * @return void
	 */
	public function getFeed($type = 'rss')
	{
		// Create feeder instance
		$feed = Feed::make($type);

		// Only the public pastes are accessible in the feed
		$query = Paste::where('private', '<>', 1);

		// We fetch 100 pastes only
		$pastes = $query->take(100)->orderBy('id', 'desc')->get();

		// We populate the data manually here as there is some
		// per item processing to be done
		$list = array();

		// Get the key for each paste item
		foreach ($pastes as $paste)
		{
			$list[] = $paste->toArray();
		}

		// Serve the feed output
		return $feed->out(array('pastes'  => $list));
	}

}
