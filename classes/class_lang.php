<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

class lang
{
    // Class wide variables
    var $lang_name;
    var $admin_lang_name;

    // Constructor
    function __construct()
    {
        global $config;

        $this->lang_name       = $config->lang_name;
        $this->admin_lang_name = $config->admin_lang_name;
    }

    // Function to parse localization data
    function parse($data)
    {
        global $core, $gsod;

        // Get language data from lang file
        $lang_file = defined('IN_ADMIN') ? $this->admin_lang_name : $this->lang_name; 
        
        if (file_exists(realpath("lang/{$lang_file}.php")))
        {
            include("lang/{$lang_file}.php");
        }
        else
        {
            $message  = '<b>Sticky Notes language parse error</b><br /><br />';
            $message .= 'Error: Language file not found<br />';
            $message .= 'Verify that the language selected is present in the lang/ folder';
            $gsod->trigger($message);
        }

        $data = $this->set_defaults($data);

        foreach ($lang_data as $key => $value)
        {
            $value = str_replace("[[host]]", $core->current_uri(), $value);
            $data = str_replace("{{{$key}}}", $value, $data);
        }

        // Show unlocalized data as is
        $data = preg_replace('/\{\{(.*?)\}\}/', '$1', $data);

        // Done!
        return $data;
    }

    // Function to return a localized phrase
    function get($key)
    {
        global $config;

        // Return default data
        switch($key)
        {
            case 'lang_name':
                return $this->lang_name;
            case 'site_name':
                return $config->site_name;
            case 'site_title':
                return $config->site_title;
            case 'site_copyright':
                return $config->site_copyright;
        }

        // Get language data from lang file
        if (file_exists(realpath('lang/' . $this->lang_name . '.php')))
        {
            include('lang/' . $this->lang_name . '.php');
        }

        if (isset($lang_data[$key]))
        {
            return $lang_data[$key];
        }
        else
        {
            return $key;
        }
    }

    // Function to assign default variables
    function set_defaults($data)
    {
        global $config;

        $data = str_replace("{{lang_name}}", $this->lang_name, $data);
        $data = str_replace("{{site_name}}", $config->site_name, $data);
        $data = str_replace("{{site_title}}", $config->site_title, $data);
        $data = str_replace("{{site_copyright}}", $config->site_copyright, $data);

        return $data;
    }
    
    // Function to exclude a string from being treated as a key
    function escape(&$data)
    {
        $data = preg_replace('/\{\{(.*?)\}\}/', '&#123;&#123;$1&#125;&#125;', $data);
    }
}

?>