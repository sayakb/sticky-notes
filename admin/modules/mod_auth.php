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
$auth_method = $core->variable('auth_method', $config->auth_method);
$ldap_server = $core->variable('ldap_server', $config->ldap_server);
$ldap_port = $core->variable('ldap_port', $config->ldap_port);
$ldap_base_dn = $core->variable('ldap_base_dn', $config->ldap_base_dn);
$ldap_uid = $core->variable('ldap_uid', $config->ldap_uid);
$ldap_filter = $core->variable('ldap_filter', $config->ldap_filter);
$ldap_user_dn = $core->variable('ldap_user_dn', $config->ldap_user_dn);
$ldap_password = $core->variable('ldap_password', $config->ldap_password);

$auth_save = isset($_POST['auth_save']);

// Save button was pressed
if ($auth_save)
{
    // Validate required fields
    if ($auth_method == 'ldap' &&
       (empty($ldap_server) || empty($ldap_base_dn) || empty($ldap_uid)))
    {
        $module->notify($lang->get('auth_reqd'));
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
        $config->auth_method   = $auth_method;
        $config->ldap_server   = $ldap_server;
        $config->ldap_port     = $ldap_port;
        $config->ldap_base_dn  = $ldap_base_dn;
        $config->ldap_uid      = $ldap_uid;
        $config->ldap_filter   = $ldap_filter;
        $config->ldap_user_dn  = $ldap_user_dn;
        $config->ldap_password = $ldap_password;

        $config->save();
        $module->notify($lang->get('changes_saved'));
    }
}

// Assign skin data
$skin->assign(array(
    'db_selected'     => $skin->selected($auth_method == 'db'),
    'ldap_selected'   => $skin->selected($auth_method == 'ldap'),
    'ldap_server'     => htmlspecialchars($ldap_server),
    'ldap_port'       => htmlspecialchars($ldap_port),
    'ldap_base_dn'    => htmlspecialchars($ldap_base_dn),
    'ldap_uid'        => htmlspecialchars($ldap_uid),
    'ldap_filter'     => htmlspecialchars($ldap_filter),
    'ldap_user_dn'    => htmlspecialchars($ldap_user_dn),
    'ldap_password'   => htmlspecialchars($ldap_password),
));

// Set the page title
$module_title = $lang->get('authentication');
$module_data =  $skin->output('tpl_config_auth', true, true);

?>

