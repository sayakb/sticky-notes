<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Collect some data
$config_name = $core->variable('config_name', '');
$config_title = $core->variable('config_title', '');
$config_copyright = $core->variable('config_copyright', '');
$config_skin = $core->variable('config_skin', '');
$config_admin_skin = $core->variable('config_admin_skin', '');
$config_lang = $core->variable('config_lang', '');
$config_sg_svcs = $core->variable('config_sg_svcs', array(''));
$config_php_key = $core->variable('config_php_key', '');
$config_php_days = $core->variable('config_php_days', 0);
$config_php_score = $core->variable('config_php_score', 0);
$config_php_type = $core->variable('config_php_type', 0);

$config_save = isset($_POST['config_save']) ? true : false;

// Save button was pressed
if ($config_save)
{
    // Make the data markup friendly
    $config_name = htmlentities($config_name);
    $config_title = htmlentities($config_title);
    $config_copyright = htmlentities($config_copyright);
    $config_skin = htmlentities($config_skin);
    $config_admin_skin = htmlentities($config_admin_skin);
    $config_lang = htmlentities($config_lang);
    $config_sg_svcs = htmlentities(implode(',', $config_sg_svcs));
    $config_php_key = htmlentities($config_php_key);
    
    // Validate required fields
    if (empty($config_name) || empty($config_title) || empty($config_copyright) ||
        empty($config_skin) || empty($config_admin_skin) || empty($config_lang))
    {
        $module->notify($lang->get('config_reqd'));
    }
    
    // Check if the file is writable
    else if (!is_writable(realpath('../config.php')))
    {
        $module->notify($lang->get('config_cantwrite'));
    }
    
    // Write the conf data
    else
    {
        $fp = fopen(realpath('../config.php'), 'w');
        
        fwrite($fp, "<?php\n");
        fwrite($fp, "// Sticky Notes Pastebin configuration file\n");
        fwrite($fp, "// (C) 2011 Sayak Banerjee. All rights reserved\n\n");
        fwrite($fp, "/// This is an auto generated file\n");
        fwrite($fp, "/// Please DO NOT modify manually\n");
        fwrite($fp, "/// Unless you are absolutely sure what you're doing ;-)\n\n");
        
        fwrite($fp, '$db_host = "' . $config->db_host . '";' . "\n");
        fwrite($fp, '$db_port = "' . $config->db_port . '";' . "\n");
        fwrite($fp, '$db_name = "' . $config->db_name . '";' . "\n");
        fwrite($fp, '$db_username = "' . $config->db_username . '";' . "\n");
        fwrite($fp, '$db_password = "' . $config->db_password . '";' . "\n");
        fwrite($fp, '$db_prefix = "' . $config->db_prefix . '";' . "\n\n");
        
        fwrite($fp, '$site_name = "' . $config_name . '";' . "\n");
        fwrite($fp, '$site_title = "' . $config_title . '";' . "\n");
        fwrite($fp, '$site_copyright = "' . $config_copyright . '";' . "\n");
        fwrite($fp, '$skin_name = "' . $config_skin . '";' . "\n");
        fwrite($fp, '$admin_skin_name = "' . $config_admin_skin . '";' . "\n");
        fwrite($fp, '$lang_name = "' . $config_lang . '";' . "\n\n");

        fwrite($fp, '$sg_services = "' . $config_sg_svcs . '";' . "\n");
        fwrite($fp, '$sg_php_key = "' . $config_php_key . '";' . "\n");
        fwrite($fp, '$sg_php_days = ' . $config_php_days . ';' . "\n");
        fwrite($fp, '$sg_php_score = ' . $config_php_score . ';' . "\n");
        fwrite($fp, '$sg_php_type = ' . $config_php_type . ';' . "\n");

        fwrite($fp, "?>");
        fclose($fp);
        
        // Redirect to refresh
        $core->redirect($core->path() . 'config/');
    }
}

// Generate the skins list
$skin_dir = opendir(realpath('../skins'));
$skin_list = '';
$skin_entries = array();

while ($entry = readdir($skin_dir))
{
    if ($entry != '.' && $entry != '..')
    {
        $skin_entries[] = strtoupper(substr($entry, 0, 1)) . 
                          substr($entry, 1, strlen($entry) - 1);
    }
}

sort($skin_entries);

foreach($skin_entries as $entry)
{
    if (strtolower($entry) == $skin->skin_name)
    {
        $selected = true;
    }
    else
    {
        $selected = false;
    }
    
    $skin_list .= '<option' . ($selected ? ' selected="selected"' : '') .
                    '>' . $entry . '</option>';
}

// Generate the admin skins list
$admin_skin_dir = opendir(realpath('./skins'));
$admin_skin_list = '';
$admin_skin_entries = array();

while ($entry = readdir($admin_skin_dir))
{
    if ($entry != '.' && $entry != '..')
    {
        $admin_skin_entries[] = strtoupper(substr($entry, 0, 1)) . 
                                substr($entry, 1, strlen($entry) - 1);
    }
}

sort($admin_skin_entries);

foreach($admin_skin_entries as $entry)
{
    if (strtolower($entry) == $skin->admin_skin_name)
    {
        $selected = true;
    }
    else
    {
        $selected = false;
    }
    
    $admin_skin_list .= '<option' . ($selected ? ' selected="selected"' : '') .
                    '>' . $entry . '</option>';
}

// Generate the anti-spam services list
$enabled_svcs = explode(',', $config->sg_services);
$available_svcs = $sg->get_registered();
$sg_svcs = '';

foreach ($available_svcs as $svc)
{
    if (in_array($svc, $enabled_svcs))
    {
        $selected = true;
    }
    else
    {
        $selected = false;
    }
    
    if ($svc == 'ipban')
    {
        continue;
    }
    
    $sg_svcs .= '<option' . ($selected ? ' selected="selected"' : '') . '>' .
               $svc . '</option>';
}

// Assign skin data
$skin->assign(array(
    'config_name'         => $config->site_name,
    'config_title'        => $config->site_title,
    'config_copyright'    => $config->site_copyright,
    'config_lang'         => $config->lang_name,
    'config_php_key'           => $config->sg_php_key,
    'config_php_days'          => $config->sg_php_days,
    'config_php_score'         => $config->sg_php_score,
    'config_php_type'          => $config->sg_php_type,
    'skin_list'         => $skin_list,
    'admin_skin_list'   => $admin_skin_list, 
    'sg_svcs'           => $sg_svcs,
));

// Set the page title
$module_title = $lang->get('site_config');
$module_data =  $skin->output('tpl_config_main', true, true);

?>
 
