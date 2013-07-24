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
$smtp_host = $core->variable('smtp_host', $config->smtp_host);
$smtp_port = $core->variable('smtp_port', $config->smtp_port);
$smtp_crypt = $core->variable('smtp_crypt', $config->smtp_crypt);
$smtp_username = $core->variable('smtp_username', $config->smtp_username);
$smtp_password = $core->variable('smtp_password', $config->smtp_password);
$smtp_from = $core->variable('smtp_from', $config->smtp_from);

$smtp_save = isset($_POST['smtp_save']);

// Save button was pressed
if ($smtp_save)
{
    // Validate required fields
    if (empty($smtp_host) || empty($smtp_port) || empty($smtp_from))
    {
        $module->notify($lang->get('smtp_reqd'));
    }

    // Check if the file is writable
    else if (!is_writable(realpath('../config.php')))
    {
        $module->notify($lang->get('config_cantwrite'));
    }

    // Write the conf data
    else
    {
        // Update configuration data to new values
        $config->smtp_host     = $smtp_host;
        $config->smtp_port     = $smtp_port;
        $config->smtp_crypt    = $smtp_crypt;
        $config->smtp_username = $smtp_username;
        $config->smtp_password = $smtp_password;
        $config->smtp_from     = $smtp_from;

        $config->save();
        $module->notify($lang->get('changes_saved'));
    }
}

// Assign skin data
$skin->assign(array(
    'smtp_host'       => htmlspecialchars($smtp_host),
    'smtp_port'       => htmlspecialchars($smtp_port),
    'smtp_crypt'      => htmlspecialchars($smtp_crypt),
    'smtp_username'   => htmlspecialchars($smtp_username),
    'smtp_password'   => htmlspecialchars($smtp_password),
    'smtp_from'       => htmlspecialchars($smtp_from),
    'smtp_crypt_none' => $skin->selected($smtp_crypt == ''),
    'smtp_crypt_ssl'  => $skin->selected($smtp_crypt == 'ssl'),
    'smtp_crypt_tls'  => $skin->selected($smtp_crypt == 'tls'),
));

// Set the page title
$module_title = $lang->get('email_config');
$module_data =  $skin->output('tpl_config_email', true, true);

?>

