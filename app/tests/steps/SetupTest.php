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
 * SetupTest
 *
 * Unit test cases for SetupController
 *
 * @package     StickyNotes
 * @subpackage  UnitTests
 * @author      Sayak Banerjee
 */
class SetupTest extends StickyNotesTestCase {

	/**
	 * Tests the getInstall method of the controller
	 */
	public function testGetInstall()
	{
		$this->initTestStep(TRUE, FALSE);

		$response = $this->call('GET', 'setup/install');

		$this->assertResponseOk();

		$this->assertViewHas('success');
	}

	/**
	 * Tests the postInstall method of the controller
	 */
	public function testPostInstall()
	{
		$this->initTestStep(TRUE, FALSE);

		$response = $this->call('POST', 'setup/install', array(
			'_test' => 1,
		));

		$this->assertRedirectedTo('setup/install');

		$this->assertSessionHas('setup.stage', 2);
	}

	/**
	 * Tests the getUpdate method of the controller
	 */
	public function testGetUpdate()
	{
		$this->initTestStep(TRUE, FALSE);

		$response = $this->call('GET', 'setup/update');

		$this->assertResponseOk();

		$this->assertViewHas('success');
	}

	/**
	 * Tests the postUpdate method of the controller
	 */
	public function testPostUpdate()
	{
		$this->initTestStep(TRUE, FALSE);

		$response = $this->call('POST', 'setup/update', array(
			'version' => 1,
			'_update' => 1,
		));

		$this->assertRedirectedTo('setup/update');

		$this->assertSessionHas('setup.stage', 2);
	}

}
