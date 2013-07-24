<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
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
    $core->redirect($core->current_uri() . 'login.php');
}
else
{
    // Validate session ID
    $sql = "SELECT sid FROM {$db->prefix}users " .
           "WHERE username = :username AND sid = :sid";

    $row = $db->query($sql, array(
        ':username' => $username,
        ':sid'      => $sid
    ), true);

    if ($row != null)
    {
        $core->set_cookie('session_id_admin', $sid);
        $core->set_cookie('username_admin', $username);
    }
    else
    {
        // Unset the cookie and serve the login screen
        $core->unset_cookie('session_id_admin');
        $core->unset_cookie('username_admin');
        $core->redirect($core->current_uri() . 'login.php');
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

    'home_url'          => $core->root_uri(),
    'dashboard_url'     => $core->current_uri(),
    'pastes_url'        => $core->current_uri() . '?mode=pastes',
    'users_url'         => $core->current_uri() . '?mode=users',
    'ipbans_url'        => $core->current_uri() . '?mode=ipbans',
    'email_url'         => $core->current_uri() . '?mode=email',
    'auth_url'          => $core->current_uri() . '?mode=auth',
    'config_url'        => $core->current_uri() . '?mode=config',
    'logout_url'        => $core->current_uri() . '?mode=logout',

    'dashboard_class'   => ($mode == "dashboard" ? 'nav_selected' : 'nav_unselected'),
    'pastes_class'      => ($mode == "pastes" ? 'nav_selected' : 'nav_unselected'),
    'users_class'       => ($mode == "users" ? 'nav_selected' : 'nav_unselected'),
    'ipbans_class'      => ($mode == "ipbans" ? 'nav_selected' : 'nav_unselected'),
    'email_class'       => ($mode == "email" ? 'nav_selected' : 'nav_unselected'),
    'auth_class'        => ($mode == "auth" ? 'nav_selected' : 'nav_unselected'),
    'config_class'      => ($mode == "config" ? 'nav_selected' : 'nav_unselected'),
));

// Output the page
$skin->title($module_title . ' &bull; ' . $lang->get('site_title'));
echo $skin->output(false, false, true);

?>