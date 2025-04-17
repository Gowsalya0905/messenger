<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/common_model');
        $this->load->model('dashboard_model');
        isLogin();
    }

    public function index()
    {
        $userdata = $this->session->userinfo;

        //print_r($userdata);exit;
        $login = $userdata->LOGIN_ID;

        if ($login != '') {
            $userdata = $this->session->userinfo;
            $role = $userdata->USER_TYPE_ID;


            redirect('dashboard/chart');
            // $this->main_dashboard();
        } else {
            redirect('login');
        }
    }

    public function generateCaptcha()
    {
        $this->load->helper('captcha');
        $mpath = 'public/captcha/';
        if (!file_exists(FCPATH . $mpath)) {
            @mkdir(FCPATH . $mpath, 0755, true);
        }
        // Captcha configuration
        $config = array(
            'img_path' => $mpath,
            'img_url' => BASE_URL . $mpath,
            'img_width' => '150',
            'img_height' => 50,
            'word_length' => 5,
            'font_size' => 18,
            'img_id' => 'Imageid',
            'pool' => '0123456789',
            'colors' => array(
                'text' => array(0, 0, 0),
            )
        );
        $captcha = create_captcha($config);

        // Unset previous captcha and set new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        return $captcha['image'];
    }

    public function captcharefresh()
    {
        echo $this->generateCaptcha();
    }

    public function profile($error = ['captcha_error' => ''])
    {

        $captchaCode = $this->generateCaptcha();
        $userdata = $this->session->userinfo;


        $userTypeId = postData($userdata, 'USER_TYPE_ID');
        $userRefId = postData($userdata, 'USER_REF_ID');
        $userLoginId = postData($userdata, 'LOGIN_ID');
        $options['return_type'] = 'row';
        $options['where'] = [
            'LOGIN_ID' => $userLoginId,
        ];

        $options['join'] = [
            EMPL . ' as emp' => ['emp.EMP_AUTO_ID = LD.USER_REF_ID', 'INNER']
        ];
        // $options['join'][NATION . ' as nation'] = ['nation.NATIONALITY_ID = emp.EMP_NATIONALITY', 'left'];
        // $options['join'][GENDER . ' as gend'] = ['gend.GENDER_ID = emp.EMP_GENDER', 'left'];

        // $options['join'][MAS_ZNE.' zone'] = ['find_in_set(zone.zone_auto_id,emp.EMP_ZONE_IDS) > 0','left'];
        // $options['join'][MAS_SZE.' sub'] = ['find_in_set(sub.sub_auto_id,emp.EMP_SUBZONE_IDS) > 0','left'];
        $param = [
            'LD.LOGIN_ID,LD.PROFILE_IMG,LD.USERNAME,USER_REF_ID,USER_LOGIN_TYP,USER_TYPE_ID,USER_DESINATION_ID,emp.*,
            FN_ROLE_NAME(EMP_USERTYPE_ID) as role_name, 	
            FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID) as design_name, 	
            FN_COMP_NAME(EMP_COMP_ID) as company_name,
            FN_AREA_NAME(EMP_AREA_ID) as area_name,
            FN_BUILD_NAME(EMP_BUILDING_ID) as building_name,
            FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID) as department_name,
            FN_EMP_TYPE(EMP_TYPE) as emp_type_name'

        ];


        $profileInfo = $this->common_model->getAlldata(LOGIN . ' as LD', $param, $options);

        // echo '<pre>' . print_r($profileInfo, true) . '</pre>';
        // exit;

        $data = array(
            'view_file' => 'profile',
            'title' => 'Profile',
            'current_menu' => 'Profile',
            'captchaCode' => $captchaCode,
            'profileInfo' => $profileInfo,
            'userTypeId' => $userTypeId,
            'userdata' => $userdata
        );
        $dispData = $error + $data;
        $this->template->load_common_template($dispData);
    }

    public function savepassword()
    {
        $userdata = $this->session->userinfo;
        // If captcha form is submitted
        if ($this->input->post('passsubmit')) {
            $inputCaptcha = $this->input->post('captcha');
            $sessCaptcha = $this->session->userdata('captchaCode');
            if ($inputCaptcha === $sessCaptcha) {

                $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|matches[password]');

                if ($this->form_validation->run() == true) {

                    $userTypeId = postData($userdata, 'USER_REF_ID');
                    $loginId = postData($userdata, 'LOGIN_ID');
                    $password = $this->input->post('password');

                    $updateData = [
                        'ENCRYPT_PASSWORD' => md5($password),
                        'ORG_PWD' => $password,
                    ];

                    $userWhere = [
                        'LOGIN_ID' => $loginId
                    ];


                    $update = $this->common_model->updateData(LOGIN, $updateData, $userWhere);
                    if ($update) {
                        $this->session->set_flashdata('profilemsg', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Success!</span> Password update successfully. </div>');
                        redirect('dashboard/profile/');
                    } else {
                        $this->session->set_flashdata('profilemsg', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Sorry!</span> Password update failed </div>');
                        redirect('dashboard/profile/');
                    }
                } else {
                    $error['captcha_error'] = '';
                    $this->profile($error);
                }
            } else {
                $error['captcha_error'] = 'Captcha code does not match, please try again.';
                $this->profile($error);
            }
        }
    }

    public function savePhoto()
    {

        $userdata = $this->session->userinfo;
        if ($this->input->post('photosubmit')) {
            if (isset($_FILES['profileImg']) && count($_FILES['profileImg']) > 0) {
                $profileImage = uploadImage($filename = 'profileImg', $imgUploadpath = 'assets/images/profile', $allowedTyp = "png|jpg|jpeg");

                $loginId = postData($userdata, 'LOGIN_ID');
                $updateData = [
                    'PROFILE_IMG' => $profileImage,
                ];

                $userWhere = [
                    'LOGIN_ID' => $loginId
                ];

                $update = $this->common_model->updateData(LOGIN, $updateData, $userWhere);
                if ($update) {
                    if ($profileImage != '') {
                        $userdata->PROFILE_IMG = $profileImage;
                        $session_data['user_details'] = $userdata;
                        $this->session->set_userdata($session_data);
                    }

                    $this->session->set_flashdata('profilemsg', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Success!</span> User Profile update successfully. </div>');
                    redirect('dashboard/profile/');
                } else {
                    $this->session->set_flashdata('profilemsg', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Sorry!</span> User Profile update failed </div>');
                    redirect('dashboard/profile/');
                }
            } else {
                $this->session->set_flashdata('profilemsg', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Sorry!</span> User Profile update failed </div>');
                redirect('dashboard/profile/');
            }
        }
    }







    public function sliderData()
    {
        $getAllslider = [];
        $desigOptn['where'] = [
            'SLI_STATUS' => 'Y'
        ];
        $getAllslider = $this->common_model->getAlldata(DASH_SLIDER, ['*'], $desigOptn);
        return $getAllslider;
    }

    public function main_dashboard()
    {
        ///////////////////////////////dashboard filter



        $base_url = BASE_URL();
        $sliderData = $this->sliderData();


        //echo 'M<pre>';print_r($inciCounts);exit;
        $data = array(
            // 'calendarInfo' => $calendarInfo,
            'view_file' => 'dashboard/dashboard',
            'title' => 'Dashboard',
            'current_menu' => 'Dashboard',

            'sliderData' => $sliderData,

            'headerfiles' => array(
                "css" => array(
                    // "assets/plugins/jvectormap/jquery-jvectormap.css",
                    // "assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css",
                    // "assets/plugins/bootstrap-daterangepicker/daterangepicker.css",
                ),
                "js" => array(
                    "assets/plugins/amcharts/amcharts.js",
                    "assets/plugins/amcharts/serial.js",
                    "assets/plugins/amcharts/pie.js",
                    "assets/plugins/amcharts/themes/light.js"
                ),
                "priority" => 'high'
            )
        );

        $this->template->load_common_template($data);
    }

    public function notify($id = '')
    {

        $userid = getCurrentUserid();
        $userGroupId = getCurrentUserGroupId();
        $allRead = $this->input->get('all');
        if (isset($allRead)) {
            if ($allRead == 'notify') {
                $getNotificationCount = getUnreadNotification();
            }
            if ($allRead == 'task') {
                $getNotificationCount = getUnreadTask();
            }
            $NotifyIds = (isset($getNotificationCount->notify_ids) && !empty($getNotificationCount->notify_ids)) ? explode(',', $getNotificationCount->notify_ids) : array();



            if (count($NotifyIds) > 0) {
                foreach ($NotifyIds as $val) {
                    $notifyData = array(
                        'NOTIFICATION_USER_MASTER_ID' => $userid,
                        'NOTIFICATION_LOG_NOTI_ID' => $val,
                        'CREATED_ON' => date('Y-m-d H:i:s')
                    );
                    $data[] = $notifyData;
                }
                $this->db->insert_batch(NOTI_LOG, $data);
            }
        }

        $noId = decryptval($id);


        $notificationInfo = array();
        if ($noId != FALSE) {
            $where = array('where' => array('NOTIFICATION_ID' => $noId));
            $notificationInfo = getNotificationInfo('*', $where);
        }

        if ($notificationInfo != false) {
            $aherf = (isset($notificationInfo->NOTIFICATION_HREF) && $notificationInfo->NOTIFICATION_HREF != '') ? $notificationInfo->NOTIFICATION_HREF : false;
            $nid = (isset($notificationInfo->NOTIFICATION_ID) && $notificationInfo->NOTIFICATION_ID != '') ? $notificationInfo->NOTIFICATION_ID : false;
            if ($aherf != '') {
                $data = array(
                    'NOTIFICATION_USER_MASTER_ID' => $userid,
                    'NOTIFICATION_LOG_NOTI_ID' => $nid,
                    'CREATED_ON' => date('Y-m-d H:i:s')
                );
                $condition = array(
                    'NOTIFICATION_USER_MASTER_ID' => $userid,
                    'NOTIFICATION_LOG_NOTI_ID' => $nid
                );
                $insert = $this->common_model->updateInfo(NOTI_LOG, $data, 'NOTIFICATION_LOG_ID', $condition);

                if ($allRead != 'notify') {
                    $redirectUrl = BASE_URL . $aherf;
                    redirect($redirectUrl);
                    exit;
                }

                /////new code start
                $getNotificationCount = getUnreadNotification();

                $NotifyIds = (isset($getNotificationCount->notify_ids) && !empty($getNotificationCount->notify_ids)) ? explode(',', $getNotificationCount->notify_ids) : array();

                if (count($NotifyIds) > 0) {
                    foreach ($NotifyIds as $val) {
                        if ($val != $noId) {
                            $notifyData = array(
                                'NOTIFICATION_USER_MASTER_ID' => $userid,
                                'NOTIFICATION_LOG_NOTI_ID' => $val,
                                'CREATED_ON' => date('Y-m-d H:i:s')
                            );
                            $datanew[] = $notifyData;
                        }
                    }
                    $this->db->insert_batch(NOTI_LOG, $datanew);
                }
                /////new code end
                if ($insert) {
                    $redirectUrl = BASE_URL . $aherf;
                    redirect($redirectUrl);
                    exit;
                }
            }
        }

        if (isset($allRead)) {
            if ($allRead == 'notify') {
                //                $data = array(
                //                    'view_file' => 'notification/notify_list',
                //                    'current_menu' => '',
                //                    'ajaxurl' => 'dashboard/notifyList?id=1',
                //                );
                //                $this->template->load_table_template($data);
                redirect(BASE_URL . 'dashboard');
            }
            if ($allRead == 'task') {
                //                $data = array(
                //                    'view_file' => 'notification/notify_list',
                //                    'current_menu' => '',
                //                    'ajaxurl' => 'dashboard/notifyList?id=2',
                //                );
                //                $this->template->load_table_template($data);
                redirect(BASE_URL . 'dashboard');
            }
        }
    }

    public function notifyList()
    {
        $option = [];
        $userid = getCurrentUserid();
        $msgtype = $_GET['id'];
        $noWhere = array('where' => array('NOTIFICATION_TYPE' => $msgtype), 'orwhere' => array('NOTIFICATION_GROUP_CATID =' => '', 'NOTIFICATION_EMPLOYEE_ID' => ''));
        if (!is_admin()) {
            $option['find_in_set'] = array('NOTIFICATION_EMPLOYEE_ID' => $userid);
        }
        $notification = $this->common_model->get_datatables_notify($noWhere, $option);
        $no = $_POST['start'];
        $data_inc = array();
        foreach ($notification as $notify) {
            $action = '';
            $notifyData = array();

            //$row_inc[] = $no;
            $notifyData[] = $notify->NOTIFICATION_DESC;
            $notifyData[] = timeago($notify->CREATED_ON);
            $notifyData[] = $action;
            $data_inc[] = $notifyData;
        }

        $output_inc = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->common_model->count_all_notify(),
            "recordsFiltered" => $this->common_model->count_filtered_notify(),
            "data" => $data_inc,
        );
        //output to json format
        echo json_encode($output_inc);
    }



    private function getNotifyInfo()
    {
        $start_date = date("Y-m-d", strtotime($this->input->get('start')));
        $end_date = date("Y-m-d", strtotime($this->input->get('end')));

        $options['where'] = [
            'NOTIFY_STATUS' => 'Y',
            'EVENT_DATE >= ' => $start_date,
            'EVENT_DATE <= ' => $end_date,
        ];
        $userType = getCurrentUserGroupId();
        $userid = getCurrentUserid();
        //    switch ($userType) {
        //        case 4:
        //            $options['where']['CREATED_BY'] = $userid;
        //
        //            break;
        //        case 5:
        //            $options['where']['OA_USER_LOGIN_ID'] = $userid;
        //
        //            break;
        //        case 6:
        //            $options['where']['SHO_HSE_USER_LOGIN_ID'] = $userid;
        //
        //            break;
        //        case 11:
        //            $options['where']['CREATED_BY'] = $userid;
        //
        //            break;
        //
        //        default:
        //            break;
        //    }

        $options['return_type'] = 'result';
        $NotifyInfo = $this->common_model->getAlldata('INCIDENT_NOTIFICATION_DETAILS', ['*'], $options);
        // echo $this->db->last_query();exit;
        $events = [];
        if ($NotifyInfo != FALSE && count($NotifyInfo) > 0) {
            foreach ($NotifyInfo as $row) {
                $nid = postData($row, 'INCIDENT_ID');
                $title = postData($row, 'INCIDENT_NOTIFY_ID');
                $startDate = postData($row, 'EVENT_DATE');
                $endDate = postData($row, 'EVENT_DATE');
                $INVESTIGATION_ACTION = postData($row, 'INVESTIGATION_ACTION');
                if ($INVESTIGATION_ACTION != '1') {
                    $url = 'incident/investCheck/' . encryptval($title);
                } else {
                    $url = 'incident/ViewList/' . encryptval($title);
                }


                $events[] = [
                    'title' => $title,
                    'url' => $url,
                    'start' => $startDate,
                    'end' => $endDate,
                ];
            }
        }
        return $events;
    }

    public function NotificationList()
    {

        $tab_id = $this->input->get('ids');
        $data = array(
            'view_file' => 'notification/notification',
            'site_title' => 'Notification List',
            'current_menu' => 'Notification List',
            'tab_id' => $tab_id,
            'ajaxurl' => 'dashboard/list_notification?tab_id=' . $tab_id . '',
        );

        $this->template->load_table_template($data);
    }

    public function list_notification()
    {
        $userid = getCurrentUserid();
        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;

        $getModule = $_GET['tab_id'];

        // if($getModule =='2'){
        //     $tabName ='observation';
        //     // $menuName='Observation';
        // }else if($getModule =='3'){
        //     $tabName ='incident';
        //     // $menuName='Incident';
        // }

        $where = [];

        $table = NOTI;
        $column_order = array(null, 'NOTIFICATION_ID', 'NOTIFICATION_DESC', 'DATE_FORMAT(CREATED_ON,"%d-%m-%Y")', null);
        $column_search = array('NOTIFICATION_ID', 'NOTIFICATION_DESC', 'DATE_FORMAT(CREATED_ON,"%d-%m-%Y")');
        $order = array('NOTIFICATION_ID' => 'desc');

        if ($user_type != 2) {
            $options['findinnew'] = ['NOTIFICATION_EMPLOYEE_ID' => $userid];
        }


        // if($getModule !='0'){
        //     //$options['like']=['NOTIFICATION_HREF'=>$tabName];

        //     $options['like']=['NOTIFICATION_HREF'=>$tabName];
        // }

        if ($getModule != '0') {
            $options['where_new'] = [
                'NOTIFICATION_STATUS' => 'Y',
                'MODULE_AUTO_ID' => $getModule
            ];
        } else {
            $options['where_new'] = [
                'NOTIFICATION_STATUS' => 'Y'
            ];
        }

        // print_r( $options['where']);exit;

        $listResp = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        // echo $this->db->last_query();exit;
        $finalDatas = [];
        $i = 0;
        if (isset($listResp) && !empty($listResp)) {
            foreach ($listResp as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $url = ($ltVal->NOTIFICATION_HREF);

                $action .= " " . anchor($url, '<i class="fa fa-eye"></i>', array('class' => '', 'title' => 'View'));
                // $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteInspchecklist', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                $rows[] = $i;
                $rows[] = $ltVal->NOTIFICATION_DESC;
                $rows[] = date('d-m-Y H:i:s', strtotime($ltVal->CREATED_ON));
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );
        //output to json format
        echo json_encode($output);
    }
}
