<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Invoke required files
include_once('init.php');

// Collect some data
$paste_id = $core->variable('id', '');
$hash = $core->variable('hash', 0);
$mode = $core->variable('mode', '');
$project = $core->variable('project', '');
$password = $core->variable('password', '');
$mode = strtolower($mode);
$exempt = false;
$is_key = false;

// Trim trailing /
if (strrpos($password, '/') == strlen($password) - 1)
{
    $password = substr($password, 0, strlen($password) - 1);
}

// Set the view mode
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

// Prepare the paste ID for use
if (!empty($paste_id))
{
    if ($config->url_key_enabled && strtolower(substr($paste_id, 0, 1)) == 'p')
    {
        $paste_id = substr($paste_id, 1);
        $is_key = true;
    }
    else if (is_numeric($paste_id))
    {
        $paste_id = intval($paste_id);
        $is_key = false;
    }
    else
    {
        $paste_id = 0;
    }
}
else
{
    $core->redirect($core->current_uri() . 'all/');
}

// Set up session for the paste
$sid = $core->variable('session_id_' . $paste_id, '', true);

// Build the WHERE claus condition
$sql_col = $is_key ? 'urlkey' : 'id';
$sql_where = "{$sql_col} = :id";
$params = array(':id' => $paste_id);

$sql = "SELECT * FROM {$db->prefix}main WHERE {$sql_where} LIMIT 1";
$row = $db->query($sql, $params, true);

// If we queried using an ID, we show the paste only if there is no corresponding
// key in the DB. We skip this check if keys are disabled
if ($config->url_key_enabled && $row != null)
{
    if (!$is_key && !empty($row['urlkey']))
    {
        $row = null;
    }
}

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
    $sql = "SELECT sid FROM {$db->prefix}session " .
           "WHERE sid = :sid";

    $pass_data = $db->query($sql, array(
        ':sid' => $sid
    ), true);

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
    if ($auth->check_password('main', $row['password'], $password, $row['salt']))
    {
        // Create a session
        $sid = $auth->create_uid();

        $sql = "INSERT INTO {$db->prefix}session " .
               "(sid, timestamp) VALUES (:sid, :timestamp)";

        $db->query($sql, array(
            ':sid'          => $sid,
            ':timestamp'    => time()
        ));

        $core->set_cookie('session_id_' . $paste_id, $sid);
    }
    else
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
}

// Hit counter: check for legit hits
// We check if the current IP already created a hit before
// If they didn't, we increment the hit count for this paste
$hit_key = $paste_id . $core->remote_ip();
$hit_time = $cache->get($hit_key);

if ($hit_time === false)
{
    $sql = "UPDATE {$db->prefix}main SET hits = hits + 1 WHERE {$sql_where}";
    $db->query($sql, $params);

    if ($db->affected_rows == 1)
    {
        $cache->set($hit_key, time());
    }
}

// Is it raw mode? just dump the code then
if ($mode == 'raw')
{
    header('Content-type: text/plain; charset=UTF-8');
    header('Content-Disposition: inline; filename="pastedata"');

    echo $row['data'];
    exit;
}

// Syntax highlighting - only for web interfaces
if (empty($mode))
{
    // Check if the GeSHi output was cached
    $geshi_key = $row['data'] . $row['language'];
    $code_data = $cache->get($geshi_key . 'data');
    $code_style = $cache->get($geshi_key . 'style');

    if ($code_data === false || $code_style === false)
    {
        // Configure GeSHi
        $geshi = $skin->geshi($row['data'], $row['language']);

        // Run GeSHi
        $code_data = $geshi->parse_code();
        $code_style = $geshi->get_stylesheet(true);

        $cache->set($geshi_key . 'data', $code_data);
        $cache->set($geshi_key . 'style', $code_style);
    }
}
else
{
    $code_data = htmlspecialchars($row['data']);
    $code_style = '';
}

// Generate the data
$user = empty($row['author']) ? $lang->get('anonymous') : htmlspecialchars($row['author']);
$time = date('d M Y, H:i:s e', $row['timestamp']);
$info = $lang->get('posted_info');

$info = preg_replace('/\_\_user\_\_/', $user, $info);
$info = preg_replace('/\_\_time\_\_/', $time, $info);

// Before we display, we need to escape the data from the skin/lang parsers
$lang->escape($code_data);
$skin->escape($code_data);

// Nullify newlines in API output
if (!empty($mode) && $mode != 'raw')
{
    $code_data = preg_replace('/\\n|\\r\\n/', '\\\\n', $code_data);
}

$skin_key = $is_key ? 'p' . $paste_id : $paste_id;

// Save URL shortening language data to cookies
$core->set_cookie('short_get', $lang->get('short_get'), 365);
$core->set_cookie('short_generating', $lang->get('short_generating'), 365);
$core->set_cookie('short_error', $lang->get('short_error'), 365);

// Format the paste title
if (!empty($row['title']))
{
    $title = htmlspecialchars($row['title']);
}
else
{
    $title = $lang->get('paste') . " #{$skin_key}";
}

// Assign template variables
$skin->assign(array(
    'paste_id'           => $skin_key,
    'paste_title'        => $title,
    'paste_data'         => $code_data,
    'paste_lang'         => htmlspecialchars($row['language']),
    'paste_info'         => $info,
    'paste_user'         => $user,
    'paste_timestamp'    => $row['timestamp'],
    'raw_url'            => $nav->get_paste($row['id'], $row['urlkey'], $hash, $project, 'raw'),
    'share_url'          => urlencode($core->full_uri()),
    'share_title'        => urlencode($lang->get('paste') . ' #' . $skin_key),
    'error_visibility'   => 'hidden',
    'geshi_stylesheet'   => $code_style,
    'shorten_url'        => $core->current_uri() . "shorten.php?id={$skin_key}&project={$project}&hash={$hash}",
    'shorten_visibility' => $skin->visibility(empty($config->google_api_key), true),
));

// Let's output the page now
$skin->title("{$title} &bull; " . $lang->get('site_title'));

if ($mode == 'raw')
{
    $skin->output(false, true);
}
else if (!empty($mode))
{
    echo $skin->output("api_show.{$mode}");
}
else
{
    $skin->output();
}

?>
