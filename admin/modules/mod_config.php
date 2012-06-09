<?php
/**
* Sticky Notes pastebin
* @ver 0.3
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Collect some data
$config_name = $core->variable('config_name', $config->site_name);
$config_title = $core->variable('config_title', $config->site_title);
$config_copyright = $core->variable('config_copyright', $config->site_copyright);
$config_skin = $core->variable('config_skin', $config->skin_name);
$config_lang = $core->variable('config_lang', $config->lang_name);
$config_admin_skin = $core->variable('config_admin_skin', $config->admin_skin_name);
$config_admin_lang = $core->variable('config_admin_lang', $config->admin_lang_name);
$config_sg_svcs = $core->variable('config_sg_svcs', explode(',', $config->sg_services));
$config_php_key = $core->variable('config_php_key', $config->sg_php_key);
$config_php_days = $core->variable('config_php_days', $config->sg_php_days);
$config_php_score = $core->variable('config_php_score', $config->sg_php_score);
$config_php_type = $core->variable('config_php_type', $config->sg_php_type);
$config_censor = $core->variable('config_censor', $config->sg_censor);

$config_save = isset($_POST['config_save']);

// Save button was pressed
if ($config_save)
{
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
        // Update configuration data
        $config->site_name       = $config_name;
        $config->site_title      = $config_title;
        $config->site_copyright  = $config_copyright;
        $config->skin_name       = $config_skin;
        $config->lang_name       = $config_lang;
        $config->admin_skin_name = $config_admin_skin;
        $config->admin_lang_name = $config_admin_lang;
        
        $config->sg_services     = implode(',', $config_sg_svcs);
        $config->sg_php_key      = $config_php_key;
        $config->sg_php_days     = $config_php_days;
        $config->sg_php_score    = $config_php_score;
        $config->sg_php_type     = $config_php_type;
        $config->sg_censor       = $config_censor;
        
        $config->save();
        $module->notify($lang->get('changes_saved'));
    }
}

// Generate the skin and language lists
$skin_list       = $skin->get_list('../skins', "", $config_skin, true);
$lang_list       = $skin->get_list('../lang', "index.html", $config_lang, false, true);
$admin_skin_list = $skin->get_list('./skins', "", $config_admin_skin, true);
$admin_lang_list = $skin->get_list('./lang', "index.html", $config_admin_lang, false, true);

// Generate the anti-spam services list
if (!is_array($config_sg_svcs))
{
    $config_sg_svcs = explode(',', $config_sg_svcs);
}

$available_svcs = $sg->get_registered();
$sg_svcs = '';

foreach ($available_svcs as $svc)
{
    $selected =  (in_array($svc, $config_sg_svcs));    
    $sg_svcs .= '<option' . ($selected ? ' selected="selected"' : '') . '>' .
                $svc . '</option>';
}

// Assign skin data
$skin->assign(array(
    'config_name'         => $config_name,
    'config_title'        => $config_title,
    'config_copyright'    => $config_copyright,
    'config_php_key'      => $config_php_key,
    'config_php_days'     => $config_php_days,
    'config_php_score'    => $config_php_score,
    'config_php_type'     => $config_php_type,
    'config_censor'       => $config_censor,
    'skin_list'           => $skin_list,
    'lang_list'           => $lang_list,
    'admin_skin_list'     => $admin_skin_list,
    'admin_lang_list'     => $admin_lang_list,
    'sg_svcs'             => $sg_svcs,
));

// Set the page title
$module_title = $lang->get('site_config');
$module_data  = $skin->output('tpl_config_main', true, true);

?>
 
