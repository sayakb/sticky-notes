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

/**
 * API class
 *
 * Sticky notes REST API class
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class API {

	/**
	 * Mode of operation, usally xml or json
	 *
	 * @var string
	 */
	public $mode;

	/**
	 * Creates a new API instance
	 *
	 * @static
	 * @param  string  $mode
	 * @return void
	 */
	public static function make($mode)
	{
		$api = new API();

		$api->mode = $mode;

		return $api;
	}

	/**
	 * Throws a user specified error
	 *
	 * @param  string  $error
	 * @param  int     $code
	 * @return void
	 */
	public function error($error, $code = 200)
	{
		return $this->out('error', array('error' => "err_{$error}"), $code);
	}

	/**
	 * Generates the output and does some pre-processing
	 * before that
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @param  int     $code
	 * @return void
	 */
	public function out($view, $data, $code = 200)
	{
		$callback = array($this, 'sanitize'.studly_case($this->mode));

		// We sanitize the data before displaying
		//  - For XML, a specific set of characters are escaped
		//  - For JSON, PHP's inbuild json_encode is called
		array_walk_recursive($data, $callback);

		// Add an iterator to the data
		if ( ! isset($data['iterator']))
		{
			$data['iterator'] = 0;
		}

		// Now we create a custom response
		$response = Response::view("templates/api/{$this->mode}/{$view}", $data, $code);

		// We set the header based on mode
		switch ($this->mode)
		{
			case 'xml':

				$response->header('Content-Type', 'text/xml');

				break;

			case 'json':

				$response->header('Content-Type', 'application/json');

				break;
		}

		return $response;
	}

	/**
	 * Sanitize the data for XML mode
	 *
	 * @param  string  $data
	 * @return void
	 */
	private function sanitizeXml(&$data)
	{
		if (is_string($data))
		{
			$data = strtr($data, array(
				"<" => "&lt;",
				">" => "&gt;",
				'"' => "&quot;",
				"'" => "&apos;",
				"&" => "&amp;",
			));
		}
	}

	/**
	 * Sanitize the data for JSON mode
	 *
	 * @param  string  $data
	 * @return void
	 */
	private function sanitizeJson(&$data)
	{
		if (is_string($data) OR empty($data))
		{
			$data = json_encode($data);
		}
	}

}
