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

use stdClass;

/**
 * View class
 *
 * Abstraction over \Illuminate\Support\Facades\Auth to add functionality
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Auth extends \Illuminate\Support\Facades\Auth {

	/**
	 * Defines the roles for the logged in user
	 *
	 * @var array
	 */
	private static $roles;

	/**
	 * Validates if a user has access to a specific resource
	 * by matching the passed userId with the ID of the logged
	 * in user
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public static function access($id)
	{
		$roles = static::roles();

		return ! $roles->guest AND ($roles->admin OR static::user()->id == $id);
	}

	/**
	 * Fetches the roles for the currently logged in user
	 *
	 * @return object
	 */
	public static function roles()
	{
		if ( ! isset(static::$roles) OR php_sapi_name() == 'cli')
		{
			static::$roles = new stdClass();

			static::$roles->guest = FALSE;
			static::$roles->user  = FALSE;
			static::$roles->admin = FALSE;

			if (static::guest())
			{
				static::$roles->guest = TRUE;
			}
			else
			{
				static::$roles->user = TRUE;

				static::$roles->admin = static::user()->admin;
			}
		}

		return static::$roles;
	}

}
