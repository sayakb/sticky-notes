<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

class core
{
    // Global vars
    var $build;
    var $build_num;
    var $root_dir;

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
        $this->root_dir = defined('IN_ADMIN') ? '../' : '';
    }

    // Function to return root URI base
    function root_uri()
    {
        $path = $this->current_uri();

        if (defined('IN_ADMIN') !== false)
        {
            return substr($path, 0, strrpos($path, 'admin'));
        }
        else
        {
            return $path;
        }
    }

    // Get the current URI base
    function current_uri()
    {
        $path = $_SERVER['PHP_SELF'];
        $snip = strrpos($path, '/');
        $path = substr($path, 0, $snip + 1);

        return $this->hostname() . $path;
    }

    // Get the full URI, uncluding the script name
    function full_uri()
    {
        return $this->hostname() . $_SERVER['REQUEST_URI'];
    }

    // Function to return the script name
    function script_name()
    {
        return $_SERVER['SCRIPT_NAME'];
    }

    // Returns the server's hostname
    function hostname($add_protocol = true)
    {
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

        if ($add_protocol)
        {
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
            return $protocol . '://' . $hostname;
        }
        else
        {
            return $hostname;
        }
    }

    // Function to return remote IP
    function remote_ip()
    {
        global $config;

        // Get the user's IP
        $remote = getenv($config->tracking_method);

        if ($remote === false)
        {
            $remote = getenv('REMOTE_ADDR');
        }

        // Return the last entry of the comma separated IP list
        $ip_ary = explode(',', $remote);
        $ip_addr = trim(end($ip_ary));

        if (filter_var($ip_addr, FILTER_VALIDATE_IP))
        {
            return $ip_addr;
        }

        return '0.0.0.0';
    }

    // Function to set a cookie
    function set_cookie($name, $value, $expire = 0)
    {
        if ($expire > 0)
        {
            $expire = time() + ($expire * 24 * 60 * 60);
        }

        setcookie('stickynotes_' . $name, $value, $expire, $this->root_uri());
    }

    // Function to expire a cookie
    function unset_cookie($name)
    {
        setcookie('stickynotes_' . $name, null, time() - 3600, $this->root_uri());
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
        global $lang;

        // Get the system's load based on the OS
        $os = strtolower(PHP_OS);

        if (strpos($os, 'win') === false)
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

        return $lang->get('n_a');
    }
}

?>