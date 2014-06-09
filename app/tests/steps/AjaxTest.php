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
 * AjaxTest
 *
 * Unit test cases for AjaxController
 *
 * @package     StickyNotes
 * @subpackage  UnitTests
 * @author      Sayak Banerjee
 */
class AjaxTest extends StickyNotesTestCase {

	/**
	 * Tests the getVersion method of the controller
	 */
	public function testGetVersion()
	{
		$this->initTestStep();

		$this->call('GET', 'ajax/version');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getSysload method of the controller
	 */
	public function testGetSysload()
	{
		$this->initTestStep();

		$this->call('GET', 'ajax/sysload');

		$this->assertResponseOk();
	}

	/**
	 * Tests the getShorten method of the controller
	 */
	public function testGetShorten()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', "ajax/shorten/{$paste->urlkey}/{$paste->hash}");

		$this->assertResponseOk();
	}

}
