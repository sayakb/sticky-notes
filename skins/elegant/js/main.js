/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

var IsIe = (navigator.appName.indexOf("Microsoft") >= 0) ? true : false;
var privateChecked = false, captured = false;

// Startup function
$(document).ready(function() {
    var skinPath = $('#skin_path').html();

    // Disable auto complete
    $('#paste_form').attr('autocomplete', 'off');

    // Make textarea resizable
    $('#paste_data').resizable({
        handles: 'se',
        minWidth: $('#paste_data').width() + (IsIe ? 3 : 13),
        minHeight: (IsIe ? 300 : 310),
        maxWidth: $('#paste_data').width() + (IsIe ? 3 : 13)
    });

    // Let's make buttons!
    $('#paste_submit, #paste_private, #pass_submit').button();
    $('#paste_submit, #paste_private_label').css('textShadow', 'none');
    $('#private_label').hide();
    $('#js_switch').attr('href', '');

    // Show hand for button
    $('button').mouseover(function() {
        this.style.cursor = 'pointer';
    });

    // Remove dotted lines around links
    $('a').click(function() {
        this.blur();
    });

    // Remove dotted line for drop menus
    $('select').change(function() {
        this.blur();
    });

    // Finally, let's hide the spacer
    $('#js_spacer').hide();

    // Animate the message
    if ($('#message').css('display') != 'none')
    {
        $('#message').css('top', '-90px');

        setTimeout(function() {
            $('#message').animate({top: '0px'});
        }, 500);
    }

    // Check if private box is checked
    if ($('#paste_private').is(':checked'))
    {
        privateChecked = true;
    }
    else
    {
        privateChecked = false;
    }

    $('#paste_private').click(function() {
        if ($(this).is(':checked'))
        {
            privateChecked = true;
        }
        else
        {
            privateChecked = false;
        }
    });

    // Update private checkbox if password is entered
    setInterval(function() {
        if ($('#paste_password').val() != '') {
            $('#paste_private')
                .attr('checked', true)
                .button('refresh')
                .button('disable');

            captured = true;
        }
        else if (captured && $('#paste_password').val() == '') {
            $('#paste_private')
                .attr('checked', privateChecked)
                .button('refresh')
                .button('enable');

            captured = false;
        }
    }, 100);
    
    // Fetch author and language values from cookies
    var author = $.cookie('stickynotes_author');
    var language = $.cookie('stickynotes_language');
    
    $('#paste_user').val(author);
    $('#paste_lang').val(language);    
});

// Function to insert tab in text area
function insertTab(o, e)
{
    var kC = e.keyCode ? e.keyCode : e.charCode ? e.charCode : e.which;

    if (kC == 9 && !e.shiftKey && !e.ctrlKey && !e.altKey)
    {
        var oS = o.scrollTop;

        if (o.setSelectionRange)
        {
            var sS = o.selectionStart;
            var sE = o.selectionEnd;
            o.value = o.value.substring(0, sS) + "\t" + o.value.substr(sE);
            o.setSelectionRange(sS + 1, sS + 1);
            o.focus();
        }
        else if (o.createTextRange)
        {
            document.selection.createRange().text = "\t";
            e.returnValue = false;
        }

        o.scrollTop = oS;

        if (e.preventDefault)
        {
            e.preventDefault();
        }

        return false;
    }

    return true;
}