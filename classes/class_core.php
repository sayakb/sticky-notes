<?php
/**
* Sticky Notes pastebin
* @ver 0.3
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

class core
{
    // Global vars
    var $build;
    var $build_num;

    // Constructor
    function __construct()
    {
        // Define globals
        global $gsod;
        
        // Get the version number       
        if (file_exists('VERSION'))
        {
            $data = file_get_contents('VERSION');
        }
        else if (file_exists('../VERSION'))
        {
            $data = file_get_contents('../VERSION');
        }
        else
        {
            $gsod->trigger('<b>Sticky Notes fatal error</b><br /><br />' .
                           'Version file not found');
        }
        
        $data = explode("\n", $data);
        $this->build = $data[0];
        $this->build_num = $data[1];
    }

    // Function to return root path
    function path()
    {
        $path = $_SERVER['PHP_SELF'];
        $snip = strrpos($path, '/');
        $path = substr($path, 0, $snip + 1);

        return $path;
    }
    
    // Function to return root path
    function root_path()
    {
        $path = $this->path();
        
        if (strpos($path, 'admin') !== false)
        {
            return substr($path, 0, strrpos($path, 'admin'));
        }
        else
        {
            return $path;
        }
    }

    // Check if we are in admin path
    function in_admin()
    {
        $path = $this->path();

        return (strpos($path, 'admin') !== false);
    }

    // Function to return remote IP
    function remote_ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    // Function to set a cookie
    function set_cookie($name, $value, $expire = 0)
    {      
        if ($expire > 0)
        {
            $expire = time() + ($expire * 24 * 60 * 60);
        }

        setcookie('stickynotes_' . $name, $value, $expire, $this->root_path());
    }
    
    // Function to expire a cookie
    function unset_cookie($name)
    {
        setcookie('stickynotes_' . $name, null, time() - 3600, $this->root_path());
    }

    // Function to fetch query strings / post data
    function variable($name, $default, $is_cookie = false)
    {
        if (gettype($default) == "integer")
        {
            settype($default, "double");
        }

        if ($is_cookie && isset($_COOKIE['stickynotes_' . $name]))
        {
            $cookie_data = $_COOKIE['stickynotes_' . $name];
            settype($cookie_data, gettype($default));

            return $cookie_data;
        }
        else if (isset($_POST[$name]))
        {
            $post_data = $_POST[$name];
            settype($post_data, gettype($default));

            return $post_data;
        }
        else if (isset($_GET[$name]))
        {
            $get_data = $_GET[$name];
            settype($get_data, gettype($default));

            return $get_data;
        }
        else
        {
            return $default;
        }
    }

    // Function to return the script name
    function script_name()
    {
        return $_SERVER['SCRIPT_NAME'];
    }

    // Get the request URI
    function request_uri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    // Get the base URI
    function base_uri()
    {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';

        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
        {
            $hostname = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }
        elseif (isset($_SERVER['HTTP_HOST']))
        {
            $hostname = $_SERVER['HTTP_HOST'];
        }
        else
        {
            $hostname = "unknown_host";
        }

        $uri = $protocol . '://' . $hostname . $this->path();
        
        return $uri;
    }
    
    // Method to replace square brackets with normal braces
    function rss_encode(&$data)
    {
        $data = str_replace('[', '(', $data);
        $data = str_replace(']', ')', $data);
        $data = str_replace('{', '(', $data);
        $data = str_replace('}', ')', $data);
        $data = str_replace(chr(0), '', $data);
    }
    
    // Method to redirect to a specified URL
    function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }
 
    // Method to return the server load
    function server_load() 
    {
        $os = strtolower(PHP_OS);

        if (strpos($os, 'win') === FALSE)
        {
            if (file_exists('/proc/loadavg'))
            {
                $load = file_get_contents('/proc/loadavg');
                $load = explode(' ', $load);
                return $load[0];
            }
            else if (function_exists('shell_exec'))
            {
                $load = explode(' ', `uptime`);
                return $load[count($load) - 1];
            }
        }
        else
        {
            if (function_exists('exec'))
            {
                $load = array();
                exec('wmic cpu get loadpercentage', $load);

                if ( ! empty($load[1]))
                {
                    return "{$load[1]}%";
                }
            }
        }

        return 'N/A';
    }
}

?>