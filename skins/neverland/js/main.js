/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

var IsIe = (navigator.appName.indexOf("Microsoft") >= 0);
var privateChecked = false, captured = false;

// Startup function
$(document).ready(function() {
    // Disable auto complete
    $('#paste_form').attr('autocomplete', 'off');

    // Remove dotted lines around links
    $('a').click(function() {
        this.blur();
    });

    // Remove dotted line for drop menus
    $('select').change(function() {
        this.blur();
    });

    // Check if private box is checked
    privateChecked = $('#paste_private').is(':checked');

    $('#paste_private').click(function() {
        privateChecked = $(this).is(':checked');
    });

    // Update private checkbox if password is entered
    $('#paste_password').keyup(function() {
        var checked = $(this).val().length == 0 ? privateChecked : true;
        $('#paste_private').attr('checked', checked);
    });
    
    // Fetch author and language values from cookies
    var author = $.cookie('stickynotes_author');
    var language = $.cookie('stickynotes_language');
    var index = -1;

    if (author != null) {
        $('#paste_user').val(author);
    }

    for (i = 1; i <= 10; i++) {
        var $option = $('#paste_lang option:nth-child(' + i.toString() + ')');
        
        if ($option.attr('value') == language) {
            index = i - 1;
        }
    }

    if (language != null && index < 0) {
        $('#paste_lang').val(language);
    } else if (language != null) {
        $('#paste_lang').get(0).selectedIndex = index;
    }
    
    // Insert tab in the code box
    $('#paste_data').keydown(function (e) {      
        if (e.keyCode == 9) {
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

    // Alias textbox functions
    var defaultUser = $('#paste_user_default').html();
    
    $('#paste_user')
        .click(function() {
            if ($(this).val() == defaultUser) {
                $(this).val('');
            }
        })
        .focusout(function() {
            if ($(this).val().length == 0) {
                $(this).val(defaultUser);
            }
        });

    // Toggle code wrapping
    $('#wrap_toggle').click(function() {
        var isWrapped = $('.codewrap div').css('white-space') != 'nowrap';

        if (isWrapped) {
            $('.codewrap div').css('white-space', 'nowrap');
        } else {
            $('.codewrap div').css('white-space', 'inherit');
        }

        return false;
    });
    
    // URL shorener logic
    $('#shorten_url').click(function() {
        var shortener = $(this).attr('data-shortener');
        
        // Get texts for shortener
        var lang_get = $.cookie('stickynotes_short_get');        
        var lang_generating = $.cookie('stickynotes_short_generating');
        var lang_error = $.cookie('stickynotes_short_error');

        if ($(this).html() == lang_error) {
            return false;
        }
        
        if ($(this).html().indexOf('goo.gl') != -1) {
            return true;
        }

        // Get the short URL    
        $(this).html(lang_generating);

        $.get(shortener, function(data) {
            if (data != "ERROR") {
                $('#shorten_url')
                    .attr('href', data)
                    .html(data);
            } else {
                $('#shorten_url').html(lang_error);
                
                setTimeout(function() {
                    $('#shorten_url').html(lang_get);
                }, 5000);
            }
        });

        return false;
    });
});