<?php

function getObsNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(obs_auto_id) as counts');

    $result = $ci->db->get(OBS_FLOW_SEE)->row();
    $ci->db->where(['obs_project_id' => $ProjectId]);
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'OBS' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getProjectName($ProjectId)
{
    $ci = &get_instance();
    $options['where'] = [
        'project_id' => $ProjectId,
        'project_status' => 'Y'
    ];
    $options['return_type'] = 'row';
    $details = $ci->common_model->getAlldata(MAS_PROJ, ['project_name'], $options, $limit = "", $offset = "", $orderby = "", $disporder = "");
    return ($details != FALSE) ? $details->project_name : '';
}

function getAreaShortName($AreaId)
{
    $ci = &get_instance();
    $options['where'] = [
        'area_id' => $AreaId,
        'area_status' => 'Y'
    ];
    $options['return_type'] = 'row';
    $details = $ci->common_model->getAlldata(MAS_AREA, ['area_short_name'], $options, $limit = "", $offset = "", $orderby = "", $disporder = "");
    return ($details != FALSE) ? $details->area_short_name : '';
}

function getBuildingName($BuildingId)
{
    $ci = &get_instance();
    $options['where'] = [
        'building_id' => $BuildingId,
        'building_status' => 'Y'
    ];
    $options['return_type'] = 'row';
    $details = $ci->common_model->getAlldata(MAS_BUILDING, ['building_name'], $options, $limit = "", $offset = "", $orderby = "", $disporder = "");
    return ($details != FALSE) ? $details->building_name : '';
}
function sendObsNotification($obs_id)
{

    $ci = &get_instance();
    global $risk_rating, $obs_type_list;
    ////email   
    $obs_admin = ['7'];
    $obs_super = ['2'];
    $obs_assigner = ['5'];
    $obs_approver = ['6'];
    $obs_final_approver = ['8'];
    ////
    $option['where'] = ['obs_auto_id' => $obs_id];

    $option['return_type'] = 'row';

    $option['join'] = [

        LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_reporter_id', 'LEFT'],
        EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],


        LOGIN . ' AS LD1' => ['LD1.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_supervisor_id', 'LEFT'],
        EMPL . ' AS supervisor' => ['supervisor.EMP_AUTO_ID = LD1.USER_REF_ID', 'LEFT'],

        LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_assigner_id', 'LEFT'],
        EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

        LOGIN . ' AS LD4' => ['LD4.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_hsse_es_id', 'LEFT'],
        EMPL . ' AS Hsse_es' => ['Hsse_es.EMP_AUTO_ID = LD4.USER_REF_ID', 'LEFT'],

        LOGIN . ' AS LD3' => ['LD3.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_hsse_id', 'LEFT'],
        EMPL . ' AS Hsse' => ['Hsse.EMP_AUTO_ID = LD3.USER_REF_ID', 'LEFT'],




    ];



    $aOption = $option + $option;

    $atardetails = $ci->common_model->getAlldata(OBS_FLOW_SEE, [
        '*',
        'Reporter.EMP_NAME` as Reporter',
        'FN_COMP_NAME(obs_owner_eng) as owner_eng_name',
        'FN_COMP_NAME(obs_comp_id) as comp_name',
        'FN_AREA_NAME(obs_area_id) as area_name',
        'FN_BUILD_NAME(obs_building_id) as building_name',
        'FN_GET_DEPARTMENT_NAME(obs_dept_id) as dep_name',
        'FN_PROJECT_NAME(obs_project_id) as proj_name',
        'FN_HSE_CAT_NAME(obs_cat_id) as hse_cat',
        'Reporter.EMP_NAME as empName',
        'supervisor.EMP_NAME as supervisor',
        'supervisor.EMP_EMAIL_ID as supervisor_email',
        'Hsse_es.EMP_NAME as Hsse_es',
        'Hsse_es.EMP_EMAIL_ID as Hsse_es_email',
        'Hsse.EMP_NAME as hsse',
        'Hsse.EMP_EMAIL_ID as hsse_email',
        'Hod.EMP_NAME as assignee',
        'Hod.EMP_EMAIL_ID as assignee_email',
        'FN_GET_DESIGNATION_NAME(obs_reporter_desg_id) as desig',
    ], $aOption);

    $option_img['where'] = [
        'fk_obs_main_id' => $obs_id,
        'obs_attach_status' => 'Y',
        'obs_file_type' => '1'
    ];
    $getimgusee = $ci->common_model->getAlldata(OBS_IMG_SEE, ['obs_file_path,obs_filename'], $option_img);

    if ($atardetails != FALSE) {

        $obs_id = postData($atardetails, 'obs_id');
        $obs_auto_id  = postData($atardetails, 'obs_auto_id');
        $Reporter = postData($atardetails, 'Reporter');
        $desig = postData($atardetails, 'desig');
        $obs_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'obs_report_datetime')));
        $obs_type_id = postData($atardetails, 'obs_type_id');
        $obs_risk_id = postData($atardetails, 'obs_risk_id');


        $obs_app_status = postData($atardetails, 'obs_app_status');
        $obs_assigner_id = postData($atardetails, 'obs_assigner_id');
        $obs_supervisor_id = postData($atardetails, 'obs_supervisor_id');
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

        $assignee_email = postData($atardetails, 'assignee_email');
        $supervisor_email = postData($atardetails, 'supervisor_email');
        $hsse_email = postData($atardetails, 'hsse_email');
        $obs_assigner_target_date = postData($atardetails, 'obs_assigner_target_date');
        $obs_assigner_desc = postData($atardetails, 'obs_assigner_desc');
        $obs_hsse_es_type_id = postData($atardetails, 'obs_hsse_es_type_id');
        $obs_hsse_es_appr_rej_desc = postData($atardetails, 'obs_hsse_es_appr_rej_desc');
        $obs_hsse_appr_rej_desc = postData($atardetails, 'obs_hsse_appr_rej_desc');
        $obs_hsse_es_id = postData($atardetails, 'obs_hsse_es_id');
        $Hsse_es_email = postData($atardetails, 'Hsse_es_email');

        $obs_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'obs_supervisor_date')));


        ////company admin
        $comp_id_admin =  postData($atardetails, 'obs_comp_id');;
        $optWhradmin['where'] = [
            'Reporter.EMP_COMP_ID' => $comp_id_admin
        ];

        $optWhradmin["where_in"] = [
            "USER_TYPE_ID" => $obs_admin
        ];
        $optWhradmin["join"][EMPL . " AS Reporter"] = ["Reporter.EMP_AUTO_ID = USER_REF_ID", "LEFT"];

        $optWhradmin["return_type"] = "result";
        $getStatdataadmin = $ci->common_model->getAlldata(LOGIN, ['LOGIN_ID,Reporter.EMP_EMAIL_ID'], $optWhradmin);
        $loginIds_admin = array();
        $emailIds_admin = array();

        if (isset($getStatdataadmin) && !empty($getStatdataadmin)) {
            foreach ($getStatdataadmin as $obj) {
                $loginIds_admin[] = $obj->LOGIN_ID;
                $emailIds_admin[] = $obj->EMP_EMAIL_ID;
            }
        }
        //assigner
        $comp_id_assigner =  postData($atardetails, 'obs_comp_id');
        // $optWhrassigner['where'] = [
        //     'Assigner.EMP_COMP_ID' => $comp_id_admin
        // ];

        $optWhrassigner["where_in"] = [
            "USER_TYPE_ID" => $obs_assigner
        ];
        $optWhrassigner["join"][EMPL . " AS Assigner"] = ["Assigner.EMP_AUTO_ID = USER_REF_ID", "LEFT"];

        $optWhrassigner["return_type"] = "result";
        $getStatdataassigner = $ci->common_model->getAlldata(LOGIN, ['LOGIN_ID,Assigner.EMP_EMAIL_ID'], $optWhrassigner);

        $loginIds_assigner = array();
        $emailIds_assigner = array();

        if (isset($getStatdataassigner) && !empty($getStatdataassigner)) {
            foreach ($getStatdataassigner as $obj) {
                $loginIds_assigner[] = $obj->LOGIN_ID;
                $emailIds_assigner[] = $obj->EMP_EMAIL_ID;
            }
        }

        ///superadmin
        $optWhrsupadmin["where_in"] = [
            "USER_TYPE_ID" => $obs_super
        ];
        $optWhrsupadmin["join"][EMPL . " AS Reporter"] = ["Reporter.EMP_AUTO_ID = USER_REF_ID", "LEFT"];

        $optWhrsupadmin["return_type"] = "result";
        $getStatdatasupadmin = $ci->common_model->getAlldata(LOGIN, ['LOGIN_ID,Reporter.EMP_EMAIL_ID'], $optWhrsupadmin);

        $loginIds_supadmin = array();
        $emailIds_supadmin = array();

        if (isset($getStatdatasupadmin) && !empty($getStatdatasupadmin)) {
            foreach ($getStatdatasupadmin as $objsup) {
                $loginIds_supadmin[] = $objsup->LOGIN_ID;
                $emailIds_supadmin[] = $objsup->EMP_EMAIL_ID;
            }
        }


        //First Approval
        $comp_id_approval =  postData($atardetails, 'obs_comp_id');;
        // $optWhrapproval['where'] = [
        //     'Approver.EMP_COMP_ID' => $comp_id_approval
        // ];

        $optWhrapproval["where_in"] = [
            "USER_TYPE_ID" => $obs_approver
        ];
        $optWhrapproval['where'] = [
            'Approver.EMP_LOGIN_STATUS' => 'E',
            'Approver.EMP_STATUS' => 'Y'
        ];
        $optWhrapproval["join"][EMPL . " AS Approver"] = ["Approver.EMP_AUTO_ID = USER_REF_ID", "LEFT"];

        $optWhrapproval["return_type"] = "result";
        $getStatdataapproval = $ci->common_model->getAlldata(LOGIN, ['LOGIN_ID,Approver.EMP_EMAIL_ID'], $optWhrapproval);

        $loginIds_approval = array();
        $emailIds_approval = array();

        if (isset($getStatdataapproval) && !empty($getStatdataapproval)) {
            foreach ($getStatdataapproval as $obj) {
                $loginIds_approval[] = $obj->LOGIN_ID;
                $emailIds_approval[] = $obj->EMP_EMAIL_ID;
            }
        }

        //final Approver
        $comp_id_fapproval =  postData($atardetails, 'obs_comp_id');;
        // $optWhrapproval['where'] = [
        //     'Fapprover.EMP_COMP_ID' => $comp_id_fapproval
        // ];

        $optWhrfapproval["where_in"] = [
            "USER_TYPE_ID" => $obs_final_approver
        ];
        $optWhrfapproval['where'] = [
            'Fapprover.EMP_LOGIN_STATUS' => 'E',
            'Fapprover.EMP_STATUS' => 'Y'
        ];
        $optWhrfapproval["join"][EMPL . " AS Fapprover"] = ["Fapprover.EMP_AUTO_ID = USER_REF_ID", "LEFT"];

        $optWhrfapproval["return_type"] = "result";
        $getStatdatafapproval = $ci->common_model->getAlldata(LOGIN, ['LOGIN_ID,Fapprover.EMP_EMAIL_ID'], $optWhrfapproval);

        $loginIds_fapproval = array();
        $emailIds_fapproval = array();

        if (isset($getStatdatafapproval) && !empty($getStatdatafapproval)) {
            foreach ($getStatdatafapproval as $obj) {
                $loginIds_fapproval[] = $obj->LOGIN_ID;
                $emailIds_fapproval[] = $obj->EMP_EMAIL_ID;
            }
        }

        ////////end


        $combinedNotifyIdArray = array_merge($loginIds_supadmin ?? [], $loginIds_admin ?? []);
        $combinedEmailArray = array_unique(array_merge($emailIds_supadmin ?? [], $emailIds_admin ?? []));

        $notify_id_ad_sup = implode(',', $combinedNotifyIdArray);
        $notify_email_id_ad_sup = implode(',', $combinedEmailArray);

        //assigner(supervisor) & admin & superadmin
        $combinedNotifyIdArray_withass = array_merge($loginIds_supadmin ?? [], $loginIds_admin ?? [], $loginIds_assigner ?? []);
        $combinedEmailArray_withass = array_unique(array_merge($emailIds_supadmin ?? [], $emailIds_admin ?? [], $emailIds_assigner ?? []));

        $notify_id_ad_sup_assigner = implode(',', $combinedNotifyIdArray_withass);
        $notify_email_id_ad_sup_assigner = implode(',', $combinedEmailArray_withass);


        //responsible person, admin&superamin
        $notify_resp_ad_sup_ids = [$notify_id_ad_sup, $obs_assigner_id];
        $notify_resp_ad_sup_email_address = [$notify_email_id_ad_sup, $assignee_email];

        $uniqunotify_resp_ad_sup_ids = array_unique($notify_resp_ad_sup_ids);
        $uniqeresp_ad_sup_email_address = array_unique($notify_resp_ad_sup_email_address);

        $notify_final_resp_ad_sup_id = (count($uniqunotify_resp_ad_sup_ids) > 0) ? implode(',', $uniqunotify_resp_ad_sup_ids) : "";
        $notify_final_resp_ad_sup_email_id = (count($uniqeresp_ad_sup_email_address) > 0) ? implode(',', $uniqeresp_ad_sup_email_address) : "";

        //admin & superadmin &approver
        $combinedNotifyIdArray_withapp = array_merge($loginIds_supadmin ?? [], $loginIds_admin ?? [], $loginIds_approval ?? []);
        $combinedEmailArray_withapp = array_unique(array_merge($emailIds_supadmin ?? [], $emailIds_admin ?? [], $emailIds_approval ?? []));

        $notify_id_ad_sup_app = implode(',', $combinedNotifyIdArray_withapp);
        $notify_email_id_ad_sup_app = implode(',', $combinedEmailArray_withapp);

        //admin & superadmin & final approver
        $combinedNotifyIdArray_withfapp = array_merge($loginIds_supadmin ?? [], $loginIds_admin ?? [], $loginIds_fapproval ?? []);
        $combinedEmailArray_withfapp = array_unique(array_merge($emailIds_supadmin ?? [], $emailIds_admin ?? [], $emailIds_fapproval ?? []));

        $notify_id_ad_sup_fapp = implode(',', $combinedNotifyIdArray_withfapp);
        $notify_email_id_ad_sup_fapp = implode(',', $combinedEmailArray_withfapp);

        ////////////////////////////close ( admin.superadmin, reporter,verifier,responsible,supervisor)
        $notify_closed_ids = [$obs_supervisor_id, $obs_assigner_id, $obs_hsse_es_id, $notify_id_ad_sup];
        $closed_email_address = [$supervisor_email, $assignee_email, $Hsse_es_email, $notify_email_id_ad_sup];

        $uniqunotify_closed_ids = array_unique($notify_closed_ids);
        $uniqemail_closed_address = array_unique($closed_email_address);

        $notify_closed_id = (count($uniqunotify_closed_ids) > 0) ? implode(',', $uniqunotify_closed_ids) : "";
        $notify_closed_email = (count($uniqemail_closed_address) > 0) ? implode(',', $uniqemail_closed_address) : "";


        //obs_hsse_es_id(first approver) & admin & sup

        $notify_frej_ids = [$obs_hsse_es_id, $notify_id_ad_sup];
        $frej_email_address = [$Hsse_es_email, $notify_email_id_ad_sup];

        $uniqunotify_frej_ids = array_unique($notify_frej_ids);
        $uniqemail_frej_address = array_unique($frej_email_address);

        $notify_frej_id = (count($uniqunotify_frej_ids) > 0) ? implode(',', $uniqunotify_frej_ids) : "";
        $notify_frej_email = (count($uniqemail_frej_address) > 0) ? implode(',', $uniqemail_frej_address) : "";


        //obs_assigner_id(resp) & admin & sup

        $notify_rej_ids = [$obs_assigner_id, $notify_id_ad_sup];
        $rej_email_address = [$assignee_email, $notify_email_id_ad_sup];

        $uniqunotify_rej_ids = array_unique($notify_rej_ids);
        $uniqemail_rej_address = array_unique($rej_email_address);

        $notify_rej_id = (count($uniqunotify_rej_ids) > 0) ? implode(',', $uniqunotify_rej_ids) : "";
        $notify_rej_email = (count($uniqemail_rej_address) > 0) ? implode(',', $uniqemail_rej_address) : "";
    }


    $encAtar = encryptval($obs_auto_id);

    $notifyUrl = '';

    $employeeEmail = FALSE;

    $ptwFooterMailContent = '<br/>This is system generated E-mail, do not reply to this email.<br/>';

    $imgmsg = '';
    switch ($obs_app_status) {

        case '1':

            /* Pending */
            if (isset($getimgusee) && !empty($getimgusee)) {
                $im = 1;
                foreach ($getimgusee as $imguse) {
                    $imgfile = BASE_URL . $imguse->obs_file_path . $imguse->obs_filename;
                    $imgmsg .= '<b>Image-' . $im . ' </b>:<a href =' . $imgfile . '>Click Here</a><br/>';
                    $im++;
                }
            }
            $notifyMessage = 'New Observation has been created, details are as follows :<br/><br/><b>Observation ID : </b> ' . $obs_id . '<br/> 
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $obs_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>HSE Category : </b> ' . $hse_cat . ' <br/>
            <b>Category Type: </b> ' . $obs_type . '<br/>';
            if ($obs_type_id != 3) {
                $notifyMessage .= '<b>Risk Rating: </b> ' . $obs_risk . '<br/>';
            }
            $notifyUrl = 'atarusee/incident/view/' . $encAtar;
            $subject = 'New Observation - ID - ' . $obs_id . ' has been created';
            $employeeEmail = TRUE;
            $notfiy_id = $notify_id_ad_sup_assigner; //admin&supad&assigner
            $emailId = $notify_email_id_ad_sup_assigner; //admin&supad&assigner
            break;

        case '4':

            /* Assign CA */
            $notifyMessage = 'Activity of the CA has been assigned, details are as follows :<br/><br/><b>Observation ID : </b> ' . $obs_id . '<br/> 
           <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $obs_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>HSE Category : </b> ' . $hse_cat . ' <br/>
            <b>Category Type: </b> ' . $obs_type . '<br/>';
            if ($obs_type_id != 3) {
                $notifyMessage .= '<b>Risk Rating: </b> ' . $obs_risk . '<br/>';
            }
            $notifyMessage .= '<b>Target Date to complete CA : </b> ' . $obs_assigner_target_date . ' <br/>';
            $notifyUrl = 'atarusee/incident/view/' . $encAtar;
            $subject = 'Observation - ID - ' . $obs_id . ' has been assigned';
            $employeeEmail = TRUE;
            $notfiy_id = $notify_final_resp_ad_sup_id;
            $emailId = $notify_final_resp_ad_sup_email_id;
            break;

        case '5':

            /* waiting for approval */
            $notifyMessage = 'EPC E&S Manager has been waiting to approve, details are as follows :<br/><br/><b>Observation ID : </b> ' . $obs_id . '<br/>
             <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $obs_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>HSE Category : </b> ' . $hse_cat . ' <br/>
            <b>Category Type: </b> ' . $obs_type . '<br/>';
            if ($obs_type_id != 3) {
                $notifyMessage .= '<b>Risk Rating: </b> ' . $obs_risk . '<br/>';
            }
            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $obs_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $obs_assigner_desc . ' <br/>';

            $notifyUrl = 'atarusee/incident/view/' . $encAtar;
            $subject = 'Observation - ID - ' . $obs_id . ' has been waiting for approval';

            $employeeEmail = TRUE;
            $notfiy_id = $notify_id_ad_sup_app;
            $emailId = $notify_email_id_ad_sup_app;
            break;

        case '7':

            /* HSSE E&S rejected */
            $notifyMessage = 'EPC E&S Manager has been "Rejected" the action item :<br/><br/><b>Observation ID : </b> ' . $obs_id . '<br/>
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
           <b>Reporter Designation : </b> ' . $desig . ' <br/>
           <b>Reported Date & Time : </b> ' . $obs_report_datetime . ' <br/>
           <b>EPC : </b> ' . $comp_name . ' <br/>
           <b>Area : </b> ' . $area_name . '<br/>
           <b>Building/Block/Direction: </b> ' . $building_name . '<br/>
           <b>Project : </b> ' . $proj_name . '<br/>
           <b>Department : </b> ' . $dep_name . ' <br/>
           <b>HSE Category : </b> ' . $hse_cat . ' <br/>
           <b>Category Type: </b> ' . $obs_type . '<br/>';
            if ($obs_type_id != 3) {
                $notifyMessage .= '<b>Risk Rating: </b> ' . $obs_risk . '<br/>';
            }
            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $obs_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $obs_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $obs_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'atarusee/incident/view/' . $encAtar;
            $subject = 'Observation - ID - ' . $obs_id . ' has been rejected';
            $employeeEmail = TRUE;
            $notfiy_id = $notify_rej_id;
            $emailId = $notify_rej_email;
            break;

        case '9':

            /* waiting for HSSE Manger Approval */
            $notifyMessage = 'EPC E&S Manager has been "Approved" the action item and "Waiting for HSE Manager Approval" the Observation, details are as follows: <br/><br/><b>Observation ID : </b> ' . $obs_id . '<br/>
                <b>Reporter Name : </b> ' . $Reporter . ' <br/>
               <b>Reporter Designation : </b> ' . $desig . ' <br/>
               <b>Reported Date & Time : </b> ' . $obs_report_datetime . ' <br/>
               <b>EPC : </b> ' . $comp_name . ' <br/>
               <b>Area : </b> ' . $area_name . '<br/>
               <b>Building/Block/Direction: </b> ' . $building_name . '<br/>  
               <b>Project : </b> ' . $proj_name . '<br/>
               <b>Department : </b> ' . $dep_name . ' <br/>
               <b>HSE Category : </b> ' . $hse_cat . ' <br/>
               <b>Category Type: </b> ' . $obs_type . '<br/>';
            if ($obs_type_id != 3) {
                $notifyMessage .= '<b>Risk Rating: </b> ' . $obs_risk;
            }
            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $obs_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $obs_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $obs_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'atarusee/incident/view/' . $encAtar;
            $subject = 'Observation - ID - ' . $obs_id . ' has been approved and  Waiting for HSE Manager Approval';
            $employeeEmail = TRUE;
            $notfiy_id = $notify_id_ad_sup_fapp;
            $emailId = $notify_email_id_ad_sup_fapp;
            break;

        case '10':

            /* HSSE Rejected */
            $notifyMessage = 'PC/OE - HSSE Manager has been "Rejected" the action item :<br/><br/><b>Observation ID : </b> ' . $obs_id . '<br/>
                <b>Reporter Name : </b> ' . $Reporter . ' <br/>
               <b>Reporter Designation : </b> ' . $desig . ' <br/>
               <b>Reported Date & Time : </b> ' . $obs_report_datetime . ' <br/>
               <b>EPC : </b> ' . $comp_name . ' <br/>
               <b>Area : </b> ' . $area_name . '<br/>
               <b>Building/Block/Direction: </b> ' . $building_name . '<br/>
               <b>Project : </b> ' . $proj_name . '<br/>
               <b>Department : </b> ' . $dep_name . ' <br/>
               <b>HSE Category : </b> ' . $hse_cat . ' <br/>
               <b>Category Type: </b> ' . $obs_type . '<br/>';
            if ($obs_type_id != 3) {
                $notifyMessage .= '<b>Risk Rating: </b> ' . $obs_risk . '<br/>';
            }
            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $obs_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $obs_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $obs_hsse_appr_rej_desc . ' <br/>';

            $notifyUrl = 'atarusee/incident/view/' . $encAtar;
            $subject = 'Observation - ID - ' . $obs_id . ' has been rejected';
            $employeeEmail = TRUE;
            $notfiy_id = $notify_frej_id;
            $emailId = $notify_frej_email;
            break;

        case '3':

            /* approved */
            $notifyMessage = 'PC/OE - HSSE Manager has been "Approved" the action item and "Closed" the Observation, details are as follows: <br/><br/><b>Observation ID : </b> ' . $obs_id . '<br/>
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
           <b>Reporter Designation : </b> ' . $desig . ' <br/>
           <b>Reported Date & Time : </b> ' . $obs_report_datetime . ' <br/>
           <b>EPC : </b> ' . $comp_name . ' <br/>
           <b>Area : </b> ' . $area_name . '<br/>
           <b>Building/Block/Direction: </b> ' . $building_name . '<br/>
           <b>Project : </b> ' . $proj_name . '<br/>
           <b>Department : </b> ' . $dep_name . ' <br/>
           <b>HSE Category : </b> ' . $hse_cat . ' <br/>
           <b>Category Type: </b> ' . $obs_type . '<br/>';
            if ($obs_type_id != 3) {
                $notifyMessage .= '<b>Risk Rating: </b> ' . $obs_risk . '<br/>';
                $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $obs_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $obs_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $obs_hsse_es_appr_rej_desc;
            }
            $notifyMessage .= ' <br/>';
            $notifyUrl = 'atarusee/incident/view/' . $encAtar;
            $subject = 'Observation - ID - ' . $obs_id . ' has been approved and  Closed';
            $employeeEmail = TRUE;
            $notfiy_id = $notify_closed_id;
            $emailId = $notify_closed_email;
            break;
    }



    $notifydata = [

        'text' => $notifyMessage,

        'url' => $notifyUrl,

        'type' => 1,

        'notifygroup' => '',

        'employee' => $notfiy_id,

        'mobilelink' => $obs_id,
        'module_auto_id' => 1,

    ];


    insertNotifications($notifydata);
    // exit;


    /* Email Template Details */





    $emailMessage = $notifyMessage . $imgmsg  . $ptwFooterMailContent;

    //print_r($notifydata);

    //print_r($emailMessage);exit;



    if ($employeeEmail) {

        $emailData = [

            'email' => $emailId,

            //            'email' => $emailId,

            'subject' => $subject,

            'message' => $emailMessage,

        ];

        sendNotificationEmail_project($emailData);
    }

    return TRUE;
}


function sendNotificationEmail_project($data = [])
{

    // print_r($data);exit;

    $ci = &get_instance();

    $ci->load->model('Email_model');

    $subject = isset($data['subject']) ? $data['subject'] : '';

    $toemail = isset($data['email']) ? $data['email'] : '';

    $message = isset($data['message']) ? $data['message'] : '';



    $tempdata = array(

        'view_file' => 'common/email/common_mail',

        'messageText' => $message,

        'footerText' => ''

    );

    $template = $ci->template->load_email_template($tempdata);

    return $ci->email_model->sendEmail($toemail, $subject, $template, $file = "");
}
