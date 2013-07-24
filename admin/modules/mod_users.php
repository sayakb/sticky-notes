<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Collect some data
$action = $core->variable('action', '');
$user = $core->variable('user', '');
$user_id = $core->variable('user_id', 0);
$user_username = $core->variable('user_username', '');
$user_email = $core->variable('user_email', '');
$user_fname = $core->variable('user_fname', '');
$user_lname = $core->variable('user_lname', '');
$user_pass1 = $core->variable('user_pass1', '');
$user_pass2 = $core->variable('user_pass2', '');

$user_new = isset($_POST['user_new']);
$user_save = isset($_POST['user_save']);
$user_cancel = isset($_POST['user_cancel']);

// Validate action
$actions_ary = array('editor', 'delete');

if (!empty($action) && !in_array($action, $actions_ary))
{
    $core->redirect($core->current_uri() . '?mode=users');
}

// Create button was pressed
if ($user_new)
{
    $core->redirect($core->current_uri() . '?mode=users&action=editor');
}

// Cancel button was pressed
if ($user_cancel)
{
    $core->redirect($core->current_uri() . '?mode=users');
}

// Save button was pressed
if ($user_save)
{
    // Set globals
    $module_name = 'tpl_users_editor';
    $validation_failed = false;

    // Perform field validation
    if (empty($user_username))
    {
        $module->notify($lang->get('username_required'));
        $validation_failed = true;
    }

    else if (!preg_match('/^[A-Za-z0-9\.]+$/', $user_username))
    {
        $module->notify($lang->get('username_invalid'));
        $validation_failed = true;
    }

    else if (!preg_match('/^[A-Za-z][A-Za-z0-9]*(?:\.[A-Za-z0-9]+)*$/', $user_username))
    {
        $module->notify($lang->get('username_startchar'));
        $validation_failed = true;
    }

    else if (empty($user_email))
    {
        $module->notify($lang->get('email_required'));
        $validation_failed = true;
    }

    else if (!filter_var($user_email, FILTER_VALIDATE_EMAIL))
    {
        $module->notify($lang->get('email_invalid'));
        $validation_failed = true;
    }

    else if (!empty($user_lname) && empty($user_fname))
    {
        $module->notify($lang->get('fname_required'));
        $validation_failed = true;
    }

    else if ((empty($user_pass1) || empty($user_pass2)) && $user_id == 0)
    {
        $module->notify($lang->get('password_required'));
        $validation_failed = true;
    }

    else if ($user_pass1 != $user_pass2)
    {
        $module->notify($lang->get('password_dontmatch'));
        $validation_failed = true;
    }

    // Preserve view state
    $skin->assign(array(
        'user_username'     => $user_username,
        'user_email'        => $user_email,
        'user_fname'        => $user_fname,
        'user_lname'        => $user_lname,
        'user_id'           => $user_id,
        'user_pass1'        => $user_pass1,
        'user_pass2'        => $user_pass2,
    ));

    $disp_name = $user_fname . ' ' . $user_lname;

    $sql = "SELECT username, email FROM {$db->prefix}users " .
           "WHERE (username = :username OR email = :email) " .
           "AND password <> '' ";

    $params = array(
        ':username' => $user_username,
        ':email'    => $user_email,
    );

    if ($user_id > 0)
    {
        $sql .= 'AND id <> :id';
        $params[':id'] = $user_id;
    }

    $row = $db->query($sql, $params, true);

    if ($row != null)
    {
        $validation_failed = true;

        if ($row['username'] == $user_username)
        {
            $module->notify($lang->get('username_taken'));
        }

        else if ($row['email'] == $user_email)
        {
            $module->notify($lang->get('email_taken'));
        }
    }

    // Let's update the data!
    if (!$validation_failed)
    {
        // It is an update operation
        if ($user_id > 0)
        {
            if (!empty($user_pass1))
            {
                // Get the salt
                $sql = "SELECT salt FROM {$db->prefix}users " .
                       "WHERE id = :id";

                $row = $db->query($sql, array(
                    ':id' => $user_id
                ), true);

                // Generate password hash
                $hash = $auth->create_password($user_pass1, $row['salt']);
            }

            $sql = "UPDATE {$db->prefix}users " .
                   "SET username = :username, " .
                   "    email = :email, " .
                   "    dispname = :dispname " .
                   (!empty($user_pass1) ? ", password = :password " : "") .
                   "WHERE id = :id";

            $params = array(
                ':username' => $user_username,
                ':email'    => $user_email,
                ':dispname' => $disp_name,
                ':id'       => $user_id
            );

            if (!empty($user_pass1))
            {
                $params[':password'] = $hash;
            }

            $db->query($sql, $params);

            // Update the username cookie if user updates own details
            $current_user = $core->variable('username_admin', '', true);

            if ($current_user == $user)
            {
                $expire = time() + (60 * 30);
                $core->set_cookie('username_admin', $user_username, $expire);
            }
        }

        // It is an insert operation
        else
        {
            $salt = $auth->create_uid(5);
            $hash = $auth->create_password($user_pass1, $salt);

            $sql = "INSERT INTO {$db->prefix}users " .
                   "(username, password, salt, email, dispname) " .
                   "VALUES (:username, :password, :salt, :email, :dispname)";

            $db->query($sql, array(
                ':username' => $user_username,
                ':password' => $hash,
                ':salt'     => $salt,
                ':email'    => $user_email,
                ':dispname' => $disp_name
            ));
        }

        $core->redirect($core->current_uri() . '?mode=users');
    }

    // Set editor title
    $skin->assign(array(
        'editor_title'      => (empty($user) ? $lang->get('create_user') : $lang->get('edit_user')),
        'preq_visibility'   => (empty($user) ? 'visible' : 'collapsed'),
    ));
}

