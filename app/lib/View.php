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

use Auth;
use Schema;
use Session;
use Site;
use User;

/**
 * View class
 *
 * Abstraction over \Illuminate\Support\Facades\View to enable skin support
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class View extends \Illuminate\Support\Facades\View {

	/**
	 * Returns default view data.
	 *
	 * @static
	 * @return array
	 */
	public static function defaults()
	{
		$site = Site::config('general');

		$defaults = array(
			'site'        => Site::config(),
			'error'       => Session::get('messages.error'),
			'success'     => Session::get('messages.success'),
		);

		// View can be called even before tables are available.
		// So we check if a valid version number is available before
		// injecting user data.
		if (Site::versionNbr($site->version) > 0)
		{
			$defaults = array_merge($defaults, array(
				'user'        => Auth::user(),
				'role'        => User::getRoles(),
			));
		}

		return $defaults;
	}

	/**
	 * This abstraction over the base method injects the skin name
	 * and default view data.
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @param  array   $mergeData
	 * @return \Illuminate\View\View
	 */
	public static function make($view, $data = array())
	{
		return parent::make(static::skin($view), $data, static::defaults());
	}

	/**
	 * Injects the skin name into a view name.
	 * This excludes the e-mail, JSON and XML templates.
	 *
	 * @static
	 * @param  string  $view
	 * @return string
	 */
	public static function skin($view)
	{
		if ( ! starts_with($view, 'templates'))
		{
			$skin = Site::config('general')->skin;

			$view = "skins/{$skin}/{$view}";
		}

		return $view;
	}

}
