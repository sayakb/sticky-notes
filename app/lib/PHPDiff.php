<?php namespace StickyNotes;

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

use App;
use Diff;
use DiffRenderer;
use Lang;
use Paste;
use Revision;

/**
 * PHPDiff class
 *
 * Provides methods to generate a diff
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class PHPDiff {

	/**
	 * Stores a class instance
	 *
	 * @var PHPDiff
	 */
	private static $instance;

	/**
	 * The diff renderer instance
	 *
	 * @var Diff_Renderer_Html_SideBySid
	 */
	private $renderer;

	/**
	 * Creates a new instance of PHPass
	 *
	 * @return void
	 */
	public function __construct()
	{
		require_once base_path().'/vendor/phpdiff/Diff.php';

		require_once base_path().'/vendor/phpdiff/Diff/Renderer/Html/SideBySide.php';

		$this->renderer = new DiffRenderer;
	}

	/**
	 * Returns a new instance of Crypt class
	 *
	 * @static
	 * @return PHPass
	 */
	public static function make()
	{
		if ( ! isset(static::$instance))
		{
			static::$instance = new PHPDiff();
		}

		return static::$instance;
	}

	/**
	 * Generates a diff between two pastes
	 *
	 * @param  string  $oldKey
	 * @param  string  $newKey
	 * @return string
	 */
	public function compare($oldKey, $newKey)
	{
		$oldPaste = Paste::where('urlkey', $oldKey)->first();

		$newPaste = Paste::where('urlkey', $newKey)->first();

		// Both pastes need to be valid
		if (is_null($oldPaste) OR is_null($newPaste))
		{
			App::abort(404); // Not found
		}

		// We check that the new paste is actually a revision of the old
		// paste
		$revision = Revision::where('paste_id', $newPaste->id)->where('urlkey', $oldPaste->urlkey);

		if ($revision->count() == 0)
		{
			App::abort(404); // Not found
		}

		// The php-diff library expects an array as an input
		// for each of the texts. Each array elementb will represent a
		// line in the text block
		$left = explode("\n", $oldPaste->data);

		$right = explode("\n", $newPaste->data);

		// We set these options so that the headers of the diff
		// table are more informative
		$options = array(
			'oldHead' => sprintf(Lang::get('show.old_rev'), link_to($oldKey, '#'.$oldKey)),
			'newHead' => sprintf(Lang::get('show.new_rev'), link_to($newKey, '#'.$newKey)),
		);

		// Create a new diff instance
		$diff = new Diff($left, $right, $options);

		// Render using the sideBySide renderer and return the html
		return $diff->Render($this->renderer);
	}

}
