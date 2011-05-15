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

    // Yea, we have JS \o/
    $('#js_switch').attr('href', '');

    // Show hand for button
    $('.button').mouseover(function() {
        this.style.cursor = 'pointer';
    });

    // Remove dotted lines around links
    $('a').click(function()    {
        this.blur();
    });

    // Remove dotted line for drop menus
    $('select').change(function()    {
        this.blur();
    });

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
            $('#paste_private').attr('checked', true);
            captured = true;
        }
        else if (captured && $('#paste_password').val() == '') {
            $('#paste_private').attr('checked', privateChecked);
            captured = false;
        }
    }, 100);
    
    // Fetch author and language values from cookies
    var author = getCookie('stickynotes_author');
    var language = getCookie('stickynotes_language');
    
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

function getCookie(c_name)
{
    var i, x, y, cookies_ary = document.cookie.split(";");

    for (i = 0; i < cookies_ary.length; i++)
    {
	x = cookies_ary[i].substr(0, cookies_ary[i].indexOf("="));
	y = cookies_ary[i].substr(cookies_ary[i].indexOf("=") + 1);
	x = x.replace(/^\s+|\s+$/g, "");
	
	if (x == c_name)
	{
	    return unescape(y);
	}
    }
}