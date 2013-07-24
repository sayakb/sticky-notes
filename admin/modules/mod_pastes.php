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
$paste_id = $core->variable('paste_id', '');
$paste_search = isset($_POST['paste_search']);
$paste_rempass = isset($_POST['paste_rempass']);
$paste_makepub = isset($_POST['paste_makepub']);
$paste_delete = isset($_POST['paste_delete']);

// Set globals
$paste_id_orig = $paste_id;
$spacer_visibility = 'visible';
$detail_visibility = 'collapsed';
$script_notification = '';
$sql_where = '';
$params = array();

// Get the sql where claud based on the type of paste id
if ($paste_id)
{
    if ($config->url_key_enabled && strtolower(substr($paste_id, 0, 1)) == 'p')
    {
        $paste_id = substr($paste_id, 1);
        $sql_where = "WHERE urlkey = :paste_id";
    }
    else if (is_numeric($paste_id))
    {
        $paste_id = intval($paste_id);
        $sql_where = "WHERE id = :paste_id";
    }

    $params = array(':paste_id' => trim($paste_id));
}

// Make public
if ($paste_makepub && $paste_id)
{
    $sql = "UPDATE {$db->prefix}main SET private=0, password='' " . $sql_where;
    $db->query($sql, $params);

    $module->notify($lang->get('made_public'));

    $skin->assign(array(
        'rempass_visibility'    => 'collapsed',
        'makepub_visibility'    => 'collapsed',
    ));
}

// Remove password
if ($paste_rempass && $paste_id)
{
    $sql = "UPDATE {$db->prefix}main SET password='' " . $sql_where;
    $db->query($sql, $params);

    $module->notify($lang->get('pass_removed'));

    $skin->assign(array(
        'rempass_visibility'    => 'collapsed',
    ));
}

// Search form submitted
if ($paste_search || $paste_rempass || $paste_makepub)
{
    $sql = "SELECT * FROM {$db->prefix}main " . $sql_where;
    $row = $db->query($sql, $params, true);

    if ($row != null)
    {
        $spacer_visibility = 'collapsed';
        $detail_visibility = 'visible';

        $skin->assign(array(
            'paste_author'          => (empty($row['author']) ? $lang->get('anonymous') : htmlentities($row['author'])),
            'paste_time'            => date('d M Y, H:i:s e', $row['timestamp']),
            'paste_expire'          => date('d M Y, H:i:s e', $row['expire']),
            'paste_data'            => htmlentities(substr($row['data'], 0, 100)) . '&hellip;',
            'paste_private'         => ($row['private'] == 1 ? $lang->get('yes') : $lang->get('no')),
            'paste_haspass'         => (!empty($row['password']) ? $lang->get('yes') : $lang->get('no')),
            'paste_ip'              => $row['ip'],
            'rempass_visibility'    => (!empty($row['password']) ? 'visible' : 'collapsed'),
            'makepub_visibility'    => ($row['private'] == 1 ? 'visible' : 'collapsed'),
        ));
    }
    else if ($paste_id)
    {
        $module->notify($lang->get('paste_id_404'));
    }
}

// Delete paste
if ($paste_delete)
{
    $sql = "DELETE FROM {$db->prefix}main " . $sql_where;
    $db->query($sql, $params);

    $paste_id = '';
    $paste_id_orig = '';
    $module->notify($lang->get('paste_deleted'));
}

// Set the module variables
$skin->assign(array(
    'spacer_visibility'   => $spacer_visibility,
    'detail_visibility'   => $detail_visibility,
    'paste_id'            => !empty($paste_id_orig) ? $paste_id_orig : '',
));

// Set the module data
$module_title = $lang->get('manage_pastes');
$module_data =  $skin->output('tpl_pastes', false, true);

?>
