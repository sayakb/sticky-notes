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
$username = $core->variable('login_user', '');
$password = $core->variable('login_pass', '');
$logout = $core->variable('logout_do', '', true);
$submit = isset($_POST['login_submit']);
$reset = isset($_POST['reset_password']);

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
        $banner_text = preg_replace('/\_\_user\_\_/', htmlspecialchars($username), $lang->get('invalid_login'));
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

// Reset password
if ($reset)
{
    if ($config->auth_method == 'db')
    {
        $data = $auth->reset($username);

        if ($data !== false)
        {
            // Send the notification mail
            $email->assign(array(
                'user'      => $data['user'],
                'pass'      => $data['pass'],
                'host'      => $core->current_uri(),
                'login_url' => $core->full_uri(),
            ));

            $email->send($data['email'], $lang->get('pass_reset'), 'pass_reset');

            // Show an appropriate message
            $banner_type = 'notice';
            $banner_visibility = 'visible';
            $banner_text = $lang->get('email_sent');

            // Hide the reset options now
            $reset = false;
        }
        else
        {
            $banner_type = 'error';
            $banner_visibility = 'visible';
            $banner_text = $lang->get('reset_404');
        }
    }
    else
    {
        $banner_type = 'error';
        $banner_visibility = 'visible';
        $banner_text = $lang->get('reset_ldap');
    }
}

// Build page data
$toplink = preg_replace('/\_\_sitename\_\_/', $config->site_title, $lang->get('back_to_home'));

$skin->assign(array(
    'top_link'          => $toplink,
    'home_url'          => $core->root_uri(),
    'banner_type'       => $banner_type,
    'banner_text'       => $banner_text,
    'banner_visibility' => $banner_visibility,
    'login_visibility'  => $skin->visibility($reset, true),
    'reset_visibility'  => $skin->visibility($reset),
));

// Output the page
$skin->title($lang->get('admin_login') . ' &bull; ' . $lang->get('site_title'));
echo $skin->output(false, false, true);

?>