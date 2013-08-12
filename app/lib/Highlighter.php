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

		// Initialize GeSHi with defaults
		self::$geshi = new GeSHi();
		self::$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
		self::$geshi->set_overall_style('word-wrap:break-word');
	}

	// --------------------------------------------------------------------

	/**
	 * Fetches a list of languages supported by GeSHi
	 *
	 * @access	public
	 * @param	bool	return comma separated string
	 * @return	mixed	array or string depending on the CSV flag
	 */
	public static function languages($csv = FALSE)
	{
		// get_supported_languages takes a param that tells whether or not
		// to return full names. We don't need full names if we just want CSV
		$langs = self::$geshi->get_supported_languages( ! $csv);

		if ($csv)
		{
			$langs = implode(',', $langs);
		}
		else
		{
			asort($langs);
		}

		return $langs;
	}

	/**
	 * Parses and outputs highlighted code
	 *
	 * @static
	 * @param	string	code to parse
	 * @param	string	language
	 * @return	string	highlighted code markup
	 */
	public static function parse($code, $language)
	{
		self::$geshi->set_source($code);
		self::$geshi->set_language($language);

		return self::$geshi->parse_code($code);
	}

}
