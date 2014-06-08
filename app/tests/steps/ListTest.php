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
 * ListTest
 *
 * Unit test cases for ListController
 *
 * @package     StickyNotes
 * @subpackage  UnitTests
 * @author      Sayak Banerjee
 */
class ListTest extends StickyNotesTestCase {

	/**
	 * Tests the getAll method of the controller
	 */
	public function testGetAll()
	{
		$this->action('GET', 'ListController@getAll');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getTrending method of the controller
	 */
	public function testGetTrending()
	{
		$this->action('GET', 'ListController@getTrending');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getUserPastes method of the controller
	 */
	public function testGetUserPastes()
	{
		$this->be(User::first());

		$this->action('GET', 'ListController@getUserPastes', array(
			'userid' => 'u1',
		));

		$this->assertResponseOk();
	}

	/**
	 * Tests the getSearch method of the controller
	 */
	public function testGetSearch()
	{
		$this->action('GET', 'ListController@getSearch', array(
			'q' => 'UnitTest',
		));

		$this->assertResponseOk();
	}

	/**
	 * Tests the postSearch method of the controller
	 */
	public function testPostSearch()
	{
		$this->action('POST', 'ListController@postSearch', array(
			'search' => 'UnitTest',
		));

		$this->assertRedirectedTo('search?q=UnitTest');
	}

	/**
	 * Tests the getFlagged method of the controller
	 */
	public function testGetFlagged()
	{
		// Flag the first paste
		Paste::where('id', 1)->update(array(
			'flagged' => 1,
		));

		// Only admins have access to flags
		$this->be(User::first());

		$this->action('GET', 'ListController@getFlagged');

		$this->assertResponseOk();
	}

}
