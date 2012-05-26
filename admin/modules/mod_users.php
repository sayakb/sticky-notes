<?php
/**
* Sticky Notes pastebin
* @ver 0.2
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
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

$user_new = isset($_POST['user_new']) ? true : false;
$user_save = isset($_POST['user_save']) ? true : false;
$user_cancel = isset($_POST['user_cancel']) ? true : false;

// Validate action
$actions_ary = array('editor', 'delete');

if (!empty($action) && !in_array($action, $actions_ary))
{
    $core->redirect($core->path() . '?mode=users');
}

// Create button was pressed
if ($user_new)
{
    $core->redirect($core->path() . '?mode=users&action=editor');
}

// Cancel button was pressed
if ($user_cancel)
{
    $core->redirect($core->path() . '?mode=users');
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
    
    // Escape data
    $db->escape($user_id);
    $db->escape($user_username);
    $db->escape($user_email);
    $db->escape($user_fname);
    $db->escape($user_lname);
    
    $disp_name = $user_fname . ' ' . $user_lname;

    $sql = "SELECT username, email FROM {$db->prefix}users " .
           "WHERE (username = '{$user_username}' OR email = '{$user_email}')" .
           ($user_id > 0 ? " AND id <> {$user_id}" : "");
    $row = $db->query($sql, true);
    
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
                       "WHERE id = {$user_id}";
                $row = $db->query($sql, true);
                
                // Generate password hash
                $hash = sha1($user_pass1 . $row['salt']);
            }
            
            $sql = "UPDATE {$db->prefix}users " .
                   "SET username='{$user_username}', " .
                   "    email='{$user_email}', " .
                   "    dispname='{$disp_name}' " .
                   (!empty($user_pass1) ? ", password = '{$hash}' " : "") .
                   "WHERE id = {$user_id}";
            $db->query($sql);
            
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
            $salt = substr(sha1(time()), rand(0, 34), 5);
            $hash = sha1($user_pass1 . $salt);
            
            $sql = "INSERT INTO {$db->prefix}users " .
                   "(username, password, salt, email, dispname) " .
                   "VALUES ('{$user_username}', '{$hash}', '{$salt}', " .
                   "        '{$user_email}', '{$disp_name}')";
            $db->query($sql);
        }

        $core->redirect($core->path() . '?mode=users');
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
    // Set globals
    $module_name = 'tpl_users_list';
    $user_list = '';
    
    $sql = "SELECT * FROM {$db->prefix}users ORDER BY username ASC";
    $rows = $db->query($sql);
    
    foreach ($rows as $row)
    {       
        $skin->assign(array(
            'user_username'         => $row['username'],
            'user_name'             => htmlentities($row['dispname']),
            'user_email'            => $row['email'],
            'user_email_hash'       => md5(strtolower($row['email'])),
            'user_edit_link'        => $core->path() . '?mode=users&action=editor&user=' . $row['username'],
            'user_delete_link'      => $core->path() . '?mode=users&action=delete&user=' . $row['username'],
            'delete_visibility'     => $row['username'] == $username ? 'hidden' : '',
        ));
        
        $user_list .= $skin->output('tpl_users_entry', true, true);
    }
    
    $skin->assign('user_list', $user_list);
}

// Edit/new action specified
if ($action == 'editor' && !$user_save)
{
    // Set globals
    $module_name = 'tpl_users_editor';
    
    // In edit mode, load data
    if (!empty($user))
    {
        // Escape the username
        $db->escape($user);
        
        $sql = "SELECT * FROM {$db->prefix}users " .
               "WHERE username = '{$user}'";
        $row = $db->query($sql, true);
        
        if (!empty($row['dispname']))
        {
            $user_name_ary = explode(' ', $row['dispname']);
            $user_fname = $user_name_ary[0];
            $user_lname = $user_name_ary[1];
        }
        
        $skin->assign(array(
            'user_username'     => $row['username'],
            'user_email'        => $row['email'],
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
        // Escape the username
        $db->escape($user);
        
        $sql = "DELETE FROM {$db->prefix}users WHERE username = '{$user}'";
        $db->query($sql);
        $core->redirect($core->path() . '?mode=users');
    }
}

// Set the page title
$module_title = $lang->get('manage_users');
$module_data =  $skin->output($module_name, false, true);

?>
 
