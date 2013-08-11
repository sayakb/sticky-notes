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

/**
 * Paste class
 *
 * Manages and fetches pastes
 *
 * @package		StickyNotes
 * @subpackage	Models
 * @author		Sayak Banerjee
 */
class Paste extends Eloquent {

	/**
	 * @var	string	table name for the model
	 */
	protected $table = 'main';

	/**
	 * @var	bool	disable timestamps
	 */
	public $timestamps = FALSE;

	/**
	 * @var array	define fillable properties
	 */
	protected $fillable = array(
		'id',
		'author',
		'project',
		'timestamp',
		'expire',
		'title',
		'data',
		'language',
		'password',
		'salt',
		'private',
		'hash',
		'ip',
		'urlkey',
		'hits'
	);

	/**
	 * Generates a unique URL key for the paste
	 *
	 * @static
	 * @return string	generated key
	 */
	public static function getUrlKey()
	{
		while (TRUE)
		{
			$key = strtolower(str_random(8));
			$count = Paste::where('urlkey', $key)->count();

			if ($count == 0)
			{
				return $key;
			}
		}
	}

}
