/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package     StickyNotes
 * @author      Sayak Banerjee
 * @copyright   (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>. All rights reserved.
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @link        http://sayakbanerjee.com/sticky-notes
 * @since       Version 1.0
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

	// Initialize code wrapping
	initWrapToggle();

	// Initialize the code editor
	initEditor();

	// Initialize tab persistence
	initTabPersistence();

	// Initialize line reference
	initLineReference();

	// Initialize bootstrap components
	initBootstrap();
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

	// Setup AJAX requests
	$('[data-toggle="ajax"]').each(function()
	{
		var id = 'stickynotes-' + count++;
		var onload = $(this).attr('data-onload') === 'true';
		var realtime = $(this).attr('data-realtime') === 'true';
		var component = $(this).attr('data-component');
		var extra = $(this).attr('data-extra');

		// Set the id of this element
		$(this).attr('data-id', id);

		// ajaxUrl and component must be defined
		if (ajaxUrl !== undefined && component !== undefined)
		{
			var getUrl = ajaxUrl + '/' + component + (extra !== undefined ? '/' + extra : '');

			var callback = function(e)
			{
				// Add the loading icon
				var iconWhite = $(this).hasClass('btn') ? 'icon-white' : '';

				$(this).html('<i class="icon-refresh ' + iconWhite + '"></i>');

				// Send the AJAX request
				$.ajax({
					url: getUrl + '?key=' + Math.random(),
					context: $('[data-id="' + id + '"]'),
					success: function(response)
					{
						// Dump the HTML in the element
						$(this).html(response);

						// If response is link, set it as href as well
						if (response.indexOf('http') === 0)
						{
							$(this).attr('href', response);
							$(this).removeAttr('data-toggle');
							$(this).off('click');
						}

						// Activate bootstrap components again
						initBootstrap();
					}
				});

				if (e !== undefined)
				{
					e.preventDefault();
				}
			};

			// For onload requests, execute the callback right away
			// For the rest, bind it to the click event of the element
			if (onload)
			{
				if (realtime)
				{
					setInterval(callback, 1000);
				}
				else
				{
					setTimeout(callback);
				}
			}
			else
			{
				$(this).on('click', callback);
			}
		}
	});
}

/**
 * Activates the code wrapping toggle function
 *
 * @return void
 */
function initWrapToggle()
{
	$('[data-toggle="wrap"]').click(function(e)
	{
		var isWrapped = $('.pre div').css('white-space') != 'nowrap';
		var newValue = isWrapped ? 'nowrap' : 'inherit';

		$('.pre div').css('white-space', newValue);

		e.preventDefault();
	});
}

/**
 * Activates the paste editor
 *
 * @return void
 */
function initEditor()
{
	// Insert tab in the code box
	$('[name="data"]').keydown(function (e)
	{
		if (e.keyCode == 9)
		{
			var myValue = "\t";
			var startPos = this.selectionStart;
			var endPos = this.selectionEnd;
			var scrollTop = this.scrollTop;

			this.value = this.value.substring(0, startPos) + myValue + this.value.substring(endPos,this.value.length);
			this.focus();
			this.selectionStart = startPos + myValue.length;
			this.selectionEnd = startPos + myValue.length;
			this.scrollTop = scrollTop;

			e.preventDefault();
		}
	});

	// Tick the private checkbox if password is entered
	$('[name="password"]').keyup(function()
	{
		$('[name="private"]').attr('checked', $(this).val().length > 0);
	});
}

/**
 * Activates some bootstrap components
 *
 * @return void
 */
function initBootstrap()
{
	// Activate tooltips
	$('[data-toggle="tooltip"]').tooltip();
}

/**
 * Saves the tab state on all pages
 *
 * @return void
 */
function initTabPersistence()
{
	// Restore the previous tab state
	$('.nav-tabs').each(function()
	{
		var id = $(this).attr('id');
		var index = $.cookie('stickynotes_tabstate');

		if (index !== undefined)
		{
			$('.nav-tabs > li:eq(' + index + ') a').tab('show');
		}
	});

	// Save the current tab state
	$('.nav-tabs > li > a').on('shown.bs.tab', function (e)
	{
		var id = $(this).parents('.nav-tabs').attr('id');
		var index = $(this).parents('li').index();

		$.cookie('stickynotes_tabstate', index);
	})

	// Clear tab state when navigated to a different page
	if ($('.nav-tabs').length == 0)
	{
		$.cookie('stickynotes_tabstate', null);
	}
}

/**
 * Highlights lines upon clicking them on the #show page
 *
 * @return void
 */
function initLineReference()
{
	if ($('section#show').length != 0)
	{
		var line = 1;

		// First, we allocate unique IDs to all lines
		$('.pre li').each(function()
		{
			$(this).attr('id', 'line-' + line++);
		});

		// Next, navigate to an ID if the user requested it
		var anchor = window.location.hash;

		if (anchor.length > 0)
		{
			window.location = anchor;

			$(anchor).addClass('highlight');
		}

		// Click to change anchor
		$('.pre li').mouseup(function()
		{
			if (window.getSelection() == '')
			{
				window.location.hash = '#' + $(this).attr('id');

				$('.pre li').removeClass('highlight');
				$(this).addClass('highlight');
			}
		});
	}
}

/**
 * Draws a Google chart in a container
 *
 * @return void
 */
function initAreaChart()
{
	if (typeof(chartData) !== undefined && typeof(chartContainer) !== undefined)
	{
		// Create an instance of line chart
		var chart = new google.visualization.AreaChart(chartContainer);

		// Define chart options
		var options = {
			colors: [ '#0068c6', '#da4f49' ],
			areaOpacity: 0.1,
			lineWidth: 4,
			pointSize: 8,
			hAxis: {
				textStyle: {
					color: '#666'
				},
				gridlines: {
					color: 'transparent'
				},
				baselineColor: '#eeeeee',
				format:'MMM d'
			},
			vAxis: {
				textStyle: {
					color: '#666'
				},
				gridlines: {
					color: '#eee'
				}
			},
			chartArea: {
				left: 50,
				top: 10,
				width: '95%',
				height: 210
			},
			legend: {
				position: 'bottom'
			}
		};

		// Draw the line chart
		chart.draw(chartData, options);
	}
}

/**
 * Invoke the entry point on DOM ready
 */
$(stickyNotes);

/**
 * Draw google chart on window resize
 */
$(window).resize(initAreaChart);
