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
 * ShowController
 *
 * This controller handles displaying of a paste
 *
 * @package     StickyNotes
 * @subpackage  Controllers
 * @author      Sayak Banerjee
 */
class ShowController extends BaseController {

	/**
	 * Displays the default view page
	 *
	 * @access public
	 * @param  string  $urlkey
	 * @param  string  $hash
	 * @param  string  $action
	 * @param  string  $extra
	 * @return \Illuminate\Support\Facades\View|\Illuminate\Support\Facades\Redirect|null
	 */
	public function getPaste($urlkey, $hash = '', $action = '', $extra = '')
	{
		$site = Site::config('general');

		$paste = Paste::where('urlkey', $urlkey)->first();

		// Paste was not found
		if (is_null($paste))
		{
			App::abort(404); // Not found
		}

		// Check if the logged in user is the owner of the paste
		$owner = Auth::access($paste->author_id);

		// We do not make password prompt mandatory for owners
		if ( ! $owner)
		{
			// Require hash to be passed for private pastes
			if ($paste->private AND $paste->hash != $hash)
			{
				App::abort(401); // Unauthorized
			}

			// Check if paste is password protected and user hasn't entered
			// the password yet
			if ($paste->password AND ! Session::has('paste.password'.$paste->id))
			{
				return View::make('site/password', array());
			}
		}

		// Increment the hit counter
		if ( ! Session::has('paste.viewed'.$paste->id))
		{
			$paste->hits++;

			$paste->save();

			Session::put('paste.viewed'.$paste->id, TRUE);
		}

		// Let's do some action!
		switch ($action)
		{
			case 'delete':

				if (empty($extra))
				{
					// Delete the paste if the user has access
					if ($site->allowPasteDel AND $owner)
					{
						Revision::where('urlkey', $paste->urlkey)->delete();

						$paste->comments()->delete();

						$attachment = storage_path()."/uploads/{$paste->urlkey}";

						if ($paste->attachment AND File::exists($attachment))
						{
							File::delete($attachment);
						}

						$paste->delete();

						Session::flash('messages.success', Lang::get('global.paste_deleted'));

						return Redirect::to('/');
					}
					else
					{
						App::abort(401); // Unauthorized
					}
				}
				else if (is_numeric($extra))
				{
					$comment = Comment::findOrFail($extra);

					// Delete the comment if the user has access
					if ($owner OR Auth::user()->username == $comment->author)
					{
						$comment->delete();
					}
					else
					{
						App::abort(401); // Unauthorized
					}
				}

				return Redirect::to(URL::previous());

			case 'raw':

				$response = Response::make($paste->data);

				$response->header('Content-Type', 'text/plain');

				return $response;

			case 'toggle':

				if ($owner)
				{
					Revision::where('urlkey', $paste->urlkey)->delete();

					$paste->private = $paste->private ? 0 : 1;

					$paste->password = '';

					$paste->save();
				}

				return Redirect::to(URL::previous());

			case 'flag':

				if ($site->flagPaste == 'all' OR ($site->flagPaste == 'user' AND Auth::roles()->user))
				{
					$paste->flagged = 1;

					$paste->save();

					Cache::forget('global.flags');

					Session::flash('messages.success', Lang::get('global.paste_flagged'));
				}
				else
				{
					App::abort(401); // Unauthorized
				}

				return Redirect::to(URL::previous());

			case 'unflag':

				if (Auth::roles()->admin)
				{
					$paste->flagged = 0;

					$paste->save();

					Cache::forget('global.flags');

					Session::flash('messages.success', Lang::get('global.paste_unflagged'));
				}
				else
				{
					App::abort(401); // Unauthorized
				}

				return Redirect::to(URL::previous());
		}

		// Build the sharing subject for the paste
		$subject = sprintf(Lang::get('mail.share_subject'), $site->title, URL::current());

		// Build data for show paste page
		$data = array(
			'paste'      => $paste,
			'revisions'  => $paste->revisions,
			'comments'   => $paste->comments()->paginate($site->perPage),
			'share'      => 'mailto:?subject='.urlencode($subject),
			'attachment' => sprintf(Lang::get('show.download_attachment'), Lang::get('show.unknown')),
		);

		// If paste has an attachment, get the file type
		if ($paste->attachment)
		{
			$pathToFile = storage_path()."/uploads/{$paste->urlkey}";

			if (File::exists($pathToFile))
			{
				$file = new Symfony\Component\HttpFoundation\File\File($pathToFile);

				$data['attachment'] = sprintf(Lang::get('show.download_attachment'), $file->getMimeType());
			}
		}

		// Display the show paste view
		return View::make('site/show', $data);
	}

