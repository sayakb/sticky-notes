<?php
/**
* Sticky Notes pastebin
* @ver 0.1
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
        
        include($core->root_path() . 'config.php');
        
        // Set the data
        $this->db_host          = $db_host;
        $this->db_port          = $db_port;
        $this->db_name          = $db_name;
        $this->db_username      = $db_username;
        $this->db_password      = $db_password;
        $this->db_prefix        = $db_prefix;
        
        $this->site_name        = $site_name;
        $this->site_title       = $site_title;
        $this->site_copyright   = $site_copyright;
        $this->skin_name        = $skin_name;
        $this->admin_skin_name  = $admin_skin_name;
        $this->lang_name        = $lang_name;
        
        $this->sg_services      = $sg_services;
        $this->sg_php_key       = $sg_php_key;
        $this->sg_php_days      = $sg_php_days;
        $this->sg_php_score     = $sg_php_score;
        $this->sg_php_type      = $sg_php_type;
    }    
}

?>
