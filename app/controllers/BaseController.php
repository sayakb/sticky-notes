<?php

/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package     StickyNotes
 * @author      Sayak Banerjee
 * @copyright   (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @link        http://sayakbanerjee.com/sticky-notes
 * @since       Version 1.0
 * @filesource
 */

/**
 * BaseController
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class BaseController extends Controller {

	/**
	 * Current project
	 *
	 * @var string
	 */
	public $project = NULL;

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->setupProject();
		$this->processInput();
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @access protected
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
	 * Setup the project for sticky notes
	 *
	 * @access protected
	 * @return void
	 */
	protected function setupProject()
	{
		$site = Site::config('general')->fqdn;
		$host = $_SERVER["SERVER_NAME"];

		if ($site != $host)
		{
			$this->project = trim(str_replace($site, '', $host), '.');
		}
	}

	/**
	 * Process and clean the POSTed data
	 *
	 * @access protected
	 * @return void
	 */
	protected function processInput()
	{
		$input = Input::all();

		// Trim leading and trailing whitspace
		$input = array_map('trim', $input);

		// Merge it back to the Input data
		Input::merge($input);
	}

}
