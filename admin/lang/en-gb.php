<?php
/**
* Sticky Notes pastebin
* @ver 0.2
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

$lang_data = array(
    /* Page: index.php */
    'manage_pastes'         => 'Manage Pastes',
    'manage_users'          => 'Manage Users',
    'manage_ip_bans'        => 'Manage IP Bans',
    'authentication'        => 'Authentication',
    'site_config'           => 'Site Configuration',
    'dashboard'             => 'Dashboard',
    'logout'                => 'Log Out',
    'welcome_user'          => 'Hello __user__', // Do not remove __user__
    'search_paste_id'       => 'Search paste by ID',
    'search'                => 'Search',
    'search_exp'            => 'To view a paste, enter a paste ID above in the search box',
    'paste_id_404'          => 'Could not find the specified paste',
    'anonymous'             => 'Anonymous',
    'yes'                   => 'Yes',
    'no'                    => 'No',
    'author'                => 'Author',
    'posted_on'             => 'Posted on',
    'expires_on'            => 'Expires on',
    'data'                  => 'Data',
    'is_private'            => 'Is private',
    'has_password'          => 'Has password',
    'poster_ip'             => 'Poster\'s IP',
    'paste_details'         => 'Paste details',
    'action_confirm'        => 'Are you sure you want to perform this action?',
    'remove_password'       => 'Remove Password',
    'make_public'           => 'Make Public',
    'delete_paste'          => 'Delete Paste',
    'made_public'           => 'The paste has been made public',
    'pass_removed'          => 'Password removed',
    'paste_deleted'         => 'Paste has been deleted',
    'field'                 => 'Field',
    'value'                 => 'Value',
    'create_user'           => 'Create new user',
    'edit_user'             => 'Edit user',
    'name'                  => 'Name',
    'email'                 => 'E-mail',
    'actions'               => 'Actions',
    'edit'                  => 'Edit',
    'delete'                => 'Delete',
    'required'              => 'required',
    'first_name'            => 'First Name',
    'last_name'             => 'Last Name',
    'twice'                 => 'twice',
    'save'                  => 'Save',
    'cancel'                => 'Cancel',
    'user_note'             => 'Note: New users will be created with full admin privileges',
    'ip_address'            => 'IP Address',
    'banned_ips'            => 'Banned IP addresses',
    'no_ips'                => 'No banned IP addresses',
    'ban'                   => 'Ban',
    'already_banned'        => 'Specified IP address has already been banned',
    'banned_success'        => 'IP address banned successfully',
    'invalid_ip'            => 'Invalid IP address entered',
    'deselect_all'          => 'Deselect all',
    'select_all'            => 'Select all',
    'site_config'           => 'Site configuration',
    'sitename'              => 'Site name',
    'sitetitle'             => 'Site title',
    'appears_title'         => 'This appears on the page title',
    'sitecopyright'         => 'Copyright notice',
    'copyright_warn'        => 'You may add additional text but you <b>may not</b> remove the '.
                               'original copyright notice',
    'bsd_protected'         => 'Protected by the BSD License',
    'siteskin'              => 'Skin name',
    'admin_skin'            => 'Admin panel skin',
    'sitelang'              => 'Language',
    'adminlang'             => 'Admin panel lang',
    'antispam_config'       => 'Anti-spam configuration',
    'enabled_sg_svcs'       => 'Enabled anti-spam services',
    'svcs_exp'              => 'Hold down the Ctrl key to select multiple services',
    'access_key'            => 'Access key',
    'access_key_exp'        => 'Leave this blank if you want to disable Project Honey Pot integration',
    'age_threshold'         => 'Age threshold',
    'age_exp'               => 'PHP responses older than these no. of days will be ignored',
    'threat_score'          => 'Threat score',
    'threat_score_exp'      => 'IPs with PHP threat score greater than or equal to this will be disallowed',
    'type_filter'           => 'Visitor type filter',
    'type_exp'              => 'Visitor type greater than or equal to this will be disallowed',
    'php_config'            => 'Project Honey Pot configuration',
    'php_conf_exp'          => 'For info on the configuration values below, check: ' .
                               '<a href="http://www.projecthoneypot.org/httpbl_api.php" rel="nofollow">' .
                               'http://www.projecthoneypot.org/httpbl_api.php</a>',
    'js_alert'              => 'Warning! You do not have JavaScript enabled. Actions will take place without ' .
                               'confirmation.',
    'site_info'             => 'Site information',
    'php_version'           => 'PHP version',
    'mysql_version'         => 'MySQL version',
    'server_load'           => 'Server load',
    'db_size'               => 'Database size',
    'paste_count'           => 'Total no. of pastes',
    'stickynotes_ver'       => 'Sticky Notes version',
    'n_a'                   => 'N/A',
    'new_ver_available'     => 'Newer version available',
    'download_latest'       => 'Download latest version',
    'censor_config'         => 'Word censor configuration',
    'censored_phrases'      => 'Censored phrases',
    'censored_phrases_exp'  => 'Pastes containing these phrases will be dropped (case <b>insensitive</b>).<br />' .
                               'Enter each phrase in a new line',
    'admin_auth_settings'   => 'Admin authentication settings',
    'auth_method'           => 'Auth method',
    'database'              => 'Database',
    'ldap'                  => 'LDAP',
    'ldap_server'           => 'LDAP server name',
    'ldap_server_exp'       => 'If using LDAP this is the hostname or IP address of the LDAP server. Alternatively ' .
                               'you can specify an URL like ldap://hostname:port/',
    'ldap_port'             => 'LDAP server port',
    'ldap_port_exp'         => 'Optionally you can specify a port which should be used to connect to the LDAP ' .
                               'server instead of the default port 389.',
    'ldap_base_dn'          => 'LDAP base <i>dn</i>',
    'ldap_base_dn_exp'      => 'This is the Distinguished Name, locating the user information, e.g. o=My Company,c=US.',
    'ldap_uid'              => 'LDAP <i>uid</i>',
    'ldap_uid_exp'          => 'This is the key under which to search for a given login identity, e.g. uid, sn, etc.',
    'ldap_filter'           => 'LDAP user filter',
    'ldap_filter_exp'       => 'Optionally you can further limit the searched objects with additional filters. ' .
                               'For example objectClass=posixGroup would result in the use of (&amp;(uid=$username)' .
                               '(objectClass=posixGroup))',
    'ldap_user_dn'          => 'LDAP user <i>dn</i>',
    'ldap_user_dn_exp'      => 'Leave blank to use anonymous binding. If filled in, sticky-notes uses the specified ' .
                               'distinguished name on login attempts to find the correct user, e.g. uid=Username,' .
                               'ou=MyUnit,o=MyCompany,c=US. Required for Active Directory Servers.',
    'ldap_password'         => 'LDAP password',
    'ldap_password_exp'     => 'Leave blank to use anonymous binding, otherwise fill in the password for the above ' .
                               'user. Required for Active Directory Servers.<br />' .
                               '<i><b>Warning:</b> This password will be stored as plain text in the config file, ' .
                               'visible to everybody who can access your configuration file.</i>',
    
    'username_required'         => 'Please enter the username',
    'email_required'            => 'Please enter e-mail address',
    'username_invalid'          => 'Username can contain only alphabets, numbers and dots (.)',
    'username_startchar'        => 'Username should start with an alphabet',
    'fname_required'            => 'Please enter first name',
    'password_dontmatch'        => 'Passwords do not match',
    'email_invalid'             => 'Invalid e-mail address',
    'password_required'         => 'Please fill in the password fields',
    'username_taken'            => 'Username already registered. Please choose a different username',
    'email_taken'               => 'E-mail address already registered. Please choose a different e-mail',
    'auth_reqd'                 => 'Please fill in all the mandatory fields',
    'config_reqd'               => 'All fields under Site Configuration are mandatory',
    'config_cantwrite'          => 'The config file isn\'t writable. Please check file permissions',
    'no_user_ldap'              => 'This module is unavailable when LDAP authentication is in use',

    /* Page: login.php */
    'admin_login'       => 'Administration Login',
    'username'          => 'Username',
    'password'          => 'Password',
    'login'             => 'Login',
    'invalid_login'     => 'Login failed! Invalid username or password entered.',
    'logged_out'        => 'You have logged out successfully.',

    /* Global */
    'back_to_home'      => '&larr; Back to __sitename__', // Do not remove __sitename__
    'dismiss'           => 'Dismiss',
);

?>