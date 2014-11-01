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
	 * Tests the getCreate method of the controller without
	 * guest posts enabled
	 */
	public function testGetCreateNoGuest()
	{
		$this->initTestStep(FALSE);

		Site::config('general', array('guestPosts' => '0'));

		$this->call('GET', '/');

		$this->assertRedirectedTo('user/login');

		Site::config('general', array('guestPosts' => '1'));
	}

	/**
	 * Tests the getCreate method of the controller with noExpire
	 * set to 'none' and logged in as admin
	 */
	public function testExpirationAdmin()
	{
		$this->initTestStep();

		Site::config('general', array('noExpire' => 'none'));

		$response = $this->client->request('GET', '/');

		$this->assertResponseOk();

		$this->assertCount(1, $response->filter('option:contains("forever")'));
	}

	/**
	 * Tests the getCreate method of the controller with noExpire
	 * set to 'user' and not logged in
	 */
	public function testExpirationGuest()
	{
		$this->initTestStep(FALSE);

		Site::config('general', array('noExpire' => 'user'));

		$response = $this->client->request('GET', '/');

		$this->assertResponseOk();

		$this->assertCount(0, $response->filter('option:contains("forever")'));
	}

	/**
	 * Tests the postCreate method of the controller and
	 * creates a public paste
	 */
	public function testPostCreatePublic()
	{
		$this->initTestStep();

		$key = 'UnitTest::Public'.str_random(64);

		$response = $this->call('POST', 'create', array(
			'title'     => 'UnitTest::Title',
			'data'      => $key,
			'language'  => 'text',
		));

		$this->assertRedirectedTo($response->getTargetUrl());

		$this->assertEquals(Paste::where('data', $key)->count(), 1);
	}

	/**
	 * Tests the postCreate method of the controller and
	 * creates a password protected paste
	 */
	public function testPostCreateProtected()
	{
		$this->initTestStep();

		$key = 'UnitTest::Protected'.str_random(64);

		$this->call('POST', 'create', array(
			'title'    => 'UnitTest::Title',
			'data'     => $key,
			'password' => 'UnitTest::Password',
			'language' => 'text',
		));

		$this->assertRedirectedTo('/');

		$this->assertSessionHas('messages.success');

		$this->assertEquals(Paste::where('data', $key)->count(), 1);
	}

	/**
	 * Verifies 'enforce public' setting when creating pastes
	 */
	public function testPostCreatePublicSite()
	{
		$this->initTestStep();

		Site::config('general', array('paste_visibility' => 'public'));

		$key = 'UnitTest::Protected'.str_random(64);

		$response = $this->call('POST', 'create', array(
			'title'    => 'UnitTest::Title',
			'data'     => $key,
			'password' => 'UnitTest::Password',
			'language' => 'text',
		));

		Site::config('general', array('paste_visibility' => 'default'));

		$this->assertRedirectedTo($response->getTargetUrl());

		$this->assertEquals(Paste::where('data', $key)->first()->private, 0);
	}

	/**
	 * Verifies 'enforce private' setting when creating pastes
	 */
	public function testPostCreatePrivateSite()
	{
		$this->initTestStep();

		Site::config('general', array('paste_visibility' => 'private'));

		$key = 'UnitTest::Protected'.str_random(64);

		$response = $this->call('POST', 'create', array(
			'title'    => 'UnitTest::Title',
			'data'     => $key,
			'language' => 'text',
		));

		Site::config('general', array('paste_visibility' => 'default'));

		$this->assertRedirectedTo($response->getTargetUrl());

		$this->assertEquals(Paste::where('data', $key)->first()->private, 1);
	}

	/**
	 * Tests the postCreate method of the controller without
	 * guest posts enabled
	 */
	public function testPostCreateNoGuest()
	{
		$this->initTestStep(FALSE);

		Site::config('general', array('guest_posts' => '0'));

		$key = 'UnitTest::Protected'.str_random(64);

		$response = $this->call('POST', 'create', array(
			'title'    => 'UnitTest::Title',
			'data'     => $key,
			'language' => 'text',
		));

		$this->assertSessionHas('messages.error');

		$this->assertEquals(Paste::where('data', $key)->count(), 0);
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
	 * Tests the getRevision method of the controller without
	 * guest posts enabled
	 */
	public function testGetRevisionNoGuest()
	{
		$this->initTestStep(FALSE);

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', "rev/{$paste->urlkey}");

		$this->assertRedirectedTo('user/login');
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

		$this->assertEquals(Revision::where('urlkey', $paste->urlkey)->count(), 1);
	}

	/**
	 * Tests the postRevision method of the controller without
	 * guest posts enabled
	 */
	public function testPostRevisionNoGuest()
	{
		$this->initTestStep(FALSE);

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

		$this->assertSessionHas('messages.error');

		$this->assertEquals(Revision::where('urlkey', $paste->urlkey)->count(), 0);
	}

}
