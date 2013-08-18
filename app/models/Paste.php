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
 * Paste class
 *
 * Manages and fetches pastes
 *
 * @package     StickyNotes
 * @subpackage  Models
 * @author      Sayak Banerjee
 */
class Paste extends Eloquent {

	/**
	 * Table name for the model
	 *
	 * @var string
	 */
	protected $table = 'main';

	/**
	 * Disable timestamps for the model
	 *
	 * @var bool
	 */
	public $timestamps = FALSE;

	/**
	 * Define fillable properties
	 *
	 * @var array
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
	 * Fetches a post by its urlkey or id
	 *
	 * @param  string  $key
	 * @return \Illuminate\Database\Eloquent\Mode|null
	 */
	public static function getByKey($key)
	{
		if (starts_with($key, 'p'))
		{
			$key = substr($key, 1);

			return static::where('urlkey', $key)->first();
		}
		else if (is_numeric($key))
		{
			return static::find($key);
		}
	}

	/**
	 * Generates a unique URL key for the paste
	 *
	 * @static
	 * @return string
	 */
	public static function getUrlKey()
	{
		while (TRUE)
		{
			$key = strtolower(str_random(8));
			$count = static::where('urlkey', $key)->count();

			if ($count == 0)
			{
				return $key;
			}
		}
	}

	/**
	 * Generates a secure hashfor a paste
	 *
	 * @static
	 * @return string
	 */
	public static function getHash()
	{
		return strtolower(str_random(6));
	}

	/**
	 * Returns the first five lines of a pasted code
	 *
	 * @static
	 * @param  string   $data
	 * @return string
	 */
	public static function getAbstract($data)
	{
		$count = substr_count($data, "\n");

		if ($count > 5)
		{
			$lines = explode("\n", $data);
			$data = '';

			for ($idx = 0; $idx < 5; $idx++)
			{
				$data .= ($lines[$idx] . "\n");
			}
		}

		return trim($data);
	}

	/**
	 * Returns trending posts based on the age
	 *
	 * @param  string  $age
	 * @param  int     $perPage
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public static function getTrending($age, $perPage)
	{
		$time = time();
		$filter = $time - 259200;

		switch ($age)
		{
			case 'week':
				$filter = $time - 1814400;
				break;

			case 'month':
				$filter = $time - 7776000;
				break;

			case 'year':
				$filter = $time - 94608000;
				break;

			case 'all':
				$filter = 0;
				break;
		}

		return static::where('timestamp', '>=', $filter)->orderBy('hits', 'desc')->take($perPage);
	}

}
