<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserType extends CI_Controller {

    public function __construct() {
        parent::__construct();
       
        isLogin();
       
    }
    
    /*
     * User Type
     */
    public function index() {
        $data = array(
            'pageTitle' => 'User Type',
            'view_file' => 'master/user_type/list_form',
            'site_title' => 'User Type',
            'current_menu' => 'user_type',
            'ajaxurl' => 'master/userType/list_user_type',
        );

        $this->template->load_table_template($data);
    }

    public function list_user_type() {
        $table = 'USER_TYPE';
        $column_order = array('USER_TYPE_ID', 'USER_TYPE_NAME', 'CREATED_ON', null);
        $column_search = array('USER_TYPE_ID','USER_TYPE_NAME', 'CREATED_ON');
        $order = array('USER_TYPE_ID' => 'DESC');
        $where = ['USER_TYPE_STATUS' => 'Y'];

        $listIncicat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where);

        $finalDatas = [];
        if (isset($listIncicat) && !empty($listIncicat)) {
            foreach ($listIncicat as $ltKey => $ltVal) {

                $action = '';
                $id = $ltVal->USER_TYPE_ID;

               
               

                $rows = [];
                $rows[] = $ltVal->USER_TYPE_NAME;
                $rows[] = convertDate($ltVal->CREATED_ON);
                
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
    
    
    public function designationMapping() {
        $data = array(
            'pageTitle' => 'User Type - Designation Mapping',
            'view_file' => 'master/user_type_design_mapping/list_form',
            'site_title' => 'User Type - Designation Mapping',
            'current_menu' => 'user_type',
            'ajaxurl' => 'master/userType/list_user_type_mapping',
        );

        $this->template->load_table_template($data);
    }
    
    public function list_user_type_mapping() {
        
       
        $table = 'user_type_designation_view';
        $column_order = array('USER_TYPE_ID','USER_TYPE_NAME' ,'GROUP_CONCAT(DISTINCT DESIGNATION_NAME) as designname', 'CREATED_ON');
        $column_search = array('USER_TYPE_ID', 'USER_TYPE_NAME' ,'GROUP_CONCAT(DISTINCT DESIGNATION_NAME) as designname', 'CREATED_ON');
        $options['group_by']= 'USER_TYPE_ID';
        $where = [];
        $order = ['USER_TYPE_ID' => 'DESC'];
        $options['select'] = $column_order;

        $listIncicat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where,$options);

        $finalDatas = [];
        if (isset($listIncicat) && !empty($listIncicat)) {
            foreach ($listIncicat as $ltKey => $ltVal) {

                $action = '';
                $id = $ltVal->USER_TYPE_ID;
                $encId = encryptval($id);
                $action .= " " . anchor('master/UserType/mapping/' . $encId, '<i class="fa fa-edit"></i>', array('class' => 'designation', 'title' => 'Edit'));

                $rows = [];
                $rows[] = $ltVal->USER_TYPE_NAME;
                $rows[] = $ltVal->designname;
                $rows[] = convertDate($ltVal->CREATED_ON);
                $rows[] = $action;
                
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
    
    public function mapping(){
        echo 'hai';
    }
}