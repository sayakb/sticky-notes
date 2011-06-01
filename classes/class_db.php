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

    // Function to initialize a db connection
    function connect($host, $port, $name, $user, $pass)
    {
        try
        {
            $port = intval($port);
            $this->mysqli = new mysqli($host, $user, $pass, $name, $port);

            if ($this->mysqli->connect_error)
            {
                return $this->mysqli->connect_error;
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