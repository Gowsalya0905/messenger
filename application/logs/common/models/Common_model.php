<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        //$this->load->database();
        
    }

     public function deleteData($table, $where = array()) {
        $this->db->delete($table, $where);
        return $this->db->affected_rows();
    }

    public function updateInfo($table, $data, $returnid = '', $where = array())
    {
        if (count($where) > 0) {
            $this->db->where($where);
            $result = $this->db->get($table);
        } else {
            $result = FALSE;
        }

        if ($result != false && $result->num_rows() > 0 && $result->num_rows() != '') {
            $this->db->where($where);
            $this->db->update($table, $data);
            //$this->db->affected_rows();
            return $result->row()->$returnid;
        } else {
            $this->db->insert($table, $data);
            $insert_id = $this->db->insert_id();
            //echo $this->db->last_query();exit;
            return $insert_id;
        }
    }
    public function getSingledata($table, $where = array(), $limit = "", $offset = "", $orderby = "", $disporder = "") {
        if (count($where) > 0)
            $this->db->where($where);

        if ($orderby != "" && $disporder != "")
            $this->db->order_by($orderby, $disporder);
        else
            $this->db->order_by("id", "asc");

        if ($limit != "" && $offset != "")
            $this->db->limit($limit, $offset);

        $result = $this->db->get($table);
        if ($result != false && $result->num_rows() > 0)
            return $result->row();
        else
            return false;
    }
    

    public function getAlldata($table, $columns = [], $options = array(), $limit = "", $offset = "", $orderby = "", $disporder = "")
    {

        if (is_array($columns) && count($columns) > 0) {
            foreach ($columns as $col) {
                $allColumns[] = $col;
                // $this->db->select($col)
            }
            $implodeColumns = implode(",", $allColumns);
            $this->db->select($implodeColumns);
        } else {
            $this->db->select($columns);
        }

        $this->db->from($table);

        if (isset($options['where']) && !empty($options['where'])) {
            $optwhere = $options['where'];
            foreach ($optwhere as $wKey => $wVal) {
                $this->db->where($wKey, $wVal);
            }
        }
        if (isset($options['where_in']) && !empty($options['where_in'])) {
            $where_in = $options['where_in'];
            foreach ($where_in as $key => $val) {
                $this->db->where_in($key, $val);
            }
        }
        if (isset($options['where_not_in']) && !empty($options['where_not_in'])) {
            $where_not_in = $options['where_not_in'];
            foreach ($where_not_in as $key => $val) {
                $this->db->where_not_in($key, $val);
            }
        }

        if (isset($options['like']) && !empty($options['like'])) {
            foreach ($options['like'] as $key => $value) {
                $this->db->like($key, $value);
            }
        }

        if (isset($options['or_like']) && !empty($options['or_like'])) {
            foreach ($options['or_like'] as $key => $value) {
                $this->db->or_like($key, $value);
            }
        }

        if (isset($options['join']) && !empty($options['join'])) {
            foreach ($options['join'] as $joinKey => $joinVal) {
                $this->db->join($joinKey, $joinVal[0], $joinVal[1]);
            }
        }

        if (isset($options['find_in_set']) && !empty($options['find_in_set'])) {
            foreach ($options['find_in_set'] as $findKey => $findVal) {
                $this->db->join($findKey, 'find_in_set( ' . $findVal[0] . ') > 0 ', $findVal[1]);
            }
        }

        if (isset($options['orwhere']) && count($options['orwhere']) > 0) {
            $this->db->group_start();
            foreach ($options['orwhere'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
            $this->db->group_end();
        }

        if (isset($options['orwhere_new_group']) && count($options['orwhere_new_group']) > 0) {
            $this->db->or_group_start();
            foreach ($options['orwhere_new_group'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
            $this->db->group_end();
        }


        if (isset($options['group_by']) && !empty(($options['group_by']))) {
            $this->db->group_by($options['group_by']);
        }

        if ($orderby != "" && $disporder != "") {
            $this->db->order_by($orderby, $disporder);
        }

        if ($limit != "" && $offset != "") {
            $this->db->limit($limit, $offset);
        }

      

        $result = $this->db->get();

  

        //       $returnTyp = (isset($options['return_type']) && !empty($options['return_type']) ? $options['return_type'] : '');

        if ($result != false && $result->num_rows() > 0) {
            if (isset($options['return_type'])) {
                if ($options['return_type'] == 'result') {
                    return $result->result();
                } else {

                    return $result->row();
                }
            } else {
                return $result->result();
            }
        } else {
            return false;
        }
    }

    public function updateData($table, $data, $where = array(), $insertValue = '')
    {

        if (count($where) > 0) {
            $this->db->where($where);
            return $this->db->update($table, $data);
        } else {
            $this->db->insert($table, $data);
            if ($insertValue == '') {
                $insert_id = $this->db->insert_id();
            } else {
                $insert_id = $insertValue;
            }

            return $insert_id;
        }
    }

    function get_datatables($table, $column_order, $column_search, $order, $where = array(), $options = [])
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $where, $options);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        $query = $this->db->get();
        //       echo $this->db->last_query();exit;
        if ($query !=  FALSE && $query->num_rows() > 0) {

            return $query->result();
        } else {
            return false;
        }
    }

    private function _get_datatables_query($table, $column_order, $column_search, $order, $where, $options = [])
    {
        $findInset = isset($options['find_in_set']) ? $options['find_in_set'] : FALSE;

        if (isset($options['select']) && $options['select'] != '') {
            $this->db->select($options['select']);
        } else {
            $this->db->select('*');
        }
        $this->db->from($table);

        if (isset($options['join']) && !empty($options['join'])) {
            foreach ($options['join'] as $joinKey => $joinVal) {

                $this->db->join($joinKey, $joinVal[0], $joinVal[1]);
            }
        }

        $i = 0;

        foreach ($column_search as $item) { // loop column 
            if (isset($this->input->post('search')['value'])) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }

                if (count($column_search) - 1 === $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }


        if (isset($options['where_in']) && !empty($options['where_in'])) {
            $where_in = $options['where_in'];
            foreach ($where_in as $key => $val) {
                $this->db->where_in($key, $val);
            }
        }

        /*CUSTOM QUERY*/
        if (isset($options['findinnew']) && count($options['findinnew']) > 0) {
            $this->db->group_start();
            foreach ($options['findinnew'] as $key => $val) {
                $this->db->where('FIND_IN_SET("' . $val . '",' . $key . ') !=0');
            }
            if(isset($options['orwhere']) && count($options['orwhere']) > 0) {
            foreach ($options['orwhere'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
            }
            $this->db->group_end();
            if (isset($options['where']) && count($options['where']) > 0) {
                $this->db->where($options['where']);
            }
        } else if (isset($options['orwhere']) && count($options['orwhere']) > 0) {
            $this->db->group_start();
            foreach ($options['orwhere'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
            $this->db->group_end();
            if (isset($options['where']) && count($options['where']) > 0) {
                $this->db->where($options['where']);
            }

        } else {
            if (is_array($where) && count($where) > 1) {
                foreach ($where as $whKey => $whVal) {
                    $this->db->where($whKey, $whVal);
                }
            } else {
                $this->db->where($where);
            }
        }

        if (isset($options['like']) && !empty($options['like'])) {
            foreach ($options['like'] as $key => $value) {
                $this->db->like($key, $value);
            }
        }

        if (isset($options['between']) && !empty($options['between'])) {
            $currentDateandTime = date("Y-m-d H:i:s");

            $this->db->where(" '$currentDateandTime' BETWEEN theorystartdttime AND theoryenddttime");
           
        }


        if (isset($options['where_new']) && !empty($options['where_new'])) {
            $optwhere = $options['where_new'];
            foreach ($optwhere as $wKey => $wVal) {
                $this->db->where($wKey, $wVal);
            }
        }
        if (isset($options['orwhere_new']) && count($options['orwhere_new']) > 0) {
            $this->db->group_start();
            foreach ($options['orwhere_new'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
            $this->db->group_end();
        }

         if (isset($options['orwhere_new_group']) && count($options['orwhere_new_group']) > 0) {
            $this->db->or_group_start();
            foreach ($options['orwhere_new_group'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
            $this->db->group_end();
        }


        if ($findInset != FALSE) {
            $this->db->group_start();
            foreach ($findInset as $key => $val) {
                $this->db->where('FIND_IN_SET("' . $val . '",' . $key . ') !=0');
            }

            if (isset($where['where']) && count($where['where']) > 0) {
                $this->db->where($where['where']);
            }
            if (isset($where['orwhere']) && count($where['orwhere']) > 0) {
                foreach ($where['orwhere'] as $key => $val) {
                    $this->db->or_where($key, $val);
                }
                if (isset($where['where']) && count($where['where']) > 0) {
                    $this->db->where($where['where']);
                }
            }
            $this->db->group_end();
        } else {
            if (isset($where['where']) && count($where['where']) > 0) {
                $this->db->where($where['where']);
            }
            if (isset($where['orwhere']) && count($where['orwhere']) > 0) {
                foreach ($where['orwhere'] as $key => $val) {
                    $this->db->or_group_start();
                    $this->db->or_where($key, $val);
                    if (isset($where['where']) && count($where['where']) > 0) {
                        $this->db->where($where['where']);
                    }
                    if (isset($where['notin']) && count($where['notin']) > 0) {
                        foreach ($where['notin'] as $key => $val) {
                            $this->db->where_not_in($key, $val);
                        }
                    }
                    $this->db->group_end();
                }
                if (isset($where['where']) && count($where['where']) > 0) {
                    $this->db->where($where['where']);
                }
            }
        }

        if (isset($options['group_by']) && !empty($options['group_by'])) {
            $groupBy = $options['group_by'];
            $this->db->group_by($groupBy);
        }
        if ($this->input->post('order') == "") {
            $this->db->order_by(key($order), $order[key($order)]);
        } else if ($this->input->post('order')) { // here order processing
            if (isset($column[$_REQUEST['order']['0']['column']]))
                $this->db->order_by($column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


    public function count_all($table, $column_order, $column_search, $order, $where = array(), $optns = [])
    {

        if (is_array($where) && count($where) > 1) {
                foreach ($where as $whKey => $whVal) {
                    $this->db->where($whKey, $whVal);
                }
            } else {
                $this->db->where($where);
            }
        $this->db->from($table);
        return $this->db->count_all_results();
    }

    function count_filtered($table, $column_order, $column_search, $order, $where = array(), $optns = [])
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $where, $optns);

        

        $query = $this->db->get();
        if ($query !=  FALSE && $query->num_rows() > 0) {
            $res =  $query->num_rows();
        } else {
            $res = 0;
        }


        return $res;
    }

    public function getUsertoken($ids)
    {
        $this->db->select("APK_TOKEN,APK_USER_ID");
        $this->db->from(APK_TOKEN);
        $this->db->where_in("APK_USER_ID", explode(",", $ids));
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        return $query->result();
    }

    public function _get_datatables_query_notify($term = '', $where = array(), $opion = array())
    { //term is value of $_REQUEST['search']['value']

        $returnType = isset($opion['returntype']) ? $opion['returntype'] : 'row';
        $findInset = isset($opion['find_in_set']) ? $opion['find_in_set'] : FALSE;
        $orderby = isset($optional['orderby']) ? $optional['orderby'] : FALSE;
        $disporder = isset($optional['disporder']) ? $optional['disporder'] : FALSE;
        $limit = isset($optional['limit']) ? $optional['limit'] : FALSE;
        $offset = isset($optional['offset']) ? $optional['offset'] : FALSE;
        $groupby = isset($optional['groupby']) ? $optional['groupby'] : FALSE;
        $column = array('NOTIFICATION_DESC', 'CREATED_ON', 'NOTIFICATION_IS_PUBLIC');
        $this->db->select('notify.*');
        $this->db->from(NOTI . ' as notify');
        $term = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : FALSE;
        if ($term != '') {
            if (count($column) > 0) {
                foreach ($column as $col) {
                    $this->db->or_like($col, $term);
                }
            }
        }
        if (count($where) > 0) {
            if (isset($where['notin']) && count($where['notin']) > 0) {
                foreach ($where['notin'] as $key => $val) {
                    $this->db->where_not_in($key, $val);
                }
            }

            if ($findInset != FALSE) {
                $this->db->group_start();
                foreach ($findInset as $key => $val) {
                    $this->db->where('FIND_IN_SET("' . $val . '",' . $key . ') !=0');
                }

                if (isset($where['where']) && count($where['where']) > 0) {
                    $this->db->where($where['where']);
                }
                if (isset($where['orwhere']) && count($where['orwhere']) > 0) {
                    foreach ($where['orwhere'] as $key => $val) {
                        $this->db->or_where($key, $val);
                    }
                    if (isset($where['where']) && count($where['where']) > 0) {
                        $this->db->where($where['where']);
                    }
                }
                $this->db->group_end();
            } else {
                if (isset($where['where']) && count($where['where']) > 0) {
                    $this->db->where($where['where']);
                }
                if (isset($where['orwhere']) && count($where['orwhere']) > 0) {
                    foreach ($where['orwhere'] as $key => $val) {
                        $this->db->or_group_start();
                        $this->db->or_where($key, $val);
                        if (isset($where['where']) && count($where['where']) > 0) {
                            $this->db->where($where['where']);
                        }
                        if (isset($where['notin']) && count($where['notin']) > 0) {
                            foreach ($where['notin'] as $key => $val) {
                                $this->db->where_not_in($key, $val);
                            }
                        }
                        $this->db->group_end();
                    }
                    if (isset($where['where']) && count($where['where']) > 0) {
                        $this->db->where($where['where']);
                    }
                }
            }
        }
        if ($groupby != FALSE) {
            $this->db->group_by($groupby);
        }

        if ($orderby != "" && $disporder != "")
            $this->db->order_by($orderby, $disporder);
        else
            $this->db->order_by('notify.NOTIFICATION_ID', "DESC");

        if ($limit != "" && $offset != "")
            $this->db->limit($limit, $offset);

        if (isset($_REQUEST['order'])) { // here order processing
            $this->db->order_by($column[$_REQUEST['order']['0']['column']], $_REQUEST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables_notify($where = array(), $options = array())
    {

        $this->_get_datatables_query_notify($term = '', $where, $options);
        $length = $this->input->post('length', TRUE);
        $start = $this->input->post('start', TRUE);
        if ($length != -1)
            $this->db->limit($length, $start);
        $query = $this->db->get();

        return $query->result();
    }

    public function count_filtered_notify()
    {
        $this->_get_datatables_query_notify($term = '');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_notify()
    {
        $this->_get_datatables_query_notify($term = '');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function uniqueId($key_word, $table, $select)
    {
        $cr_id = $this->getId($table, $select);
        //print_r($cr_id);exit;
        if ($cr_id == "") {

            $new_cr_id = $key_word . '-00001';
        } else {

            $old_cr_id = explode('-', $cr_id);

            $old_cr_id = $old_cr_id[0];

            str_pad($old_cr_id, 5, 4);
            $new_cr_id = $old_cr_id + 1;
            $length = strlen($new_cr_id);

            if ($length == 1) {
                $newapp = $key_word . '-0000';
            } else if ($length == 2) {
                $newapp = $key_word . '-000';
            } else if ($length == 3) {
                $newapp = $key_word . '-00';
            } else if ($length == 4) {
                $newapp = $key_word . '-0';
            } else if ($length == 5) {
                $newapp = $key_word . '-';
            }

            $new_cr_id = $newapp . $new_cr_id;
        }

        return $new_cr_id;
    }


    public function api_log($post_data)
    {
        $this->load->helper('file');
        $url = $this->uri->segment_array();
        $log_name =  end($url);
        $filedata = json_encode($post_data, JSON_PRETTY_PRINT);
        if (!write_file(APPPATH . 'modules/lumut-api/logs/' . $log_name . '.txt', $filedata)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function batchinsertdata($table, $data)
    {
        $this->db->insert_batch($table, $data);
        //echo $this->db->last_query();
        return 1;
    }
    public function Checkbasicinfo($forgetUser)
    {
        $query = $query1 = "";
        $status = "Y";

        $query = $this->db->query("SELECT EMP_ID,EMP_EMAIL_ID as emailId from ".EMPL." WHERE  EMP_ID='" . $forgetUser . "' AND EMP_STATUS='" . $status . "'")->row_array();

        // if (empty($query)) {

        //     $query1 = $this->db->query("SELECT CONT_ID,CONT_EMAIL_ID as emailId from CONTRACTOR_DETAILS WHERE  CONT_ID='" . $forgetUser . "' AND CONT_STATUS='" . $status . "'")->row_array();
        // }
        if ($query != "") {
            return $query;
        } else if ($query1 != "") {
            return $query1;
        } else {
            return 0;
        }
    }
    public function UpdateNewPassword($forgetUser, $passwordDet)
    {

        if ($forgetUser != '' && $passwordDet != '') {

            $currentDate = date('Y-m-d H:i:s');
            $password = md5($passwordDet);

            $this->db->set('ENCRYPT_PASSWORD', $password);
            $this->db->set('ORG_PWD', $passwordDet);
            $this->db->set('UPDATED_ON', $currentDate);
            $this->db->where('USERNAME', $forgetUser);
            $this->db->update(LOGIN);
            return $this->db->affected_rows();
        }
    }
    public function CheckforgotEncrypValue($forgetUser, $forgotEncrypValue)
    {
        $this->db->set('ENCRYPT_OTP_PASSWORD', $forgotEncrypValue);
        $this->db->where('USERNAME', $forgetUser);
        $this->db->update(LOGIN);
        return $this->db->affected_rows();
    }
    public function CheckforgotEncrypValueEmpty($forgetUser, $encryptMsg)
    {
        $pass = "";
        $this->db->set('ENCRYPT_OTP_PASSWORD', null);
        $this->db->where('USERNAME', $forgetUser);
        $this->db->where('ENCRYPT_OTP_PASSWORD', $encryptMsg);
        $this->db->update(LOGIN);
        return $this->db->affected_rows();
    }
    public function CheckExpiredOrNot($forgetUser, $encryptMsg)
    {
        $this->db->select('ENCRYPT_OTP_PASSWORD');
        $this->db->where('USERNAME', $forgetUser);
        return $this->db->get(LOGIN)->row_array();
    }

    // public function getCountForNotify()
    // {
    //     $Currentyear = date("Y");
    //     $query = $this->db->query("SELECT * FROM `INCIDENT_NOTIFICATION_DETAILS` WHERE YEAR(`CREATED_ON`) = $Currentyear AND `NOTIFY_STATUS` = 'Y'");
    //     if ($query->num_rows() > 0) {
    //         return $query->num_rows();
    //     }
    // }

    public function getAlldata2($table, $where, $options = [])
    {
        $findInset = isset($options['find_in_set']) ? $options['find_in_set'] : FALSE;

        // if (is_array($columns) && count($columns) > 0) {
        //     foreach ($columns as $col) {
        //         $allColumns[] = $col;
        //         // $this->db->select($col)
        //     }
        //     $implodeColumns = implode(",", $allColumns);
        //     $this->db->select($implodeColumns);
        // } else {
        //     $this->db->select($columns);
        // }

        $this->db->from($table);

        if (isset($options['where']) && !empty($options['where'])) {
            $optwhere = $options['where'];
            foreach ($optwhere as $wKey => $wVal) {
                $this->db->where($wKey, $wVal);
            }
        }
        if (isset($options['where_in']) && !empty($options['where_in'])) {
            $where_in = $options['where_in'];
            foreach ($where_in as $key => $val) {
                $this->db->where_in($key, $val);
            }
        }
        if (isset($options['where_not_in']) && !empty($options['where_not_in'])) {
            $where_not_in = $options['where_not_in'];
            foreach ($where_not_in as $key => $val) {
                $this->db->where_not_in($key, $val);
            }
        }

        if (isset($options['like']) && !empty($options['like'])) {
            foreach ($options['like'] as $key => $value) {
                $this->db->like($key, $value);
            }
        }

        if (isset($options['join']) && !empty($options['join'])) {
            foreach ($options['join'] as $joinKey => $joinVal) {
                $this->db->join($joinKey, $joinVal[0], $joinVal[1]);
            }
        }

        ////////////////////////////////////////////////        
        if (count($where) > 0) {
            if (isset($where['notin']) && count($where['notin']) > 0) {
                foreach ($where['notin'] as $key => $val) {
                    $this->db->where_not_in($key, $val);
                }
            }

            if ($findInset != FALSE) {
                $this->db->group_start();
                foreach ($findInset as $key => $val) {
                    $this->db->where('FIND_IN_SET("' . $val . '",' . $key . ') !=0');
                }

                if (isset($where['where']) && count($where['where']) > 0) {
                    $this->db->where($where['where']);
                }
                if (isset($where['orwhere']) && count($where['orwhere']) > 0) {
                    foreach ($where['orwhere'] as $key => $val) {
                        $this->db->or_where($key, $val);
                    }
                    if (isset($where['where']) && count($where['where']) > 0) {
                        $this->db->where($where['where']);
                    }
                }
                $this->db->group_end();
            } else {
                if (isset($where['where']) && count($where['where']) > 0) {
                    $this->db->where($where['where']);
                }
                if (isset($where['orwhere']) && count($where['orwhere']) > 0) {
                    foreach ($where['orwhere'] as $key => $val) {
                        $this->db->or_group_start();
                        $this->db->or_where($key, $val);
                        if (isset($where['where']) && count($where['where']) > 0) {
                            $this->db->where($where['where']);
                        }
                        if (isset($where['notin']) && count($where['notin']) > 0) {
                            foreach ($where['notin'] as $key => $val) {
                                $this->db->where_not_in($key, $val);
                            }
                        }
                        $this->db->group_end();
                    }
                    if (isset($where['where']) && count($where['where']) > 0) {
                        $this->db->where($where['where']);
                    }
                }
            }
        }
        ////////////////////////////////////////////////////////////// 

        //        if (isset($options['find_in_set']) && !empty($options['find_in_set'])) {
        //            foreach ($options['find_in_set'] as $findKey => $findVal) {
        //                $this->db->join($findKey, 'find_in_set( ' . $findVal[0] . ') > 0 ', $findVal[1]);
        //            }
        //        }

        if (isset($options['orwhere']) && count($options['orwhere']) > 0) {
            $this->db->group_start();
            foreach ($options['orwhere'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
            $this->db->group_end();
        }


        if (isset($options['calendar']) && count($options['calendar']) > 0) {

            //echo '<pre>';print_r($options['calendar']);exit;

            $start_date = $options['calendar']['start_date'];
            $end_date = $options['calendar']['end_date'];
            $firstDate = $options['calendar']['firstDate'];
            $secondDate = $options['calendar']['secondDate'];

            if ($secondDate != '') {

                $this->db->group_start();
                $this->db->where($firstDate . '>=', $start_date);
                $this->db->where($firstDate . '<=', $end_date);
                //$this->db->group_end();
                //$this->db->or_group_start();
                $this->db->or_where($secondDate . '>=', $start_date);
                $this->db->where($secondDate . '<=', $end_date);
                $this->db->group_end();
            } else {
                // echo '<pre>';print_r($firstDate);exit;

                // $type =  $options['calendar']['type'];



                $this->db->group_start();
                $this->db->where($firstDate . '>=', $start_date);
                $this->db->where($firstDate . '<=', $end_date);
                //$this->db->group_end();
                //$this->db->or_group_start();
                // $this->db->or_where('EVENT_DATE >= ', $start_date);
                // $this->db->where('EVENT_DATE <=', $end_date);
                $this->db->group_end();
            }
        }



        if (isset($options['group_by']) && !empty(($options['group_by']))) {
            $this->db->group_by($options['group_by']);
        }

        if ($orderby != "" && $disporder != "") {
            $this->db->order_by($orderby, $disporder);
        }

        if ($limit != "" && $offset != "") {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get();

        //       $returnTyp = (isset($options['return_type']) && !empty($options['return_type']) ? $options['return_type'] : '');

        if ($result != false && $result->num_rows() > 0) {
            if (isset($options['return_type'])) {
                if ($options['return_type'] == 'result') {
                    return $result->result();
                } else {

                    return $result->row();
                }
            } else {
                return $result->result();
            }
        } else {
            return false;
        }
    }

    public function countActive($table, $where = array())
    {
        $this->db->from($table);
   
        if (count($where) > 0)
            $this->db->where($where);
        return $this->db->count_all_results();
    }



  

            public function getAllExportdata($table, $columns = [], $options = array(), $limit = "", $offset = "", $orderby = "", $disporder = "", $column_search)
    {

        if (is_array($columns) && count($columns) > 0) {
            foreach ($columns as $col) {
                $allColumns[] = $col;
                // $this->db->select($col)
            }
            $implodeColumns = implode(",", $allColumns);
            $this->db->select($implodeColumns);
        } else {
            $this->db->select($columns);
        }

        $this->db->from($table);

        if (isset($options['where']) && !empty($options['where'])) {
            $optwhere = $options['where'];
            foreach ($optwhere as $wKey => $wVal) {
                $this->db->where($wKey, $wVal);
            }
        }
        if (isset($options['where_in']) && !empty($options['where_in'])) {
            $where_in = $options['where_in'];
            foreach ($where_in as $key => $val) {
                $this->db->where_in($key, $val);
            }
        }
        if (isset($options['or_where']) && !empty($options['or_where'])) {
            $where_in = $options['or_where'];
            foreach ($where_in as $key => $val) {
                $this->db->or_where($key, $val);
            }
        }

        if (isset($options['where_not_in']) && !empty($options['where_not_in'])) {
            $where_not_in = $options['where_not_in'];
            foreach ($where_not_in as $key => $val) {
                $this->db->where_not_in($key, $val);
            }
        }

        if (isset($options['like']) && !empty($options['like'])) {
            foreach ($options['like'] as $key => $value) {
                $this->db->like($key, $value);
            }
        }

        $i = 0;
        foreach ($column_search as $item) {
            if ($this->input->get('search')) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $this->input->get('search'));
                } else {
                    $this->db->or_like($item, $this->input->get('search'));
                }

                if (count($column_search) - 1 === $i)
                    $this->db->group_end();
            }
            $i++;
        }


        if (isset($options['join']) && !empty($options['join'])) {
            foreach ($options['join'] as $joinKey => $joinVal) {
                $this->db->join($joinKey, $joinVal[0], $joinVal[1]);
            }
        }

        if (isset($options['find_in_set']) && !empty($options['find_in_set'])) {
            foreach ($options['find_in_set'] as $findKey => $findVal) {
                $this->db->join($findKey, 'find_in_set( ' . $findVal[0] . ') > 0 ', $findVal[1]);
            }
        }



        if (isset($options['group_by']) && !empty(($options['group_by']))) {
            $this->db->group_by($options['group_by']);
        }

        if ($orderby != "" && $disporder != "") {
            $this->db->order_by($orderby, $disporder);
        }

        if ($limit != "" && $offset != "") {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get();

        if ($result != false && $result->num_rows() > 0) {
            if (isset($options['return_type'])) {
                if ($options['return_type'] == 'result') {
                    return $result->result();
                } else {

                    return $result->row();
                }
            } else {
                return $result->result();
            }
        } else {
            return false;
        }
    }


    /*
    * Export Data Function
    */


    function get_exportdata($table, $column_order, $column_search, $order, $where = array(), $options = [])
    {
        $this->_get_get_exportdata_query($table, $column_order, $column_search, $order, $where, $options);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        $query = $this->db->get();
        if ($query !=  FALSE && $query->num_rows() > 0) {

            return $query->result();
        } else {
            return false;
        }
    }

    private function _get_get_exportdata_query($table, $column_order, $column_search, $order, $where, $options = [])
    {
        $findInset = isset($options['find_in_set']) ? $options['find_in_set'] : FALSE;

        if (isset($options['select']) && $options['select'] != '') {
            $this->db->select($options['select']);
        } else {
            $this->db->select('*');
        }
        $this->db->from($table);

        if (isset($options['join']) && !empty($options['join'])) {
            foreach ($options['join'] as $joinKey => $joinVal) {

                $this->db->join($joinKey, $joinVal[0], $joinVal[1]);
            }
        }

        $i = 0;

        foreach ($column_search as $item) { // loop column 
            if ($this->input->get('searchvalue')) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->get('searchvalue'));
                } else {
                    $this->db->or_like($item, $this->input->get('searchvalue'));
                }

                if (count($column_search) - 1 === $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }


        if (isset($options['where_in']) && !empty($options['where_in'])) {
            $where_in = $options['where_in'];
            foreach ($where_in as $key => $val) {
                $this->db->where_in($key, $val);
            }
        }

        /*CUSTOM QUERY*/
        if (isset($options['findinnew']) && count($options['findinnew']) > 0) {
            $this->db->group_start();
            foreach ($options['findinnew'] as $key => $val) {
                $this->db->where('FIND_IN_SET("' . $val . '",' . $key . ') !=0');
            }
            if(isset($options['orwhere']) && count($options['orwhere']) > 0) {
            foreach ($options['orwhere'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
            }
            $this->db->group_end();
            if (isset($options['where']) && count($options['where']) > 0) {
                $this->db->where($options['where']);
            }
        } else if (isset($options['orwhere']) && count($options['orwhere']) > 0) {
            $this->db->group_start();
            foreach ($options['orwhere'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
            $this->db->group_end();
            if (isset($options['where']) && count($options['where']) > 0) {
                $this->db->where($options['where']);
            }

        } else {
            if (is_array($where) && count($where) > 1) {
                foreach ($where as $whKey => $whVal) {
                    $this->db->where($whKey, $whVal);
                }
            } else {
                $this->db->where($where);
            }
        }

        if (isset($options['like']) && !empty($options['like'])) {
            foreach ($options['like'] as $key => $value) {
                $this->db->like($key, $value);
            }
        }


        if (isset($options['where_new']) && !empty($options['where_new'])) {
            $optwhere = $options['where_new'];
            foreach ($optwhere as $wKey => $wVal) {
                $this->db->where($wKey, $wVal);
            }
        }
        if (isset($options['orwhere_new']) && count($options['orwhere_new']) > 0) {
            $this->db->group_start();
            foreach ($options['orwhere_new'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
            $this->db->group_end();
        }


        if ($findInset != FALSE) {
            $this->db->group_start();
            foreach ($findInset as $key => $val) {
                $this->db->where('FIND_IN_SET("' . $val . '",' . $key . ') !=0');
            }

            if (isset($where['where']) && count($where['where']) > 0) {
                $this->db->where($where['where']);
            }
            if (isset($where['orwhere']) && count($where['orwhere']) > 0) {
                foreach ($where['orwhere'] as $key => $val) {
                    $this->db->or_where($key, $val);
                }
                if (isset($where['where']) && count($where['where']) > 0) {
                    $this->db->where($where['where']);
                }
            }
            $this->db->group_end();
        } else {
            if (isset($where['where']) && count($where['where']) > 0) {
                $this->db->where($where['where']);
            }
            if (isset($where['orwhere']) && count($where['orwhere']) > 0) {
                foreach ($where['orwhere'] as $key => $val) {
                    $this->db->or_group_start();
                    $this->db->or_where($key, $val);
                    if (isset($where['where']) && count($where['where']) > 0) {
                        $this->db->where($where['where']);
                    }
                    if (isset($where['notin']) && count($where['notin']) > 0) {
                        foreach ($where['notin'] as $key => $val) {
                            $this->db->where_not_in($key, $val);
                        }
                    }
                    $this->db->group_end();
                }
                if (isset($where['where']) && count($where['where']) > 0) {
                    $this->db->where($where['where']);
                }
            }
        }

        if (isset($options['group_by']) && !empty($options['group_by'])) {
            $groupBy = $options['group_by'];
            $this->db->group_by($groupBy);
        }
        if ($this->input->post('order') == "") {
            $this->db->order_by(key($order), $order[key($order)]);
        } else if ($this->input->post('order')) { // here order processing
            if (isset($column[$_REQUEST['order']['0']['column']]))
                $this->db->order_by($column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


}
