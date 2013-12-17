<?php namespace StickyNotes;

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

use Cache;
use GeSHi;
use Input;

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
	public $geshi;

	/**
	 * Creates a new GeSHi instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		require_once base_path().'/vendor/geshi/geshi.php';

		$this->geshi = new GeSHi();

		// Display fancy (bold) line numbers
		$this->geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);

		// Use <div> wrapper for the code block
		$this->geshi->set_header_type(GESHI_HEADER_DIV);

		// Set the tab width for the highlighter
		$this->geshi->set_tab_width(4);

		// Set custom code styles
		$this->geshi->set_code_style('vertical-align: middle', TRUE);
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
	 * @param  bool  $csv
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
			$langs = $this->sortLanguages($langs);
		}

		return $langs;
	}

	/**
	 * Parses and outputs highlighted code
	 *
	 * @param  string  $key
	 * @param  string  $code
	 * @param  string  $language
	 * @return string
	 */
	public function parse($key, $code, $language)
	{
		$geshi = $this->geshi;

		$parsed = Cache::remember("site.code.{$key}", 45000, function() use ($geshi, $code, $language)
		{
			$geshi->set_source($code);

			$geshi->set_language($language);

			return @$geshi->parse_code($code);
		});

		return $parsed ?: $code;
	}

	/**
	 * Sorts the language list based on their name and history
	 *
	 * @param  array  $langs
	 * @return array
	 */
	private function sortLanguages($langs)
	{
		// First, we do a natural case-insensitive sort
		natcasesort($langs);

		// Now, get the language list from the cookie
		$historyLangs = Input::cookie('languages');

		if ($historyLangs != NULL)
		{
			foreach ($historyLangs as $lang)
			{
				$langText = $langs[$lang];

				unset($langs[$lang]);

				$langs = array_merge(array($lang => $langText), $langs);
			}
		}

		// Return the language list
		return $langs;
	}

}
