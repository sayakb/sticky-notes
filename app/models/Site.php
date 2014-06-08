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
 * Config class
 *
 * Manages and fetches site configuration data
 *
 * @package     StickyNotes
 * @subpackage  Models
 * @author      Sayak Banerjee
 */
class Site extends Eloquent {

	/**
	 * Table name for the model
	 *
	 * @var string
	 */
	protected $table = 'config';

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
		'group',
		'key',
		'value'
	);

	/**
	 * Gets or sets the site configuration data
	 *
	 * @access public
	 * @param  string  $group
	 * @param  array   $newData
	 * @return stdClass|bool
	 */
	public static function config($group = '', $newData = FALSE)
	{
		// Get a config value
		if ($newData === FALSE)
		{
			$config = Cache::rememberForever('site.config', function()
			{
				$config = Config::get('default');

				if (Schema::hasTable('config'))
				{
					$siteConfig = Site::all();

					if ( ! is_null($siteConfig))
					{
						foreach ($siteConfig as $item)
						{
							$config[$item['group']]->$item['key'] = $item['value'];
						}
					}
				}

				return $config;
			});

			return empty($group) ? (object) $config : $config[$group];
		}

		// Set config values for a group
		else
		{
			$site = static::config('general');

			// Get the tags that have HTML content
			$htmlKeys = preg_split('/\||,/', $site->htmlKeys);

			// Update the new config values in the DB
			foreach ($newData as $key => $value)
			{
				// Check for and strip HTML content
				if (in_array($key, $htmlKeys))
				{
					$value = strip_tags($value, $site->allowedTags);
				}

				// Save config data
				if ( ! empty($key) AND ! starts_with($key, '_'))
				{
					$key = camel_case($key);

					// Get the existing value of the config
					$config = static::query();

					$config->where('group', $group);

					$config->where('key', $key);

					// Do an UPSERT, i.e. if the value exists, update it.
					// If it doesn't, insert it.
					if ($config->count() > 0)
					{
						$config->update(array('value' => $value));
					}
					else
					{
						$config->insert(array(
							'group'  => $group,
							'key'    => $key,
							'value'  => $value,
						));
					}
				}
			}

			Cache::flush();

			return TRUE;
		}
	}

}
