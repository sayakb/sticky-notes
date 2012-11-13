<?php
/**
* Sticky Notes pastebin
* @ver 0.3
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Invoke required files
include_once('init.php');

// Collect some data
$paste_id = $core->variable('id', 0);
$hash = $core->variable('hash', 0);
$mode = $core->variable('mode', '');
$project = $core->variable('project', '');
$password = $core->variable('password', '');
$sid = $core->variable('session_id_' . $paste_id, '', true);
$mode = strtolower($mode);

// Password exempt
$exempt = false;

// Trim trailing /
if (strrpos($password, '/') == strlen($password) - 1)
{
    $password = substr($password, 0, strlen($password) - 1);
}

if (empty($mode))
{
    $mode = $core->variable('format', '');
    $_GET['mode'] = $mode;
}

// Check for mode validity
if ($mode && $mode != 'raw' && $mode != 'xml' && $mode != 'json')
{
    die;
}

// Initialize the skin file
if ($mode != 'raw')
{
    $skin->init('tpl_show');
}

// We want paste id
if ($paste_id == 0)
{
    $core->redirect($core->path() . 'all/');
}

// Escape the paste id
$db->escape($paste_id);

// Get the paste data
$sql = "SELECT * FROM {$db->prefix}main WHERE id = {$paste_id} LIMIT 1";
$row = $db->query($sql, true);

// Check if something was returned
if ($row == null)
{
    if ($mode == 'xml' || $mode == 'json')
    {
        $skin->assign('error_message', 'err_not_found');
        echo $skin->output("api_error.{$mode}");
        die;
    }
    else if ($mode == 'raw')
    {
        die($lang->get('error_404'));
    }
    else
    {
        $skin->assign(array(
            'error_text'        => $lang->get('error_404'),
            'data_visibility'    => 'hidden',
        ));

        $skin->kill();
    }
}

// Is it a private paste?
if ($row['private'] == "1")
{
    if (empty($hash) || $row['hash'] != $hash)
    {
        if ($mode == 'xml' || $mode == 'json')
        {
            $skin->assign('error_message', 'err_invalid_hash');
            echo $skin->output("api_error.{$mode}");
            die;
        }
        else if ($mode == 'raw')
        {
            die($lang->get('error_hash'));
        }
        else
        {
            $skin->assign(array(
                'error_text'        => $lang->get('error_hash'),
                'data_visibility'   => 'hidden',
            ));

            $skin->kill();
        }
    }
}

// Check if password cookie is there
if (!empty($row['password']) && !empty($sid))
{
    // Escape the session id
    $db->escape($sid);
    
    // Clean up the session data every 30 seconds
    if (time() % 30 == 0)
    {
        $age = time() - 1200;
        $db->query("DELETE FROM {$db->prefix}session " .
                   "WHERE timestamp < {$age}");
    }

    $pass_data = $db->query("SELECT sid FROM {$db->prefix}session " .
                            "WHERE sid = '{$sid}'", true);

    if (!empty($pass_data['sid']))
    {
        $exempt = true;
    }
}

// Is it password protected?
if (!empty($row['password']) && empty($password) && !$exempt)
{
    if ($mode == 'xml' || $mode == 'json')
    {
        $skin->assign('error_message', 'err_password_required');
        echo $skin->output("api_error.{$mode}");
        die;
    }
    else if ($mode == 'raw')
    {
        die($lang->get('err_passreqd'));
    }
    else
    {
        $skin->init('tpl_show_password');
        $skin->title("#{$row['id']} &bull; " . $lang->get('site_title'));
        $skin->output();

        exit;
    }
}

// Check password
if (!empty($row['password']) && !empty($password) && !$exempt)
{
    $check = sha1(sha1($password) . $row['salt']);

    if ($check != $row['password'])
    {
        if ($mode == 'xml' || $mode == 'json')
        {
            $skin->assign('error_message', 'err_invalid_password');
            echo $skin->output("api_error.{$mode}");
            die;
        }
        else if ($mode == 'raw')
        {
            die($lang->get('invalid_password'));
        }
        else
        {
            $skin->assign(array(
                'error_text'        => $lang->get('invalid_password'),
                'data_visibility'    => 'hidden',
            ));

            $skin->kill();
        }
    }
    else
    {
        // Create a session
        $sid = sha1(time() . $core->remote_ip());

        $core->set_cookie('session_id_' . $paste_id, $sid);
        $db->query("INSERT INTO {$db->prefix}session " .
                   "(sid, timestamp) VALUES ('{$sid}', " . time() . ")");
    }
}

// Is it raw? just dump the code then
if ($mode == 'raw')
{
    header('Content-type: text/plain; charset=UTF-8');
    header('Content-Disposition: inline; filename="pastedata"');
    
    echo $row['data'];
    exit;
}

// Prepare GeSHi
$geshi = new GeSHi($row['data'], $row['language']);
$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
$geshi->set_header_type(GESHI_HEADER_DIV);
$geshi->set_line_style('background: #f7f7f7; text-shadow: 0px 1px #fff; padding: 1px;',
                       'background: #fbfbfb; text-shadow: 0px 1px #fff; padding: 1px;');
$geshi->set_overall_style('word-wrap:break-word;');

// Generate the data
$user = empty($row['author']) ? $lang->get('anonymous') : htmlspecialchars($row['author']);
$time = date('d M Y, h:i:s e', $row['timestamp']);
$info = $lang->get('posted_info');

$info = preg_replace('/\_\_user\_\_/', $user, $info);
$info = preg_replace('/\_\_time\_\_/', $time, $info);

// Before we display, we need to escape the data from the skin/lang parsers
$code_data = (empty($mode) ? $geshi->parse_code() : htmlspecialchars($row['data']));

$lang->escape($code_data);
$skin->escape($code_data);

// Assign template variables
$skin->assign(array(
    'paste_id'          => $row['id'],
    'paste_data'        => $code_data,
    'paste_lang'        => htmlspecialchars($row['language']),
    'paste_info'        => $info,
    'paste_user'        => $user,
    'paste_timestamp'   => $row['timestamp'],
    'raw_url'           => $nav->get_paste($row['id'], $hash, $project, false, 'raw'),
    'share_url'         => urlencode($core->base_uri()),
    'share_title'       => urlencode($lang->get('paste') . ' #' . $row['id']),
    'error_visibility'  => 'hidden',
    'geshi_stylesheet'  => $geshi->get_stylesheet(),
));

// Let's output the page now
$skin->title("#{$row['id']} &bull; " . $lang->get('site_title'));

if ($mode == 'raw')
{
    $skin->output(false, true);
}
else if ($mode)
{
    echo $skin->output("api_show.{$mode}");
}
else
{
    $skin->output();
}

?>