	/**
	 * Handles the paste password submission
	 *
	 * @param  string  $urlkey
	 * @param  string  $hash
	 * @return \Illuminate\Support\Facades\Redirect|null
	 */
	public function postPassword($urlkey, $hash = '')
	{
		$paste = Paste::where('urlkey', $urlkey)->first();

		if ( ! is_null($paste) AND Input::has('password'))
		{
			$entered = Input::get('password');

			if (PHPass::make()->check('Paste', $entered, $paste->salt, $paste->password))
			{
				Session::put("paste.password{$paste->id}", TRUE);

				return Redirect::to("{$urlkey}/{$hash}");
			}
		}

		// Something wrong here
		App::abort(401);
	}

	/**
	 * Shows a diff between two pastes
	 *
	 * @param  string  $oldkey
	 * @param  string  $newkey
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getDiff($oldkey, $newkey)
	{
		// Generate the paste differences
		$diff = PHPDiff::make()->compare($oldkey, $newkey);

		// Build the view data
		$data = array(
			'diff'      => $diff,
			'oldkey'    => $oldkey,
			'newkey'    => $newkey,
		);

		return View::make('site/diff', $data);
	}

	/**
	 * Triggers download action for a paste's attachment
	 *
	 * @param  string  $urlkey
	 * @param  string  $hash
	 * @return \Illuminate\Support\Facades\View
	 */
	public function getAttachment($urlkey, $hash = '')
	{
		$paste = Paste::where('urlkey', $urlkey)->first();

		// Paste and/or attachment was not found
		if (is_null($paste))
		{
			App::abort(404); // Not found
		}

		// Check if the logged in user is the owner of the paste
		$owner = Auth::access($paste->author_id);

		// We do not make password prompt mandatory for owners
		if ( ! $owner)
		{
			// Require hash to be passed for private pastes
			if ($paste->private AND $paste->hash != $hash)
			{
				App::abort(401); // Unauthorized
			}

			// Check if paste is password protected and user hasn't entered
			// the password yet
			if ($paste->password AND ! Session::has('paste.password'.$paste->id))
			{
				return View::make('site/password', array());
			}
		}

		// Find the attachment, and process the download
		if ($paste->attachment)
		{
			$pathToFile = storage_path()."/uploads/{$paste->urlkey}";

			if (File::exists($pathToFile))
			{
				return Response::download($pathToFile);
			}
		}

		// If we are here, the attachment wasn't found
		App::abort(404);
	}

	/**
	 * Handles the paste password submission
	 *
	 * @param  string  $urlkey
	 * @param  string  $hash
	 * @return \Illuminate\Support\Facades\Redirect|null
	 */
	public function postComment()
	{
		if (Site::config('general')->comments)
		{
			// Define validation rules
			$validator = Validator::make(Input::all(), array(
				'comment' => 'required|auth|min:5|max:1024',
			));

			// Generate anti-spam modules
			$antispam = Antispam::make('comment', 'comment');

			// Run validations
			$resultValidation = $validator->passes();

			// Execute antispam services
			$resultAntispam = $antispam->passes();

			if ($resultValidation AND $resultAntispam)
			{
				// Get the associated paste
				$paste = Paste::findOrFail(Input::get('id'));

				// Insert the new comment
				if ( ! is_null($paste))
				{
					$comment = new Comment;

					$comment->paste_id = $paste->id;
					$comment->data = nl2br(strip_tags(Input::get('comment')));
					$comment->author = Auth::check() ? Auth::user()->username : Lang::get('global.anonymous');
					$comment->timestamp = time();

					$comment->save();
				}

				return Redirect::to(URL::previous());
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

				return Redirect::to(URL::previous())->withInput();
			}
		}
		else
		{
			App::abort(401); // Unauthorized
		}
	}

}
