<?php
/**
* Sticky Notes pastebin
* @ver 0.2
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Define constants
define('IN_ADMIN', true);

// Invoke required files
include_once('../init.php');

// Collect some data
$mode = $core->variable('mode', 'dashboard');
$sid = $core->variable('session_id_admin', '', true);
$username = $core->variable('username_admin', '', true);

// Check if session cookie is set
if (empty($sid) || empty($username))
{
    $core->redirect($core->path() . 'login.php');
}
else
{
    // Process the username
    $db->escape($username);   
    $username = trim($username);
    
    // Validate sid
    $sql = "SELECT sid FROM {$db->prefix}users " .
           "WHERE username='{$username}'";
    $row = $db->query($sql, true);
    
    if ($sid == $row['sid'])
    {
        // Set expiry to 30 minutes from now
        $expire = time() + (60 * 30);
        $core->set_cookie('session_id_admin', $sid, $expire);
        $core->set_cookie('username_admin', $username, $expire);
    }
    else
    {
        // Unset the cookie and serve the login screen
        $core->unset_cookie('session_id_admin');
        $core->unset_cookie('username_admin');
        $core->redirect($core->path() . 'login.php');
    }
}

// Initialize the skin
$skin->init('tpl_index');

// Validate the mode
$module->validate($mode);

// Invoke the active module
$module->load($mode);

// Build page data
$toplink = preg_replace('/\_\_sitename\_\_/', $config->site_title, $lang->get('back_to_home'));
$welcome_text = preg_replace('/\_\_user\_\_/', $username, $lang->get('welcome_user'));

$skin->assign(array(
    'top_link'          => $toplink,
    'welcome_text'      => $welcome_text,
    'module_title'      => $module_title,
    'module_data'       => $module_data,
    
    'home_url'          => $core->root_path(),
    'dashboard_url'     => $core->path(),
    'pastes_url'        => $core->path() . '?mode=pastes',
    'users_url'         => $core->path() . '?mode=users',
    'ipbans_url'        => $core->path() . '?mode=ipbans',
    'config_url'        => $core->path() . '?mode=config',
    'logout_url'        => $core->path() . '?mode=logout',
    
    'dashboard_class'   => ($mode == "dashboard" ? 'nav_selected' : 'nav_unselected'),
    'pastes_class'      => ($mode == "pastes" ? 'nav_selected' : 'nav_unselected'),
    'users_class'       => ($mode == "users" ? 'nav_selected' : 'nav_unselected'),
    'ipbans_class'      => ($mode == "ipbans" ? 'nav_selected' : 'nav_unselected'),
    'config_class'      => ($mode == "config" ? 'nav_selected' : 'nav_unselected'),
)); 

// Output the page
$skin->title($module_title . ' &bull; ' . $lang->get('site_title'));   
echo $skin->output(false, false, true);

?>