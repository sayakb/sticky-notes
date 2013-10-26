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
use Cache;
use Config;
use Lang;
use Request;
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
			'site'       => Site::config(),
			'error'      => Session::get('messages.error'),
			'success'    => Session::get('messages.success'),
		);

		// View can be called even before tables are available.
		// So we check if a valid version number is available before
		// injecting user data.
		if (System::version($site->version) > 0)
		{
			$defaults = array_merge($defaults, array(
				'auth'   => Auth::user(),
				'role'   => User::getRoles(),
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
	 * Injects skin to asset paths.
	 *
	 * @static
	 * @param  string  $asset
	 * @return string
	 */
	public static function asset($asset)
	{
		return asset('assets/'.static::skin($asset, FALSE));
	}

	/**
	 * Injects the skin name into a resource name.
	 * This excludes the e-mail, JSON and XML templates.
	 *
	 * @static
	 * @param  string  $resource
	 * @param  bool    $prefix
	 * @return string
	 */
	public static function skin($resource, $prefix = TRUE)
	{
		if ( ! starts_with($resource, 'templates'))
		{
			$skin = Site::config('general')->skin;

			$resource = ($prefix ? 'skins/' : '')."{$skin}/{$resource}";
		}

		return $resource;
	}

	/**
	 * Generates a navigation menu
	 *
	 * @access public
	 * @param  string  $menu
	 * @return string
	 */
	public static function menu($menu)
	{
		// Current path - will be used to highlight menu item
		$path = Request::path();

		// Current user ID for role based menus
		$user = Auth::check() ? Auth::user()->id : 0;

		// Get current project name
		$project = System::project();

		// Grab and parse all the menus
		$group = Config::get("menus.{$menu}");

		// The cache key is not only menu and path specific but also
		// unique for a user and a project
		$cacheKey = "site.menu.{$menu}.{$path}.{$user}.{$project}";

		// Build the menu items. Items are cached for 60 minutes
		$output = Cache::remember($cacheKey, 60, function() use ($path, $user, $group)
		{
			$output = NULL;

			foreach ($group as $key => $item)
			{
				if ( ! str_contains($key, '_'))
				{
					$label = Lang::get($item['label']);

					$current = FALSE;

					// Check if visibility of the item is bound
					if (isset($item['visible']))
					{
						$bindings = explode('.', $item['visible']);

						// Check for the invert flag
						if (starts_with($bindings[0], '!'))
						{
							$bindings[0] = substr($bindings[0], 1);

							$invert = TRUE;
						}
						else
						{
							$invert = FALSE;
						}

						// Get the binding flags
						switch ($bindings[0])
						{
							case 'role':
								$flags = User::getRoles();
								break;

							case 'config':
								$flags = Site::config('general');
								break;

							default:
								$flags = NULL;
								break;
						}

						// Do not parse the menu item if the flag does not
						// evaluate to true
						if ($flags != NULL)
						{
							if ( ! $flags->$bindings[1] XOR $invert)
							{
								continue;
							}
						}
					}

					// Determine whether this is the active link
					if ($group['_exact'] AND $key === $path)
					{
						$current = TRUE;
					}
					else if ( ! $group['_exact'] AND starts_with($path, $key))
					{
						$current = TRUE;
					}

					// Highlight the active item
					if ($current)
					{
						$active = 'class="active"';

						$href = '';
					}
					else
					{
						$active = '';

						$href = 'href="'.url($key).'"';
					}

					// Set the entry icon
					if (isset($item['icon']))
					{
						$icon = View::make('common/icon', array('icon' => $item['icon']));
					}
					else
					{
						$icon = NULL;
					}

					// Generate the item markup
					$output .= "<li {$active}><a {$href}>{$icon} {$label}</a></li>";
				}
			}

			// Add login/logout link if menu is set for that
			if ($group['_showLogin'])
			{
				if ($user)
				{
					$label = Lang::get('global.logout');

					$href = 'href="'.url('user/logout').'"';
				}
				else
				{
					$label = Lang::get('global.login');

					$href = 'href="'.url('user/login').'"';
				}

				// Are we on the login screen?
				$active = $path == 'user/login' ? 'class="active"' : '';

				$icon = View::make('common/icon', array('icon' => 'user'));;

				// Generate the markup
				$output .= "<li {$active}><a {$href}>{$icon} {$label}</a></li>";
			}

			return $output;
		});

		return $output;
	}

}
