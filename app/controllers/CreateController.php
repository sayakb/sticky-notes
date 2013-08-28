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
 * CreateController
 *
 * This is the default homepage of the site and allows the user to create a new
 * paste.
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class CreateController extends BaseController {

	/**
	 * Displays the new paste form
	 *
	 * @access public
	 * @return \Illuminate\View\View
	 */
	public function getCreate()
	{
		$data = array(
			'languages'  => Highlighter::make()->languages()
		);

		return View::make('site/create', $data, Site::defaults());
	}

	/**
	 * Creates a new paste item
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postCreate()
	{
		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'title'     => 'max:30',
			'data'      => 'required',
			'language'  => 'required|in:'.Highlighter::make()->languages(TRUE),
			'expire'    => 'required|in:'.implode(',', array_keys(Config::get('expire')))
		));

		// Generate anti-spam modules
		$antispam = Antispam::make();

		// Run validations
		$resultValidation = $validator->passes();

		// Execute antispam services
		$resultAntispam = $antispam->passes();

		if ($resultValidation AND $resultAntispam)
		{
			// We inject the project into the input so that
			// it is also inserted into the DB accordingly
			Input::merge(array('project' => $this->project));

			// All OK! Create the paste already!!
			$paste = Paste::createNew(Input::all());

			// Redirect to paste if there's no password
			// Otherwise, just show a link
			if ($paste['is_protected'])
			{
				$url = link_to('p'.$paste['urlkey'].'/'.$paste['hash']);

				$message = sprintf(Lang::get('create.click_for_paste'), $url);

				Session::flash('messages.success', $message);
			}
			else if ($paste['is_private'])
			{
				return Redirect::to('p'.$paste['urlkey'].'/'.$paste['hash']);
			}
			else
			{
				return Redirect::to('p'.$paste['urlkey']);
			}
		}
		else
		{
			// Set the error message as flashdata
			if ( ! $resultValidation)
			{
				Session::flash('messages.error', $validator->messages()->all('<p>:message</p>'));
			}
			else if ( ! $resultAntispam)
			{
				Session::flash('messages.error', $antispam->message());
			}
		}

		return Redirect::to('/')->withInput();
	}

}
