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
	 */
	public function testGetCreate()
	{
		$this->initTestStep();

		$this->call('GET', '/');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postCreate method of the controller and
	 * creates a public paste
	 */
	public function testPostCreatePublic()
	{
		$this->initTestStep();

		$key = 'UnitTest::Public'.time();

		$response = $this->call('POST', 'create', array(
			'title'     => 'UnitTest::Title',
			'data'      => $key,
			'language'  => 'text',
		));

		$this->assertRedirectedTo($response->getTargetUrl());

		$this->assertTrue(Paste::where('data', $key)->count() == 1);
	}

	/**
	 * Tests the postCreate method of the controller and
	 * creates a password protected paste
	 */
	public function testPostCreateProtected()
	{
		$this->initTestStep();

		$key = 'UnitTest::Protected'.time();

		$this->call('POST', 'create', array(
			'title'    => 'UnitTest::Title',
			'data'     => $key,
			'password' => 'UnitTest::Password',
			'language' => 'text',
		));

		$this->assertRedirectedTo('/');

		$this->assertSessionHas('messages.success');

		$this->assertTrue(Paste::where('data', $key)->count() == 1);
	}

	/**
	 * Tests the getRevision method of the controller
	 */
	public function testGetRevision()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', "rev/{$paste->urlkey}");

		$this->assertResponseOk();
	}

	/**
	 * Tests the postRevision method of the controller
	 */
	public function testPostRevision()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->session(array('paste.revision' => $paste->id));

		$response = $this->call('POST', 'revise', array(
			'id'       => $paste->id,
			'title'    => 'UnitTest::Title',
			'data'     => 'UnitTest::Revision',
			'language' => 'text',
		));

		$this->assertRedirectedTo($response->getTargetUrl());

		$this->assertTrue(Revision::where('urlkey', $paste->urlkey)->count() == 1);
	}

}
