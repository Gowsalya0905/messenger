<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TrackingList extends CI_Controller {

    public function __construct() {
        parent::__construct();
       
        isLogin();
       
    }
    
    /*
     * User Type
     */
    public function index() {
        $data = array(
            'pageTitle' => 'User Log',
            'view_file' => 'master/usertracking/list_form',
            'site_title' => 'User Log',
            'current_menu' => 'user_log',
            'ajaxurl' => 'master/TrackingList/list_usertracking',
        );

        $this->template->load_table_template($data);
    }

    public function list_usertracking() {
        
        $table = 'usertracking as track';
        $column_select = array('id','e.EMP_NAME as user_name', 'client_ip','request_uri', 'client_user_agent','referer_page','DATE_FORMAT(FROM_UNIXTIME(timestamp), "%d-%m-%Y %H:%i:%s") as user_timestamp','timestamp','e.EMP_ID as empId');
        $column_order = array('id','e.EMP_NAME as user_name', 'client_ip','request_uri', 'client_user_agent','referer_page','DATE_FORMAT(FROM_UNIXTIME(timestamp), "%d-%m-%Y %H:%i:%s")');
        $column_search = array('id','user_login_id', 'client_ip','request_uri', 'client_user_agent','referer_page','DATE_FORMAT(FROM_UNIXTIME(timestamp), "%d-%m-%Y %H:%i:%s")');
        $order = array('track.timestamp' => 'desc');
        $where = [];
        $options['select'] = $column_select;
        $options['join'][LOGIN . ' as LD'] = ['LD.LOGIN_ID = track.user_login_id', 'inner'];
        $options['join'][EMPL . ' as e'] = ['e.EMP_AUTO_ID = LD.USER_REF_ID', 'left'];
       // $options['join']['CONTRACTOR_DETAILS' . ' as c'] = ['c.CONT_AUTO_ID = LD.USER_REF_ID', 'left'];
        
        $listIncicat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where,$options);
// echo $this->db->last_query();exit;
        $finalDatas = [];
         $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
         $baseURL =$protocol.$_SERVER['HTTP_HOST'];
        if (isset($listIncicat) && !empty($listIncicat)) {
            $i = 0;
            foreach ($listIncicat as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = $ltVal->id;
                
                $rows = [];
                $rows[] = $ltVal->empId.' - '.$ltVal->user_name;
                // $rows[] = $ltVal->client_ip;
                // $rows[] = '<a href="'.$baseURL.$ltVal->request_uri.'" target="_new">'.$baseURL.$ltVal->request_uri.'</a>' ;
                $rows[] = $ltVal->client_user_agent;
                $rows[] = '<a href="'.$ltVal->referer_page.'" target="_new">'.$ltVal->referer_page.'</a>' ;
                $rows[] = date('d-m-Y H:i:s',$ltVal->timestamp);
                
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where),
            "data" => $finalDatas,
        );
        //output to json format
        echo json_encode($output);
    }


    
    
}
