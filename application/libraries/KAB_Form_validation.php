<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class KAB_Form_validation extends CI_Form_validation {

    public function alpha_dash_space($fullname) {
     $ci = & get_instance(); 
     
    if (!preg_match('/^[a-zA-Z0-9\s,.\/]+$/i', $fullname)) {
       $ci->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters , White spaces and /,. are allowed');
      // $this->CI->form_validation->set_message(  _FUNCTION_ , 'The %s field may only contain alpha characters & White spaces');
        return FALSE;
    } else {
        return TRUE;
    }
}

public function alpha_dash_space_slash_bracket($fullname) {
     $ci = & get_instance(); 
     
    if (!preg_match('/^[a-zA-Z\s()\/]+$/', $fullname)) {
       $ci->form_validation->set_message('alpha_dash_space_slash_bracket', 'The %s field may only contain alpha characters & White spaces,/()special characters ');
     
        return FALSE;
    } else {
        return TRUE;
    }
}

 public function addr_line($addr_line1) {
     $ci = & get_instance(); 
    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $addr_line1)) {
      $ci->form_validation->set_message('addr_line', 'Should contain only space,comma,dot,numbers and alphabets.');
     //$this->CI->form_validation->set_message(  _FUNCTION_ , 'Shouls contain only space,comma,dot,numbers and alphabets.');
        } else {
        return true;
    }
}

 public function description_line($desc_line1) {
     $ci = & get_instance(); 
    if (preg_match('/^[A-Za-z0-9,.()s\/]+$/', $desc_line1)) {
      $ci->form_validation->set_message('description_line', 'Should contain only space,comma,dot,numbers,alphabets and ().');
       } else {
        return true;
    }
}

public function is_unique($str, $field) {
    if (substr_count($field, '.') == 3) {
        list($table, $field, $id_field, $id_val) = explode('.', $field);
        $query = $this->CI->db->limit(1)->where($field, $str)->where($id_field . ' != ', $id_val)->get($table);
    } else if (substr_count($field, '.') == 5) {
        list($table, $field, $id_field, $id_val,$where_field,$where_val) = explode('.', $field);
        $query = $this->CI->db->limit(1)->where($field, $str)->where($id_field . ' != ', $id_val)->where($where_field, $where_val)->get($table);
       
    }
    else if (substr_count($field, '.') == 7) {
        list($table, $field, $id_field, $id_val,$where_field,$where_val,$where_field1,$where_val1) = explode('.', $field);
        $query = $this->CI->db->limit(1)->where($field, $str)->where($id_field . ' != ', $id_val)->where($where_field,$where_val)->where($where_field1, $where_val1)->get($table);
       
    }
    else {
        list($table, $field) = explode('.', $field);
        $query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
    }
    return ($query)? $query->num_rows()===0:$query;
}
public function set_group_rules($group = '') {
        // Is there a validation rule for the particular URI being accessed?
        $uri = ($group == '') ? trim($this->CI->uri->ruri_string(), '/') : $group;

        if ($uri != '' AND isset($this->_config_rules[$uri])) {
            $this->set_rules($this->_config_rules[$uri]);
            return true;
        }
        return false;
    }

}