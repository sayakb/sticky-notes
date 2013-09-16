/**
 * Sticky Notes API Client
 *
 * A javascript client for the Sticky Notes REST API
 *
 * @package     StickyNotesAPI
 * @author      Sayak Banerjee
 * @copyright   (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>. All rights reserved.
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @link        http://sayakbanerjee.com/sticky-notes
 * @since       Version 1.0
 * @filesource
 */

var baseUrl = 'http://localhost/sticky-notes/';

function createPaste()
{
	$('#paste').click(function()
	{
		$.ajax({
			url: baseUrl + 'api/json/create',
			type: 'POST',
			dataType: 'html',
			data: {
				'title': $('#title').val(),
				'data': $('#data').val(),
				'language': $('#language').val(),
				'expire': $('#expire').val(),
				'private': $('#private').is(':checked') ? true : null
			},
			success: function(response)
			{
				console.log(response);
			},
			error: function(jqXhr, status, error)
			{
				console.log(status);
				console.log(error);
			}
		})
	});
}
