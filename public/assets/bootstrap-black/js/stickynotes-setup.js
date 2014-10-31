/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package     StickyNotes
 * @author      Sayak Banerjee
 * @copyright   (c) 2014 Sayak Banerjee <mail@sayakbanerjee.com>. All rights reserved.
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @link        http://sayakbanerjee.com/sticky-notes
 * @since       Version 1.0
 * @filesource
 */

/**
 * Sends the install commands to the server
 *
 * @param  string  baseUrl
 * @param  string  action
 * @return void
 */
function setup(baseUrl, action)
{
	action = action !== undefined ? '/' + action : '';

	$.ajax({
		url: baseUrl + '/ajax' + action + '?key=' + Math.random(),
		success: function(response)
		{
			response = response.split('|');

			// Set the response params
			var percent = parseInt(response[0]);
			var nextAction = response[1];
			var message = response[2];

			// Set the message
			$('#message').html(message);

			if (percent != -1)
			{
				// Set the percent on the screen
				$('#bar').css('width', percent + '%');
				$('#percent').html(percent + '%');

				if (percent < 100)
				{
					// Make the next request. We use setTimeout to protect
					// the server from rapid requests
					setTimeout(function()
					{
						setup(baseUrl, nextAction);
					}, 1000);
				}
				else
				{
					// Installation complete
					setTimeout(function()
					{
						location.reload();
					}, 2000);
				}
			}
			else
			{
				// Make the progress bar red
				$('.progress-bar').removeClass('progress-bar-info');
				$('.progress-bar').addClass('progress-bar-danger');

				// Installation aborted
				setTimeout(function()
				{
					window.location = baseUrl + '/error';
				}, 2000);
			}
		}
	});
}
