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

use Illuminate\Foundation\Testing\TestCase;

/**
 * StickyNotesTestCase
 *
 * Defines the root test case scenarios used in all unit test cases
 *
 * @package     StickyNotes
 * @subpackage  UnitTests
 * @author      Sayak Banerjee
 */
class StickyNotesTestCase extends TestCase {

	/**
	 * Creates the application.
	 *
	 * @return Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = TRUE;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

	/**
	 * Initializes the test step
	 *
	 * @param  bool  $authenticate
	 * @param  bool  $enableFilters
	 * @param  bool  $flushCaches
	 * @return void
	 */
	protected function initTestStep($authenticate = TRUE, $enableFilters = TRUE, $flushCaches = TRUE)
	{
		if ($authenticate)
		{
			$this->be(User::first());
		}
		else
		{
			Auth::logout();
		}

		if ($enableFilters)
		{
			Route::enableFilters();
		}

		if ($flushCaches)
		{
			Config::flush();

			Session::flush();
		}
	}

}
