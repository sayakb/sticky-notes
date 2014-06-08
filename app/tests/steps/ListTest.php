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
		$this->initTestStep();

		Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', 'all');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getTrending method's 'now' age
	 */
	public function testGetTrendingNow()
	{
		$this->initTestStep();

		Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', 'trending');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getTrending method's 'week' age
	 */
	public function testGetTrendingWeek()
	{
		$this->initTestStep();

		Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', 'trending/week');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getTrending method's 'month' age
	 */
	public function testGetTrendingMonth()
	{
		$this->initTestStep();

		Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', 'trending/month');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getTrending method's 'year' age
	 */
	public function testGetTrendingYear()
	{
		$this->initTestStep();

		Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', 'trending/year');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getTrending method's 'all' age
	 */
	public function testGetTrendingAll()
	{
		$this->initTestStep();

		Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', 'trending/all');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getUserPastes method of the controller
	 */
	public function testGetUserPastes()
	{
		$this->initTestStep();

		Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', 'user/u1/pastes');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getSearch method of the controller
	 */
	public function testGetSearch()
	{
		$this->initTestStep();

		Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', 'search', array(
			'q' => 'UnitTest',
		));

		$this->assertResponseOk();
	}

	/**
	 * Tests the postSearch method of the controller
	 */
	public function testPostSearch()
	{
		$this->initTestStep();

		$this->call('POST', 'search', array(
			'search' => 'UnitTest',
		));

		$this->assertRedirectedTo('search?q=UnitTest');
	}

	/**
	 * Tests the getFlagged method of the controller
	 */
	public function testGetFlagged()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$paste->flagged = 1;

		$paste->save();

		$this->call('GET', 'flagged');

		$this->assertResponseOk();
	}

}
