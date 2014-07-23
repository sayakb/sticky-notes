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
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getCreate()
	{
		// Build the view data
		$data = array(
			'languages'  => Highlighter::make()->languages(),
			'language'   => 'text',
			'paste'      => new Paste,
			'action'     => 'CreateController@postCreate',
			'disabled'   => NULL,
			'attach'     => TRUE,
		);

		// Get the default language from cookie
		$history = Cookie::get('languages');

		if (is_array($history))
		{
			$data['language'] = end($history);
		}

		return View::make('site/create', $data);
	}

	/**
	 * Creates a new paste item
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postCreate()
	{
		// Get the site configuration
		$site = Site::config('general');

		// Define validation rules
		$validator = Validator::make(Input::all(), array(
			'title'      => 'max:30',
			'data'       => 'required|auth|mbmax:'.$site->maxPasteSize,
			'language'   => 'required|in:'.Highlighter::make()->languages(TRUE),
			'expire'     => 'in:'.Paste::getExpiration('create', TRUE),
		));

		// Generate anti-spam modules
		$antispam = Antispam::make('paste', 'data');

		// Run validations
		$resultValidation = $validator->passes();

		// Execute antispam services
		$resultAntispam = $antispam->passes();

		// Get the paste language. We use it to store a language history
		$language = Input::get('language');

		$historyLangs = Cookie::get('languages');

		// History languages must always be an array
		$historyLangs = is_array($historyLangs) ? $historyLangs : array();

		// No dulicates allowed in the history
		if (in_array($language, $historyLangs))
		{
			$key = array_search($language, $historyLangs);

			unset($historyLangs[$key]);
		}

		// Max. 10 history languages are allowed
		else if (count($historyLangs) >= 10)
		{
			$historyLangs = array_slice($historyLangs, 1, count($historyLangs));
		}

		// Add current language to the history
		array_push($historyLangs, $language);

		$cookie = Cookie::forever('languages', $historyLangs);

		// Evaluate validation results
		if ($resultValidation AND $resultAntispam)
		{
			// We inject the project into the input so that
			// it is also inserted into the DB accordingly
			Input::merge(array('project' => $this->project));

			// All OK! Create the paste already!!
			$paste = Paste::createNew('web', Input::all());

			// Now, save the attachment, if any (and if enabled)
			if ($site->allowAttachment AND Input::hasFile('attachment'))
			{
				$file = Input::file('attachment');

				if ($file->isValid())
				{
					$file->move(storage_path().'/uploads', $paste->urlkey);
				}
			}

			// Redirect to paste if there's no password
			// Otherwise, just show a link
			if ($paste->password)
			{
				$url = link_to("{$paste->urlkey}/{$paste->hash}");

				$message = sprintf(Lang::get('create.click_for_paste'), $url);

				Session::flash('messages.success', $message);
			}
			else
			{
				return Redirect::to(Paste::getUrl($paste))->withCookie($cookie);
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

		return Redirect::to(URL::previous())->withInput()->withCookie($cookie);
	}

	/**
	 * Editor window for creating a revision
	 *
	 * @param  string  $urlkey
	 * @return \Illuminate\Support\Facades\View|\Illuminate\Support\Facades\Redirect
	 */
	public function getRevision($urlkey)
	{
		$paste = Paste::where('urlkey', $urlkey)->first();

		// Paste was not found
		if (is_null($paste))
		{
			App::abort(404); // Not found
		}
		else
		{
			// We only allow the user to revise public pastes
			// Private pastes need to be toggled before being revised
			if ($paste->private OR $paste->password)
			{
				Session::flash('messages.error', Lang::get('create.revise_private'));

				return Redirect::to(URL::previous())->withInput();
			}

			// Now that we are good, we save the paste ID in session so that
			// when the edited paste is POSTed, we can validate against this
			Session::put('paste.revision', $paste->id);
		}

		// Output the view
		$data = array(
			'languages'  => Highlighter::make()->languages(),
			'language'   => 'text',
			'paste'      => $paste,
			'action'     => 'CreateController@postRevision',
			'disabled'   => 'disabled',
			'attach'     => FALSE,
		);

		return View::make('site/create', $data);
	}

	/**
	 * Creates a new paste revision
	 *
	 * @return \Illuminate\Support\Facades\Redirect
	 */
	public function postRevision()
	{
		$oldId = Input::get('id');

		// First and foremost, validate the ID of the revision
		if (Session::get('paste.revision') != $oldId)
		{
			App::abort(401); // Unauthorized
		}

		// Define validation rules. We don't validate the title and language
		// here as we don't allow to change that for a revision. Instead, we
		// will use the data from the old paste
		$validator = Validator::make(Input::all(), array(
			'data'    => 'required|auth',
			'expire'  => 'in:'.Paste::getExpiration('create', TRUE),
		));

		// Generate anti-spam modules
		$antispam = Antispam::make('paste', 'data');

		// Run validations
		$resultValidation = $validator->passes();

		// Execute antispam services
		$resultAntispam = $antispam->passes();

		if ($resultValidation AND $resultAntispam)
		{
			// Get the paste being revised
			$oldPaste = Paste::findOrFail($oldId);

			// If the old paste's content is same as the revision,
			// we simply redirect to the old paste itself
			if (crc32($oldPaste->data) == crc32(Input::get('data')))
			{
				return Redirect::to($oldPaste->urlkey);
			}

			// We use some data from the old paste
			$data = array(
				'project'      => $oldPaste->project,
				'title'        => $oldPaste->title,
				'language'     => $oldPaste->language,
				'private'      => NULL,
				'password'     => NULL,
				'attachment'   => NULL,
			);

			// Merge it with the input to override the values the user submitted
			Input::merge($data);

			// All set, create the new revision
			$newPaste = Paste::createNew('web', Input::all());

			// We now need to update the revisions table. One entry will be
			// created for this revision. We will also create entries for
			// any past revisions and link it to this new paste
			$revData = array(
				array(
					'paste_id'     => $newPaste->id,
					'urlkey'       => $oldPaste->urlkey,
					'author'       => $oldPaste->author,
					'timestamp'    => $oldPaste->timestamp,
				)
			);

			foreach ($oldPaste->revisions as $revision)
			{
				$revData[] = array(
					'paste_id'     => $newPaste->id,
					'urlkey'       => $revision->urlkey,
					'author'       => $revision->author,
					'timestamp'    => $revision->timestamp,
				);
			}

			// Now insert this batch data to the revisions table
			Revision::insert($revData);

			// Whoa, finally we are done, take the user to the shiny new
			// paste. Since this is a public paste, we don't need the url
			// hash or password shebang
			return Redirect::to($newPaste->urlkey);
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

		return Redirect::to(URL::previous())->withInput();
	}

}
