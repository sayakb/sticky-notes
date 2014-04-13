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
	public $project;

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		// We detect the subdomain being used and compare it with the
		// FQDN stored in the database. With that data, we extract the
		// project name and set it here
		$this->project = System::project();

		// This is a part of basic input sanitation. Currently this method
		// trims all incoming input data and merges it with the input
		// array which is then used for processed by the controllers
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
	 * Process and clean the POSTed data
	 *
	 * @access protected
	 * @return void
	 */
	protected function processInput()
	{
		$input = Input::all();

		// Trim leading and trailing whitespace
		// If the control's name is "data", we only trim trailing space
		foreach ($input as $key => $value)
		{
			$input[$key] = $key == 'data' ? rtrim($value) : trim($value);
		}

		// Merge it back to the Input data
		Input::merge($input);
	}

}
