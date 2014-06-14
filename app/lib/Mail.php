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

use Lang;
use Site;

use Swift_TransportException;

/**
 * Mail class
 *
 * Abstraction over \Illuminate\Support\Facades\Mail to enable testing
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Mail extends \Illuminate\Support\Facades\Mail {

	/**
	 * Tests a specific email configuration
	 *
	 * @static
	 * @return bool|string
	 */
	public static function test()
	{
		try
		{
			// Send a dummy e-mail
			parent::send('templates/email/test', array(), function($message)
			{
				$message->to('test@example.com');
			});

			return TRUE;
		}
		catch (Swift_TransportException $e)
		{
			return sprintf(Lang::get('admin.test_mail_error'), $e->getMessage());
		}
	}

}
