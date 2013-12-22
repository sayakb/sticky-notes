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

/**
 * Create a new paste
 *
 * @return void
 */
function createPaste()
{
	$('#paste').click(function()
	{
		$.ajax({
			url: baseUrl + 'api/json/create',
			type: 'POST',
			dataType: 'json',
			cache: false,
			data: {
				'title': $('#title').val(),
				'data': $('#data').val(),
				'language': $('#language').val(),
				'expire': $('#expire').val(),
				'private': $('#private').is(':checked') ? true : null
			},
			error: ajaxError,
			success: function(response)
			{
				var result = response.result;

				if (result.error === undefined)
				{
					var id = result.id;

					if (result.hash.length > 0)
					{
						id += '/' + result.hash;
					}

					window.location = 'show.html#' + id;
				}
				else
				{
					alert(parseError(result.error));
				}
			},
		})
	});
}

/**
 * Show an existing paste
 *
 * @return void
 */
function showPaste()
{
	if (window.location.hash.length > 0)
	{
		var key = window.location.hash.substr(1);
		var url = baseUrl + 'api/json/show/' + key.split('/')[0];

		if (key.indexOf('/') != -1)
		{
			url += '/' + key.split('/')[1];
		}

		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			cache: false,
			error: ajaxError,
			success: function(response)
			{
				var result = response.result;

				if (result.error === undefined)
				{
					$('#data').text(result.data);
					prettyPrint();

					if (result.title == null)
					{
						$('#title').html('Paste #' + result.id);
					}
					else
					{
						$('#title').html(result.title);
					}
				}
				else
				{
					alert(parseError(result.error));
				}
			},
		});
	}
}

/**
 * Show a list of public pastes
 *
 * @return void
 */
function listPastes()
{
	var hash = window.location.hash;
	var page = hash.length > 0 ? parseInt(hash.substr(1)) : 1;

	$.ajax({
		url: baseUrl + 'api/json/list/' + page,
		type: 'GET',
		dataType: 'json',
		cache: false,
		error: ajaxError,
		success: function(response)
		{
			var result = response.result;

			if (result.error === undefined)
			{
				$.each(result.pastes, function(key, value)
				{
					var item = '<li><a href="show.html#' + value + '">' + value + '</a></li>';

					$('#list').append(item);
				});

				for (var pg = 1; pg <= result.pages; pg++)
				{
					var active = pg == page ? 'class="active"' : '';
					var item = '<li ' + active + '><a href="#" onclick="window.location.hash=' +
								pg + '; location.reload(); return false;">' + pg + '</a></li>';

					$('#pages').append(item);
				}
			}
			else
			{
				alert(parseError(result.error));
			}
		},
	});
}

/**
 * Handles jQuery AJAX errors
 *
 * @param  string  error
 * @return string
 */
function ajaxError(jsXHR, status, error)
{
	if (jsXHR.responseText !== undefined)
	{
		var response = JSON.parse(jsXHR.responseText);

		if (response.result !== undefined)
		{
			alert(parseError(response.result.error));
		}
		else
		{
			alert('An error occurred');
		}
	}
}

/**
 * Parses an error message
 *
 * @param  string  error
 * @return string
 */
function parseError(error)
{
	var messages = {
		'err_title_max_30': 'Title cannot be longer than 30 characters',
		'err_data_required': 'Paste body was not specified',
		'err_lang_required': 'Paste language was not specified',
		'err_lang_invalid': 'An invalid language was used',
		'err_expire_integer': 'The paste expiration value must be an integer',
		'err_expire_invalid': 'An invalid expiration time was used',
		'err_not_found': 'Paste not found',
		'err_invalid_hash': 'Invalid hash code for a private paste',
		'err_password_required': 'Password required to view the paste',
		'err_invalid_password': 'Incorrect password supplied',
		'err_no_pastes': 'No pastes found',
	};

	return messages[error] !== undefined ? messages[error] : error;
}
