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
$ban_ip = $core->variable('ipban_ip', '');
$ban_del = $core->variable('delete', '');

$ban_submit = isset($_POST['ipban_submit']) ? true : false;

// Delete IP from banned list
if (!empty($ban_del))
{
    $ban_del = urldecode($ban_del);
    $db->escape($ban_del);
    
    $sql = "DELETE FROM {$db->prefix}ipbans WHERE ip='{$ban_del}'";
    $db->query($sql);
    
    $core->redirect($core->path() . 'ipbans/');
}

// Get a list of IP bans
$sql = "SELECT ip FROM {$db->prefix}ipbans";
$rows = $db->query($sql);

// IP ban submitted
if ($ban_submit && !empty($ban_ip))
{
    // Check if IP is already banned
    $already_banned = false;
    
    if (!empty($rows))
    {
        foreach ($rows as $row)
        {
            if ($row['ip'] == $ban_ip)
            {
                $already_banned = true;
            }
        }
    }
    
    // Validate the IP address
    if(!filter_var($ban_ip, FILTER_VALIDATE_IP))
    {
        $module->notify($lang->get('invalid_ip'));
        $skin->assign('ipban_ip', $ban_ip);
    }
    else
    {
        // Process the ban
        if (!$already_banned)
        {
            $db_escape($ban_ip);

            $sql = "INSERT INTO {$db->prefix}ipbans (ip) " .
                "VALUES ('{$ban_ip}')";
            $db->query($sql);
            
            $module->notify($lang->get('banned_success'));
            
            // Get updated list of IP bans
            $sql = "SELECT ip FROM {$db->prefix}ipbans";
            $rows = $db->query($sql);
        }
        else
        {
            $module->notify($lang->get('already_banned'));
            $skin->assign('ipban_ip', $ban_ip);
        }
    }
}

// Display the list of banned IPs
$ipbans_data = '';

if (!empty($rows))
{
    foreach ($rows as $row)   
    {
        $skin->assign(array(
            'ipbans_entry'      => $row['ip'],
            'ipbans_delete'     => '<a href="' . ($core->path() . 'ipbans/delete/' . urlencode($row['ip'])) . 
                                   '" onclick="return confirm(\'' . $lang->get('action_confirm') . '\')"><b>' .
                                   $lang->get('delete') . '</b></a>',
        ));
        $ipbans_data .= $skin->output('tpl_ipbans_entry', true, true);
    }
}
else
{
    $skin->assign('ipbans_entry', '<i>' . $lang->get('no_ips') . '</i>');
    $ipbans_data = $skin->output('tpl_ipbans_entry', true, true);
}

// Assign skin data
$skin->assign('ipbans_data', $ipbans_data);

// Set the module data
$module_title = $lang->get('manage_ip_bans');
$module_data =  $skin->output('tpl_ipbans_main', true, true);

?>
 
