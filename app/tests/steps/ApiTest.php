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
 * ApiTest
 *
 * Unit test cases for ApiController
 *
 * @package     StickyNotes
 * @subpackage  UnitTests
 * @author      Sayak Banerjee
 */
class ApiTest extends StickyNotesTestCase {

	/**
	 * Tests the getParameter method's 'language' param
	 * for the JSON API
	 */
	public function testGetParameterJsonLanguage()
	{
		$this->initTestStep();

		$response = $this->call('GET', 'api/json/parameter/language');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'application/json'));
	}

	/**
	 * Tests the getParameter method's 'language' param
	 * for the XML API
	 */
	public function testGetParameterXmlLanguage()
	{
		$this->initTestStep();

		$response = $this->call('GET', 'api/xml/parameter/language');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/xml'));
	}

	/**
	 * Tests the getParameter method's 'expire' param
	 * for the JSON API
	 */
	public function testGetParameterJsonExpire()
	{
		$this->initTestStep();

		$response = $this->call('GET', 'api/json/parameter/expire');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'application/json'));
	}

	/**
	 * Tests the getParameter method's 'expire' param
	 * for the XML API
	 */
	public function testGetParameterXmlExpire()
	{
		$this->initTestStep();

		$response = $this->call('GET', 'api/xml/parameter/expire');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/xml'));
	}

	/**
	 * Tests the getParameter method's 'version' param
	 * for the JSON API
	 */
	public function testGetParameterJsonVersion()
	{
		$this->initTestStep();

		$response = $this->call('GET', 'api/json/parameter/version');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'application/json'));
	}

	/**
	 * Tests the getParameter method's 'version' param
	 * for the XML API
	 */
	public function testGetParameterXmlVersion()
	{
		$this->initTestStep();

		$response = $this->call('GET', 'api/xml/parameter/version');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/xml'));
	}

	/**
	 * Tests the getParameter method's 'theme' param
	 * for the JSON API
	 */
	public function testGetParameterJsonTheme()
	{
		$this->initTestStep();

		$response = $this->call('GET', 'api/json/parameter/theme');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'application/json'));
	}

	/**
	 * Tests the getParameter method's 'theme' param
	 * for the XML API
	 */
	public function testGetParameterXmlTheme()
	{
		$this->initTestStep();

		$response = $this->call('GET', 'api/xml/parameter/theme');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/xml'));
	}

	/**
	 * Tests the getShow method of the controller
	 * for the JSON API with a public paste
	 */
	public function testGetShowJsonPublic()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$response = $this->call('GET', "api/json/show/{$paste->urlkey}");

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'application/json'));
	}

	/**
	 * Tests the getShow method of the controller
	 * for the XML API with a public paste
	 */
	public function testGetShowXmlPublic()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$response = $this->call('GET', "api/xml/show/{$paste->urlkey}");

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/xml'));
	}

	/**
	 * Tests the getShow method of the controller
	 * for the JSON API with a protected paste
	 */
	public function testGetShowJsonProtected()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'password'  => 'UnitTest::Password',
			'language'  => 'text',
		));

		$response = $this->call('GET', "api/json/show/{$paste->urlkey}/{$paste->hash}/UnitTest::Password");

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'application/json'));
	}

	/**
	 * Tests the getShow method of the controller
	 * for the XML API with a protected paste
	 */
	public function testGetShowXmlProtected()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'password'  => 'UnitTest::Password',
			'language'  => 'text',
		));

		$response = $this->call('GET', "api/xml/show/{$paste->urlkey}/{$paste->hash}/UnitTest::Password");

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/xml'));
	}

	/**
	 * Tests the getList method of the controller
	 * for the JSON API
	 */
	public function testGetListJson()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$response = $this->call('GET', 'api/json/list');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'application/json'));
	}

	/**
	 * Tests the getList method of the controller
	 * for the XML API
	 */
	public function testGetListXml()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$response = $this->call('GET', 'api/xml/list');

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/xml'));
	}

	/**
	 * Tests the postCreate method of the controller
	 * for the JSON API
	 */
	public function testPostCreateJson()
	{
		$this->initTestStep();

		$key = 'UnitTest::JSON'.time();

		$response = $this->call('POST', 'api/json/create', array(
			'data'     => $key,
			'language' => 'text',
		));

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'application/json'));

		$this->assertEquals(Paste::where('data', $key)->count(), 1);
	}

	/**
	 * Tests the postCreate method of the controller
	 * for the XML API
	 */
	public function testPostCreateXml()
	{
		$this->initTestStep();

		$key = 'UnitTest::XML'.time();

		$response = $this->call('POST', 'api/xml/create', array(
			'data'     => $key,
			'language' => 'text',
		));

		$this->assertResponseOk();

		$this->assertTrue(str_contains($response->headers->get('Content-Type'), 'text/xml'));

		$this->assertEquals(Paste::where('data', $key)->count(), 1);
	}

}
