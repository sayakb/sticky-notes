/**
* Sticky Notes pastebin
* @ver 0.2
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Startup function
$(document).ready(function() {
    
    $('#mult_select').click(function() {
        $('#mult').children().attr('selected', 'selected');
        $('#mult').focus();
        
        return false;
    });
    
    $('#mult_deselect').click(function() {
        $('#mult').children().removeAttr('selected');
        $('#mult').focus();
        
        return false;
    });

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
    
    if ($('#stickynotes_update').length &&
        $('#stickynotes_update').html().length > 0) {
        var url = '?ver=1';
        var current = parseInt($('#stickynotes_build_num').val());
        
        var jqxhr = $.get(url, function(data) {
            if (parseInt(data) > current)
            {
                $('#stickynotes_ver')
                    .css('color', 'darkRed')
                    .removeAttr('class');
                $('.waitimg').hide();
                $('#stickynotes_update').show();
            }
            else
            {
                $('.waitimg').hide();
            }
        })
        .error(function() { 
            $('.waitimg').hide();
        });
    }
});

function strpos (haystack, needle, offset) {
    var i = (haystack + '').indexOf(needle, (offset || 0));
    return i === -1 ? false : i;
}