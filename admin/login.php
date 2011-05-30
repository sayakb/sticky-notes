<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Invoke required files
include_once('../init.php');

// Collect some data
$username = $core->variable('login_user', '');
$password = $core->variable('login_pass', '');
$submit = isset($_POST['login_submit']) ? true : false;
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
    $sql = "SELECT * FROM paste_users " .
           "WHERE username='{$username}'";
    $row = $db->query($sql, true);
    
    if ($row != null)
    {
        $hash = sha1($password . $row['salt']);
        
        if ($row['password'] == $hash)
        {
            $sid = sha1(time() . $core->remote_ip());
            $expire = time() + (60 * 30);
            $core->set_cookie('session_id_admin', $sid, $expire);
            $core->set_cookie('username_admin', $username, $expire);
            $core->redirect('../');
        }
        else
        {
            $banner_type = 'error';
            $banner_visibility = 'visible';
            $banner_text = $lang->get('invalid_login');
        }
    }
    else
    {
        $banner_type = 'error';
        $banner_visibility = 'visible';
        $banner_text = $lang->get('invalid_login');
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
$toplink = preg_replace('/\_\_sitename\_\_/', $site_title, $lang->get('back_to_home'));

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