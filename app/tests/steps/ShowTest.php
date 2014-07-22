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
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', "{$paste->urlkey}/{$paste->hash}");

		$this->assertResponseOk();
	}

	/**
	 * Tests the getPaste's 'delete-paste' action
	 */
	public function testGetPasteDeletePaste()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'      => 'UnitTest::Title',
			'data'       => 'UnitTest::Data',
			'language'   => 'text',
			'attachment' => array(true),
		));

		File::put(storage_path()."/uploads/{$paste->urlkey}", 'attachment');

		$this->call('GET', "{$paste->urlkey}/{$paste->hash}/delete");

		$this->assertSessionHas('messages.success');

		$this->assertRedirectedTo('/');

		$this->assertEquals(Paste::where('id', $paste->id)->count(), 0);

		$this->assertFalse(File::exists(storage_path()."/uploads/{$paste->urlkey}"));
	}

	/**
	 * Tests the getPaste's 'delete-comment' action
	 */
	public function testGetPasteDeleteComment()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		Comment::insert(array(
			'paste_id'  => $paste->id,
			'data'      => 'UnitTest::Comment',
			'timestamp' => time(),
		));

		$comment = Comment::where('paste_id', $paste->id)->first();

		$this->call('GET', "{$paste->urlkey}/{$paste->hash}/delete/{$comment->id}");

		$this->assertRedirectedTo('/');

		$this->assertEquals(Comment::where('id', $comment->id)->count(), 0);
	}

	/**
	 * Tests the getPaste's 'raw' action
	 */
	public function testGetPasteRaw()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', "{$paste->urlkey}/{$paste->hash}/raw");

		$this->assertResponseOk();
	}

	/**
	 * Tests the getPaste's 'toggle' action
	 */
	public function testGetPasteToggle()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
			'private'   => 1,
		));

		$this->call('GET', "{$paste->urlkey}/{$paste->hash}/toggle");

		$this->assertRedirectedTo('/');

		$this->assertEquals(Paste::find($paste->id)->private, 0);
	}

	/**
	 * Tests the getPaste's 'flag' action
	 */
	public function testGetPasteFlag()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('GET', "{$paste->urlkey}/{$paste->hash}/flag");

		$this->assertRedirectedTo('/');

		$this->assertSessionHas('messages.success');

		$this->assertEquals(Paste::find($paste->id)->flagged, 1);
	}

	/**
	 * Tests the getPaste's 'unflag' action
	 */
	public function testGetPasteUnflag()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
			'private'   => 1,
		));

		$paste->update(array('flagged', 1));

		$this->call('GET', "{$paste->urlkey}/{$paste->hash}/unflag");

		$this->assertRedirectedTo('/');

		$this->assertSessionHas('messages.success');

		$this->assertEquals(Paste::find($paste->id)->flagged, 0);
	}

	/**
	 * Tests the postPassword method of the controller
	 */
	public function testPostPassword()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'password'  => 'UnitTest::Password',
			'language'  => 'text',
		));

		$this->call('POST', "{$paste->urlkey}/{$paste->hash}", array(
			'password' => 'UnitTest::Password',
		));

		$this->assertRedirectedTo("{$paste->urlkey}/{$paste->hash}");

		$this->assertSessionHas("paste.password{$paste->id}", TRUE);
	}

	/**
	 * Tests the getDiff method of the controller
	 */
	public function testGetDiff()
	{
		$this->initTestStep();

		$oldPaste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$newPaste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Revision',
			'language'  => 'text',
		));

		Revision::insert(array(
			'paste_id'     => $newPaste->id,
			'urlkey'       => $oldPaste->urlkey,
			'author'       => $oldPaste->author,
			'timestamp'    => $oldPaste->timestamp,
		));

		$this->call('GET', "diff/{$oldPaste->urlkey}/{$newPaste->urlkey}");

		$this->assertResponseOk();
	}

	/**
	 * Tests the getAttachment method of the controller
	 */
	public function testGetAttachment()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'      => 'UnitTest::Title',
			'data'       => 'UnitTest::Data',
			'language'   => 'text',
			'attachment' => array(1),
		));

		File::put(storage_path()."/uploads/{$paste->urlkey}", 'UnitTest::Attachment');

		$this->call('GET', "attachment/{$paste->urlkey}/{$paste->hash}");

		$this->assertResponseOk();
	}

	/**
	 * Tests the postComment method of the controller
	 */
	public function testPostComment()
	{
		$this->initTestStep();

		$paste = Paste::createNew('web', array(
			'title'     => 'UnitTest::Title',
			'data'      => 'UnitTest::Data',
			'language'  => 'text',
		));

		$this->call('POST', 'comment', array(
			'id'      => $paste->id,
			'comment' => 'UnitTest::Comment',
		));

		$this->assertFalse($this->app['session.store']->has('messages.error'));

		$this->assertEquals(Comment::where('paste_id', $paste->id)->count(), 1);
	}

}
