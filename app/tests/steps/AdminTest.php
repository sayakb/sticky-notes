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
 * AdminTest
 *
 * Unit test cases for AdminController
 *
 * @package     StickyNotes
 * @subpackage  UnitTests
 * @author      Sayak Banerjee
 */
class AdminTest extends StickyNotesTestCase {

	/**
	 * Tests the getIndex method of the controller
	 */
	public function testGetIndex()
	{
		$this->initTestStep();

		$this->call('GET', 'admin');

		$this->assertRedirectedTo('admin/dashboard');
	}

	/**
	 * Tests the getDashboard method of the controller
	 */
	public function testGetDashboard()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/dashboard');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getPaste method of the controller
	 */
	public function testGetPaste()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/paste');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getPaste method's 'rempass' action
	 */
	public function testGetPasteRemPass()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'password'  => 'UnitTest::Password',
			'language'  => 'text',
		));

		$this->call('GET', "admin/paste/{$paste->urlkey}/rempass");

		$this->assertRedirectedTo('/');

		$this->assertEquals(Paste::find($paste->id)->password, '');
	}

	/**
	 * Tests the getPaste method's 'toggle' action
	 */
	public function testGetPasteToggle()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'password'  => 'UnitTest::Password',
			'language'  => 'text',
		));

		$this->call('GET', "admin/paste/{$paste->urlkey}/toggle");

		$this->assertRedirectedTo('/');

		$this->assertEquals(Paste::find($paste->id)->private, 0);
	}

	/**
	 * Tests the getPaste method's 'remattach' action
	 */
	public function testGetPasteRemAttach()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'      => 'UnitTest::Title',
			'data'       => 'UnitTest::Data',
			'password'   => 'UnitTest::Password',
			'language'   => 'text',
			'attachment' => array(true),
		));

		File::put(storage_path()."/uploads/{$paste->urlkey}", 'attachment');

		$this->call('GET', "admin/paste/{$paste->urlkey}/remattach");

		$this->assertRedirectedTo('/');

		$this->assertEquals(Paste::find($paste->id)->attachment, 0);

		$this->assertFalse(File::exists(storage_path()."/uploads/{$paste->urlkey}"));
	}

	/**
	 * Tests the getPaste method's 'delete' action
	 */
	public function testGetPasteDelete()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', "admin/paste/{$paste->urlkey}/delete");

		$this->assertRedirectedTo('admin/paste');

		$this->assertEquals(Paste::where($paste->id)->count(), 0);
	}

	/**
	 * Tests the postPaste method of the controller
	 */
	public function testPostPaste()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('POST', 'admin/paste', array(
			'search' => $paste->urlkey,
		));

		$this->assertRedirectedTo("admin/paste/{$paste->urlkey}");
	}

	/**
	 * Tests the getUser method of the controller
	 */
	public function testGetUser()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/user');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getUser method's 'create' action
	 */
	public function testGetUserCreate()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/user/create');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getUser method's 'delete' action
	 */
	public function testGetUserDelete()
	{
		$this->initTestStep();

		User::insert(array(
			'username' => 'unitdel',
			'password' => 'unitdel',
			'salt'     => '12345',
			'email'    => 'unit@del.com',
		));

		$this->call('GET', 'admin/user/delete/unitdel');

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/user');

		$this->assertEquals(User::where('username', 'unitdel')->count(), 0);
	}

	/**
	 * Tests the postUser method's 'save' action for inserting
	 * new user
	 */
	public function testPostUserInsert()
	{
		$this->initTestStep();

		$key = 'unittest'.time();

		$this->call('POST', 'admin/user', array(
			'username' => $key,
			'password' => $key,
			'email'    => "{$key}@test.com",
			'_save'    => 1,
		));

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/user');

		$this->assertEquals(User::where('username', $key)->count(), 1);
	}

	/**
	 * Tests the postUser method's 'save' action for updating
	 * existing user
	 */
	public function testPostUserUpdate()
	{
		$this->initTestStep();

		$email = 'email'.time().'@test.com';

		$this->call('POST', 'admin/user', array(
			'id'       => 1,
			'username' => 'unittest',
			'password' => 'unittest',
			'email'    => $email,
			'_save'    => 1,
		));

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/user');

		$this->assertEquals(User::where('email', $email)->count(), 1);
	}

	/**
	 * Tests the postUser method's 'search' action
	 */
	public function testPostUserSearch()
	{
		$this->initTestStep();

		$this->call('POST', 'admin/user', array(
			'search' => 'unittest',
		));

		$this->assertRedirectedTo('admin/user/edit/unittest');
	}

	/**
	 * Tests the getBan method of the controller
	 */
	public function testGetBan()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/ban');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getBan method's 'remove' action
	 */
	public function testGetBanRemove()
	{
		$this->initTestStep();

		IPBan::truncate()->insert(array(
			'ip' => '0.0.0.0',
		));

		$this->call('GET', 'admin/ban/remove/0.0.0.0');

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/ban');

		$this->assertEquals(IPBan::where('ip', '0.0.0.0')->count(), 0);
	}

	/**
	 * Tests the postBan method of the controller
	 */
	public function testPostBan()
	{
		$this->initTestStep();

		IPBan::truncate();

		$this->call('POST', 'admin/ban', array(
			'ip' => '0.0.0.0',
		));

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/ban');

		$this->assertEquals(IPBan::where('ip', '0.0.0.0')->count(), 1);
	}

	/**
	 * Tests the getMail method of the controller
	 */
	public function testGetMail()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/mail');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postMail method's 'save' action
	 */
	public function testPostMailSave()
	{
		$this->initTestStep();

		$key = 'host'.time();

		$this->call('POST', 'admin/mail', array(
			'driver'  => 'smtp',
			'host'    => $key,
			'port'    => '25',
			'address' => 'unit@test.com',
			'_save'   => 1,
		));

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/mail');

		$this->assertEquals(Site::config('mail')->host, $key);
	}

	/**
	 * Tests the postMail method's 'test' action
	 */
	public function testPostMailTest()
	{
		$this->initTestStep();

		$this->call('POST', 'admin/mail', array(
			'driver'  => 'smtp',
			'host'    => '/dev/null',
			'port'    => '25',
			'address' => 'unit@test.com',
			'_test'   => 1,
		));

		$this->assertSessionHas('messages.error');

		$this->assertRedirectedTo('admin/mail');
	}

	/**
	 * Tests the getAntispam method of the controller
	 */
	public function testGetAntispam()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/antispam');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postAntispam method of the controller
	 */
	public function testPostAntispam()
	{
		$this->initTestStep();

		$key = 'key'.time();

		$this->call('POST', 'admin/antispam', array(
			'flag_php'  => 1,
			'php_key'   => $key,
			'php_days'  => 25,
			'php_score' => 25,
			'php_type'  => 25,
		));

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/antispam');

		$this->assertEquals(Site::config('antispam')->phpKey, $key);
	}

	/**
	 * Tests the getAuth method of the controller
	 */
	public function testGetAuth()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/auth');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postAuth method of the controller
	 */
	public function testPostAuth()
	{
		$this->initTestStep();

		$allowReg = Site::config('auth')->dbAllowReg == 1 ? 0 : 1;

		$this->call('POST', 'admin/auth', array(
			'method'       => 'db',
			'db_allow_reg' => $allowReg,
		));

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/auth');

		$this->assertEquals(Site::config('auth')->dbAllowReg, $allowReg);
	}

	/**
	 * Tests the getSite method of the controller
	 */
	public function testGetSite()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/site');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postSite method of the controller
	 */
	public function testPostSite()
	{
		$this->initTestStep();

		$key = 'title'.time();

		$this->call('POST', 'admin/site', array(
			'fqdn'     => 'localhost',
			'title'    => $key,
			'per_page' => 15,
			'lang'     => 'en',
		));

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/site');

		$this->assertEquals(Site::config('general')->title, $key);
	}

	/**
	 * Tests the getSkin method's 'set' action
	 */
	public function testGetSkinSet()
	{
		$this->initTestStep();

		$skins = System::directories('views/skins');

		$skin = array_pop($skins);

		$this->call('GET', "admin/skin/set/{$skin}");

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/skin');

		$this->assertEquals(Site::config('general')->skin, $skin);
	}

	/**
	 * Tests the getSkin method's 'preview' action
	 */
	public function testGetSkinPreview()
	{
		$this->initTestStep();

		$skins = System::directories('views/skins');

		$this->call('GET', 'admin/skin/preview/'.array_pop($skins));

		$this->assertResponseOk();
	}

	/**
	 * Tests the getSkin method's 'list' action
	 */
	public function testGetSkinList()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/skin/list');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getServices method of the controller
	 */
	public function testGetServices()
	{
		$this->initTestStep();

		$this->call('GET', 'admin/services');

		$this->assertResponseOk();
	}

	/**
	 * Tests the postServices method of the controller
	 */
	public function testPostServices()
	{
		$this->initTestStep();

		$key = 'google'.time();

		$this->call('POST', 'admin/services', array(
			'google_api_key'      => $key,
			'google_analytics_id' => '',
		));

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('admin/services');

		$this->assertEquals(Site::config('services')->googleApiKey, $key);
	}

}
