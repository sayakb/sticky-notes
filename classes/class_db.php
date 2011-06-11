<?php
/**
* Sticky Notes pastebin
* @ver 0.1 
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

class db
{
    // Class wide variables
    var $mysqli;
    var $prefix;

    // Function to initialize a db connection
    function connect()
    {
        try
        {           
            global $config;
            
            $db_port_int = intval($config->db_port);
            $this->mysqli = new mysqli($config->db_host, $config->db_username, 
                                       $config->db_password, $config->db_name, $db_port_int);

            if ($this->mysqli->connect_error)
            {
                return $this->mysqli->connect_error;
            }
            else
            {
                $this->prefix = $config->db_prefix;
            }
        }
        catch (Exception $e)
        {
            return null;
        }
    }

    // Function to return a recordset
    function query($sql, $single = false)
    {
        try
        {
            $recordset = array();
            $result = $this->mysqli->query($sql);
            $sql = strtolower($sql);

            if (strpos($sql, 'select') !== false && strpos($sql, 'select') == 0)
            {
                if (!$result)
                {
                    $message  = 'Sticky Notes DB error<br /><br />Error: ' . $this->mysqli->error . "<br />";
                    $message .= 'Whole query: ' . $sql;
                    die($message);
                }

                if (!$single)
                {
                    while ($row = $result->fetch_assoc())
                    {
                        $recordset[] = $row;
                    }

                    $result->close();
                    return $recordset;
                }
                else
                {
                    $row = $result->fetch_assoc();
                    $result->close();

                    return $row;
                }
            }

            return true;
        }
        catch (Exception $e)
        {
            return null;
        }
    }

    // Function to get the last inserted query ID
    function get_id()
    {
        return $this->mysqli->insert_id;
    }

    // Function to check affected rows
    function affected_rows()
    {
        return $this->mysqli->affected_rows;
    }

    // Function to escape a special chars string
    function escape(&$data)
    {
        $data = $this->mysqli->real_escape_string($data);
    }

    // Object descturtor
    function __destruct()
    {
        $this->mysqli->close();
    }
}

?>