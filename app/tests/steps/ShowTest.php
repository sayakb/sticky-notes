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
 * ShowTest
 *
 * Unit test cases for ShowController
 *
 * @package     StickyNotes
 * @subpackage  UnitTests
 * @author      Sayak Banerjee
 */
class ShowTest extends StickyNotesTestCase {

	/**
	 * Tests the getPaste method of the controller
	 */
	public function testGetPaste()
	{
		$paste = Paste::where('private', 0)->first();

		// We need this to inject role data to the paste view
		$this->enableFilters();

		$this->action('GET', 'ShowController@getPaste', array(
			'urlkey' => $paste->urlkey,
			'hash'   => $paste->hash,
		));

		$this->assertResponseOk();
	}

	/**
	 * Tests the postPassword method of the controller
	 */
	public function testPostPassword()
	{
		$paste = Paste::where('password', '<>', '')->orderBy('id', 'desc')->first();

		$this->action('POST', 'ShowController@postPassword', array(
			'urlkey'   => $paste->urlkey,
			'hash'     => $paste->hash,
			'password' => 'UnitTest::Password',
		));

		$this->assertRedirectedTo("{$paste->urlkey}/{$paste->hash}");
	}

	/**
	 * Tests the getDiff method of the controller
	 */
	public function testGetDiff()
	{
		$left = Revision::firstOrFail();

		$right = Paste::find($left->paste_id);

		$this->action('GET', 'ShowController@getDiff', array(
			'oldKey' => $left->urlkey,
			'newKey' => $right->urlkey,
		));

		$this->assertResponseOk();
	}

	/**
	 * Tests the getAttachment method of the controller
	 */
	public function testGetAttachment()
	{
		$paste = Paste::where('private', 0)->first();

		// We force the attachment flag
		$paste->attachment = 1;

		$paste->save();

		// We create a dummy attachment
		File::put(storage_path()."/uploads/{$paste->urlkey}", 'UnitTest::Attachment');

		$this->action('GET', 'ShowController@getAttachment', array(
			'urlkey' => $paste->urlkey,
			'hash'   => $paste->hash,
		));

		$this->assertResponseOk();
	}

}
