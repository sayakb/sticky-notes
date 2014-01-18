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

use PasswordHash;
use Paste;
use User;

/**
 * PHPass class
 *
 * Provides encryption methods and updates passwords
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class PHPass {

	/**
	 * Stores a class instance
	 *
	 * @var PHPass
	 */
	private static $instance;

	/**
	 * The crytographic library instance
	 *
	 * @var PasswordHash
	 */
	private $phpass;

	/**
	 * Creates a new instance of PHPass
	 *
	 * @return void
	 */
	public function __construct()
	{
		require_once base_path().'/vendor/phpass/PasswordHash.php';

		$this->phpass = new PasswordHash(10, false);
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
			static::$instance = new PHPass();
		}

		return static::$instance;
	}

	/**
	 * Creates a bcrypt hash
	 *
	 * @param  string  $password
	 * @param  string  $salt
	 * @return string
	 */
	public function create($password, $salt)
	{
		return $this->phpass->HashPassword($password.$salt);
	}

	/**
	 * Checks a password hash, updates it to bcrypt if still using sha1
	 *
	 * @param  string  $model
	 * @param  string  $password
	 * @param  string  $salt
	 * @param  string  $hash
	 * @return bool
	 */
	public function check($model, $password, $salt, $hash)
	{
		// Hash created using blowfish algorithm
		if ($hash[0] == '$')
		{
			return $this->phpass->CheckPassword($password.$salt, $hash);
		}

		// Hash created using secure hash algorithm
		// This check is done to maintain backward compatibility
		else
		{
			$newHash = $this->create($password, $salt);

			$oldHash = NULL;

			$query = NULL;

			switch($model)
			{
				case 'Paste':
					$oldHash = sha1(sha1($password).$salt);

					$query = Paste::query();

					break;

				case 'User':
					$oldHash = sha1($password.$salt);

					$query = User::query();

					break;

				default:
					return FALSE;
			}

			// Password matches with old method, now migrate all pwds with this hash
			if ($hash == $oldHash)
			{
				$query->where('password', $oldHash)->update(array(
					'password' => $newHash
				));

				return TRUE;
			}
		}

		return FALSE;
	}

}
