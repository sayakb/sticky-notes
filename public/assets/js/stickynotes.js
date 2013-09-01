/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package		StickyNotes
 * @author		Sayak Banerjee
 * @copyright	(c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>. All rights reserved.
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @link		http://sayakbanerjee.com/sticky-notes
 * @since		Version 1.0
 * @filesource
 */

/**
 * This is the main entry point of the script
 *
 * @return void
 */
function stickyNotes()
{
	// Initialize AJAX events
	initAjax();
}

/**
 * Scans for and processes AJAX requests
 *
 * Each AJAX component can have 4 parameters:
 *  - realtime  : Indicates if the component involves realtime data
 *  - onload    : The AJAX request will be triggered automatically
 *  - component : The utility component to request
 *  - extra     : Any extra data that will be sent to the server
 *
 * @return void
 */
function initAjax()
{
	var count = 1;

	$('.ajax').each(function()
	{
		var id = 'ajax-' + count++;

		var realtime = $(this).attr('data-realtime') === 'true';

		var onload = $(this).attr('data-onload') === 'true';

		var component = $(this).attr('data-component');

		var extra = $(this).attr('data-extra');

		// Set the id of this element
		$(this).attr('id', id);

		// ajaxUrl must be defined in your page template somewhere
		if (ajaxUrl !== undefined)
		{
			var getUrl = ajaxUrl + '/' + component + (extra !== undefined ? '/' + extra : '');

			var loop = setInterval(function()
			{
				$.ajax({
					url: getUrl,
					context: $('#' + id),
					success: function(response)
					{
						$(this).html(response);
					}
				});

				if ( ! realtime || true)
				{
					clearInterval(loop);
				}
			}, 1000);
		}
	});
}

/**
 * Invoke the entry point on DOM ready
 */
$(stickyNotes);
