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
	 */
	public function testGetCreate()
	{
		$this->action('GET', 'CreateController@getCreate');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postCreate method of the controller and
	 * creates a public paste
	 */
	public function testPostCreatePublic()
	{
		// Pass only required parameters and allow Sticky Notes
		// to default the rest
		$response = $this->action('POST', 'CreateController@postCreate', array(
			'title'    => 'UnitTest::Title',
			'data'     => 'UnitTest::Data',
			'language' => 'text',
		));

		$this->assertRedirectedTo($response->getTargetUrl());
	}

	/**
	 * Tests the postCreate method of the controller and
	 * creates a password protected paste
	 */
	public function testPostCreateProtected()
	{
		$this->action('POST', 'CreateController@postCreate', array(
			'title'    => 'UnitTest::Title',
			'data'     => 'UnitTest::Data',
			'password' => 'UnitTest::Password',
			'language' => 'text',
		));

		$this->assertRedirectedTo('/');

		$this->assertSessionHas('messages.success');
	}

	/**
	 * Tests the getRevision method of the controller
	 */
	public function testGetRevision()
	{
		// Revisions are allowed only for public pastes
		// So we get the key for the first public paste
		$urlkey = Paste::where('private', 0)->firstOrFail()->urlkey;

		$this->action('GET', 'CreateController@getRevision', array(
			'urlkey' => $urlkey,
		));

		$this->assertResponseOk();
	}

	/**
	 * Tests the postRevision method of the controller
	 */
	public function testPostRevision()
	{
		$id = Paste::where('private', 0)->firstOrFail()->id;

		// This is a security check that is performed at the controller
		// level which we need to mock
		$this->session(array('paste.revision' => $id));

		$response = $this->action('POST', 'CreateController@postRevision', array(
			'id'       => $id,
			'title'    => 'UnitTest::Title',
			'data'     => 'UnitTest::Revision',
			'language' => 'text',
		));

		$this->assertRedirectedTo($response->getTargetUrl());
	}

}
