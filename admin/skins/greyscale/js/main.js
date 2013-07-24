/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Startup function
$(document).ready(function() {
    // Focus username field on login screen
    if ($('#login_user').length > 0) {
        $('#login_user').focus();
    }

    // Forgot password click event
    $('#forgot_password').click(function() {
        $('.field_login').hide();
        $('.field_reset').show();
        $('#login_user').focus();

        return false;
    });

    // Back to login link click
    $('#back_login').click(function() {
        $('.field_login').show();
        $('.field_reset').hide();
        $('#login_user').focus();

        return false;
    });

    // Multi select list select all event
    $('#mult_select').click(function() {
        $('#mult').children().attr('selected', 'selected');
        $('#mult').focus();

        return false;
    });

    // Multi select list deselect all event
    $('#mult_deselect').click(function() {
        $('#mult').children().removeAttr('selected');
        $('#mult').focus();

        return false;
    });

    // Notification message handler
    if ($('#notification_message').html().trim().length > 0) {
        $('#notification')
            .show()
            .css({
                opacity: 0,
                top: -1 * $('#notification').outerHeight(),
                left: ($('body').outerWidth() / 2) - ($('#notification').outerWidth() / 2)
            })
            .animate({
                opacity: 1,
                top: 0
            });

        $('#notification_close').click(function() {
            $('#notification').animate({
                opacity: 0,
                top: -1 * $('#notification').outerHeight()
            }, function() {
                $('#notification').hide();
            });
        });
    }

    // Update checker
    if ($('#stickynotes_update').length > 0 &&
        $('#stickynotes_update').html().length > 0) {
        var url = '?action=version';
        var current = parseInt($('#stickynotes_build_num').val());

        $.get(url, function(data) {
            if (parseInt(data) > current) {
                $('#stickynotes_ver').attr('class', 'darkred');
                $('#wait_version').hide();
                $('#stickynotes_update').show();
            }
            else {
                $('#wait_version').hide();
            }
        });
    }

    // Server load checker
    if ($('#stickynotes_sysload').length > 0) {
        setInterval(function() {
            var url = '?action=sysload';

            $.get(url, function(data) {
                $('#stickynotes_sysload').html(data);
                $('#wait_sysload').hide();
            });
        }, 2000);
    }
});