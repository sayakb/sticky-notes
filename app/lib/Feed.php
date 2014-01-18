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
 * @since       Version 1.1
 * @filesource
 */

/**
 * Feed class
 *
 * Sticky notes feed generator
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Feed {

	/**
	 * Defines the feed type
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Creates a new feed class instance
	 *
	 * @static
	 * @param  string  $type
	 * @return void
	 */
	public static function make($type)
	{
		$feed = new Feed();

		$feed->type = $type;

		return $feed;
	}

	/**
	 * Generates the feed output.
	 *
	 * @param  array  $data
	 * @return void
	 */
	public function out($data)
	{
		// Clean each data item recursively for XML output
		array_walk_recursive($data, array($this, 'sanitizeFeed'));

		// Now we create a custom response
		$response = Response::view("templates/feed/{$this->type}", $data);

		// We set the content type based on feed type
		$response->header('Content-Type', "application/{$this->type}+xml");

		return $response;
	}

	/**
	 * Sanitize the data for the feed
	 *
	 * @param  string  $data
	 * @return void
	 */
	private function sanitizeFeed(&$data)
	{
		$data = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data);

		$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
	}

}
