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
 * @since       Version 1.7
 * @filesource
 */

/**
 * CreateTest
 *
 * Unit test cases for CreateController
 *
 * @package     StickyNotes
 * @subpackage  UnitTests
 * @author      Sayak Banerjee
 */
class CreateTest extends StickyNotesTestCase {

	/**
	 * Tests the getCreate method of the controller
	 *
	 * @return void
	 */
	public function testGetCreate()
	{
		$this->client->request('GET', '/');

		$this->assertResponseOk();

		$this->assertViewHas('context', 'CreateController');
	}

}
