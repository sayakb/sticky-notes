<?php
/**
* Sticky Notes pastebin
* @ver 0.2
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

class config
{
    // Declare config variables
    var $db_host;
    var $db_port;
    var $db_name;
    var $db_username;
    var $db_password;
    var $db_prefix;

    var $site_name;
    var $site_title;
    var $site_copyright;
    var $skin_name;
    var $lang_name;
    var $admin_skin_name;
    var $admin_lang_name;
    
    var $sg_services;
    var $sg_php_key;
    var $sg_php_days;
    var $sg_php_score;
    var $sg_php_type;
    var $sg_censor;

    var $auth_method;
    var $ldap_server;
    var $ldap_port;
    var $ldap_base_dn;
    var $ldap_uid;
    var $ldap_filter;
    var $ldap_user_dn;
    var $ldap_password;
    
    // Constructor
    function __construct()
    {
        global $core;
        
        // Set the load flag
        $load_data = true;
        
        if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false)
        {
            if (file_exists(realpath('../config.php')))
            {
                include('../config.php');
            }
            else
            {
                $load_data = false;
            }
        }
        else
        {
            if (file_exists(realpath('config.php')))
            {
                include('config.php');
            }
            else
            {
                $load_data = false;
            }
        }
        
        // Set the data
        if ($load_data)
        {
            $this->db_host         = isset($db_host) ? html_entity_decode($db_host) : '';
            $this->db_port         = isset($db_port) ? html_entity_decode($db_port) : '';
            $this->db_name         = isset($db_name) ? html_entity_decode($db_name) : '';
            $this->db_username     = isset($db_username) ? html_entity_decode($db_username) : '';
            $this->db_password     = isset($db_password) ? html_entity_decode($db_password) : '';
            $this->db_prefix       = isset($db_prefix) ? html_entity_decode($db_prefix) : '';
            
            $this->site_name       = isset($site_name) ? html_entity_decode($site_name) : 'Sticky Notes';
            $this->site_title      = isset($site_title) ? html_entity_decode($site_title) : 'Sticky Notes pastebin';
            $this->site_copyright  = isset($site_copyright) ? html_entity_decode($site_copyright) : '&copy; 2012 Sayak Banerjee';
            $this->skin_name       = isset($skin_name) ? html_entity_decode($skin_name) : 'Bootstrap';
            $this->lang_name       = isset($lang_name) ? html_entity_decode($lang_name) : 'en-gb';
            $this->admin_skin_name = isset($admin_skin_name) ? html_entity_decode($admin_skin_name) : 'Greyscale';
            $this->admin_lang_name = isset($admin_lang_name) ? html_entity_decode($admin_lang_name) : 'en-gb';
            
            $this->sg_services     = isset($sg_services) ? html_entity_decode($sg_services) : 'ipban,noflood,stealth,php,censor';
            $this->sg_php_key      = isset($sg_php_key) ? html_entity_decode($sg_php_key) : '';
            $this->sg_php_days     = isset($sg_php_days) ? $sg_php_days : 90;
            $this->sg_php_score    = isset($sg_php_score) ? $sg_php_score : 50;
            $this->sg_php_type     = isset($sg_php_type) ? $sg_php_type : 2;
            $this->sg_censor       = isset($sg_censor) ? html_entity_decode($sg_censor) : '';

            $this->auth_method     = isset($auth_method) ? html_entity_decode($auth_method) : '';
            $this->ldap_server     = isset($ldap_server) ? html_entity_decode($ldap_server) : '';
            $this->ldap_port       = isset($ldap_port) ? html_entity_decode($ldap_port) : '';
            $this->ldap_base_dn    = isset($ldap_base_dn) ? html_entity_decode($ldap_base_dn) : '';
            $this->ldap_uid        = isset($ldap_uid) ? html_entity_decode($ldap_uid) : '';
            $this->ldap_filter     = isset($ldap_filter) ? html_entity_decode($ldap_filter) : '';
            $this->ldap_user_dn    = isset($ldap_user_dn) ? html_entity_decode($ldap_user_dn) : '';
            $this->ldap_password   = isset($ldap_password) ? html_entity_decode($ldap_password) : '';
        }
    }    
    
    // Method to save updated config values
    function save()
    {
        try
        {
            // Using ../config.php as this function is fired only from the admin panel
            $fp = fopen(realpath('../config.php'), 'w');
            
            fwrite($fp, "<?php\n");
            fwrite($fp, "// Sticky Notes Pastebin configuration file\n");
            fwrite($fp, "// (C) 2012 Sayak Banerjee. All rights reserved\n\n");
            fwrite($fp, "/// This is an auto generated file\n");
            fwrite($fp, "/// Please DO NOT modify manually\n");
            fwrite($fp, "/// Unless you are absolutely sure what you're doing ;-)\n\n");
            
            fwrite($fp, '$db_host = "' . htmlentities($this->db_host) . '";' . "\n");
            fwrite($fp, '$db_port = "' . htmlentities($this->db_port) . '";' . "\n");
            fwrite($fp, '$db_name = "' . htmlentities($this->db_name) . '";' . "\n");
            fwrite($fp, '$db_username = "' . htmlentities($this->db_username) . '";' . "\n");
            fwrite($fp, '$db_password = "' . htmlentities($this->db_password) . '";' . "\n");
            fwrite($fp, '$db_prefix = "' . htmlentities($this->db_prefix) . '";' . "\n\n");
            
            fwrite($fp, '$site_name = "' . htmlentities($this->site_name) . '";' . "\n");
            fwrite($fp, '$site_title = "' . htmlentities($this->site_title) . '";' . "\n");
            fwrite($fp, '$site_copyright = "' . htmlentities($this->site_copyright) . '";' . "\n");
            fwrite($fp, '$skin_name = "' . htmlentities($this->skin_name) . '";' . "\n");
            fwrite($fp, '$lang_name = "' . htmlentities($this->lang_name) . '";' . "\n");
            fwrite($fp, '$admin_skin_name = "' . htmlentities($this->admin_skin_name) . '";' . "\n");
            fwrite($fp, '$admin_lang_name = "' . htmlentities($this->admin_lang_name) . '";' . "\n\n");

            fwrite($fp, '$sg_services = "' . htmlentities($this->sg_services) . '";' . "\n");
            fwrite($fp, '$sg_php_key = "' . htmlentities($this->sg_php_key) . '";' . "\n");
            fwrite($fp, '$sg_php_days = ' . intval($this->sg_php_days) . ';' . "\n");
            fwrite($fp, '$sg_php_score = ' . intval($this->sg_php_score) . ';' . "\n");
            fwrite($fp, '$sg_php_type = ' . intval($this->sg_php_type) . ';' . "\n");
            fwrite($fp, '$sg_censor = "' . htmlentities($this->sg_censor) . '";' . "\n\n");

            fwrite($fp, '$auth_method = "' . htmlentities($this->auth_method) . '";' . "\n");
            fwrite($fp, '$ldap_server = "' . htmlentities($this->ldap_server) . '";' . "\n");
            fwrite($fp, '$ldap_port = "' . htmlentities($this->ldap_port) . '";' . "\n");
            fwrite($fp, '$ldap_base_dn = "' . htmlentities($this->ldap_base_dn) . '";' . "\n");
            fwrite($fp, '$ldap_uid = "' . htmlentities($this->ldap_uid) . '";' . "\n");
            fwrite($fp, '$ldap_filter = "' . htmlentities($this->ldap_filter) . '";' . "\n");
            fwrite($fp, '$ldap_user_dn = "' . htmlentities($this->ldap_user_dn) . '";' . "\n");
            fwrite($fp, '$ldap_password = "' . htmlentities($this->ldap_password) . '";' . "\n");

            fwrite($fp, "?>");
            fclose($fp);
            
            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }
    
    // Method to save initial data
    function create($db_host, $db_port, $db_name, $db_user, $db_pass, $db_prefix)
    {
        try
        {
            $fp = fopen(realpath('config.php'), 'w');
            
            fwrite($fp, "<?php\n");
            fwrite($fp, "// Sticky Notes Pastebin configuration file\n");
            fwrite($fp, "// (C) 2012 Sayak Banerjee. All rights reserved\n\n");
            fwrite($fp, "/// This is an auto generated file\n");
            fwrite($fp, "/// Please DO NOT modify manually\n");
            fwrite($fp, "/// Unless you are absolutely sure what you're doing ;-)\n\n");
            
            fwrite($fp, '$db_host = "' . $db_host . '";' . "\n");
            fwrite($fp, '$db_port = "' . $db_port . '";' . "\n");
            fwrite($fp, '$db_name = "' . $db_name . '";' . "\n");
            fwrite($fp, '$db_username = "' . $db_user . '";' . "\n");
            fwrite($fp, '$db_password = "' . $db_pass . '";' . "\n");
            fwrite($fp, '$db_prefix = "' . $db_prefix . '";' . "\n\n");
            
            fwrite($fp, '$site_name = "Sticky Notes";' . "\n");
            fwrite($fp, '$site_title = "Sticky Notes pastebin";' . "\n");
            fwrite($fp, '$site_copyright = "' . htmlentities('Powered by <a href="' .
                        'http://www.sayakbanerjee.com/sticky-notes/" rel="nofollow">' .
                        'Sticky Notes</a>. Copyright &copy; 2012 <a href="' .
                        'http://sayakbanerjee.com">Sayak Banerjee</a>.') . '";' . "\n");
            fwrite($fp, '$skin_name = "Bootstrap";' . "\n");
            fwrite($fp, '$lang_name = "en-gb";' . "\n");
            fwrite($fp, '$admin_skin_name = "Greyscale";' . "\n");
            fwrite($fp, '$admin_lang_name = "en-gb";' . "\n\n");

            fwrite($fp, '$sg_services = "ipban,noflood,stealth,php,censor";' . "\n");
            fwrite($fp, '$sg_php_key = "";' . "\n");
            fwrite($fp, '$sg_php_days = 90;' . "\n");
            fwrite($fp, '$sg_php_score = 50;' . "\n");
            fwrite($fp, '$sg_php_type = 2;' . "\n");
            fwrite($fp, '$sg_censor = "";' . "\n\n");

            fwrite($fp, '$auth_method = "db";' . "\n");
            fwrite($fp, '$ldap_server = "";' . "\n");
            fwrite($fp, '$ldap_port = "";' . "\n");
            fwrite($fp, '$ldap_base_dn = "";' . "\n");
            fwrite($fp, '$ldap_uid = "";' . "\n");
            fwrite($fp, '$ldap_filter = "";' . "\n");
            fwrite($fp, '$ldap_user_dn = "";' . "\n");
            fwrite($fp, '$ldap_password = "";' . "\n");

            fwrite($fp, "?>");
            fclose($fp);
            
            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }
}

?>
