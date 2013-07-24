<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

class email
{
    // Global variables
    var $email_vars;
    var $mailer;

    // Class constructor
    function __construct()
    {
        global $config, $core;

        // Referene the SwiftMailer library
        require_once "{$core->root_dir}addons/swiftmailer/swift_required.php";

        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance($config->smtp_host, $config->smtp_port);

        // Set username and password
        if ($config->smtp_username || $config->smtp_password)
        {
            $transport->setUsername($config->smtp_username);
            $transport->setPassword($config->smtp_password);
        }

        // Set encryption
        if ($config->smtp_crypt)
        {
            $transport->setEncryption($config->smtp_crypt);
        }

        // Create the mailer using the transport
        $this->mailer = Swift_Mailer::newInstance($transport);
        $this->email_vars = array();
    }

    // Load a template and return its contents
    function load($file)
    {
        global $config, $core;

        // Load the template
        $tpl = realpath("{$core->root_dir}templates/email/{$config->lang_name}/{$file}.tpl");

        if (file_exists($tpl))
        {
            return file_get_contents($tpl);
        }
        else
        {
            return false;
        }
    }

    // Parses an email body
    function parse($data)
    {
        // Replace placeholder with values
        foreach($this->email_vars as $key => $value)
        {
            $data = str_replace("[[$key]]", $value, $data);
        }

        // Remove unknown placeholders
        $data = preg_replace('/\[\[(.*?)\]\]/', '', $data);

        // Done!
        return $data;
    }

    // Sends an email message
    function send($recipient, $subject, $body_tpl)
    {
        global $config;

        // Load the mail template
        $tpl = $this->load($body_tpl);

        if ($tpl !== false)
        {
            $body = $this->parse($tpl);

            // Create a message
            $message = Swift_Message::newInstance($subject)
                           ->setFrom($config->smtp_from)
                           ->setTo($recipient)
                           ->setContentType('text/html')
                           ->setBody($body);

            // Send the message
            return $this->mailer->send($message);
        }

        // Mail template not found
        return false;
    }

    // Function to assign email variables
    function assign($data, $value = "")
    {
        if (!is_array($data) && $value)
        {
            $this->email_vars[$data] = $value;
        }
        else
        {
            foreach ($data as $key => $value)
            {
                $this->email_vars[$key] = $value;
            }
        }
    }
}
?>
