<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

class nav
{
    // Class level variables
    var $rewrite_on;

    // Constructor
    function __construct()
    {
        $this->rewrite_on = $this->check_rewrite();
    }

    // Check if mod_rewrite is enabled or not
    function check_rewrite()
    {
        if (function_exists('apache_get_modules'))
        {
            $modules = apache_get_modules();
            return in_array('mod_rewrite', $modules);
        }
        else
        {
            return false;
        }
    }

    // Gets a root navigation path
    function get($nav_key, $project = '', $page = 1, $age = '')
    {
        try
        {
            global $core;

            // Set URL bases
            $base = $core->current_uri();

            $arg_project = !empty($project) ? '?project=' . $project : '?';
            $arg_page = $page > 1 ? "&page={$page}" : "";
            $arg_age = !empty($age) ? "&age={$age}" : "";

            $rewrite_base = $core->current_uri() . (!empty($project) ? "~{$project}/" : "");
            $rewrite_page = $page > 1 ? "{$page}/" : "";
            $rewrite_age = !empty($age) ? "{$age}/" : "";

            // URLs when rewrite is enabled
            $rewrite_ary = array(
                'nav_newpaste'      => $rewrite_base,
                'nav_archives'      => "{$rewrite_base}all/{$rewrite_page}",
                'nav_trending'      => "{$rewrite_base}trending/{$rewrite_age}",
                'nav_rss'           => "{$rewrite_base}rss/",
                'nav_api'           => "{$rewrite_base}doc/api/",
                'nav_help'          => "{$rewrite_base}doc/help/",
                'nav_about'         => "{$rewrite_base}doc/about/",
                'nav_admin'         => "{$base}admin/",
            );

            // URLs when rewrite is disabled
            $general_ary = array(
                'nav_newpaste'      => "{$base}{$arg_project}",
                'nav_archives'      => "{$base}list.php{$arg_project}{$arg_page}",
                'nav_trending'      => "{$base}list.php{$arg_project}{$arg_age}&trending=1",
                'nav_rss'           => "{$base}list.php{$arg_project}&rss=1",
                'nav_api'           => "{$base}doc.php{$arg_project}&cat=api",
                'nav_help'          => "{$base}doc.php{$arg_project}&cat=help",
                'nav_about'         => "{$base}doc.php{$arg_project}&cat=about",
                'nav_admin'         => "{$base}admin/",
            );

            // Generate the navigation URL
            if ($this->rewrite_on)
            {
                $url = $rewrite_ary[$nav_key];
            }
            else
            {
                $url = $general_ary[$nav_key];
            }

            return $url;
        }
        catch (Exception $e)
        {
            return null;
        }
    }

    // Get the URL for a paste
    function get_paste($paste_id, $paste_key, $hash, $project, $format = '')
    {
        global $core, $config;

        try
        {
            // Determine whether to use ID or key
            if ($config->url_key_enabled && !empty($paste_key))
            {
                $key = 'p' . $paste_key;
            }
            else
            {
                $key = $paste_id;
            }

            if ($this->rewrite_on)
            {
                $url = $core->current_uri() . (!empty($project) ? "~{$project}/" : "") .
                                              "{$key}/" .
                                              (!empty($hash) ? "{$hash}/" : "") .
                                              (!empty($format) ? "{$format}/" : "");
            }
            else
            {
                $url = $core->current_uri() . "show.php?id={$key}" .
                                              (!empty($hash) ? "&hash={$hash}" : "") .
                                              (!empty($project) ? "&project={$project}" : "") .
                                              (!empty($format) ? "&mode={$format}" : "");
            }

            return $url;
        }
        catch (Exception $e)
        {
            return null;
        }
    }
}

?>