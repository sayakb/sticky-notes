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
 * SetupController
 *
 * This controller handles app install and updates
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class SetupController extends BaseController {

	/**
	 * Shows the installation screen
	 *
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getInstall($method = 'web', $action = '')
	{
		//Session::flush();
		//Cache::flush();

		// Installation stage
		$stage = Session::has('install.stage') ? Session::get('install.stage') : 1;

		// Output based on request method
		switch ($method)
		{
			case 'web':
				// Build view data
				$data = array(
					'error'       => Session::get('messages.error'),
					'success'     => Session::get('messages.success'),
				);

				return View::make("setup/install/stage{$stage}", $data);

			case 'ajax':
				return Setup::install($action);
		}
	}

	/**
	 * Handles POST requests for the install page
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postInstall()
	{
		// Stage 1 submitted
		if (Input::has('test'))
		{
			$status = Setup::testConnection();

			if ($status === TRUE)
			{
				Session::put('install.stage', 2);

				return Redirect::to('setup/install');
			}
			else
			{
				$error = sprintf(Lang::get('setup.test_fail'), $status);

				Session::flash('messages.error', $error);

				return Redirect::to('setup/install')->withInput();
			}
		}

		// Stage 2 submitted
		if (Input::has('install'))
		{
			Session::put('install.stage', 3);

			return Redirect::to('setup/install');
		}
	}

	/**
	 * Shows the update screen
	 *
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getUpdate()
	{
		return "in update";
	}

}
