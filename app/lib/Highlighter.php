<?php

/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package		StickyNotes
 * @author		Sayak Banerjee
 * @copyright	(c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @link		http://sayakbanerjee.com/sticky-notes
 * @since		Version 1.0
 * @filesource
 */

//-----------------------------------------------------------------------------

/**
 * Highlighter class
 *
 * Abstraction over the GeSHi syntax highlighting library
 *
 * @package		StickyNotes
 * @subpackage	Libraries
 * @author		Sayak Banerjee
 */
class Highlighter {

/**
	 * GeSHi instance
	 *
	 * @access public
	 * @var object
	 */
	public static $geshi;

	// --------------------------------------------------------------------

	/**
	 * Initialize the GeSHi class
	 *
	 * @access	public
	 * @return	void
	 */
	public static function init()
	{
		require_once base_path().'/vendor/geshi/geshi.php';

		self::$geshi = new GeSHi();
	}

	// --------------------------------------------------------------------

	/**
	 * Fetches a list of languages supported by GeSHi
	 *
	 * @access	public
	 * @return	array	with key as lang name, value as human readable name
	 */
	public static function languages()
	{
		$langs = self::$geshi->get_supported_languages(true);

		// Sort in ascending order
		asort($langs);

		return $langs;
	}

}
