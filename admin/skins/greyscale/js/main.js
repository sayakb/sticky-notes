/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
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
});