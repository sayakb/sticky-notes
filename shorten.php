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
$project = $core->variable('project', '');
$hash = $core->variable('hash', 0);
$api_url = "https://www.googleapis.com/urlshortener/v1/url?key={$config->google_api_key}";
$is_key = false;

// We need the google API key for this to work
if (empty($config->google_api_key))
{
    die("ERROR");
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
    die("ERROR");
}

// Build the query based on whether a key or ID was used
if ($is_key)
{
    $sql = "SELECT * FROM {$db->prefix}main WHERE urlkey = :id LIMIT 1";
}
else
{
    $sql = "SELECT * FROM {$db->prefix}main WHERE id = :id LIMIT 1";
}

$row = $db->query($sql, array(
    ':id' => $paste_id
), true);

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
    die("ERROR");
}

// Validate the hash
if ($row['private'] == "1")
{
    if (empty($hash) || $row['hash'] != $hash)
    {
        die("ERROR");
    }
}

// Now that we know the paste exists, generate the paste URL
$paste_url = $nav->get_paste($row['id'], $row['urlkey'], $hash, $project);

// Create cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("longUrl" => $paste_url)));
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Execute the post
$result = curl_exec($ch);

// Close the connection
curl_close($ch);

// Parse the response
$response = json_decode($result, true);

if (isset($response['id']))
{
    die($response['id']);
}
else
{
    die("ERROR");
}

?>
