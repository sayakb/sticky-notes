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
    var $link;

    // Function to initialize a db connection
    function connect($host, $port, $name, $user, $pass)
    {
        try
        {
            if ($port)
            {
                $host .= (":" . $port);
            }

            $this->link = mysql_connect($host, $user, $pass);

            if (!isset($this->link))
            {
                return mysql_error();
            }
            else
            {
                mysql_select_db($name, $this->link);
                mysql_set_charset('utf8', $this->link);
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
            $result = mysql_query($sql, $this->link);
            $sql = strtolower($sql);

            if (strpos($sql, 'select') !== false && strpos($sql, 'select') == 0)
            {
                if (!$result)
                {
                    $message  = 'Sticky Notes DB error<br /><br />Error: ' . mysql_error() . "<br />";
                    $message .= 'Whole query: ' . $sql;
                    die($message);
                }

                if (!$single)
                {
                    while ($row = mysql_fetch_assoc($result))
                    {
                        $this->unescape($row);
                        $recordset[] = $row;
                    }

                    mysql_free_result($result);
                    return $recordset;
                }
                else
                {
                    $row = mysql_fetch_assoc($result);

                    $this->unescape($row);
                    mysql_free_result($result);

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
        return mysql_insert_id($this->link);
    }

    // Function to check affected rows
    function affected_rows()
    {
        return mysql_affected_rows($this->link);
    }

    // Function to escape a special chars string
    function escape(&$data)
    {
        $data = mysql_real_escape_string($data);
    }

    // Function to unescape the characters in a string
    function unescape(&$data)
    {
        $search = array("\'", '\"');
        $replace = array("'", '"');

        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                $data[$key] = str_replace($search, $replace, $val);
            }
        }
        else
        {
            $data = str_replace($search, $replace, $data);
        }
    }

    // Object descturtor
    function __destruct()
    {
        if (isset($this->link))
        {
            mysql_close($this->link);
            unset($this->link);
        }
    }
}

?>