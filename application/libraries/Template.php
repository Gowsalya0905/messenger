<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

/**
* Template class
*
* Displays webpage i.e(view page)
*
* @package CodeIgniter
* @subpackage Libraries
* @category Libraries
*/
class Template {

 
   private $CI;
   private $class_menu = '';
   private $class_parent = 'class="parent"';
   private $class_last = 'class="last"';

    public function __construct() {

       $this->CI = & get_instance();
    }

    public function load_common_template($data = array('content' => '', 'title' => '', 'site_title' => '', 'view_file' => '','current_menu'=>'')) {

       $data['leftmenu'] = buildMenu($data['current_menu']);
       $this->CI->load->view('common/header', $data);
     
       $this->CI->load->view('common/leftmenu', $data);

       $this->CI->load->view($data['view_file'], $data);
      
       $this->CI->load->view('common/footer', $data);
      
       
   }
    public function load_login_template($data = array('content' => '', 'title' => '', 'site_title' => '', 'view_file' => '')) {
        $this->CI->load->view('common/login/header', $data);
        $this->CI->load->view($data['view_file'], $data);
        $this->CI->load->view('common/login/footer', $data);
        
    }
    
    public function load_table_template($data = array('content' => '', 'title' => '', 'site_title' => '', 'view_file' => '','current_menu'=>'')) {

        $data['leftmenu'] = buildMenu($data['current_menu']);

        $this->CI->load->view('common/table/header', $data);
        $this->CI->load->view('common/leftmenu', $data);
        $this->CI->load->view($data['view_file'], $data);
        $this->CI->load->view('common/table/footer', $data);
    }

    public function load_table_exp_template($data = array('content' => '', 'title' => '', 'site_title' => '', 'view_file' => '','current_menu'=>'')) {

        $data['leftmenu'] = buildMenu($data['current_menu']);

        $this->CI->load->view('common/table/headerexport', $data);
        $this->CI->load->view('common/leftmenu', $data);
        $this->CI->load->view($data['view_file'], $data);
        $this->CI->load->view('common/table/footerexport', $data);
    }
    
     public function load_popup_template($data = array('content' => '', 'title' => '', 'site_title' => '', 'view_file' => '')) {
        $this->CI->load->view($data['view_file'], $data);
    }
     public function load_menupopup_template($data = array('content' => '', 'title' => '', 'site_title' => '', 'view_file' => '')) {
         $data['leftmenu'] = buildMenu($data['current_menu']);

        $this->CI->load->view('common/table/header', $data);
        $this->CI->load->view('common/leftmenu', $data);
        $this->CI->load->view($data['view_file'], $data);
        $this->CI->load->view('common/table/footer', $data);
    }
    
    public function load_email_template($data = array('content' => '', 'title' => '', 'site_title' => '', 'view_file' => '','current_menu'=> '')) {
       $emailtemp = $this->CI->load->view('common/email/header', $data,TRUE);
       $emailtemp .=  $this->CI->load->view($data['view_file'], $data,TRUE);
       $emailtemp .=  $this->CI->load->view('common/email/footer', $data,TRUE);
        return $emailtemp;
    }

    

}