// No action specified, so show a user list
if (empty($action))
{
    if ($config->auth_method == 'db')
    {
        // Set globals
        $module_name = 'tpl_users_list';
        $user_list = '';

        // We want those users that were created for DB auth only
        // LDAP users will not have a password
        $sql = "SELECT * FROM {$db->prefix}users " .
               "WHERE password <> '' " .
               "ORDER BY username ASC";
        $rows = $db->query($sql);

        foreach ($rows as $row)
        {
            $skin->assign(array(
                'user_username'         => htmlspecialchars($row['username']),
                'user_name'             => htmlspecialchars($row['dispname']),
                'user_email'            => htmlspecialchars($row['email']),
                'user_email_hash'       => md5(strtolower($row['email'])),
                'user_edit_link'        => $core->current_uri() . '?mode=users&action=editor&user=' . $row['username'],
                'user_delete_link'      => $core->current_uri() . '?mode=users&action=delete&user=' . $row['username'],
                'delete_visibility'     => $row['username'] == $username ? 'hidden' : '',
            ));

            $user_list .= $skin->output('tpl_users_entry', true, true);
        }

        $skin->assign('user_list', $user_list);
    }
    else
    {
        $module_name = 'tpl_users_ldap';
    }
}

// Edit/new action specified
if ($action == 'editor' && !$user_save)
{
    // Set globals
    $module_name = 'tpl_users_editor';

    // In edit mode, load data
    if (!empty($user))
    {
        $sql = "SELECT * FROM {$db->prefix}users " .
               "WHERE username = :username " .
               "AND password <> ''";

        $row = $db->query($sql, array(
            ':username' => $user
        ), true);

        if (!empty($row['dispname']))
        {
            $user_name_ary = explode(' ', $row['dispname']);
            $user_fname = $user_name_ary[0];
            $user_lname = $user_name_ary[1];
        }

        $skin->assign(array(
            'user_username'     => htmlspecialchars($row['username']),
            'user_email'        => htmlspecialchars($row['email']),
            'user_fname'        => (!empty($row['dispname']) ? $user_fname : ''),
            'user_lname'        => (!empty($row['dispname']) ? $user_lname : ''),
            'user_id'           => $row['id'],
        ));
    }

    // Set editor title
    $skin->assign(array(
        'editor_title'      => (empty($user) ? $lang->get('create_user') : $lang->get('edit_user')),
        'preq_visibility'   => (empty($user) ? 'visible' : 'collapsed'),
    ));
}

// Delete user action specified
if ($action == 'delete')
{
    if ($user != $username)
    {
        $sql = "DELETE FROM {$db->prefix}users " .
               "WHERE username = :username " .
               "AND password <> ''";

        $db->query($sql, array(
            ':username' => $user
        ));

        $core->redirect($core->current_uri() . '?mode=users');
    }
}

// Set the page title
$module_title = $lang->get('manage_users');
$module_data =  $skin->output($module_name, false, true);

?>

