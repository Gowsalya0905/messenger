<?php


class Login_model extends CI_Model {
    
    public $userlogtable = LOGIN;

    function __construct() {
        parent::__construct();
    }

    
    public function auth($username, $pwd) {
        $loginCheck= [
                    'USERNAME' => $username, 
                    'ENCRYPT_PASSWORD' => MD5($pwd)
                    ];
        
        $q1 = $this->db->get_where(LOGIN,$loginCheck);
      
        $newu = ($q1 != false && $q1->num_rows() > 0) ? $q1->row() : FALSE;
        if (($newu != FALSE) && ($newu->USER_LOG_STATUS == 'Y')) {
            return $newu;
        } else if (($newu != FALSE) && ($newu->USER_LOG_STATUS == 'N')) {
            return 'deactivated';
        } else {
            return FALSE;
        }
    }
    
  


}