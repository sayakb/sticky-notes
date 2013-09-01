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
 * Highlighter class
 *
 * Abstraction over the GeSHi syntax highlighting library
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Highlighter {

	/**
	 * Stores a class instance
	 *
	 * @var Highlighter
	 */
	private static $instance;

	/**
	 * GeSHi library instance
	 *
	 * @access public
	 * @var GeSHi
	 */
	private $geshi;

	/**
	 * Creates a new GeSHi instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		require_once base_path().'/vendor/geshi/geshi.php';

		$this->geshi = new GeSHi();

		$this->geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);

		$this->geshi->set_header_type(GESHI_HEADER_DIV);

		$this->geshi->set_tab_width(4);

		$this->geshi->set_overall_style('word-wrap:break-word');
	}

	/**
	 * Creates a new instance of Highlighter class
	 *
	 * @static
	 * @return Highlighter
	 */
	public static function make()
	{
		if ( ! isset(static::$instance))
		{
			static::$instance = new Highlighter();
		}

		return static::$instance;
	}

	/**
	 * Fetches a list of languages supported by GeSHi
	 *
	 * @access public
	 * @param  bool   $csv
	 * @return array|string
	 */
	public function languages($csv = FALSE)
	{
		// get_supported_languages takes a param that tells whether or not
		// to return full names. We don't need full names if we just want CSV
		$langs = $this->geshi->get_supported_languages( ! $csv);

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
	 * @param  string  $code
	 * @param  string  $language
	 * @return string
	 */
	public function parse($code, $language)
	{
		$this->geshi->set_source($code);

		$this->geshi->set_language($language);

		return $this->geshi->parse_code($code);
	}

}
