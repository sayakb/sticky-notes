<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

class lang
{
    // Class wide variables
    var $lang_name;

    // Constructor
    function __construct()
    {
        global $lang_name;

        $this->lang_name = $lang_name;
    }

    // Function to parse localization data
    function parse($data)
    {
        global $core;

        // Get language data from lang file
        if (file_exists(realpath('lang/' . $this->lang_name . '.php')))
        {
            include('lang/' . $this->lang_name . '.php');
        }

        $data = $this->set_defaults($data);

        foreach ($lang_data as $key => $value)
        {
            $value = str_replace("[[host]]", $core->base_uri(), $value);
            $data = str_replace("{{{$key}}}", $value, $data);
        }

        $data = preg_replace('/\{\{(.*?)\}\}/', '$1', $data);

        // Done!
        return $data;
    }

    // Function to return a localized phrase
    function get($key)
    {
        global $site_name, $site_title, $site_copyright;

        // Return default data
        switch($key)
        {
            case 'lang_name':
                return $this->lang_name;
            case 'site_name':
                return $site_name;
            case 'site_title':
                return $site_title;
            case 'site_copyright':
                return $site_copyright;
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
        global $site_name, $site_title, $site_copyright;

        $data = str_replace("{{lang_name}}", $this->lang_name, $data);
        $data = str_replace("{{site_name}}", $site_name, $data);
        $data = str_replace("{{site_title}}", $site_title, $data);
        $data = str_replace("{{site_copyright}}", $site_copyright, $data);

        return $data;
    }
}

?>