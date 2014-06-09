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
 * @since       Version 1.8
 * @filesource
 */

/**
 * FeedTest
 *
 * Unit test cases for FeedController
 *
 * @package     StickyNotes
 * @subpackage  UnitTests
 * @author      Sayak Banerjee
 */
class FeedTest extends StickyNotesTestCase {

	/**
	 * Tests the getFeed method of the controller
	 */
	public function testGetFeed()
	{
		$this->initTestStep();

		Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$response = $this->call('GET', 'feed/rss');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'application/rss+xml'));
	}

}
