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
 * Stores the current URL
 *
 * @var string
 */
var currentUrl = $(location).attr('href');

/**
 * Timer container
 *
 * @var array
 */
var timers = new Array();

/**
 * Instance counter
 *
 * @var int
 */
var instance = 0;

/**
 * This is the main entry point of the script
 *
 * @return void
 */
function initMain()
{
	// Initialize a new instance
	initInstance();

	// Initialize AJAX components
	initAjaxComponents();

	// Initialize AJAX navigation
	initAjaxNavigation();

	// Initialize addons
	initAddons();
}

/**
 * This initializes all JS addons
 *
 * @return void
 */
function initAddons()
{
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
 * Initializes a new instance of the JS library
 *
 * @return void
 */
function initInstance()
{
	// Clear all timers
	if (timers[instance] !== undefined)
	{
		for (idx in timers[instance])
		{
			clearInterval(timers[instance][idx]);
		}
	}

	// Create a new instance and timer container
	instance++;

	timers[instance] = new Array();
}

/**
 * Starts a new timed operation
 *
 * @param  operation
 * @param  callback
 * @param  interval
 * @return void
 */
function initTimer(operation, callback, interval)
{
	switch (operation)
	{
		case 'once':
			setTimeout(callback, interval);
			break;

		case 'repeat':
			timers[instance].push(setInterval(callback, interval));
			break;
	}
}

/**
 * Scans for and processes AJAX components
 *
 * Each AJAX component can have 4 parameters:
 *  - realtime  : Indicates if the component involves realtime data
 *  - onload    : The AJAX request will be triggered automatically
 *  - component : The utility component to request
 *  - extra     : Any extra data that will be sent to the server
 *
 * @return void
 */
function initAjaxComponents()
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

		// AJAX URL and component must be defined
		if (ajaxUrl !== undefined && component !== undefined)
		{
			var getUrl = ajaxUrl + '/' + component + (extra !== undefined ? '/' + extra : '');

			var callback = function(e)
			{
				// Add the loading icon
				$(this).html('<span class="glyphicon glyphicon-refresh"></span>');

				// Send the AJAX request
				$.ajax({
					url: getUrl,
					data: { key: Math.random(), ajax: 1 },
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

						// Load addons again
						initAddons();
					}
				});

				if (e !== undefined)
				{
					e.preventDefault();
				}
			};

			// Execute the AJAX callback
			if (onload)
			{
				if (realtime)
				{
					initTimer('repeat', callback, 5000);
				}

				initTimer('once', callback, 0);
			}
			else
			{
				$(this).off('click').on('click', callback);
			}
		}
	});
}

/**
 * Enabled AJAX navigation across the site
 *
 * @return void
 */
function initAjaxNavigation()
{
	if (ajaxNav !== undefined && ajaxNav && $.support.cors)
	{
		// AJAX callback
		var callback = function(e)
		{
			var navMethod = $(this).prop('tagName') == 'A' ? 'GET' : 'POST';
			var seek = $(this).attr('data-seek');

			// Set up data based on method
			switch (navMethod)
			{
				case 'GET':
					navUrl = $(this).attr('href');
					payload = 'ajax=1';
					break;

				case 'POST':
					navUrl = $(this).attr('action');
					payload = $(this).serialize() + '&ajax=1';
					break;
			}

			// Send an AJAX request for all but anchor links
			if (navUrl !== undefined && !$('.loader').is(':visible'))
			{
				$('.loader').show();

				$.ajax({
					url: navUrl,
					method: navMethod,
					context: $('body'),
					data: payload,
					success: function(response, status, info)
					{
						var isPageSection = response.indexOf('<!DOCTYPE html>') == -1;
						var isHtmlContent = info.getResponseHeader('Content-Type').indexOf('text/html') != -1;

						// Change the page URL
						currentUrl = info.getResponseHeader('StickyNotes-Url');
						window.history.pushState({ html: response }, null, currentUrl);

						// Handle the response
						if (isPageSection && isHtmlContent)
						{
							$(this).html(response);
						}
						else if (isHtmlContent)
						{
							dom = $(document.createElement('html'));
							dom[0].innerHTML = response;

							$(this).html(dom.find('body').html());
						}
						else
						{
							window.location = navUrl;
						}

						// Seek to top of the page
						$.scrollTo(0, 200);

						// Load JS triggers again
						initMain();
					},
					error: function()
					{
						window.location = navUrl;
					}
				});

				e.preventDefault();
			}
		};

		// Execute callback on all links, excluding some
		$('body').find('a' +
			':not([href*="/admin"])' +
			':not([href*="/attachment"])' +
			':not([href*="#"])' +
			':not([href*="mailto:"])' +
			':not([onclick])'
		).off('click').on('click', callback);

		// Execute callback on all designated forms
		$('body').find('form[data-navigate="ajax"]').off('submit').on('submit', callback);

		// URL change monitor
		initTimer('repeat', function()
		{
			var href = $(location).attr('href');

			// Trim the trailing slash from currentUrl
			if (currentUrl.substr(-1) == '/')
			{
				currentUrl = currentUrl.substr(0, currentUrl.length - 1);
			}

			// Trim the trailing slash from href
			if (href.substr(-1) == '/')
			{
				href = href.substr(0, href.length - 1);
			}

			// Reload page if URL changed
			if (currentUrl != href && href.indexOf('#') == -1)
			{
				currentUrl = href;

				// Load the selected page
				$('.loader').show();

				$.get(href, function(response)
				{
					dom = $(document.createElement('html'));
					dom[0].innerHTML = response;

					$('body').html(dom.find('body').html());
				});
			}
		}, 300);
	}
}

/**
 * Activates the code wrapping toggle function
 *
 * @return void
 */
function initWrapToggle()
{
	$('[data-toggle="wrap"]').off('click').on('click', function(e)
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
	$('[name="data"]').off('keydown').on('keydown', function (e)
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
	$('[name="password"]').off('keyup').on('keyup', function()
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
			var top = $(anchor).offset().top;

			// Scroll to the anchor
			$.scrollTo(top, 200);

			// Highlight the anchor
			$(anchor).addClass('highlight');
		}

		// Click to change anchor
		$('.pre li').off('mouseup').on('mouseup', function()
		{
			if (window.getSelection() == '')
			{
				var id = $(this).attr('id');
				var top = $(this).offset().top;

				// Scroll to the anchor
				$.scrollTo(top, 200, function() {
					window.location.hash = '#' + id;
				});

				// Highlight the anchor
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
	if (chartData !== undefined && chartContainer !== undefined)
	{
		// Create an instance of line chart
		var chart = new google.visualization.AreaChart(chartContainer);

		// Define chart options
		var options = {
			colors: [ '#428bca', '#d9534f' ],
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
				width: '100%',
				height: 210
			},
			legend: {
				position: 'bottom'
			}
		};

		// Draw the line chart
		chart.draw(chartData, options);
	}

	// Redraw chart on window resize
	$(window).off('resize').on('resize', initAreaChart);
}

/**
 * Invoke the entry point on DOM ready
 */
$(initMain);
