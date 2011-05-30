<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

class module
{
    // Method to load a module
    function load($module)
    {
        if (file_exists(realpath("modules/mod_{$module}.php")))
        {
            include("modules/mod_{$module}.php");
        }
        else
        {
            $message  = 'Sticky Notes module error<br /><br />';
            $message .= 'Error: Cannot find specified module<br />';
            $message .= 'Make sure the module scripts exist inside the admin/modules/ folder';
            die($message);
        }
    }
    
    // Method to validate the current module
    function validate($mode)
    {
        global $core;
        
        // Available modes
        $modes_ary = array('dashboard', 'pastes', 'users', 'ipbans', 'config', 'logout');
        
        if (!in_array($mode, $modes_ary))
        {
            $core->redirect($core->path());
        }
    }
}

?>