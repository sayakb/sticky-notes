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
		$this->action('GET', 'UserController@getLogin');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postLogin method of the controller
	 */
	public function testPostLogin()
	{
		$response = $this->action('POST', 'UserController@postLogin', array(
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
		$this->action('GET', 'UserController@getRegister');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postRegister method of the controller
	 */
	public function testPostRegister()
	{
		// Disable the captcha
		Site::config('auth', array(
			'db_show_captcha' => 0
		));

		// Generate a random user key
		$key = 'unittest'.time();

		$this->action('POST', 'UserController@postRegister', array(
			'username' => $key,
			'password' => $key,
			'email'    => "{$key}@test.com",
		));

		$this->assertRedirectedTo('user/login');
	}

	/**
	 * Tests the getLogout method of the controller
	 */
	public function testGetLogout()
	{
		$this->be(User::first());

		$this->action('GET', 'UserController@getLogout');

		$this->assertFalse(Auth::check());
	}

	/**
	 * Tests the getForgot method of the controller
	 */
	public function testGetForgot()
	{
		$this->action('GET', 'UserController@getForgot');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postForgot method of the controller
	 *
	 * @expectedException Swift_TransportException
	 */
	public function testPostForgot()
	{
		$username = User::orderBy('id', 'desc')->first()->username;

		$this->action('POST', 'UserController@postForgot', array(
			'username' => $username,
		));

		$this->assertRedirectedTo('user/login');
	}

	/**
	 * Tests the getProfile method of the controller
	 */
	public function testGetProfile()
	{
		$this->be(User::first());

		$this->enableFilters();

		$this->action('GET', 'UserController@getProfile');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postProfile method of the controller
	 */
	public function testPostProfile()
	{
		$this->be(User::first());

		$this->action('POST', 'UserController@postProfile', array(
			'username' => 'unittest',
			'password' => 'unittest',
			'email'    => 'unit@test.com',
			'dispname' => 'Unit Test',
		));

		$this->assertSessionHas('messages.success');
	}

}
