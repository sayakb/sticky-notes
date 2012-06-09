<?php
/**
* Sticky Notes pastebin
* @ver 0.3
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

class auth
{
    // Global vars
    var $sid;
    var $max_age;

    // Method for creating a new session and killing expired ones
    function create_session()
    {
        global $core, $db;

        // Generate a session ID and set the maximum session age
        $this->sid = sha1(time() . $core->remote_ip());
        $this->max_age = time() - (60 * 60);

        // Expire old sessions
        $sql = "UPDATE {$db->prefix}users SET sid = '', lastlogin = 0 " .
                "WHERE lastlogin < {$this->max_age} AND lastlogin > 0";
        $db->query($sql);
    }
    
    // Method for authenticating a user
    function login($username, $password)
    {
        global $config, $db;

        // Get authentication method
        $method = $config->auth_method;

        // Check if the auth method is implemented
        if (method_exists($this, "authenticate_{$method}"))
        {
            // Create a new session
            $this->create_session();

            // Escape the username
            $db->escape($username);

            // Generate the delegate and execute the method
            $delegate = '$auth_status = $this->authenticate_' . $method .
                        '("' . $username . '", "' . $password . '");';
            eval($delegate);

            // Return the authentication status returned by the delegate
            return $auth_status;
        }

        // Method not implemented, invalidate user
        else
        {
            return false;
        }
    }

    // DB based authentication
    function authenticate_db($username, $password)
    {
        global $db;

        // Get the user details
        $sql = "SELECT * FROM {$db->prefix}users " .
               "WHERE username = '{$username}' " .
               "AND password <> ''";
        $row = $db->query($sql, true);

        // Check if the user exists
        if ($row != null)
        {
            $hash = sha1($password . $row['salt']);

            if ($row['password'] == $hash)
            { 
                // Update the session ID and details for the user
                $sql = "UPDATE {$db->prefix}users SET sid = '{$this->sid}' " .
                       "WHERE username = '{$username}' AND password <> ''";
                $db->query($sql);

                // Authentication was successful
                return true;
            }

            // Authentication failed
            else
            {
                return false;
            }
        }
        
        // User was not found in the DB
        else
        {
            return false;
        }
    }

    // Authentication via LDAP
    function authenticate_ldap($username, $password)
    {
        global $config, $db;

        // Connect to the LDAP server
        if (!empty($config->ldap_port))
        {
            $ldap = @ldap_connect($config->ldap_server, (int)$config->ldap_port);
        }
        else
        {
            $ldap = @ldap_connect($config->ldap_server);
        }

        // Check if connection failed
        if (!$ldap)
        {
            return false;
        }

        @ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        @ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        // Try to bind with the user DN and password, if provided
        if ($config->ldap_user_dn || $config->ldap_password)
        {
            if (!@ldap_bind($ldap, htmlspecialchars_decode($config->ldap_user_dn), htmlspecialchars_decode($config->ldap_password)))
            {
                return false;
            }
        }

        // Generate the user key (filter)
        $key = '(' . $config->ldap_uid . '=' . $this->escape(htmlspecialchars_decode($username)) . ')';

        // Check if an additional filter is set
        if ($config->ldap_filter)
        {
            $filter = ($config->ldap_filter[0] == '(' && substr($config->ldap_filter, -1) == ')')
                          ? $config->ldap_filter
                          : "({$config->ldap_filter})";
            $key = "(&{$key}{$filter})";
        }

        // Look up for the user
        $search = @ldap_search($ldap, htmlspecialchars_decode($config->ldap_base_dn), $key,
                               array(htmlspecialchars_decode($config->ldap_uid)), 0, 1);
        $ldap_result = @ldap_get_entries($ldap, $search);

        if (is_array($ldap_result) && sizeof($ldap_result) > 1)
        {
            // Validate credentials by binding with user's password
            if (@ldap_bind($ldap, $ldap_result[0]['dn'], htmlspecialchars_decode($password)))
            {
                @ldap_close($ldap);
                unset($ldap_result);

                // Check if user is already present. We check for a blank password here indicating
                // that we are looking for an LDAP user. DB users will not have blank passwords
                $sql = "SELECT * FROM {$db->prefix}users " .
                       "WHERE username = '{$username}' AND password = ''";
                $row = $db->query($sql, true);

                // If user is not found, insert one!
                if ($row == null)
                {
                    $sql = "INSERT INTO {$db->prefix}users " .
                           "(username, password, salt, email, dispname, sid, lastlogin) " .
                           "VALUES ('{$username}', '', '', '', '', '{$this->sid}', 0)";
                    $db->query($sql);
                }

                // User was found, just update the session ID
                else
                {
                    $sql = "UPDATE {$db->prefix}users SET sid = '{$this->sid}' " .
                           "WHERE username = '{$username}' AND password = ''";
                    $db->query($sql);
                }

                // Authentication was successful
                return true;
            }
            else
            {
                unset($ldap_result);
                @ldap_close($ldap);

                // Password was wrong
                return false;
            }
        }

        @ldap_close($ldap);

        // Username was not found
        return false;
    }

    // Escapes auth string needed for plugins like LDAP
    function escape($string)
    {
        return str_replace(array('*', '\\', '(', ')'), array('\\*', '\\\\', '\\(', '\\)'), $string);
    }
}

?>