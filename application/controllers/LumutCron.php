<?php
defined("BASEPATH") or exit("No direct script access allowed");

class LumutCron extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        error_reporting(1);
    }

    public function autoOBSExceedNotification()
    {
        $path = '';
        global $cronTo_Supervisor_Escalation_Times;
        global $superadmin;
        global $cronAdmin;
        global $risk_rating, $obs_type_list;

        //////first time escalation
        $currentDateTime = date("Y-m-d H:i:s");
        $currentTime = date("H:i:s");
        $currentDate = date("Y-m-d");
        $options["where"] = [
            'cron_sent_date !=' => $currentDate,
            "obs_status" => "Y",
            "obs_final_tar_date_time <=" => $currentDateTime,
        ];
        $options["where_in"] = [
            "obs_app_status" => ['4', '7']
        ];

        $options["return_type"] = "result";
        $options['join'] = [
            LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_reporter_id', 'LEFT'],
            EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD1' => ['LD1.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_supervisor_id', 'LEFT'],
            EMPL . ' AS Hsse_es' => ['Hsse_es.EMP_AUTO_ID = LD1.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_assigner_id', 'LEFT'],
            EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD3' => ['LD3.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_hsse_id', 'LEFT'],
            EMPL . ' AS Hsse' => ['Hsse.EMP_AUTO_ID = LD3.USER_REF_ID', 'LEFT'],

        ];

        $obsDetails = $this->common_model->getAlldata(
            OBS_FLOW_SEE,
            [
                OBS_FLOW_SEE . ".*",
                'Reporter.EMP_NAME` as Reporter',
                'FN_COMP_NAME(obs_owner_eng) as owner_eng_name',
                'FN_COMP_NAME(obs_comp_id) as comp_name',
                'FN_AREA_NAME(obs_area_id) as area_name',
                'FN_BUILD_NAME(obs_building_id) as building_name',
                'FN_GET_DEPARTMENT_NAME(obs_dept_id) as dep_name',
                'FN_PROJECT_NAME(obs_project_id) as proj_name',
                'FN_HSE_CAT_NAME(obs_cat_id) as hse_cat',
                'Reporter.EMP_NAME as empName',
                'Hsse_es.EMP_NAME as supervisor',
                'Hsse_es.EMP_EMAIL_ID as supervisor_email',
                'Hsse.EMP_NAME as hsse',
                'Hsse.EMP_EMAIL_ID as hsse_email',
                'Hod.EMP_NAME as assignee',
                'Hod.EMP_EMAIL_ID as assignee_email',
                'FN_GET_DESIGNATION_NAME(obs_reporter_desg_id) as desig',
            ],
            $options,
            $limit = "",
            $offset = "",
            $orderby = "",
            $disporder = ""
        );

        $aResponse = [];
        if ($obsDetails != false) {

            foreach ($obsDetails as $atardetails) {
                $obs_id = postData($atardetails, 'obs_id');
                $obs_auto_id  = postData($atardetails, 'obs_auto_id');
                $Reporter = postData($atardetails, 'Reporter');
                $desig = postData($atardetails, 'desig');
                $obs_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'obs_report_datetime')));
                $obs_type_id = postData($atardetails, 'obs_type_id');
                $obs_risk_id = postData($atardetails, 'obs_risk_id');


                $obs_app_status = postData($atardetails, 'obs_app_status');
                $obs_assigner_id = postData($atardetails, 'obs_assigner_id');
                $obs_assigner_type_id = postData($atardetails, 'obs_assigner_type_id');
                $obs_supervisor_id = postData($atardetails, 'obs_supervisor_id');
                $obs_supervisor_type_id = postData($atardetails, 'obs_supervisor_type_id');
                $obs_hsse_id = postData($atardetails, 'obs_hsse_id');


                $comp_name = postData($atardetails, 'comp_name');
                $area_name = postData($atardetails, 'area_name');
                $building_name = postData($atardetails, 'building_name');
                $dep_name = postData($atardetails, 'dep_name');
                $proj_name = postData($atardetails, 'proj_name');
                $hse_cat = postData($atardetails, 'hse_cat');
                $inj_name = postData($atardetails, 'inj_name');
                if ($obs_type_id) {
                    $obs_type = $obs_type_list[$obs_type_id];
                }
                if (isset($risk_rating[$obs_risk_id])) {
                    $obs_risk = $risk_rating[$obs_risk_id];
                } else {
                    $obs_risk = '';
                }

                $assignee = postData($atardetails, 'assignee');
                $assignee_email = postData($atardetails, 'assignee_email');
                $supervisor_email = postData($atardetails, 'supervisor_email');
                $hsse_email = postData($atardetails, 'hsse_email');
                $obs_assigner_target_date = postData($atardetails, 'obs_assigner_target_date');
                $obs_assigner_desc = postData($atardetails, 'obs_assigner_desc');
                $obs_hsse_es_type_id = postData($atardetails, 'obs_hsse_es_type_id');
                $obs_hsse_es_appr_rej_desc = postData($atardetails, 'obs_hsse_es_appr_rej_desc');
                $obs_hsse_appr_rej_desc = postData($atardetails, 'obs_hsse_appr_rej_desc');



                $obs_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'obs_supervisor_date')));

                //$path = $getRec->obs_email_pdf;
                $getAutoObsid = $atardetails->obs_auto_id;
                $obs_generate_id = $atardetails->obs_id;
                $prevStatus = $atardetails->obs_app_status;

                $NewfinalTargetDate = date("Y-m-d H:i:s", strtotime($atardetails->obs_final_tar_date_time));
                $targetdate = date("Y-m-d", strtotime($atardetails->obs_assigner_target_date));
                /////cron sent times

                $cron_sent_times = $atardetails->cron_sent_times;

                /////////////////

                if ($cron_sent_times == $cronTo_Supervisor_Escalation_Times) {


                    /////////take hsse E&S                  
                    $obs_hsse_id = $atardetails->obs_hsse_id;

                    $optionsplant['where'] = [
                        'LOGIN_ID' => $obs_hsse_id
                    ];
                    $optionsplant["join"][EMPL . " AS supervisor"] = ["supervisor.EMP_AUTO_ID = USER_REF_ID", "LEFT"];
                    $optionsplant["return_type"] = "result";
                    $getStatdatasph = $this->common_model->getAlldata(LOGIN, ['*'], $optionsplant);

                    $loginIds_ph = array();
                    $emailIds_ph = array();

                    // if (isset($getStatdatasph) && !empty($getStatdatasph)) {
                    //     foreach ($getStatdatasph as $objph) {
                    //         $loginIds_ph[] = $objph->LOGIN_ID;
                    //         $emailIds_ph[] = $objph->EMP_EMAIL_ID;
                    //     }
                    // }
                    /////end
                }

                ///////take admin and superadmin
                ////admin

                $comp_id_admin =  $atardetails->obs_comp_id;
                $optWhradmin['where'] = [
                    'admin.EMP_COMP_ID' => 1
                ];

                $optWhradmin["where_in"] = [
                    "USER_TYPE_ID" => $cronAdmin
                ];
                $optWhradmin["join"][EMPL . " AS admin"] = ["admin.EMP_AUTO_ID = USER_REF_ID", "LEFT"];

                $optWhradmin["return_type"] = "result";
                $getStatdataadmin = $this->common_model->getAlldata(LOGIN, ['LOGIN_ID,admin.EMP_EMAIL_ID'], $optWhradmin);

                $loginIds_admin = array();
                $emailIds_admin = array();

                if (isset($getStatdataadmin) && !empty($getStatdataadmin)) {
                    foreach ($getStatdataadmin as $obj) {
                        $loginIds_admin[] = $obj->LOGIN_ID;
                        $emailIds_admin[] = $obj->EMP_EMAIL_ID;
                    }
                }


                ///superadmin

                $optWhrsupadmin["where_in"] = [
                    "USER_TYPE_ID" => $superadmin
                ];
                $optWhrsupadmin["join"][EMPL . " AS Reporter"] = ["Reporter.EMP_AUTO_ID = USER_REF_ID", "LEFT"];

                $optWhrsupadmin["return_type"] = "result";
                $getStatdatasupadmin = $this->common_model->getAlldata(LOGIN, ['LOGIN_ID,Reporter.EMP_EMAIL_ID'], $optWhrsupadmin);

                $loginIds_supadmin = array();
                $emailIds_supadmin = array();

                if (isset($getStatdatasupadmin) && !empty($getStatdatasupadmin)) {
                    foreach ($getStatdatasupadmin as $objsup) {
                        $loginIds_supadmin[] = $objsup->LOGIN_ID;
                        $emailIds_supadmin[] = $objsup->EMP_EMAIL_ID;
                    }
                }
                ////////end



                // $userTypeIdman = $obs_supervisor_id;
                // $optionph["where"] = [];


                // $manmail_id = [];

                // if ($userTypeIdman != '' && $userTypeIdman != NULL) {
                //     $manmail_id = $this->getCronGroupInvEmailDetails($userTypeIdman, "INV", $optionph);
                // }


                // $notify_email_id_man = "";
                // $notify_id_man = "";

                // if ($manmail_id != '' && $manmail_id != null) {
                //     $notify_email_id_man = $manmail_id->emailList;
                //     $notify_id_man = $manmail_id->notifyidList;
                // }

                $combinedNotifyIdArray = array_merge([$obs_supervisor_id ?? []], $loginIds_supadmin ?? [], $loginIds_admin ?? [], $loginIds_ph ?? []);
                $combinedEmailArray = array_unique(array_merge([$supervisor_email ?? []], $emailIds_supadmin ?? [], $emailIds_admin ?? [], $emailIds_ph ?? []));


                // print_r($combinedNotifyIdArray);
                // exit;
                /////////////first reporting manager setting target date 

                $updateData = [
                    'obs_app_status' => 8,
                    'cron_sent_date' => $currentDate
                ];

                $updateWhere = [
                    'obs_auto_id' => $getAutoObsid
                ];

                $updateInfo = FALSE;
                if (count($updateWhere) > 0) {
                    $this->db->where($updateWhere);
                    $this->db->update(OBS_FLOW_SEE, $updateData);
                    $updateInfo = $this->db->affected_rows();
                }

                ////////////////////////////////end      

                ///manager track escalation  
                $useeCAEscalation = [
                    'fk_obs_auto_main_id' => $getAutoObsid,
                    "fk_obs_main_stat" => $prevStatus,
                    'cron_notify_login_id' => implode(',', $combinedNotifyIdArray),
                    'cron_notify_email_id' => implode(',', $combinedEmailArray),
                    "obs_assigned_type_id" => 4,

                ];

                $updtPr = $this->common_model->updateData(OBS_ESL_SEE, $useeCAEscalation);

                ///////////////////////////////    

                $encAtar = encryptval($getAutoObsid);
                $notifyUrl = "";
                $employeeEmail = false;
                $ptwFooterMailContent =
                    "<br/>This is system generated E-mail, do not reply to this email.<br/>";

                $notifyMessage =
                    "Action taken of the assigned task has been overdue, details are as follows :<br/>
                    <br/><b>Observation - ID : </b> " .
                    $obs_generate_id .
                    "<br/> <b>Reporter Name : </b> " .
                    $Reporter .
                    "<br/><b>Reporter Designation : </b> " .
                    $desig .
                    "<br/><b>Reported Date & Time : </b> " .
                    $obs_report_datetime .
                    " <br/><b>EPC : </b> " .
                    $comp_name .
                    " <br/><b>Area : </b> " .
                    $area_name .
                    " <br/><b>Building/Block/Direction : </b> " .
                    $building_name .
                    " <br/><b>Project : </b> " .
                    $proj_name .
                    " <br/><b>Department : </b> " .
                    $dep_name .
                    " <br/><b>Hse Category : </b> " .
                    $hse_cat .
                    " <br/><b>Assigned Person : </b> " .
                    $assignee .
                    " <br/><b>Target Date to complete CA  : </b> " .
                    $targetdate .
                    " <br/>";
                $notifyUrl =
                    "atarusee/incident/view/" . $encAtar;
                $subject =
                    "Observation - ID - " .
                    $obs_generate_id .
                    " has been overdue";




                $notfiy_id = implode(',', $combinedNotifyIdArray);
                $notify_email_id = implode(',', $combinedEmailArray);


                if ($notify_email_id != '') {
                    $employeeEmail = true;
                }

                $notifydata = [
                    "text" => $notifyMessage,
                    "url" => $notifyUrl,
                    "type" => 1,
                    "notifygroup" => "",
                    "employee" => $notfiy_id,
                    'mobilelink' => $obs_auto_id,
                    'module_auto_id' => 1,
                ];

                if ($notfiy_id != '') {
                    insertNotifications($notifydata);
                }

                $emailMessage = $notifyMessage . $ptwFooterMailContent;


                if ($employeeEmail) {
                    // print_r($notify_email_id);exit;

                    $toemail = $notify_email_id;
                    $message = $emailMessage;



                    $tempdata = [
                        "view_file" => "common/email/common_mail",
                        "messageText" => $message,
                        "footerText" => "",
                    ];
                    //print_r($toemail);exit;
                    $template = $this->template->load_email_template($tempdata);

                    $this->email_model->sendEmail(
                        $toemail,
                        $subject,
                        $template,
                        $path
                    );
                    //$aResponse["success"][$REC_AUTO_ID]["Id"] = $REC_AUTO_ID;

                }
            }
        }
        echo 'success';
        exit;
    }


    function getCronGroupInvEmailDetails($ids, $type = "", $opt = "")
    {
        $options = $opt;
        $ci = &get_instance();

        $options["join"] = [
            LOGIN . " AS LD" => ["LD.USER_REF_ID = EMP.EMP_AUTO_ID", "LEFT"],
        ];
        $options["return_type"] = "row";

        if ($type == "USERTYPE") {
            $options["where_in"] = ["USER_TYPE_ID" => $ids];
            $options["group_by"] = [
                "DESC" => "LD.USER_TYPE_ID",
            ];
        } else {
            $options["where_in"] = ["LOGIN_ID" => $ids];
        }


        echo "<pre>";
        print_r($options);
        exit;
        $details = $ci->common_model->getAlldata(
            EMPL . " as EMP",
            [
                "GROUP_CONCAT( DISTINCT EMP_EMAIL_ID) as emailList",
                "GROUP_CONCAT( DISTINCT LOGIN_ID) as notifyidList",
            ],
            $options,
            $limit = "",
            $offset = "",
            $orderby = "",
            $disporder = ""
        );
        return $details;
    }


    // Inspection Cron 
    public function autoAuditInspExceedNotification()
    {


        $path = '';
        global $cronTo_Supervisor_Escalation_Times;
        global $superadmin;
        global $cronAdmin;
        global $risk_rating, $obs_type_list;

        //////first time escalation
        $currentDateTime = date("Y-m-d H:i:s");
        $currentTime = date("H:i:s");
        $currentDate = date("Y-m-d");
        $options["where"] = [
            'cron_sent_date !=' => $currentDate,
            "insp_status" => "Y",
            "insp_final_tar_date_time <=" => $currentDateTime,
        ];
        $options["where_in"] = [
            "insp_app_status" => ['4', '7']
        ];

        $options["return_type"] = "result";
        $options['join'] = [
            LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_AUDIT_ITEMS . '.insp_reporter_id', 'LEFT'],
            EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_AUDIT_ITEMS . '.insp_assigner_id', 'LEFT'],
            EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

            INSP_AUDIT_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_AUDIT_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
            'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_AUDIT_ITEMS . '.fk_item_cat_id', 'LEFT'],



        ];

        $insDetails = $this->common_model->getAlldata(
            INSP_AUDIT_ITEMS,
            [
                INSP_AUDIT_ITEMS . ".*",
                'insp_main.ins_id',
                'Reporter.EMP_NAME` as Reporter',
                'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
                'FN_COMP_NAME(insp_comp_id) as comp_name',
                'FN_AREA_NAME(insp_area_id) as area_name',
                'FN_BUILD_NAME(insp_building_id) as building_name',
                'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
                'FN_PROJECT_NAME(insp_project_id) as proj_name',
                'Reporter.EMP_NAME as empName',
                'Reporter.EMP_EMAIL_ID as reporter_email_id',
                'Hod.EMP_NAME as assignee',
                'Hod.EMP_EMAIL_ID as assignee_email',
                'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
                'ins_cate_mas.category as category_name',
            ],
            $options,
            $limit = "",
            $offset = "",
            $orderby = "",
            $disporder = ""
        );

        $aResponse = [];
        if ($insDetails != false) {

            foreach ($insDetails as $atardetails) {


                $insp_generate_id = postData($atardetails, 'ins_id');
                $insp_auto_id  = postData($atardetails, 'insp_auto_id');
                $Reporter = postData($atardetails, 'Reporter');
                $desig = postData($atardetails, 'desig');
                $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
                $insp_type_id = postData($atardetails, 'insp_type_id');
                $insp_risk_id = postData($atardetails, 'insp_risk_id');
                $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
                $reporter_email_id = postData($atardetails, 'reporter_email_id');


                $insp_app_status = postData($atardetails, 'insp_app_status');
                $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
                $insp_assigner_type_id = postData($atardetails, 'insp_assigner_type_id');
                $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
                $insp_supervisor_type_id = postData($atardetails, 'insp_supervisor_type_id');
                $insp_hsse_id = postData($atardetails, 'insp_hsse_id');


                $comp_name = postData($atardetails, 'comp_name');
                $area_name = postData($atardetails, 'area_name');
                $building_name = postData($atardetails, 'building_name');
                $dep_name = postData($atardetails, 'dep_name');
                $proj_name = postData($atardetails, 'proj_name');
                $hse_cat = postData($atardetails, 'category_name');
                $inj_name = postData($atardetails, 'inj_name');

                if (isset($risk_rating[$insp_risk_id])) {
                    $insp_risk = $risk_rating[$insp_risk_id];
                } else {
                    $insp_risk = '';
                }

                $assignee = postData($atardetails, 'assignee');
                $assignee_email = postData($atardetails, 'assignee_email');
                $supervisor_email = postData($atardetails, 'supervisor_email');
                $hsse_email = postData($atardetails, 'hsse_email');
                $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
                $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
                $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
                $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
                $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');



                $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));

                //$path = $getRec->insp_email_pdf;
                $getAutoinspid = $atardetails->insp_item_auto_id;

                $prevStatus = $atardetails->insp_app_status;

                $NewfinalTargetDate = date("Y-m-d H:i:s", strtotime($atardetails->insp_final_tar_date_time));
                $targetdate = date("Y-m-d", strtotime($atardetails->insp_assigner_target_date));

                /////cron sent times

                $cron_sent_times = $atardetails->cron_sent_times;

                /////////////////

                if ($cron_sent_times == $cronTo_Supervisor_Escalation_Times) {


                    /////////take hsse E&S                  
                    $insp_hsse_id = $atardetails->insp_hsse_id;

                    $optionsplant['where'] = [
                        'LOGIN_ID' => $insp_hsse_id
                    ];
                    $optionsplant["join"][EMPL . " AS supervisor"] = ["supervisor.EMP_AUTO_ID = USER_REF_ID", "LEFT"];
                    $optionsplant["return_type"] = "result";
                    $getStatdatasph = $this->common_model->getAlldata(LOGIN, ['*'], $optionsplant);

                    $loginIds_ph = array();
                    $emailIds_ph = array();
                }



                $updateData = [
                    'insp_app_status' => 8,
                    'cron_sent_date' => $currentDate
                ];

                $updateWhere = [
                    'insp_item_auto_id' => $getAutoinspid
                ];

                $updateInfo = FALSE;
                if (count($updateWhere) > 0) {
                    $this->db->where($updateWhere);
                    $this->db->update(INSP_AUDIT_ITEMS, $updateData);
                    $updateInfo = $this->db->affected_rows();
                }

                ////////////////////////////////end      

                ///manager track escalation  
                $useeCAEscalation = [
                    'fk_insp_auto_main_id' => $getAutoinspid,
                    "fk_insp_main_stat" => $prevStatus,
                    'cron_notify_login_id' => $insp_reporter_id,
                    'cron_notify_email_id' => $reporter_email_id,
                    "insp_assigned_type_id" => 4,

                ];

                $updtPr = $this->common_model->updateData(INSP_AUDIT_ESL_LOG, $useeCAEscalation);

                ///////////////////////////////    

                $encAtar = encryptval($getAutoinspid);
                $notifyUrl = "";
                $employeeEmail = false;
                $ptwFooterMailContent =
                    "<br/>This is system generated E-mail, do not reply to this email.<br/>";

                $notifyMessage =
                    "Action taken of the assigned task has been overdue, details are as follows :<br/>
                    <br/><b>Inspection - ID : </b> " .
                    $insp_generate_id .
                    "<br/> <b>Reporter Name : </b> " .
                    $Reporter .
                    "<br/><b>Reporter Designation : </b> " .
                    $desig .
                    "<br/><b>Reported Date & Time : </b> " .
                    $insp_report_datetime .
                    " <br/><b>EPC : </b> " .
                    $comp_name .
                    " <br/><b>Area : </b> " .
                    $area_name .
                    " <br/><b>Building/Block/Direction : </b> " .
                    $building_name .
                    " <br/><b>Project : </b> " .
                    $proj_name .
                    " <br/><b>Department : </b> " .
                    $dep_name .
                    " <br/><b>Category : </b> " .
                    $hse_cat .
                    " <br/><b>Assigned Person : </b> " .
                    $assignee .
                    " <br/><b>Target Date to complete CA  : </b> " .
                    $targetdate .
                    " <br/>";
                $notifyUrl =
                    "inspection/audit/view/" . $encAtar;
                $subject =
                    "Inspection - ID - " .
                    $insp_generate_id .
                    " has been overdue";




                $notfiy_id = $insp_reporter_id;
                $notify_email_id = $reporter_email_id;


                if ($notify_email_id != '') {
                    $employeeEmail = true;
                }

                $notifydata = [
                    "text" => $notifyMessage,
                    "url" => $notifyUrl,
                    "type" => 1,
                    "notifygroup" => "",
                    "employee" => $insp_reporter_id,
                    'mobilelink' => $insp_auto_id,
                    'module_auto_id' => 1,
                ];

                if ($notfiy_id != '') {
                    insertNotifications($notifydata);
                }

                $emailMessage = $notifyMessage . $ptwFooterMailContent;


                if ($employeeEmail) {
                    // print_r($notify_email_id);exit;

                    $toemail = $notify_email_id;
                    $message = $emailMessage;



                    $tempdata = [
                        "view_file" => "common/email/common_mail",
                        "messageText" => $message,
                        "footerText" => "",
                    ];
                    //print_r($toemail);exit;
                    $template = $this->template->load_email_template($tempdata);

                    $this->email_model->sendEmail(
                        $toemail,
                        $subject,
                        $template,
                        $path
                    );
                    //$aResponse["success"][$REC_AUTO_ID]["Id"] = $REC_AUTO_ID;

                }
            }
        }
        echo 'success';
        exit;
    }

    public function autoWeeklyInspExceedNotification()
    {


        $path = '';
        global $cronTo_Supervisor_Escalation_Times;
        global $superadmin;
        global $cronAdmin;
        global $risk_rating, $obs_type_list;

        //////first time escalation
        $currentDateTime = date("Y-m-d H:i:s");
        $currentTime = date("H:i:s");
        $currentDate = date("Y-m-d");
        $options["where"] = [
            'cron_sent_date !=' => $currentDate,
            "insp_status" => "Y",
            "insp_final_tar_date_time <=" => $currentDateTime,
        ];
        $options["where_in"] = [
            "insp_app_status" => ['4', '7']
        ];

        $options["return_type"] = "result";
        $options['join'] = [
            LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_WEEKLY_ITEMS . '.insp_reporter_id', 'LEFT'],
            EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_WEEKLY_ITEMS . '.insp_assigner_id', 'LEFT'],
            EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

            INSP_WEEKLY_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_WEEKLY_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
            'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_WEEKLY_ITEMS . '.fk_item_cat_id', 'LEFT'],



        ];

        $insDetails = $this->common_model->getAlldata(
            INSP_WEEKLY_ITEMS,
            [
                INSP_WEEKLY_ITEMS . ".*",
                'insp_main.ins_id',
                'Reporter.EMP_NAME` as Reporter',
                'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
                'FN_COMP_NAME(insp_comp_id) as comp_name',
                'FN_AREA_NAME(insp_area_id) as area_name',
                'FN_BUILD_NAME(insp_building_id) as building_name',
                'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
                'FN_PROJECT_NAME(insp_project_id) as proj_name',
                'Reporter.EMP_NAME as empName',
                'Reporter.EMP_EMAIL_ID as reporter_email_id',
                'Hod.EMP_NAME as assignee',
                'Hod.EMP_EMAIL_ID as assignee_email',
                'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
                'ins_cate_mas.category as category_name',
            ],
            $options,
            $limit = "",
            $offset = "",
            $orderby = "",
            $disporder = ""
        );

        $aResponse = [];
        if ($insDetails != false) {

            foreach ($insDetails as $atardetails) {


                $insp_generate_id = postData($atardetails, 'ins_id');
                $insp_auto_id  = postData($atardetails, 'insp_auto_id');
                $Reporter = postData($atardetails, 'Reporter');
                $desig = postData($atardetails, 'desig');
                $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
                $insp_type_id = postData($atardetails, 'insp_type_id');
                $insp_risk_id = postData($atardetails, 'insp_risk_id');
                $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
                $reporter_email_id = postData($atardetails, 'reporter_email_id');


                $insp_app_status = postData($atardetails, 'insp_app_status');
                $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
                $insp_assigner_type_id = postData($atardetails, 'insp_assigner_type_id');
                $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
                $insp_supervisor_type_id = postData($atardetails, 'insp_supervisor_type_id');
                $insp_hsse_id = postData($atardetails, 'insp_hsse_id');


                $comp_name = postData($atardetails, 'comp_name');
                $area_name = postData($atardetails, 'area_name');
                $building_name = postData($atardetails, 'building_name');
                $dep_name = postData($atardetails, 'dep_name');
                $proj_name = postData($atardetails, 'proj_name');
                $hse_cat = postData($atardetails, 'category_name');
                $inj_name = postData($atardetails, 'inj_name');

                if (isset($risk_rating[$insp_risk_id])) {
                    $insp_risk = $risk_rating[$insp_risk_id];
                } else {
                    $insp_risk = '';
                }

                $assignee = postData($atardetails, 'assignee');
                $assignee_email = postData($atardetails, 'assignee_email');
                $supervisor_email = postData($atardetails, 'supervisor_email');
                $hsse_email = postData($atardetails, 'hsse_email');
                $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
                $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
                $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
                $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
                $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');



                $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));

                //$path = $getRec->insp_email_pdf;
                $getAutoinspid = $atardetails->insp_item_auto_id;

                $prevStatus = $atardetails->insp_app_status;

                $NewfinalTargetDate = date("Y-m-d H:i:s", strtotime($atardetails->insp_final_tar_date_time));
                $targetdate = date("Y-m-d", strtotime($atardetails->insp_assigner_target_date));

                /////cron sent times

                $cron_sent_times = $atardetails->cron_sent_times;

                /////////////////

                if ($cron_sent_times == $cronTo_Supervisor_Escalation_Times) {


                    /////////take hsse E&S                  
                    $insp_hsse_id = $atardetails->insp_hsse_id;

                    $optionsplant['where'] = [
                        'LOGIN_ID' => $insp_hsse_id
                    ];
                    $optionsplant["join"][EMPL . " AS supervisor"] = ["supervisor.EMP_AUTO_ID = USER_REF_ID", "LEFT"];
                    $optionsplant["return_type"] = "result";
                    $getStatdatasph = $this->common_model->getAlldata(LOGIN, ['*'], $optionsplant);

                    $loginIds_ph = array();
                    $emailIds_ph = array();
                }



                $updateData = [
                    'insp_app_status' => 8,
                    'cron_sent_date' => $currentDate
                ];

                $updateWhere = [
                    'insp_item_auto_id' => $getAutoinspid
                ];

                $updateInfo = FALSE;
                if (count($updateWhere) > 0) {
                    $this->db->where($updateWhere);
                    $this->db->update(INSP_WEEKLY_ITEMS, $updateData);
                    $updateInfo = $this->db->affected_rows();
                }

                ////////////////////////////////end      

                ///manager track escalation  
                $useeCAEscalation = [
                    'fk_insp_auto_main_id' => $getAutoinspid,
                    "fk_insp_main_stat" => $prevStatus,
                    'cron_notify_login_id' => $insp_reporter_id,
                    'cron_notify_email_id' => $reporter_email_id,
                    "insp_assigned_type_id" => 4,

                ];

                $updtPr = $this->common_model->updateData(INSP_WEEKLY_ESL_LOG, $useeCAEscalation);

                ///////////////////////////////    

                $encAtar = encryptval($getAutoinspid);
                $notifyUrl = "";
                $employeeEmail = false;
                $ptwFooterMailContent =
                    "<br/>This is system generated E-mail, do not reply to this email.<br/>";

                $notifyMessage =
                    "Action taken of the assigned task has been overdue, details are as follows :<br/>
                    <br/><b>Inspection - ID : </b> " .
                    $insp_generate_id .
                    "<br/> <b>Reporter Name : </b> " .
                    $Reporter .
                    "<br/><b>Reporter Designation : </b> " .
                    $desig .
                    "<br/><b>Reported Date & Time : </b> " .
                    $insp_report_datetime .
                    " <br/><b>EPC : </b> " .
                    $comp_name .
                    " <br/><b>Area : </b> " .
                    $area_name .
                    " <br/><b>Building/Block/Direction : </b> " .
                    $building_name .
                    " <br/><b>Project : </b> " .
                    $proj_name .
                    " <br/><b>Department : </b> " .
                    $dep_name .
                    " <br/><b>Category : </b> " .
                    $hse_cat .
                    " <br/><b>Assigned Person : </b> " .
                    $assignee .
                    " <br/><b>Target Date to complete CA  : </b> " .
                    $targetdate .
                    " <br/>";
                $notifyUrl =
                    "inspection/audit/view/" . $encAtar;
                $subject =
                    "Inspection - ID - " .
                    $insp_generate_id .
                    " has been overdue";




                $notfiy_id = $insp_reporter_id;
                $notify_email_id = $reporter_email_id;


                if ($notify_email_id != '') {
                    $employeeEmail = true;
                }

                $notifydata = [
                    "text" => $notifyMessage,
                    "url" => $notifyUrl,
                    "type" => 1,
                    "notifygroup" => "",
                    "employee" => $insp_reporter_id,
                    'mobilelink' => $insp_auto_id,
                    'module_auto_id' => 1,
                ];

                if ($notfiy_id != '') {
                    insertNotifications($notifydata);
                }

                $emailMessage = $notifyMessage . $ptwFooterMailContent;


                if ($employeeEmail) {
                    // print_r($notify_email_id);exit;

                    $toemail = $notify_email_id;
                    $message = $emailMessage;



                    $tempdata = [
                        "view_file" => "common/email/common_mail",
                        "messageText" => $message,
                        "footerText" => "",
                    ];
                    //print_r($toemail);exit;
                    $template = $this->template->load_email_template($tempdata);

                    $this->email_model->sendEmail(
                        $toemail,
                        $subject,
                        $template,
                        $path
                    );
                    //$aResponse["success"][$REC_AUTO_ID]["Id"] = $REC_AUTO_ID;

                }
            }
        }
        echo 'success';
        exit;
    }


    public function autoWorkcampInspExceedNotification()
    {


        $path = '';
        global $cronTo_Supervisor_Escalation_Times;
        global $superadmin;
        global $cronAdmin;
        global $risk_rating, $obs_type_list;

        //////first time escalation
        $currentDateTime = date("Y-m-d H:i:s");
        $currentTime = date("H:i:s");
        $currentDate = date("Y-m-d");
        $options["where"] = [
            'cron_sent_date !=' => $currentDate,
            "insp_status" => "Y",
            "insp_final_tar_date_time <=" => $currentDateTime,
        ];
        $options["where_in"] = [
            "insp_app_status" => ['4', '7']
        ];

        $options["return_type"] = "result";
        $options['join'] = [
            LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_WORKCAMP_ITEMS . '.insp_reporter_id', 'LEFT'],
            EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_WORKCAMP_ITEMS . '.insp_assigner_id', 'LEFT'],
            EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

            INSP_WORKCAMP_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_WORKCAMP_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
            'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_WORKCAMP_ITEMS . '.fk_item_cat_id', 'LEFT'],



        ];

        $insDetails = $this->common_model->getAlldata(
            INSP_WORKCAMP_ITEMS,
            [
                INSP_WORKCAMP_ITEMS . ".*",
                'insp_main.ins_id',
                'Reporter.EMP_NAME` as Reporter',
                'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
                'FN_COMP_NAME(insp_comp_id) as comp_name',
                'FN_AREA_NAME(insp_area_id) as area_name',
                'FN_BUILD_NAME(insp_building_id) as building_name',
                'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
                'FN_PROJECT_NAME(insp_project_id) as proj_name',
                'Reporter.EMP_NAME as empName',
                'Reporter.EMP_EMAIL_ID as reporter_email_id',
                'Hod.EMP_NAME as assignee',
                'Hod.EMP_EMAIL_ID as assignee_email',
                'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
                'ins_cate_mas.category as category_name',
            ],
            $options,
            $limit = "",
            $offset = "",
            $orderby = "",
            $disporder = ""
        );

        $aResponse = [];
        if ($insDetails != false) {

            foreach ($insDetails as $atardetails) {


                $insp_generate_id = postData($atardetails, 'ins_id');
                $insp_auto_id  = postData($atardetails, 'insp_auto_id');
                $Reporter = postData($atardetails, 'Reporter');
                $desig = postData($atardetails, 'desig');
                $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
                $insp_type_id = postData($atardetails, 'insp_type_id');
                $insp_risk_id = postData($atardetails, 'insp_risk_id');
                $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
                $reporter_email_id = postData($atardetails, 'reporter_email_id');


                $insp_app_status = postData($atardetails, 'insp_app_status');
                $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
                $insp_assigner_type_id = postData($atardetails, 'insp_assigner_type_id');
                $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
                $insp_supervisor_type_id = postData($atardetails, 'insp_supervisor_type_id');
                $insp_hsse_id = postData($atardetails, 'insp_hsse_id');


                $comp_name = postData($atardetails, 'comp_name');
                $area_name = postData($atardetails, 'area_name');
                $building_name = postData($atardetails, 'building_name');
                $dep_name = postData($atardetails, 'dep_name');
                $proj_name = postData($atardetails, 'proj_name');
                $hse_cat = postData($atardetails, 'category_name');
                $inj_name = postData($atardetails, 'inj_name');

                if (isset($risk_rating[$insp_risk_id])) {
                    $insp_risk = $risk_rating[$insp_risk_id];
                } else {
                    $insp_risk = '';
                }

                $assignee = postData($atardetails, 'assignee');
                $assignee_email = postData($atardetails, 'assignee_email');
                $supervisor_email = postData($atardetails, 'supervisor_email');
                $hsse_email = postData($atardetails, 'hsse_email');
                $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
                $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
                $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
                $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
                $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');



                $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));

                //$path = $getRec->insp_email_pdf;
                $getAutoinspid = $atardetails->insp_item_auto_id;

                $prevStatus = $atardetails->insp_app_status;

                $NewfinalTargetDate = date("Y-m-d H:i:s", strtotime($atardetails->insp_final_tar_date_time));
                $targetdate = date("Y-m-d", strtotime($atardetails->insp_assigner_target_date));

                /////cron sent times

                $cron_sent_times = $atardetails->cron_sent_times;

                /////////////////

                if ($cron_sent_times == $cronTo_Supervisor_Escalation_Times) {


                    /////////take hsse E&S                  
                    $insp_hsse_id = $atardetails->insp_hsse_id;

                    $optionsplant['where'] = [
                        'LOGIN_ID' => $insp_hsse_id
                    ];
                    $optionsplant["join"][EMPL . " AS supervisor"] = ["supervisor.EMP_AUTO_ID = USER_REF_ID", "LEFT"];
                    $optionsplant["return_type"] = "result";
                    $getStatdatasph = $this->common_model->getAlldata(LOGIN, ['*'], $optionsplant);

                    $loginIds_ph = array();
                    $emailIds_ph = array();
                }



                $updateData = [
                    'insp_app_status' => 8,
                    'cron_sent_date' => $currentDate
                ];

                $updateWhere = [
                    'insp_item_auto_id' => $getAutoinspid
                ];

                $updateInfo = FALSE;
                if (count($updateWhere) > 0) {
                    $this->db->where($updateWhere);
                    $this->db->update(INSP_WORKCAMP_ITEMS, $updateData);
                    $updateInfo = $this->db->affected_rows();
                }

                ////////////////////////////////end      

                ///manager track escalation  
                $useeCAEscalation = [
                    'fk_insp_auto_main_id' => $getAutoinspid,
                    "fk_insp_main_stat" => $prevStatus,
                    'cron_notify_login_id' => $insp_reporter_id,
                    'cron_notify_email_id' => $reporter_email_id,
                    "insp_assigned_type_id" => 4,

                ];

                $updtPr = $this->common_model->updateData(INSP_WORKCAMP_ESL_LOG, $useeCAEscalation);

                ///////////////////////////////    

                $encAtar = encryptval($getAutoinspid);
                $notifyUrl = "";
                $employeeEmail = false;
                $ptwFooterMailContent =
                    "<br/>This is system generated E-mail, do not reply to this email.<br/>";

                $notifyMessage =
                    "Action taken of the assigned task has been overdue, details are as follows :<br/>
                    <br/><b>Inspection - ID : </b> " .
                    $insp_generate_id .
                    "<br/> <b>Reporter Name : </b> " .
                    $Reporter .
                    "<br/><b>Reporter Designation : </b> " .
                    $desig .
                    "<br/><b>Reported Date & Time : </b> " .
                    $insp_report_datetime .
                    " <br/><b>EPC : </b> " .
                    $comp_name .
                    " <br/><b>Area : </b> " .
                    $area_name .
                    " <br/><b>Building/Block/Direction : </b> " .
                    $building_name .
                    " <br/><b>Project : </b> " .
                    $proj_name .
                    " <br/><b>Department : </b> " .
                    $dep_name .
                    " <br/><b>Category : </b> " .
                    $hse_cat .
                    " <br/><b>Assigned Person : </b> " .
                    $assignee .
                    " <br/><b>Target Date to complete CA  : </b> " .
                    $targetdate .
                    " <br/>";
                $notifyUrl =
                    "inspection/audit/view/" . $encAtar;
                $subject =
                    "Inspection - ID - " .
                    $insp_generate_id .
                    " has been overdue";




                $notfiy_id = $insp_reporter_id;
                $notify_email_id = $reporter_email_id;


                if ($notify_email_id != '') {
                    $employeeEmail = true;
                }

                $notifydata = [
                    "text" => $notifyMessage,
                    "url" => $notifyUrl,
                    "type" => 1,
                    "notifygroup" => "",
                    "employee" => $insp_reporter_id,
                    'mobilelink' => $insp_auto_id,
                    'module_auto_id' => 1,
                ];

                if ($notfiy_id != '') {
                    insertNotifications($notifydata);
                }

                $emailMessage = $notifyMessage . $ptwFooterMailContent;


                if ($employeeEmail) {
                    // print_r($notify_email_id);exit;

                    $toemail = $notify_email_id;
                    $message = $emailMessage;



                    $tempdata = [
                        "view_file" => "common/email/common_mail",
                        "messageText" => $message,
                        "footerText" => "",
                    ];
                    //print_r($toemail);exit;
                    $template = $this->template->load_email_template($tempdata);

                    $this->email_model->sendEmail(
                        $toemail,
                        $subject,
                        $template,
                        $path
                    );
                    //$aResponse["success"][$REC_AUTO_ID]["Id"] = $REC_AUTO_ID;

                }
            }
        }
        echo 'success';
        exit;
    }

    public function autoMonthlyInspExceedNotification()
    {


        $path = '';
        global $cronTo_Supervisor_Escalation_Times;
        global $superadmin;
        global $cronAdmin;
        global $risk_rating, $obs_type_list;

        //////first time escalation
        $currentDateTime = date("Y-m-d H:i:s");
        $currentTime = date("H:i:s");
        $currentDate = date("Y-m-d");
        $options["where"] = [
            'cron_sent_date !=' => $currentDate,
            "insp_status" => "Y",
            "insp_final_tar_date_time <=" => $currentDateTime,
        ];
        $options["where_in"] = [
            "insp_app_status" => ['4', '7']
        ];

        $options["return_type"] = "result";
        $options['join'] = [
            LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_MONTH_ITEMS . '.insp_reporter_id', 'LEFT'],
            EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_MONTH_ITEMS . '.insp_assigner_id', 'LEFT'],
            EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

            INSP_MONTH_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_MONTH_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
            'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_MONTH_ITEMS . '.fk_item_cat_id', 'LEFT'],



        ];

        $insDetails = $this->common_model->getAlldata(
            INSP_MONTH_ITEMS,
            [
                INSP_MONTH_ITEMS . ".*",
                'insp_main.ins_id',
                'Reporter.EMP_NAME` as Reporter',
                'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
                'FN_COMP_NAME(insp_comp_id) as comp_name',
                'FN_AREA_NAME(insp_area_id) as area_name',
                'FN_BUILD_NAME(insp_building_id) as building_name',
                'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
                'FN_PROJECT_NAME(insp_project_id) as proj_name',
                'Reporter.EMP_NAME as empName',
                'Reporter.EMP_EMAIL_ID as reporter_email_id',
                'Hod.EMP_NAME as assignee',
                'Hod.EMP_EMAIL_ID as assignee_email',
                'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
                'ins_cate_mas.category as category_name',
            ],
            $options,
            $limit = "",
            $offset = "",
            $orderby = "",
            $disporder = ""
        );

        $aResponse = [];
        if ($insDetails != false) {

            foreach ($insDetails as $atardetails) {


                $insp_generate_id = postData($atardetails, 'ins_id');
                $insp_auto_id  = postData($atardetails, 'insp_auto_id');
                $Reporter = postData($atardetails, 'Reporter');
                $desig = postData($atardetails, 'desig');
                $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
                $insp_type_id = postData($atardetails, 'insp_type_id');
                $insp_risk_id = postData($atardetails, 'insp_risk_id');
                $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
                $reporter_email_id = postData($atardetails, 'reporter_email_id');


                $insp_app_status = postData($atardetails, 'insp_app_status');
                $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
                $insp_assigner_type_id = postData($atardetails, 'insp_assigner_type_id');
                $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
                $insp_supervisor_type_id = postData($atardetails, 'insp_supervisor_type_id');
                $insp_hsse_id = postData($atardetails, 'insp_hsse_id');


                $comp_name = postData($atardetails, 'comp_name');
                $area_name = postData($atardetails, 'area_name');
                $building_name = postData($atardetails, 'building_name');
                $dep_name = postData($atardetails, 'dep_name');
                $proj_name = postData($atardetails, 'proj_name');
                $hse_cat = postData($atardetails, 'category_name');
                $inj_name = postData($atardetails, 'inj_name');

                if (isset($risk_rating[$insp_risk_id])) {
                    $insp_risk = $risk_rating[$insp_risk_id];
                } else {
                    $insp_risk = '';
                }

                $assignee = postData($atardetails, 'assignee');
                $assignee_email = postData($atardetails, 'assignee_email');
                $supervisor_email = postData($atardetails, 'supervisor_email');
                $hsse_email = postData($atardetails, 'hsse_email');
                $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
                $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
                $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
                $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
                $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');



                $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));

                //$path = $getRec->insp_email_pdf;
                $getAutoinspid = $atardetails->insp_item_auto_id;

                $prevStatus = $atardetails->insp_app_status;

                $NewfinalTargetDate = date("Y-m-d H:i:s", strtotime($atardetails->insp_final_tar_date_time));
                $targetdate = date("Y-m-d", strtotime($atardetails->insp_assigner_target_date));

                /////cron sent times

                $cron_sent_times = $atardetails->cron_sent_times;

                /////////////////

                if ($cron_sent_times == $cronTo_Supervisor_Escalation_Times) {


                    /////////take hsse E&S                  
                    $insp_hsse_id = $atardetails->insp_hsse_id;

                    $optionsplant['where'] = [
                        'LOGIN_ID' => $insp_hsse_id
                    ];
                    $optionsplant["join"][EMPL . " AS supervisor"] = ["supervisor.EMP_AUTO_ID = USER_REF_ID", "LEFT"];
                    $optionsplant["return_type"] = "result";
                    $getStatdatasph = $this->common_model->getAlldata(LOGIN, ['*'], $optionsplant);

                    $loginIds_ph = array();
                    $emailIds_ph = array();
                }



                $updateData = [
                    'insp_app_status' => 8,
                    'cron_sent_date' => $currentDate
                ];

                $updateWhere = [
                    'insp_item_auto_id' => $getAutoinspid
                ];

                $updateInfo = FALSE;
                if (count($updateWhere) > 0) {
                    $this->db->where($updateWhere);
                    $this->db->update(INSP_MONTH_ITEMS, $updateData);
                    $updateInfo = $this->db->affected_rows();
                }

                ////////////////////////////////end      

                ///manager track escalation  
                $useeCAEscalation = [
                    'fk_insp_auto_main_id' => $getAutoinspid,
                    "fk_insp_main_stat" => $prevStatus,
                    'cron_notify_login_id' => $insp_reporter_id,
                    'cron_notify_email_id' => $reporter_email_id,
                    "insp_assigned_type_id" => 4,

                ];

                $updtPr = $this->common_model->updateData(INSP_MONTH_ESL_LOG, $useeCAEscalation);

                ///////////////////////////////    

                $encAtar = encryptval($getAutoinspid);
                $notifyUrl = "";
                $employeeEmail = false;
                $ptwFooterMailContent =
                    "<br/>This is system generated E-mail, do not reply to this email.<br/>";

                $notifyMessage =
                    "Action taken of the assigned task has been overdue, details are as follows :<br/>
                    <br/><b>Inspection - ID : </b> " .
                    $insp_generate_id .
                    "<br/> <b>Reporter Name : </b> " .
                    $Reporter .
                    "<br/><b>Reporter Designation : </b> " .
                    $desig .
                    "<br/><b>Reported Date & Time : </b> " .
                    $insp_report_datetime .
                    " <br/><b>EPC : </b> " .
                    $comp_name .
                    " <br/><b>Area : </b> " .
                    $area_name .
                    " <br/><b>Building/Block/Direction : </b> " .
                    $building_name .
                    " <br/><b>Project : </b> " .
                    $proj_name .
                    " <br/><b>Department : </b> " .
                    $dep_name .
                    " <br/><b>Category : </b> " .
                    $hse_cat .
                    " <br/><b>Assigned Person : </b> " .
                    $assignee .
                    " <br/><b>Target Date to complete CA  : </b> " .
                    $targetdate .
                    " <br/>";
                $notifyUrl =
                    "inspection/audit/view/" . $encAtar;
                $subject =
                    "Inspection - ID - " .
                    $insp_generate_id .
                    " has been overdue";




                $notfiy_id = $insp_reporter_id;
                $notify_email_id = $reporter_email_id;


                if ($notify_email_id != '') {
                    $employeeEmail = true;
                }

                $notifydata = [
                    "text" => $notifyMessage,
                    "url" => $notifyUrl,
                    "type" => 1,
                    "notifygroup" => "",
                    "employee" => $insp_reporter_id,
                    'mobilelink' => $insp_auto_id,
                    'module_auto_id' => 1,
                ];

                if ($notfiy_id != '') {
                    insertNotifications($notifydata);
                }

                $emailMessage = $notifyMessage . $ptwFooterMailContent;


                if ($employeeEmail) {
                    // print_r($notify_email_id);exit;

                    $toemail = $notify_email_id;
                    $message = $emailMessage;



                    $tempdata = [
                        "view_file" => "common/email/common_mail",
                        "messageText" => $message,
                        "footerText" => "",
                    ];
                    //print_r($toemail);exit;
                    $template = $this->template->load_email_template($tempdata);

                    $this->email_model->sendEmail(
                        $toemail,
                        $subject,
                        $template,
                        $path
                    );
                    //$aResponse["success"][$REC_AUTO_ID]["Id"] = $REC_AUTO_ID;

                }
            }
        }
        echo 'success';
        exit;
    }

    public function autoHotelInspExceedNotification()
    {


        $path = '';
        global $cronTo_Supervisor_Escalation_Times;
        global $superadmin;
        global $cronAdmin;
        global $risk_rating, $obs_type_list;

        //////first time escalation
        $currentDateTime = date("Y-m-d H:i:s");
        $currentTime = date("H:i:s");
        $currentDate = date("Y-m-d");
        $options["where"] = [
            'cron_sent_date !=' => $currentDate,
            "insp_status" => "Y",
            "insp_final_tar_date_time <=" => $currentDateTime,
        ];
        $options["where_in"] = [
            "insp_app_status" => ['4', '7']
        ];

        $options["return_type"] = "result";
        $options['join'] = [
            LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_HOTEL_ITEMS . '.insp_reporter_id', 'LEFT'],
            EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_HOTEL_ITEMS . '.insp_assigner_id', 'LEFT'],
            EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

            INSP_HOTEL_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_HOTEL_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
            'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_HOTEL_ITEMS . '.fk_item_cat_id', 'LEFT'],



        ];

        $insDetails = $this->common_model->getAlldata(
            INSP_HOTEL_ITEMS,
            [
                INSP_HOTEL_ITEMS . ".*",
                'insp_main.ins_id',
                'Reporter.EMP_NAME` as Reporter',
                'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
                'FN_COMP_NAME(insp_comp_id) as comp_name',
                'FN_AREA_NAME(insp_area_id) as area_name',
                'FN_BUILD_NAME(insp_building_id) as building_name',
                'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
                'FN_PROJECT_NAME(insp_project_id) as proj_name',
                'Reporter.EMP_NAME as empName',
                'Reporter.EMP_EMAIL_ID as reporter_email_id',
                'Hod.EMP_NAME as assignee',
                'Hod.EMP_EMAIL_ID as assignee_email',
                'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
                'ins_cate_mas.category as category_name',
            ],
            $options,
            $limit = "",
            $offset = "",
            $orderby = "",
            $disporder = ""
        );

        $aResponse = [];
        if ($insDetails != false) {

            foreach ($insDetails as $atardetails) {


                $insp_generate_id = postData($atardetails, 'ins_id');
                $insp_auto_id  = postData($atardetails, 'insp_auto_id');
                $Reporter = postData($atardetails, 'Reporter');
                $desig = postData($atardetails, 'desig');
                $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
                $insp_type_id = postData($atardetails, 'insp_type_id');
                $insp_risk_id = postData($atardetails, 'insp_risk_id');
                $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
                $reporter_email_id = postData($atardetails, 'reporter_email_id');


                $insp_app_status = postData($atardetails, 'insp_app_status');
                $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
                $insp_assigner_type_id = postData($atardetails, 'insp_assigner_type_id');
                $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
                $insp_supervisor_type_id = postData($atardetails, 'insp_supervisor_type_id');
                $insp_hsse_id = postData($atardetails, 'insp_hsse_id');


                $comp_name = postData($atardetails, 'comp_name');
                $area_name = postData($atardetails, 'area_name');
                $building_name = postData($atardetails, 'building_name');
                $dep_name = postData($atardetails, 'dep_name');
                $proj_name = postData($atardetails, 'proj_name');
                $hse_cat = postData($atardetails, 'category_name');
                $inj_name = postData($atardetails, 'inj_name');

                if (isset($risk_rating[$insp_risk_id])) {
                    $insp_risk = $risk_rating[$insp_risk_id];
                } else {
                    $insp_risk = '';
                }

                $assignee = postData($atardetails, 'assignee');
                $assignee_email = postData($atardetails, 'assignee_email');
                $supervisor_email = postData($atardetails, 'supervisor_email');
                $hsse_email = postData($atardetails, 'hsse_email');
                $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
                $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
                $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
                $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
                $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');



                $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));

                //$path = $getRec->insp_email_pdf;
                $getAutoinspid = $atardetails->insp_item_auto_id;

                $prevStatus = $atardetails->insp_app_status;

                $NewfinalTargetDate = date("Y-m-d H:i:s", strtotime($atardetails->insp_final_tar_date_time));
                $targetdate = date("Y-m-d", strtotime($atardetails->insp_assigner_target_date));

                /////cron sent times

                $cron_sent_times = $atardetails->cron_sent_times;

                /////////////////

                if ($cron_sent_times == $cronTo_Supervisor_Escalation_Times) {


                    /////////take hsse E&S                  
                    $insp_hsse_id = $atardetails->insp_hsse_id;

                    $optionsplant['where'] = [
                        'LOGIN_ID' => $insp_hsse_id
                    ];
                    $optionsplant["join"][EMPL . " AS supervisor"] = ["supervisor.EMP_AUTO_ID = USER_REF_ID", "LEFT"];
                    $optionsplant["return_type"] = "result";
                    $getStatdatasph = $this->common_model->getAlldata(LOGIN, ['*'], $optionsplant);

                    $loginIds_ph = array();
                    $emailIds_ph = array();
                }



                $updateData = [
                    'insp_app_status' => 8,
                    'cron_sent_date' => $currentDate
                ];

                $updateWhere = [
                    'insp_item_auto_id' => $getAutoinspid
                ];

                $updateInfo = FALSE;
                if (count($updateWhere) > 0) {
                    $this->db->where($updateWhere);
                    $this->db->update(INSP_HOTEL_ITEMS, $updateData);
                    $updateInfo = $this->db->affected_rows();
                }

                ////////////////////////////////end      

                ///manager track escalation  
                $useeCAEscalation = [
                    'fk_insp_auto_main_id' => $getAutoinspid,
                    "fk_insp_main_stat" => $prevStatus,
                    'cron_notify_login_id' => $insp_reporter_id,
                    'cron_notify_email_id' => $reporter_email_id,
                    "insp_assigned_type_id" => 4,

                ];

                $updtPr = $this->common_model->updateData(INSP_HOTEL_ESL_LOG, $useeCAEscalation);

                ///////////////////////////////    

                $encAtar = encryptval($getAutoinspid);
                $notifyUrl = "";
                $employeeEmail = false;
                $ptwFooterMailContent =
                    "<br/>This is system generated E-mail, do not reply to this email.<br/>";

                $notifyMessage =
                    "Action taken of the assigned task has been overdue, details are as follows :<br/>
                    <br/><b>Inspection - ID : </b> " .
                    $insp_generate_id .
                    "<br/> <b>Reporter Name : </b> " .
                    $Reporter .
                    "<br/><b>Reporter Designation : </b> " .
                    $desig .
                    "<br/><b>Reported Date & Time : </b> " .
                    $insp_report_datetime .
                    " <br/><b>EPC : </b> " .
                    $comp_name .
                    " <br/><b>Area : </b> " .
                    $area_name .
                    " <br/><b>Building/Block/Direction : </b> " .
                    $building_name .
                    " <br/><b>Project : </b> " .
                    $proj_name .
                    " <br/><b>Department : </b> " .
                    $dep_name .
                    " <br/><b>Category : </b> " .
                    $hse_cat .
                    " <br/><b>Assigned Person : </b> " .
                    $assignee .
                    " <br/><b>Target Date to complete CA  : </b> " .
                    $targetdate .
                    " <br/>";
                $notifyUrl =
                    "inspection/audit/view/" . $encAtar;
                $subject =
                    "Inspection - ID - " .
                    $insp_generate_id .
                    " has been overdue";




                $notfiy_id = $insp_reporter_id;
                $notify_email_id = $reporter_email_id;


                if ($notify_email_id != '') {
                    $employeeEmail = true;
                }

                $notifydata = [
                    "text" => $notifyMessage,
                    "url" => $notifyUrl,
                    "type" => 1,
                    "notifygroup" => "",
                    "employee" => $insp_reporter_id,
                    'mobilelink' => $insp_auto_id,
                    'module_auto_id' => 1,
                ];

                if ($notfiy_id != '') {
                    insertNotifications($notifydata);
                }

                $emailMessage = $notifyMessage . $ptwFooterMailContent;


                if ($employeeEmail) {
                    // print_r($notify_email_id);exit;

                    $toemail = $notify_email_id;
                    $message = $emailMessage;



                    $tempdata = [
                        "view_file" => "common/email/common_mail",
                        "messageText" => $message,
                        "footerText" => "",
                    ];
                    //print_r($toemail);exit;
                    $template = $this->template->load_email_template($tempdata);

                    $this->email_model->sendEmail(
                        $toemail,
                        $subject,
                        $template,
                        $path
                    );
                    //$aResponse["success"][$REC_AUTO_ID]["Id"] = $REC_AUTO_ID;

                }
            }
        }
        echo 'success';
        exit;
    }

    public function autoInitialInspExceedNotification()
    {


        $path = '';
        global $cronTo_Supervisor_Escalation_Times;
        global $superadmin;
        global $cronAdmin;
        global $risk_rating, $obs_type_list;

        //////first time escalation
        $currentDateTime = date("Y-m-d H:i:s");
        $currentTime = date("H:i:s");
        $currentDate = date("Y-m-d");
        $options["where"] = [
            'cron_sent_date !=' => $currentDate,
            "insp_status" => "Y",
            "insp_final_tar_date_time <=" => $currentDateTime,
        ];
        $options["where_in"] = [
            "insp_app_status" => ['4', '7']
        ];

        $options["return_type"] = "result";
        $options['join'] = [
            LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_INITIAL_ITEMS . '.insp_reporter_id', 'LEFT'],
            EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_INITIAL_ITEMS . '.insp_assigner_id', 'LEFT'],
            EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

            INSP_INITIAL_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_INITIAL_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
            'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_INITIAL_ITEMS . '.fk_item_cat_id', 'LEFT'],



        ];

        $insDetails = $this->common_model->getAlldata(
            INSP_INITIAL_ITEMS,
            [
                INSP_INITIAL_ITEMS . ".*",
                'insp_main.ins_id',
                'Reporter.EMP_NAME` as Reporter',
                'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
                'FN_COMP_NAME(insp_comp_id) as comp_name',
                'FN_AREA_NAME(insp_area_id) as area_name',
                'FN_BUILD_NAME(insp_building_id) as building_name',
                'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
                'FN_PROJECT_NAME(insp_project_id) as proj_name',
                'Reporter.EMP_NAME as empName',
                'Reporter.EMP_EMAIL_ID as reporter_email_id',
                'Hod.EMP_NAME as assignee',
                'Hod.EMP_EMAIL_ID as assignee_email',
                'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
                'ins_cate_mas.category as category_name',
            ],
            $options,
            $limit = "",
            $offset = "",
            $orderby = "",
            $disporder = ""
        );

        $aResponse = [];
        if ($insDetails != false) {

            foreach ($insDetails as $atardetails) {


                $insp_generate_id = postData($atardetails, 'ins_id');
                $insp_auto_id  = postData($atardetails, 'insp_auto_id');
                $Reporter = postData($atardetails, 'Reporter');
                $desig = postData($atardetails, 'desig');
                $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
                $insp_type_id = postData($atardetails, 'insp_type_id');
                $insp_risk_id = postData($atardetails, 'insp_risk_id');
                $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
                $reporter_email_id = postData($atardetails, 'reporter_email_id');


                $insp_app_status = postData($atardetails, 'insp_app_status');
                $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
                $insp_assigner_type_id = postData($atardetails, 'insp_assigner_type_id');
                $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
                $insp_supervisor_type_id = postData($atardetails, 'insp_supervisor_type_id');
                $insp_hsse_id = postData($atardetails, 'insp_hsse_id');


                $comp_name = postData($atardetails, 'comp_name');
                $area_name = postData($atardetails, 'area_name');
                $building_name = postData($atardetails, 'building_name');
                $dep_name = postData($atardetails, 'dep_name');
                $proj_name = postData($atardetails, 'proj_name');
                $hse_cat = postData($atardetails, 'category_name');
                $inj_name = postData($atardetails, 'inj_name');

                if (isset($risk_rating[$insp_risk_id])) {
                    $insp_risk = $risk_rating[$insp_risk_id];
                } else {
                    $insp_risk = '';
                }

                $assignee = postData($atardetails, 'assignee');
                $assignee_email = postData($atardetails, 'assignee_email');
                $supervisor_email = postData($atardetails, 'supervisor_email');
                $hsse_email = postData($atardetails, 'hsse_email');
                $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
                $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
                $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
                $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
                $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');



                $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));

                //$path = $getRec->insp_email_pdf;
                $getAutoinspid = $atardetails->insp_item_auto_id;

                $prevStatus = $atardetails->insp_app_status;

                $NewfinalTargetDate = date("Y-m-d H:i:s", strtotime($atardetails->insp_final_tar_date_time));
                $targetdate = date("Y-m-d", strtotime($atardetails->insp_assigner_target_date));

                /////cron sent times

                $cron_sent_times = $atardetails->cron_sent_times;

                /////////////////

                if ($cron_sent_times == $cronTo_Supervisor_Escalation_Times) {


                    /////////take hsse E&S                  
                    $insp_hsse_id = $atardetails->insp_hsse_id;

                    $optionsplant['where'] = [
                        'LOGIN_ID' => $insp_hsse_id
                    ];
                    $optionsplant["join"][EMPL . " AS supervisor"] = ["supervisor.EMP_AUTO_ID = USER_REF_ID", "LEFT"];
                    $optionsplant["return_type"] = "result";
                    $getStatdatasph = $this->common_model->getAlldata(LOGIN, ['*'], $optionsplant);

                    $loginIds_ph = array();
                    $emailIds_ph = array();
                }



                $updateData = [
                    'insp_app_status' => 8,
                    'cron_sent_date' => $currentDate
                ];

                $updateWhere = [
                    'insp_item_auto_id' => $getAutoinspid
                ];

                $updateInfo = FALSE;
                if (count($updateWhere) > 0) {
                    $this->db->where($updateWhere);
                    $this->db->update(INSP_INITIAL_ITEMS, $updateData);
                    $updateInfo = $this->db->affected_rows();
                }

                ////////////////////////////////end      

                ///manager track escalation  
                $useeCAEscalation = [
                    'fk_insp_auto_main_id' => $getAutoinspid,
                    "fk_insp_main_stat" => $prevStatus,
                    'cron_notify_login_id' => $insp_reporter_id,
                    'cron_notify_email_id' => $reporter_email_id,
                    "insp_assigned_type_id" => 4,

                ];

                $updtPr = $this->common_model->updateData(INSP_INITIAL_ESL_LOG, $useeCAEscalation);

                ///////////////////////////////    

                $encAtar = encryptval($getAutoinspid);
                $notifyUrl = "";
                $employeeEmail = false;
                $ptwFooterMailContent =
                    "<br/>This is system generated E-mail, do not reply to this email.<br/>";

                $notifyMessage =
                    "Action taken of the assigned task has been overdue, details are as follows :<br/>
                    <br/><b>Inspection - ID : </b> " .
                    $insp_generate_id .
                    "<br/> <b>Reporter Name : </b> " .
                    $Reporter .
                    "<br/><b>Reporter Designation : </b> " .
                    $desig .
                    "<br/><b>Reported Date & Time : </b> " .
                    $insp_report_datetime .
                    " <br/><b>EPC : </b> " .
                    $comp_name .
                    " <br/><b>Area : </b> " .
                    $area_name .
                    " <br/><b>Building/Block/Direction : </b> " .
                    $building_name .
                    " <br/><b>Project : </b> " .
                    $proj_name .
                    " <br/><b>Department : </b> " .
                    $dep_name .
                    " <br/><b>Category : </b> " .
                    $hse_cat .
                    " <br/><b>Assigned Person : </b> " .
                    $assignee .
                    " <br/><b>Target Date to complete CA  : </b> " .
                    $targetdate .
                    " <br/>";
                $notifyUrl =
                    "inspection/audit/view/" . $encAtar;
                $subject =
                    "Inspection - ID - " .
                    $insp_generate_id .
                    " has been overdue";




                $notfiy_id = $insp_reporter_id;
                $notify_email_id = $reporter_email_id;


                if ($notify_email_id != '') {
                    $employeeEmail = true;
                }

                $notifydata = [
                    "text" => $notifyMessage,
                    "url" => $notifyUrl,
                    "type" => 1,
                    "notifygroup" => "",
                    "employee" => $insp_reporter_id,
                    'mobilelink' => $insp_auto_id,
                    'module_auto_id' => 1,
                ];

                if ($notfiy_id != '') {
                    insertNotifications($notifydata);
                }

                $emailMessage = $notifyMessage . $ptwFooterMailContent;


                if ($employeeEmail) {
                    // print_r($notify_email_id);exit;

                    $toemail = $notify_email_id;
                    $message = $emailMessage;



                    $tempdata = [
                        "view_file" => "common/email/common_mail",
                        "messageText" => $message,
                        "footerText" => "",
                    ];
                    //print_r($toemail);exit;
                    $template = $this->template->load_email_template($tempdata);

                    $this->email_model->sendEmail(
                        $toemail,
                        $subject,
                        $template,
                        $path
                    );
                    //$aResponse["success"][$REC_AUTO_ID]["Id"] = $REC_AUTO_ID;

                }
            }
        }
        echo 'success';
        exit;
    }

    //https://devsinohydro.neoehs.com/Development_v1/LumutCron/autoOBSExceedNotification


    //   /5    * curl https://welsafe.welspun.com/LumutCron/autoObsExceedNotification
    // /5    * curl https://welsafe.welspun.com/LumutCron/sendNotification


}
