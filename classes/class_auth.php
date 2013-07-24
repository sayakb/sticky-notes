<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

class auth
{
    // Global vars
    var $sid;
    var $max_age;
    var $crypt;

    // Constructor
    function __construct()
    {
        global $core;
        require_once "{$core->root_dir}addons/phpass/PasswordHash.php";

        // Use cost=10 as it works nicely on most hardware
        $this->crypt = new PasswordHash(10, false);
    }

    // Method for creating a new session and killing expired ones
    function create_session()
    {
        global $core, $db;

        // Generate a session ID and set the maximum session age
        $this->sid = $this->create_uid();
        $this->max_age = time() - (60 * 60);

        // Expire old sessions
        $sql = "UPDATE {$db->prefix}users SET sid = '', lastlogin = 0 " .
               "WHERE lastlogin < :max_age AND lastlogin > 0";

        $db->query($sql, array(
            ':max_age' => $this->max_age
        ));
    }

    // Method for authenticating a user
    function login($username, $password)
    {
        global $config, $db;

        // Get authentication method callback
        $callback = array($this, "authenticate_{$config->auth_method}");

        // Execute the method if it exists
        if (is_callable($callback))
        {
            $this->create_session();
            return call_user_func($callback, $username, $password);
        }

        // Method not implemented, invalidate user
        else
        {
            return false;
        }
    }

    // Reset user password
    function reset($username)
    {
        global $db;

        // Get the user details
        $sql = "SELECT * FROM {$db->prefix}users " .
               "WHERE username = :username " .
               "AND password <> ''";

        $row = $db->query($sql, array(
            ':username' => $username
        ), true);

        // Check if the user exists
        if ($row != null)
        {
            // Generate and update a new password
            $newpass = $this->create_uid(8);
            $hash = $this->create_password($newpass, $row['salt']);

            $sql = "UPDATE {$db->prefix}users " .
                   "SET password = :hash " .
                   "WHERE id = :id";

            $db->query($sql, array(
                ':hash' => $hash,
                ':id'   => $row['id']
            ));

            // Return the new password
            return array(
                'user'  => $row['dispname'] ? $row['dispname'] : $row['username'],
                'email' => $row['email'],
                'pass'  => $newpass,
            );
        }

        // No user found
        return false;
    }

    // Creates a unique identifier for specific length
    function create_uid($length = 40, $unique = 0)
    {
        global $core;

        $hash = sha1(time() . $core->remote_ip() . $unique);
        $hash = substr($hash, rand(0, 39 - $length), $length);

        return $hash;
    }

    // Create a new password hash
    function create_password($password, $salt)
    {
        return $this->crypt->HashPassword($password . $salt);
    }

    // Checks a password hash, updates it to bcrypt if still using sha1
    function check_password($table, $hash, $password, $salt)
    {
        global $db;

        // Hash created using blowfish algorithm
        if ($hash[0] == '$')
        {
            return $this->crypt->CheckPassword($password . $salt, $hash);
        }

        // Hash created using secure hash algorithm
        else
        {
            $new_hash = $this->create_password($password, $salt);
            $old_hash = '';

            switch($table)
            {
                case 'main':
                    $old_hash = sha1(sha1($password) . $salt);
                    break;

                case 'users':
                    $old_hash = sha1($password . $salt);
                    break;

                default:
                    return false;
            }

            // Password matches with old method, now migrate all pwds with this hash
            if ($hash == $old_hash)
            {
                $sql = "UPDATE {$db->prefix}{$table} " .
                       "SET password = :new_hash " .
                       "WHERE password = :old_hash";

                $db->query($sql, array(
                    ':old_hash' => $old_hash,
                    ':new_hash' => $new_hash
                ));

                return true;
            }
        }

        return false;
    }

    // Escapes auth string needed for plugins like LDAP
    function escape($string)
    {
        return str_replace(array('*', '\\', '(', ')'), array('\\*', '\\\\', '\\(', '\\)'), $string);
    }

    // DB based authentication
    function authenticate_db($username, $password)
    {
        global $db;

        // Get the user details
        $sql = "SELECT * FROM {$db->prefix}users " .
               "WHERE username = :username " .
               "AND password <> ''";

        $row = $db->query($sql, array(
            ':username' => $username
        ), true);

        // Check if the user exists
        if ($row != null)
        {
            if ($this->check_password('users', $row['password'], $password, $row['salt']))
            {
                // Update the session ID and details for the user
                $sql = "UPDATE {$db->prefix}users SET sid = :sid " .
                       "WHERE username = :username AND password <> ''";

                $db->query($sql, array(
                    ':username' => $username,
                    ':sid'      => $this->sid
                ));

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
                       "WHERE username = :username AND password = ''";

                $row = $db->query($sql, array(
                    ':username' => $username
                ), true);

                // If user is not found, insert one!
                if ($row == null)
                {
                    $sql = "INSERT INTO {$db->prefix}users " .
                           "(username, password, salt, email, dispname, sid, lastlogin) " .
                           "VALUES (:username, '', '', '', '', :sid, 0)";

                    $db->query($sql, array(
                        ':username' => $username,
                        ':sid'      => $this->sid
                    ));
                }

                // User was found, just update the session ID
                else
                {
                    $sql = "UPDATE {$db->prefix}users SET sid = :sid " .
                           "WHERE username = :username AND password = ''";

                    $db->query($sql, array(
                        ':username' => $username,
                        ':sid'      => $this->sid
                    ));
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
}

?>