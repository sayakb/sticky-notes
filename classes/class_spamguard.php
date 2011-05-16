<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

class spamguard
{
    // Sticky Notes' inbuilt Stealth Spam Guard
    function validate_stealth()
    {
        global $language, $data, $skin, $lang, $paste_submit;
        
        $html_exists = strpos(strtolower($data), '<a href') >= 0 ? true : false;
            
        if ($html_exists && $paste_submit && $language != 'html4strict')
        {   
            // Show a bounce message
            $skin->assign(array(
                'msg_visibility'	=> 'visible',
                'error_visibility'	=> 'hidden',
                'message_text'		=> $lang->get('stealth_error'),
                'msg_color'     	=> 'red',
            ));
            
            // Assign template data
            $skin->assign(array(
                'post_lang_list'        => $skin->output('tpl_languages'),
                'error_visibility'      => 'hidden',
            ));

            // Output the page
            $skin->title($lang->get('create_new') . ' &bull; ' . $lang->get('site_title'));
            $skin->output();
            exit;
        }
        else
        {
            // Hooray!! Not spam :D
        }
    }
    
    // Function to query Project Honey Pot
    // Info: http://www.projecthoneypot.org/
    function validate_php()
    {
	try
	{
	    // Set global variables
	    global $core, $lang, $sg_php_key, $sg_php_days, $sg_php_score, $sg_php_type;
	    
	    // Skip validation is no key is specified in config.php
	    if (!isset($sg_php_key) || empty($sg_php_key))
	    {
		return;
	    }
	    
	    // Check config values
	    $sg_php_days = isset($sg_php_days) ? $sg_php_days : 90;
	    $sg_php_score = isset($sg_php_score) ? $sg_php_score : 50;
	    $sg_php_type = isset($sg_php_type) ? $sg_php_type : 2;
	    
	    // We cannot process an IPv6 address
	    if(!filter_var($core->remote_ip(), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) 
	    {
		return;
	    }
	    
	    // Convert IP address to reversed octet format
	    $ip_ary = explode('.', $core->remote_ip());
	    $rev_ip = $ip_ary[3] . '.' . $ip_ary[2] . '.' . $ip_ary[1] . '.' . $ip_ary[0];
	    
	    // Query Project Honey Pot
	    $response = dns_get_record($sg_php_key . '.' . $rev_ip . '.dnsbl.httpbl.org');
	    
	    // Exit if NXDOMAIN is returned
	    if (!isset($response[0]['ip']) || empty($response[0]['ip']))
	    {
		return;
	    }
		
	    // Extract the info
	    $result = explode('.', $response[0]['ip']);
	    $days = $result[1];
	    $score = $result[2];
	    $type = $result[3];
	    
	    // Perform PHP validation
	    if ($days <= $sg_php_days && ($type >= $sg_php_type || $score >= $sg_php_score))
	    {
		die($lang->get('php_malicious'));
	    }
	}
	catch (Exception $e)
        {
            return;
        }
    }
}

?>
