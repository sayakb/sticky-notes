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
 * AdminController
 *
 * This controller handles site administration
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class AdminController extends BaseController {

	/**
	 * Redirects to the administration dashboard
	 *
	 * @access public
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function getIndex()
	{
		return Redirect::to('admin/dashboard');
	}

	/**
	 * Displays the administration dashboard
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getDashboard()
	{
		return View::make('admin/layout', array(), Site::defaults());
	}

	/**
	 * Search, edit and delete pastes
	 *
	 * @param  string  $action
	 * @param  string  $key
	 * @return \Illuminate\View\View|\Illuminate\Support\Facades\Redirect
	 */
	public function getPaste($key = "", $action = "")
	{
		$paste = NULL;

		if ( ! empty($key))
		{
			$paste = Paste::getByKey($key);

			// Paste was not found
			if ($paste == NULL)
			{
				Session::flash('messages.error', Lang::get('admin.paste_404'));
			}

			// Perform requested action
			switch ($action)
			{
				case 'rempass':
					$paste->password = NULL;
					$paste->save();

					return Redirect::to(URL::previous());

				case 'toggle':
					$paste->private = $paste->private ? 0 : 1;
					$paste->password = NULL;
					$paste->save();

					return Redirect::to(URL::previous());

				case 'delete':
					$paste->delete();

					Session::flash('messages.success', Lang::get('admin.paste_deleted'));

					return Redirect::to('admin/paste');
			}
		}

		return View::make('admin/paste', array('paste' => $paste), Site::defaults());
	}

	/**
	 * Handles POST requests to the paste module
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postPaste()
	{
		if (Input::has('key'))
		{
			$key = Input::get('key');

			return Redirect::to('admin/paste/'.urlencode($key));
		}
		else
		{
			return Redirect::to('admin/paste');
		}
	}

	/**
	 * Displays site configuration screen
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getSite()
	{
		return View::make('admin/site', array(), Site::defaults());
	}

}
