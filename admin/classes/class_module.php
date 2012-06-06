<?php
/**
* Sticky Notes pastebin
* @ver 0.2
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

class module
{
    // Method to load a module
    function load($module_name)
    {
        global $gsod;
        
        if (file_exists(realpath("modules/mod_{$module_name}.php")))
        {
            // Set globals
            global $core, $lang, $skin, $db, $config, $module, $username, $sid, $mode,
                   $module_data, $module_title, $sg, $gsod;
            
            // Include the module
            include("modules/mod_{$module_name}.php");
        }
        else
        {
            $message  = 'Sticky Notes module error<br /><br />';
            $message .= 'Error: Cannot find specified module<br />';
            $message .= 'Make sure the module scripts exist inside the admin/modules/ folder';
            
            $gsod->trigger($message);
        }
    }
    
    // Method to validate the current module
    function validate($mode)
    {
        global $core;
        
        // Available modes
        $modes_ary = array('dashboard', 'pastes', 'users', 'ipbans', 'auth', 'config', 'logout');
        
        if (!in_array($mode, $modes_ary))
        {
            $core->redirect($core->path());
        }
    }
    
    // Method to show a message
    function notify($message)
    {
        global $skin;
        $skin->assign('notification_message', $message);
    }
}

?>