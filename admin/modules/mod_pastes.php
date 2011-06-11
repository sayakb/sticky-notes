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
$paste_id = $core->variable('paste_id', 0);
$paste_id_searched = $core->variable('paste_id_searched', 0);

$paste_search = isset($_POST['paste_search']) ? true : false;
$paste_rempass = isset($_POST['paste_rempass']) ? true : false;
$paste_makepub = isset($_POST['paste_makepub']) ? true : false;
$paste_delete = isset($_POST['paste_delete']) ? true : false;

// Set globals
$spacer_visibility = 'visible';
$detail_visibility = 'collapsed';
$script_notification = '';

if (!$paste_search)
{
    $paste_id = $paste_id_searched;
}

// Make public
if ($paste_makepub)
{  
    $sql = "UPDATE {$db->prefix}main SET private=0, password='' " .
           "WHERE id={$paste_id}";
    $db->query($sql);
    
    $module->notify($lang->get('made_public'));
    
    $skin->assign(array(
        'rempass_visibility'    => 'collapsed',
        'makepub_visibility'    => 'collapsed',
    ));    
}

// Remove password
if ($paste_rempass)
{   
    $sql = "UPDATE {$db->prefix}main SET password='' " .
           "WHERE id={$paste_id}";
    $db->query($sql);
    
    $module->notify($lang->get('pass_removed'));
   
    $skin->assign(array(
        'rempass_visibility'    => 'collapsed',
    ));    
}

// Search form submitted
if ($paste_search || $paste_rempass || $paste_makepub)
{
    $paste_id = trim($paste_id);
    $sql = "SELECT * FROM {$db->prefix}main " .
           "WHERE id={$paste_id}";
    $row = $db->query($sql, true);
    
    if ($row != null)
    {
        $spacer_visibility = 'collapsed';
        $detail_visibility = 'visible';
        
        $skin->assign(array(
            'paste_author'          => (empty($row['author']) ? $lang->get('anonymous') : $row['author']),
            'paste_time'            => date('d M Y, h:i:s e', $row['timestamp']),
            'paste_expire'          => date('d M Y, h:i:s e', $row['expire']),
            'paste_data'            => substr($row['data'], 0, 100) . '&hellip;',
            'paste_private'         => ($row['private'] == 1 ? $lang->get('yes') : $lang->get('no')),
            'paste_haspass'         => (!empty($row['password']) ? $lang->get('yes') : $lang->get('no')),
            'paste_ip'              => $row['ip'],
            'rempass_visibility'    => (!empty($row['password']) ? 'visible' : 'collapsed'),
            'makepub_visibility'    => ($row['private'] == 1 ? 'visible' : 'collapsed'),
        ));
    }
    else if ($paste_id > 0)
    {
        $module->notify($lang->get('paste_id_404'));
    }    
}

// Delete paste
if ($paste_delete)
{
    $sql = "DELETE FROM {$db->prefix}main " .
           "WHERE id={$paste_id}";
    $db->query($sql);
            
    $paste_id = 0;
    $module->notify($lang->get('paste_deleted'));
}

// Set the module variables
$skin->assign(array(
    'spacer_visibility'         => $spacer_visibility,   
    'detail_visibility'         => $detail_visibility,
    'paste_id'                  => ($paste_id > 0 ? $paste_id : ''),
));

// Set the module data
$module_title = $lang->get('manage_pastes');
$module_data =  $skin->output('tpl_pastes', false, true);

?>
