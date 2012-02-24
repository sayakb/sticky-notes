<?php
/**
* Sticky Notes pastebin
* @ver 0.2
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
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
    var $admin_skin_name;
    var $lang_name;
    var $sg_services;
    var $sg_php_key;
    var $sg_php_days;
    var $sg_php_score;
    var $sg_php_type;
    
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
            $this->db_host          = isset($db_host) ? $db_host : '';
            $this->db_port          = isset($db_port) ? $db_port : '';
            $this->db_name          = isset($db_name) ? $db_name : '';
            $this->db_username      = isset($db_username) ? $db_username : '';
            $this->db_password      = isset($db_password) ? $db_password : '';
            $this->db_prefix        = isset($db_prefix) ? $db_prefix : '';
            
            $this->site_name        = isset($site_name) ? $site_name : '';
            $this->site_title       = isset($site_title) ? $site_title : '';
            $this->site_copyright   = isset($site_copyright) ? html_entity_decode($site_copyright) : '';
            $this->skin_name        = isset($skin_name) ? $skin_name : '';
            $this->admin_skin_name  = isset($admin_skin_name) ? $admin_skin_name : '';
            $this->lang_name        = isset($lang_name) ? $lang_name : '';
            
            $this->sg_services      = isset($sg_services) ? $sg_services : '';
            $this->sg_php_key       = isset($sg_php_key) ? $sg_php_key : '';
            $this->sg_php_days      = isset($sg_php_days) ? $sg_php_days : '';
            $this->sg_php_score     = isset($sg_php_score) ? $sg_php_score : '';
            $this->sg_php_type      = isset($sg_php_type) ? $sg_php_type : '';
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
            fwrite($fp, "// (C) 2011 Sayak Banerjee. All rights reserved\n\n");
            fwrite($fp, "/// This is an auto generated file\n");
            fwrite($fp, "/// Please DO NOT modify manually\n");
            fwrite($fp, "/// Unless you are absolutely sure what you're doing ;-)\n\n");
            
            fwrite($fp, '$db_host = "' . $this->db_host . '";' . "\n");
            fwrite($fp, '$db_port = "' . $this->db_port . '";' . "\n");
            fwrite($fp, '$db_name = "' . $this->db_name . '";' . "\n");
            fwrite($fp, '$db_username = "' . $this->db_username . '";' . "\n");
            fwrite($fp, '$db_password = "' . $this->db_password . '";' . "\n");
            fwrite($fp, '$db_prefix = "' . $this->db_prefix . '";' . "\n\n");
            
            fwrite($fp, '$site_name = "' . $this->site_name . '";' . "\n");
            fwrite($fp, '$site_title = "' . $this->site_title . '";' . "\n");
            fwrite($fp, '$site_copyright = "' . htmlentities($this->site_copyright) . '";' . "\n");
            fwrite($fp, '$skin_name = "' . $this->skin_name . '";' . "\n");
            fwrite($fp, '$admin_skin_name = "' . $this->admin_skin_name . '";' . "\n");
            fwrite($fp, '$lang_name = "' . $this->lang_name . '";' . "\n\n");

            fwrite($fp, '$sg_services = "' . $this->sg_services . '";' . "\n");
            fwrite($fp, '$sg_php_key = "' . $this->sg_php_key . '";' . "\n");
            fwrite($fp, '$sg_php_days = ' . $this->sg_php_days . ';' . "\n");
            fwrite($fp, '$sg_php_score = ' . $this->sg_php_score . ';' . "\n");
            fwrite($fp, '$sg_php_type = ' . $this->sg_php_type . ';' . "\n");

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
            fwrite($fp, "// (C) 2011 Sayak Banerjee. All rights reserved\n\n");
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
                        'Sticky Notes</a>. Copyright &copy; 2011 <a href="' .
                        'http://sayakbanerjee.com">Sayak Banerjee</a>.') . '";' . "\n");
            fwrite($fp, '$skin_name = "Bootstrap";' . "\n");
            fwrite($fp, '$admin_skin_name = "Greyscale";' . "\n");
            fwrite($fp, '$lang_name = "en_gb";' . "\n\n");

            fwrite($fp, '$sg_services = "stealth,php";' . "\n");
            fwrite($fp, '$sg_php_key = "";' . "\n");
            fwrite($fp, '$sg_php_days = 90;' . "\n");
            fwrite($fp, '$sg_php_score = 50;' . "\n");
            fwrite($fp, '$sg_php_type = 2;' . "\n");

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
