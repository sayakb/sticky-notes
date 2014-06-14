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
 * UserTest
 *
 * Unit test cases for UserController
 *
 * @package     StickyNotes
 * @subpackage  UnitTests
 * @author      Sayak Banerjee
 */
class UserTest extends StickyNotesTestCase {

	/**
	 * Tests the getLogin method of the controller
	 */
	public function testGetLogin()
	{
		$this->initTestStep();

		$this->call('GET', 'user/login');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postLogin method of the controller
	 */
	public function testPostLogin()
	{
		$this->initTestStep();

		$response = $this->call('POST', 'user/login', array(
			'username' => 'unittest',
			'password' => 'unittest',
		));

		$this->assertTrue(Auth::check());
	}

	/**
	 * Tests the getRegister method of the controller
	 */
	public function testGetRegister()
	{
		$this->initTestStep();

		$this->call('GET', 'user/register');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postRegister method of the controller
	 */
	public function testPostRegister()
	{
		$this->initTestStep();

		// Disable the captcha
		Site::config('auth', array(
			'db_show_captcha' => 0,
			'db_allow_reg'    => 1,
		));

		// Generate a random user key
		$key = 'unittest'.time();

		$this->call('POST', 'user/register', array(
			'username' => $key,
			'password' => $key,
			'email'    => "{$key}@test.com",
		));

		$this->assertRedirectedTo('user/login');

		$this->assertEquals(User::where('username', $key)->count(), 1);
	}

	/**
	 * Tests the getLogout method of the controller
	 */
	public function testGetLogout()
	{
		$this->initTestStep();

		$this->call('GET', 'user/logout');

		$this->assertFalse(Auth::check());
	}

	/**
	 * Tests the getForgot method of the controller
	 */
	public function testGetForgot()
	{
		$this->initTestStep();

		$this->call('GET', 'user/forgot');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postForgot method of the controller
	 *
	 * @expectedException Swift_TransportException
	 */
	public function testPostForgot()
	{
		$this->initTestStep();

		$username = User::orderBy('id', 'desc')->first()->username;

		$this->call('POST', 'user/forgot', array(
			'username' => $username,
		));

		$this->assertRedirectedTo('user/login');

		$this->assertSessionHas('messages.success');
	}

	/**
	 * Tests the getProfile method of the controller
	 */
	public function testGetProfile()
	{
		$this->initTestStep();

		$this->call('GET', 'user/profile');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postProfile method of the controller
	 */
	public function testPostProfile()
	{
		$this->initTestStep();

		$key = 'Unit Test'.time();

		$this->call('POST', 'user/profile', array(
			'username' => 'unittest',
			'password' => 'unittest',
			'email'    => 'unit@test.com',
			'dispname' => $key,
		));

		$this->assertSessionHas('messages.success');

		$this->assertEquals(User::where('dispname', $key)->count(), 1);
	}

}
