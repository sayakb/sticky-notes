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
		'authorid',
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
	 * Creates a new paste with the data supplied
	 *
	 * @static
	 * @param  array  $data
	 * @return array
	 */
	public static function createNew($data)
	{
		// Set the paste protected flag
		$is_protected = ! empty($data['password']);

		// Set the private paste flag
		$is_private = ! empty($data['private']);

		// We use an alphanumeric URL key to identify pastes
		// This is done so that users do not have access to the
		// actual primary key in the database and therefore, cannot
		// mass download all data
		$urlkey = static::makeUrlKey();

		// This hash is used for identifying private pastes
		// Unless being opened by the paste author, sticky notes
		// makes passing this hass as a part of the URL mandatory
		// for private pastes
		$hash = static::getHash();

		// Set the paste author
		if (Auth::check())
		{
			$user = Auth::user();

			$authorId = $user->id;

			$author = $user->username;
		}
		else
		{
			$authorId = 0;

			$author = NULL;
		}

		// Encrypt the password with a salt
		$password = '';

		$salt = str_random(5);

		if ( ! empty($data['password']))
		{
			$password = PHPass::make()->create($data['password'], $salt);
		}

		// Insert the new paste
		Paste::create(array(
			'project'     => empty($data['project']) ? NULL : $data['project'],
			'title'       => empty($data['title']) ? NULL : $data['title'],
			'data'        => $data['data'],
			'language'    => $data['language'],
			'private'     => $is_protected OR $is_private ? 1 : 0,
			'password'    => $password,
			'salt'        => $salt,
			'hash'        => $hash,
			'urlkey'      => $urlkey,
			'author'      => $author,
			'authorid'    => $authorId,
			'timestamp'   => time(),
			'expire'      => $data['expire'] > 0 ? $data['expire'] + time() : 0,
			'ip'          => Request::getClientIp(),
			'hits'        => 0
		));

		// Return some paste info that is needed to
		// build the paste URL later
		return array(
			'urlkey'         => $urlkey,
			'hash'           => $hash,
			'is_protected'   => $is_protected,
			'is_private'     => $is_private,
		);
	}

	/**
	 * Fetches a post by its urlkey or id
	 *
	 * @param  string  $key
	 * @return \Illuminate\Database\Eloquent\Model|null
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

	/**
	 * Returns the paste key
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $paste
	 * @return string
	 */
	public static function getUrlKey($paste)
	{
		if ( ! empty($paste->urlkey))
		{
			return 'p'.$paste->urlkey;
		}
		else
		{
			return $paste->id;
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
	 * Generates a unique URL key for the paste
	 *
	 * @static
	 * @return string
	 */
	public static function makeUrlKey()
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

}
