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
		'author_id',
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
	 * One paste will have many revisions. This relationship will be
	 * used to fetch all revisions associated with this paste. The key
	 * column that we use is paste_id
	 *
	 * @return Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function revisions()
	{
		return $this->hasMany('Revision', 'paste_id');
	}

	/**
	 * One paste will have many comments. This relationship will be
	 * used to fetch all comments associated with this paste. The key
	 * column that we use is paste_id
	 *
	 * @return Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function comments()
	{
		return $this->hasMany('Comment', 'paste_id')->orderBy('id', 'desc');
	}

	/**
	 * Creates a new paste with the data supplied
	 *
	 * @static
	 * @param  string  $source
	 * @param  array   $data
	 * @return Paste
	 */
	public static function createNew($source, $data)
	{
		// Get the site's configuration
		$config = Site::config('general');

		// Set the paste protected flag
		$protected = ! empty($data['password']);

		// Set the private paste flag
		$private = ! empty($data['private']);

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

		// If the site is set as a private site, default $private to true
		if ($config->privateSite)
		{
			$private = TRUE;
		}

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

		// Set the paste expiration time default
		if ( ! isset($data['expire']) OR $data['expire'] < 0)
		{
			$data['expire'] = $config->pasteAge;
		}

		// Insert the new paste
		$paste = new Paste;

		$paste->project   = empty($data['project']) ? NULL : $data['project'];
		$paste->title     = empty($data['title']) ? NULL : $data['title'];
		$paste->data      = $data['data'];
		$paste->language  = $data['language'];
		$paste->private   = ($protected OR $private) ? 1 : 0;
		$paste->password  = $password;
		$paste->salt      = $salt;
		$paste->hash      = $hash;
		$paste->urlkey    = $urlkey;
		$paste->author    = $author;
		$paste->author_id = $authorId;
		$paste->timestamp = time();
		$paste->expire    = $data['expire'] > 0 ? time() + $data['expire'] : 0;
		$paste->ip        = Request::getClientIp();
		$paste->hits      = 0;

		$paste->save();

		// Insert paste count to the statistics table
		$stat = Statistics::firstOrNew(array('date' => date('Y-m-d')));

		$stat->$source++;

		$stat->save();

		// Return the created paste
		return $paste;
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
	 * @param  string  $data
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

		return $data;
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
			$key = 'p'.strtolower(str_random(8));

			$count = static::where('urlkey', $key)->count();

			if ($count == 0)
			{
				return $key;
			}
		}
	}

	/**
	 * Returns the URL for a paste
	 *
	 * @static
	 * @param  Paste  $paste
	 * @return string
	 */
	public static function getUrl($paste)
	{
		$url = $paste->urlkey;

		if ($paste->private)
		{
			$url .= '/'.$paste->hash;
		}

		return $url;
	}

	/**
	 * Fetches available expiration times for a paste
	 *
	 * @static
	 * @param  string  $category
	 * @param  bool    $csv
	 * @return array
	 */
	public static function getExpiration($category = 'create', $csv = FALSE)
	{
		// Current user ID for role based expiration options
		$user = Auth::check() ? Auth::user()->id : 0;

		// Fetch/update expiration times in cache
		return Cache::rememberForever("expire.{$category}.{$user}.{$csv}", function() use ($category, $csv)
		{
			$times = array();

			// Populate the expiration times
			foreach (Config::get('expire') as $time => $properties)
			{
				// First property represents the label
				$label = $properties[0];

				// Second property represents whether the expire time
				// is enabled or not
				$condition = $properties[1];

				// Add the expire time if condition evaluates to true
				if ($condition)
				{
					$times[$time] = Lang::get("{$category}.{$label}");
				}
			}

			// Do we just want CSV?
			if ($csv)
			{
				$times = implode(',', array_keys($times));
			}

			return $times;
		});
	}

}
