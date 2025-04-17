<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        
    }

    public function setemail() {
        $email = "xyz@gmail.com";
        $subject = "some text";
        $message = "some text";
        $this->sendEmail($email, $subject, $message);
    }

    public function sendEmail($email, $subject, $message, $file = "", $file2 = "") {
        //echo 'file'.$file2;die();
        $config = Array(
            'protocol' => 'smtp',
            'useragent' => 'CodeIgniter',
            'smtp_host' => SMTP_HOST,
            'smtp_port' => SMTP_PORT,
            'smtp_user' => SMTP_USER,
            'smtp_pass' => SMTP_PWD,
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE,
            'smtp_crypto' => 'tls',
            'smtp_timeout' => 5,
            'crlf' => "\r\n",
            'newline' => "\r\n"
        );
        $config1 = Array(
            'protocol' => 'mail',
            'useragent' => 'CodeIgniter'
        );
        $config1['mailpath'] = "/usr/bin/sendmail"; 
        $config1['protocol'] = "smtp";
        $config1['smtp_host'] = "localhost";
        $config1['smtp_port'] = "465";
        $config1['mailtype'] = 'html';
        $config1['charset'] = 'utf-8';
        $config1['crlf'] = "\r\n"; 
        $config1['newline'] = "\r\n";
        $config1['wordwrap'] = TRUE;
        $this->load->library('email');
        $this->email->clear(TRUE);
        
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
       $this->email->from(SMTP_FROM_ADDR,SITE_TITLE);
        $this->email->to($email);

        // echo "<pre>";
        // print_r($email);
        // print_r($config);exit;
       
        if(TEST_MODE){
            $this->email->cc(TEST_EMAILID);
        }
        //$this->email->to("suganya.p@ardhas.com,nithyashreemukund3@gmail.com");
        $this->email->subject($subject);
        $this->email->message($message);
        // print_R($file);
        // exit;
        if ($file != "") {
            $this->email->attach($file);
        }
         if ($file2 != "") {
            $this->email->attach($file2);
        }
        // if ($this->email->send()) {
        //     return 'Email send.';
        // } else {
        //      $this->email->print_debugger(array('headers'));
        // }

        if ($this->email->send()) {
            $from = "From : ". SMTP_FROM_ADDR;
            $to = "To : ". $email;
            
            $date = date('Y-m-d H:i:s');
            $data = "Mail Sent (" .$from ." " .$to ;
            $result = "\r\n" .$date. " - " . $subject ." >> ". "\r\n".$data;
            $log_name = "emailLogs";
            $file_path = APPPATH.'modules/lumut-api/logs/'.$log_name.'.txt';
            write_file($file_path, $result . "\r\n", 'a');
            
            return 'Email send.';
        } else {
            $date = date('Y-m-d H:i:s');
            $data = $this->email->print_debugger(array('headers'));
            $result = "\r\n" .$date. " - " . $subject ." >> ". "\r\n".$data;
            $log_name = "emailLogs";
            $file_path = APPPATH.'modules/lumut-api/logs/'.$log_name.'.txt';
            write_file($file_path, $result . "\r\n", 'a');
       
 
             return false;
        }
    }

}
