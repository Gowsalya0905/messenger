<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class NotificationList extends CI_Controller {

    public function __construct() {
        parent::__construct();
       
        isLogin();
       
    }
    
    /*
     * User Type
     */
    public function index() {
        $data = array(
            'pageTitle' => 'Notification List',
            'view_file' => 'master/notificationdata/list_form',
            'site_title' => 'Notification List',
            'current_menu' => 'Notification List',
            'ajaxurl' => 'master/NotificationList/list_notificationdata',
        );

        $this->template->load_table_template($data);
    }



        function timeago($datetime)
        {
            $created = new DateTime($datetime);
            $now = new DateTime();
            $diff = $created->diff($now);
    
            $seconds = $diff->s;
            $minutes = $diff->i;
            $hours = $diff->h;
            $days = $diff->d;
            $months = $diff->m;
            $years = $diff->y;
    
            if ($seconds < 10) {
                return $seconds . ' seconds ago';
            } elseif ($seconds < 60) {
                return $seconds . ' seconds ago';
            } elseif ($minutes < 60) {
                return $minutes . ' minutes ago';
            } elseif ($hours < 24) {
                return $hours . ' hours ago';
            } elseif ($days == 1) {
                return 'Yesterday';
            } elseif ($days < 30) {
                return $days . ' days ago';
            } elseif ($months == 1) {
                return '1 month ago';
            } elseif ($months > 1) {
                return $months . ' months ago';
            } elseif ($years == 1) {
                return '1 year ago';
            } elseif ($years > 1) {
                return $years . ' years ago';
            } else {
                return $created->format('F j, Y'); // Default format
            }
        }
    

    public function list_notificationdata() {
        $userid = getCurrentUserid();
        
        $table = NOTI;
        $column_select = array('NOTIFICATION_ID','NOTIFICATION_DESC', 'NOTIFICATION_HREF','CREATED_ON','DATE_FORMAT(FROM_UNIXTIME(CREATED_ON), "%d-%m-%Y %H:%i:%s") as user_timestamp');
        $column_order = array('NOTIFICATION_ID','NOTIFICATION_DESC', 'NOTIFICATION_HREF','CREATED_ON','DATE_FORMAT(FROM_UNIXTIME(CREATED_ON), "%d-%m-%Y %H:%i:%s")');
        $column_search = array('NOTIFICATION_ID','NOTIFICATION_DESC', 'NOTIFICATION_HREF','CREATED_ON','DATE_FORMAT(FROM_UNIXTIME(CREATED_ON), "%d-%m-%Y %H:%i:%s")');
        $order = array('CREATED_ON' => 'desc');
        $where = [];
        $options['select'] = $column_select;
       
        if (!is_admin()) {
            $options['find_in_set'] = array('NOTIFICATION_EMPLOYEE_ID' => $userid);
        }
       
        
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
                //$id = $ltVal->NOTIFICATION_ID;
                $action .=
                    " " .
                    anchor(
                        $ltVal->NOTIFICATION_HREF,
                        '<i class="fa fa-eye"></i>',
                        ["title" => "view"]
                    );
                
                $rows = [];

                $rows[] = $ltVal->NOTIFICATION_DESC;
                $rows[] = timeago($ltVal->CREATED_ON);
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


    
    
}
