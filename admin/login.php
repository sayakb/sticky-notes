<?php
/**
* Sticky Notes pastebin
* @ver 0.3
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
$username = $core->variable('login_user', '');
$password = $core->variable('login_pass', '');
$submit = isset($_POST['login_submit']);
$logout = $core->variable('logout_do', '', true);

// Global variables
$banner_type = 'error';
$banner_visibility = 'hidden';
$banner_text = '&nbsp;';

// Initialize the skin file
$skin->init('tpl_login');

// Process form data
if ($submit && !empty($username) && !empty($password))
{    
    // Authenticate the user
    $login_status = $auth->login($username, $password);

    if ($login_status)
    {
        $core->set_cookie('session_id_admin', $auth->sid);
        $core->set_cookie('username_admin', $username);
        $core->redirect('index.php');
    }
    else
    {
        $banner_type = 'error';
        $banner_visibility = 'visible';
        $banner_text = preg_replace('/\_\_user\_\_/', $username, $lang->get('invalid_login'));
    }
}

// Do we want a logout message?
if (!empty($logout))
{
    $core->unset_cookie('logout_do');
    $banner_type = 'notice';
    $banner_visibility = 'visible';
    $banner_text = $lang->get('logged_out');
}

// Build page data
$toplink = preg_replace('/\_\_sitename\_\_/', $config->site_title, $lang->get('back_to_home'));

$skin->assign(array(
    'top_link'          => $toplink,
    'home_url'          => $core->root_path(),
    'banner_type'       => $banner_type,
    'banner_visibility' => $banner_visibility,
    'banner_text'       => $banner_text,
));

// Output the page
$skin->title($lang->get('admin_login') . ' &bull; ' . $lang->get('site_title'));
echo $skin->output(false, false, true);

?>