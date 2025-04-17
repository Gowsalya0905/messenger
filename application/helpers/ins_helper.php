<?php

// Inspection Unique id
function getMonthlyInsNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(ins_auto_id) as counts');

    $ci->db->where(['ins_project_id' => $ProjectId]);
    $result = $ci->db->get(INSP_MONTH_FLOW_SEE)->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'INS' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getHotelInsNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(ins_auto_id) as counts');

    $ci->db->where(['ins_project_id' => $ProjectId]);
    $result = $ci->db->get(INSP_HOTEL_FLOW_SEE)->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'INS' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getInitialInsNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(ins_auto_id) as counts');

    $ci->db->where(['ins_project_id' => $ProjectId]);
    $result = $ci->db->get(INSP_INITIAL_FLOW_SEE)->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'INS' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getWeeklyInsNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(ins_auto_id) as counts');

    $ci->db->where(['ins_project_id' => $ProjectId]);
    $result = $ci->db->get(INSP_WEEKLY_FLOW_SEE)->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'INS' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getAuditInsNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(ins_auto_id) as counts');

    $ci->db->where(['ins_project_id' => $ProjectId]);
    $result = $ci->db->get(INSP_AUDIT_FLOW_SEE)->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'INS' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getWorkcampInsNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(ins_auto_id) as counts');
    $ci->db->where(['ins_project_id' => $ProjectId]);
    $result = $ci->db->get(INSP_WORKCAMP_FLOW_SEE)->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'INS' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

// Atar Unique id

function getAuditInsAtarNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(insp_item_auto_id ) as counts');

    $result = $ci->db->get(INSP_AUDIT_ITEMS)->row();
    $ci->db->where(['insp_project_id' => $ProjectId]);
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'ATAR' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getWeeklyInsAtarNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(insp_item_auto_id) as counts');
    $ci->db->from(INSP_WEEKLY_ITEMS);
    $ci->db->where(['insp_project_id' => $ProjectId, 'send_to_atar' => 1]);
    $result = $ci->db->get()->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'ATAR' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getWorkcampInsAtarNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(insp_item_auto_id) as counts');
    $ci->db->from(INSP_WORKCAMP_ITEMS);
    $ci->db->where(['insp_project_id' => $ProjectId, 'send_to_atar' => 1]);
    $result = $ci->db->get()->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'ATAR' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getInitialInsAtarNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(insp_item_auto_id) as counts');
    $ci->db->from(INSP_INITIAL_ITEMS);
    $ci->db->where(['insp_project_id' => $ProjectId, 'send_to_atar' => 1]);
    $result = $ci->db->get()->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'ATAR' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getMonthlyInsAtarNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(insp_item_auto_id) as counts');
    $ci->db->from(INSP_MONTH_ITEMS);
    $ci->db->where(['insp_project_id' => $ProjectId, 'send_to_atar' => 1]);
    $result = $ci->db->get()->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'ATAR' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}

function getHotelInsAtarNumber($projNameThreeLetters, $areaShortTwoLetters, $blockFirstLetter, $ProjectId)
{

    $ci = &get_instance();

    $ci->db->select('MAX(insp_item_auto_id) as counts');
    $ci->db->from(INSP_HOTEL_ITEMS);
    $ci->db->where(['insp_project_id' => $ProjectId, 'send_to_atar' => 1]);
    $result = $ci->db->get()->row();
    $maxid = $result->counts;

    $maxid = $maxid + 1;
    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);
    return $projNameThreeLetters . '-' . 'ATAR' . '-' . $areaShortTwoLetters . $blockFirstLetter . '-' . $returnId;
}


// Upload Image
function uploadEvidenceImage($fileData, $imgUploadpath = '', $allowedTyp = "*")
{
    $ci = &get_instance();

    if (!file_exists(FCPATH . $imgUploadpath)) {
        mkdir(FCPATH . $imgUploadpath, 0777, true);
    }

    $config['upload_path'] = FCPATH . $imgUploadpath;
    $config['allowed_types'] = $allowedTyp;
    $config['encrypt_name'] = TRUE;

    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);

    $_FILES['temp_file'] = $fileData;

    if (!$ci->upload->do_upload('temp_file')) {
        return ['error' => $ci->upload->display_errors()];
    }

    $upload_data = $ci->upload->data();
    return [
        'file_path' => $imgUploadpath . '/' . $upload_data['file_name'],
        'file_name' => $upload_data['file_name'],
        'file_type' => $upload_data['file_type'],
        'file_ext' => $upload_data['file_ext'],
        'file_size' => $upload_data['file_size']
    ];
}


// get Master data
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


// send notification
function sendAuditInspNotification($ins_id)
{

    $ci = &get_instance();
    global $risk_rating, $obs_type_list;
    ////email   
    $insp_admin = ['7'];
    $insp_super = ['2'];
    $insp_assigner = ['5'];
    $insp_approver = ['6'];
    $insp_final_approver = ['8'];
    ////
    $option['where'] = ['insp_item_auto_id' => $ins_id];

    $option['return_type'] = 'row';

    $option['join'] = [

        LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_AUDIT_ITEMS . '.insp_reporter_id', 'LEFT'],
        EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],


        LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_AUDIT_ITEMS . '.insp_assigner_id', 'LEFT'],
        EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

        INSP_AUDIT_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_AUDIT_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
        'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_AUDIT_ITEMS . '.fk_item_cat_id', 'LEFT'],




    ];



    $aOption = $option + $option;

    $atardetails = $ci->common_model->getAlldata(INSP_AUDIT_ITEMS, [
        '*',
        'insp_main.ins_id',
        'ins_cate_mas.category as category_name',
        'Reporter.EMP_NAME` as Reporter',
        'Reporter.EMP_EMAIL_ID` as reporter_email_id',
        'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
        'FN_COMP_NAME(insp_comp_id) as comp_name',
        'FN_AREA_NAME(insp_area_id) as area_name',
        'FN_BUILD_NAME(insp_building_id) as building_name',
        'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
        'FN_PROJECT_NAME(insp_project_id) as proj_name',
        'Reporter.EMP_NAME as empName',
        'Hod.EMP_NAME as assignee',
        'Hod.EMP_EMAIL_ID as assignee_email',
        'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
    ], $aOption);

    $option_img['where'] = [
        'fk_insp_main_id' => $ins_id,
        'insp_attach_status' => 'Y',
        'insp_file_type' => '1'
    ];
    $getimgusee = $ci->common_model->getAlldata(INSP_AUDIT_IMAGE_SEE, ['insp_file_path,insp_filename'], $option_img);

    if ($atardetails != FALSE) {

        $insp_id = postData($atardetails, 'ins_id');
        $insp_auto_id  = postData($atardetails, 'insp_item_auto_id');
        $Reporter = postData($atardetails, 'Reporter');
        $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
        $reporter_email_id = postData($atardetails, 'reporter_email_id');
        $desig = postData($atardetails, 'desig');
        $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
        $insp_type_id = postData($atardetails, 'insp_type_id');
        $insp_risk_id = postData($atardetails, 'insp_risk_id');


        $insp_app_status = postData($atardetails, 'insp_app_status');
        $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
        $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
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

        $assignee_email = postData($atardetails, 'assignee_email');
        $supervisor_email = postData($atardetails, 'supervisor_email');
        $hsse_email = postData($atardetails, 'hsse_email');
        $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
        $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
        $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
        $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
        $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');
        $insp_hsse_es_id = postData($atardetails, 'insp_hsse_es_id');
        $Hsse_es_email = postData($atardetails, 'Hsse_es_email');

        $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));
    }


    $encAtar = encryptval($insp_auto_id);

    $notifyUrl = '';

    $employeeEmail = FALSE;

    $ptwFooterMailContent = '<br/>This is system generated E-mail, do not reply to this email.<br/>';

    $imgmsg = '';
    switch ($insp_app_status) {

        case '1':

            /* Pending */
            if (isset($getimgusee) && !empty($getimgusee)) {
                $im = 1;
                foreach ($getimgusee as $imguse) {
                    $imgfile = BASE_URL . $imguse->insp_file_path . $imguse->insp_filename;
                    $imgmsg .= '<b>Image-' . $im . ' </b>:<a href =' . $imgfile . '>Click Here</a><br/>';
                    $im++;
                }
            }
            $notifyMessage = 'New Inspection has been created, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/> 
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>Category : </b> ' . $hse_cat . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'New Inspection - ID - ' . $insp_id . ' has been created';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '4':

            /* Assign CA */
            $notifyMessage = 'Activity of the CA has been assigned, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/> 
           <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>';

            $notifyMessage .= '<b>Target Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/>';
            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been assigned';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '5':

            /* waiting for approval */
            $notifyMessage = 'EPC E&S Manager has been waiting to approve, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
             <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been waiting for approval';

            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '7':

            /* HSSE E&S rejected */
            $notifyMessage = 'EPC E&S Manager has been "Rejected" the action item :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
           <b>Reporter Designation : </b> ' . $desig . ' <br/>
           <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
           <b>EPC : </b> ' . $comp_name . ' <br/>
           <b>Area : </b> ' . $area_name . '<br/>
           <b>Building/Block/Direction: </b> ' . $building_name . '<br/>
           <b>Project : </b> ' . $proj_name . '<br/>
           <b>Department : </b> ' . $dep_name . ' <br/>
           <b>HSE Category : </b> ' . $hse_cat . ' <br/>';


            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been rejected';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '3':

            /* waiting for HSSE Manger Approval */
            $notifyMessage = 'EPC E&S Manager has been "Approved" the action item and "Waiting for HSE Manager Approval" the Inspection, details are as follows: <br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
                <b>Reporter Name : </b> ' . $Reporter . ' <br/>
               <b>Reporter Designation : </b> ' . $desig . ' <br/>
               <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
               <b>EPC : </b> ' . $comp_name . ' <br/>
               <b>Area : </b> ' . $area_name . '<br/>
               <b>Building/Block/Direction: </b> ' . $building_name . '<br/>  
               <b>Project : </b> ' . $proj_name . '<br/>
               <b>Department : </b> ' . $dep_name . ' <br/>
               <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Observation - ID - ' . $insp_id . ' has been approved and  Waiting for HSE Manager Approval';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;
    }



    $notifydata = [

        'text' => $notifyMessage,

        'url' => $notifyUrl,

        'type' => 1,

        'notifygroup' => '',

        'employee' => $notfiy_id,

        'mobilelink' => $insp_auto_id,
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

function sendinitialInspNotification($ins_id)
{

    $ci = &get_instance();
    global $risk_rating, $obs_type_list;
    ////email   
    $insp_admin = ['7'];
    $insp_super = ['2'];
    $insp_assigner = ['5'];
    $insp_approver = ['6'];
    $insp_final_approver = ['8'];
    ////
    $option['where'] = ['insp_item_auto_id' => $ins_id];

    $option['return_type'] = 'row';

    $option['join'] = [

        LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_INITIAL_ITEMS . '.insp_reporter_id', 'LEFT'],
        EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],


        LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_INITIAL_ITEMS . '.insp_assigner_id', 'LEFT'],
        EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

        INSP_AUDIT_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_INITIAL_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
        'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_INITIAL_ITEMS . '.fk_item_cat_id', 'LEFT'],




    ];



    $aOption = $option + $option;

    $atardetails = $ci->common_model->getAlldata(INSP_INITIAL_ITEMS, [
        '*',
        'insp_main.ins_id',
        'ins_cate_mas.category as category_name',
        'Reporter.EMP_NAME` as Reporter',
        'Reporter.EMP_EMAIL_ID` as reporter_email_id',
        'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
        'FN_COMP_NAME(insp_comp_id) as comp_name',
        'FN_AREA_NAME(insp_area_id) as area_name',
        'FN_BUILD_NAME(insp_building_id) as building_name',
        'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
        'FN_PROJECT_NAME(insp_project_id) as proj_name',
        'Reporter.EMP_NAME as empName',
        'Hod.EMP_NAME as assignee',
        'Hod.EMP_EMAIL_ID as assignee_email',
        'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
    ], $aOption);

    $option_img['where'] = [
        'fk_insp_main_id' => $ins_id,
        'insp_attach_status' => 'Y',
        'insp_file_type' => '1'
    ];
    $getimgusee = $ci->common_model->getAlldata(INSP_INITIAL_IMAGE_SEE, ['insp_file_path,insp_filename'], $option_img);

    if ($atardetails != FALSE) {

        $insp_id = postData($atardetails, 'ins_id');
        $insp_auto_id  = postData($atardetails, 'insp_item_auto_id');
        $Reporter = postData($atardetails, 'Reporter');
        $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
        $reporter_email_id = postData($atardetails, 'reporter_email_id');
        $desig = postData($atardetails, 'desig');
        $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
        $insp_type_id = postData($atardetails, 'insp_type_id');
        $insp_risk_id = postData($atardetails, 'insp_risk_id');


        $insp_app_status = postData($atardetails, 'insp_app_status');
        $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
        $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
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

        $assignee_email = postData($atardetails, 'assignee_email');
        $supervisor_email = postData($atardetails, 'supervisor_email');
        $hsse_email = postData($atardetails, 'hsse_email');
        $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
        $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
        $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
        $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
        $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');
        $insp_hsse_es_id = postData($atardetails, 'insp_hsse_es_id');
        $Hsse_es_email = postData($atardetails, 'Hsse_es_email');

        $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));
    }


    $encAtar = encryptval($insp_auto_id);

    $notifyUrl = '';

    $employeeEmail = FALSE;

    $ptwFooterMailContent = '<br/>This is system generated E-mail, do not reply to this email.<br/>';

    $imgmsg = '';
    switch ($insp_app_status) {

        case '1':


            if (isset($getimgusee) && !empty($getimgusee)) {
                $im = 1;
                foreach ($getimgusee as $imguse) {
                    $imgfile = BASE_URL . $imguse->insp_file_path . $imguse->insp_filename;
                    $imgmsg .= '<b>Image-' . $im . ' </b>:<a href =' . $imgfile . '>Click Here</a><br/>';
                    $im++;
                }
            }
            $notifyMessage = 'New Initial Inspection has been created, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $obs_id . '<br/> 
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>Category : </b> ' . $hse_cat . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'New Inspection - ID - ' . $insp_id . ' has been created';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '4':

            $notifyMessage = 'Activity of the CA has been assigned, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/> 
           <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>';

            $notifyMessage .= '<b>Target Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/>';
            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been assigned';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '5':

            $notifyMessage = 'EPC E&S Manager has been waiting to approve, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
             <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been waiting for approval';

            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '7':

            $notifyMessage = 'EPC E&S Manager has been "Rejected" the action item :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
           <b>Reporter Designation : </b> ' . $desig . ' <br/>
           <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
           <b>EPC : </b> ' . $comp_name . ' <br/>
           <b>Area : </b> ' . $area_name . '<br/>
           <b>Building/Block/Direction: </b> ' . $building_name . '<br/>
           <b>Project : </b> ' . $proj_name . '<br/>
           <b>Department : </b> ' . $dep_name . ' <br/>
           <b>HSE Category : </b> ' . $hse_cat . ' <br/>';


            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been rejected';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '3':

            $notifyMessage = 'EPC E&S Manager has been "Approved" the action item and "Waiting for HSE Manager Approval" the Inspection, details are as follows: <br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
                <b>Reporter Name : </b> ' . $Reporter . ' <br/>
               <b>Reporter Designation : </b> ' . $desig . ' <br/>
               <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
               <b>EPC : </b> ' . $comp_name . ' <br/>
               <b>Area : </b> ' . $area_name . '<br/>
               <b>Building/Block/Direction: </b> ' . $building_name . '<br/>  
               <b>Project : </b> ' . $proj_name . '<br/>
               <b>Department : </b> ' . $dep_name . ' <br/>
               <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been approved and  Waiting for HSE Manager Approval';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;
    }



    $notifydata = [

        'text' => $notifyMessage,

        'url' => $notifyUrl,

        'type' => 1,

        'notifygroup' => '',

        'employee' => $notfiy_id,

        'mobilelink' => $insp_auto_id,
        'module_auto_id' => 1,

    ];


    insertNotifications($notifydata);
    // exit;








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

function sendMonthlyInspNotification($ins_id)
{

    $ci = &get_instance();
    global $risk_rating, $obs_type_list;
    ////email   
    $insp_admin = ['7'];
    $insp_super = ['2'];
    $insp_assigner = ['5'];
    $insp_approver = ['6'];
    $insp_final_approver = ['8'];
    ////
    $option['where'] = ['insp_item_auto_id' => $ins_id];

    $option['return_type'] = 'row';

    $option['join'] = [

        LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_MONTH_ITEMS . '.insp_reporter_id', 'LEFT'],
        EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],


        LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_MONTH_ITEMS . '.insp_assigner_id', 'LEFT'],
        EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

        INSP_AUDIT_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_MONTH_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
        'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_MONTH_ITEMS . '.fk_item_cat_id', 'LEFT'],




    ];



    $aOption = $option + $option;

    $atardetails = $ci->common_model->getAlldata(INSP_MONTH_ITEMS, [
        '*',
        'insp_main.ins_id',
        'ins_cate_mas.category as category_name',
        'Reporter.EMP_NAME` as Reporter',
        'Reporter.EMP_EMAIL_ID` as reporter_email_id',
        'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
        'FN_COMP_NAME(insp_comp_id) as comp_name',
        'FN_AREA_NAME(insp_area_id) as area_name',
        'FN_BUILD_NAME(insp_building_id) as building_name',
        'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
        'FN_PROJECT_NAME(insp_project_id) as proj_name',
        'Reporter.EMP_NAME as empName',
        'Hod.EMP_NAME as assignee',
        'Hod.EMP_EMAIL_ID as assignee_email',
        'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
    ], $aOption);

    $option_img['where'] = [
        'fk_insp_main_id' => $ins_id,
        'insp_attach_status' => 'Y',
        'insp_file_type' => '1'
    ];
    $getimgusee = $ci->common_model->getAlldata(INSP_MONTH_IMAGE_SEE, ['insp_file_path,insp_filename'], $option_img);

    if ($atardetails != FALSE) {

        $insp_id = postData($atardetails, 'ins_id');
        $insp_auto_id  = postData($atardetails, 'insp_item_auto_id');
        $Reporter = postData($atardetails, 'Reporter');
        $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
        $reporter_email_id = postData($atardetails, 'reporter_email_id');
        $desig = postData($atardetails, 'desig');
        $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
        $insp_type_id = postData($atardetails, 'insp_type_id');
        $insp_risk_id = postData($atardetails, 'insp_risk_id');


        $insp_app_status = postData($atardetails, 'insp_app_status');
        $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
        $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
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

        $assignee_email = postData($atardetails, 'assignee_email');
        $supervisor_email = postData($atardetails, 'supervisor_email');
        $hsse_email = postData($atardetails, 'hsse_email');
        $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
        $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
        $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
        $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
        $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');
        $insp_hsse_es_id = postData($atardetails, 'insp_hsse_es_id');
        $Hsse_es_email = postData($atardetails, 'Hsse_es_email');

        $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));
    }


    $encAtar = encryptval($insp_auto_id);

    $notifyUrl = '';

    $employeeEmail = FALSE;

    $ptwFooterMailContent = '<br/>This is system generated E-mail, do not reply to this email.<br/>';

    $imgmsg = '';
    switch ($insp_app_status) {

        case '1':


            if (isset($getimgusee) && !empty($getimgusee)) {
                $im = 1;
                foreach ($getimgusee as $imguse) {
                    $imgfile = BASE_URL . $imguse->insp_file_path . $imguse->insp_filename;
                    $imgmsg .= '<b>Image-' . $im . ' </b>:<a href =' . $imgfile . '>Click Here</a><br/>';
                    $im++;
                }
            }
            $notifyMessage = 'New Initial Inspection has been created, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $obs_id . '<br/> 
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>Category : </b> ' . $hse_cat . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'New Inspection - ID - ' . $insp_id . ' has been created';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '4':

            $notifyMessage = 'Activity of the CA has been assigned, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/> 
           <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>';

            $notifyMessage .= '<b>Target Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/>';
            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been assigned';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '5':

            $notifyMessage = 'EPC E&S Manager has been waiting to approve, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
             <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been waiting for approval';

            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '7':

            $notifyMessage = 'EPC E&S Manager has been "Rejected" the action item :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
           <b>Reporter Designation : </b> ' . $desig . ' <br/>
           <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
           <b>EPC : </b> ' . $comp_name . ' <br/>
           <b>Area : </b> ' . $area_name . '<br/>
           <b>Building/Block/Direction: </b> ' . $building_name . '<br/>
           <b>Project : </b> ' . $proj_name . '<br/>
           <b>Department : </b> ' . $dep_name . ' <br/>
           <b>HSE Category : </b> ' . $hse_cat . ' <br/>';


            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been rejected';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '3':

            $notifyMessage = 'EPC E&S Manager has been "Approved" the action item and "Waiting for HSE Manager Approval" the Inspection, details are as follows: <br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
                <b>Reporter Name : </b> ' . $Reporter . ' <br/>
               <b>Reporter Designation : </b> ' . $desig . ' <br/>
               <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
               <b>EPC : </b> ' . $comp_name . ' <br/>
               <b>Area : </b> ' . $area_name . '<br/>
               <b>Building/Block/Direction: </b> ' . $building_name . '<br/>  
               <b>Project : </b> ' . $proj_name . '<br/>
               <b>Department : </b> ' . $dep_name . ' <br/>
               <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been approved and  Waiting for HSE Manager Approval';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;
    }



    $notifydata = [

        'text' => $notifyMessage,

        'url' => $notifyUrl,

        'type' => 1,

        'notifygroup' => '',

        'employee' => $notfiy_id,

        'mobilelink' => $insp_auto_id,
        'module_auto_id' => 1,

    ];


    insertNotifications($notifydata);
    // exit;








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

function sendHotelInspNotification($ins_id)
{

    $ci = &get_instance();
    global $risk_rating, $obs_type_list;
    ////email   
    $insp_admin = ['7'];
    $insp_super = ['2'];
    $insp_assigner = ['5'];
    $insp_approver = ['6'];
    $insp_final_approver = ['8'];
    ////
    $option['where'] = ['insp_item_auto_id' => $ins_id];

    $option['return_type'] = 'row';

    $option['join'] = [

        LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_HOTEL_ITEMS . '.insp_reporter_id', 'LEFT'],
        EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],


        LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_HOTEL_ITEMS . '.insp_assigner_id', 'LEFT'],
        EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

        INSP_AUDIT_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_HOTEL_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
        'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_HOTEL_ITEMS . '.fk_item_cat_id', 'LEFT'],




    ];



    $aOption = $option + $option;

    $atardetails = $ci->common_model->getAlldata(INSP_HOTEL_ITEMS, [
        '*',
        'insp_main.ins_id',
        'ins_cate_mas.category as category_name',
        'Reporter.EMP_NAME` as Reporter',
        'Reporter.EMP_EMAIL_ID` as reporter_email_id',
        'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
        'FN_COMP_NAME(insp_comp_id) as comp_name',
        'FN_AREA_NAME(insp_area_id) as area_name',
        'FN_BUILD_NAME(insp_building_id) as building_name',
        'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
        'FN_PROJECT_NAME(insp_project_id) as proj_name',
        'Reporter.EMP_NAME as empName',
        'Hod.EMP_NAME as assignee',
        'Hod.EMP_EMAIL_ID as assignee_email',
        'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
    ], $aOption);

    $option_img['where'] = [
        'fk_insp_main_id' => $ins_id,
        'insp_attach_status' => 'Y',
        'insp_file_type' => '1'
    ];
    $getimgusee = $ci->common_model->getAlldata(INSP_HOTEL_IMAGE_SEE, ['insp_file_path,insp_filename'], $option_img);

    if ($atardetails != FALSE) {

        $insp_id = postData($atardetails, 'ins_id');
        $insp_auto_id  = postData($atardetails, 'insp_item_auto_id');
        $Reporter = postData($atardetails, 'Reporter');
        $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
        $reporter_email_id = postData($atardetails, 'reporter_email_id');
        $desig = postData($atardetails, 'desig');
        $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
        $insp_type_id = postData($atardetails, 'insp_type_id');
        $insp_risk_id = postData($atardetails, 'insp_risk_id');


        $insp_app_status = postData($atardetails, 'insp_app_status');
        $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
        $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
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

        $assignee_email = postData($atardetails, 'assignee_email');
        $supervisor_email = postData($atardetails, 'supervisor_email');
        $hsse_email = postData($atardetails, 'hsse_email');
        $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
        $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
        $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
        $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
        $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');
        $insp_hsse_es_id = postData($atardetails, 'insp_hsse_es_id');
        $Hsse_es_email = postData($atardetails, 'Hsse_es_email');

        $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));
    }


    $encAtar = encryptval($insp_auto_id);

    $notifyUrl = '';

    $employeeEmail = FALSE;

    $ptwFooterMailContent = '<br/>This is system generated E-mail, do not reply to this email.<br/>';

    $imgmsg = '';
    switch ($insp_app_status) {

        case '1':


            if (isset($getimgusee) && !empty($getimgusee)) {
                $im = 1;
                foreach ($getimgusee as $imguse) {
                    $imgfile = BASE_URL . $imguse->insp_file_path . $imguse->insp_filename;
                    $imgmsg .= '<b>Image-' . $im . ' </b>:<a href =' . $imgfile . '>Click Here</a><br/>';
                    $im++;
                }
            }
            $notifyMessage = 'New Initial Inspection has been created, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $obs_id . '<br/> 
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>Category : </b> ' . $hse_cat . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'New Inspection - ID - ' . $insp_id . ' has been created';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '4':

            $notifyMessage = 'Activity of the CA has been assigned, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/> 
           <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>';

            $notifyMessage .= '<b>Target Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/>';
            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been assigned';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '5':

            $notifyMessage = 'EPC E&S Manager has been waiting to approve, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
             <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been waiting for approval';

            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '7':

            $notifyMessage = 'EPC E&S Manager has been "Rejected" the action item :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
           <b>Reporter Designation : </b> ' . $desig . ' <br/>
           <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
           <b>EPC : </b> ' . $comp_name . ' <br/>
           <b>Area : </b> ' . $area_name . '<br/>
           <b>Building/Block/Direction: </b> ' . $building_name . '<br/>
           <b>Project : </b> ' . $proj_name . '<br/>
           <b>Department : </b> ' . $dep_name . ' <br/>
           <b>HSE Category : </b> ' . $hse_cat . ' <br/>';


            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been rejected';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '3':

            $notifyMessage = 'EPC E&S Manager has been "Approved" the action item and "Waiting for HSE Manager Approval" the Inspection, details are as follows: <br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
                <b>Reporter Name : </b> ' . $Reporter . ' <br/>
               <b>Reporter Designation : </b> ' . $desig . ' <br/>
               <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
               <b>EPC : </b> ' . $comp_name . ' <br/>
               <b>Area : </b> ' . $area_name . '<br/>
               <b>Building/Block/Direction: </b> ' . $building_name . '<br/>  
               <b>Project : </b> ' . $proj_name . '<br/>
               <b>Department : </b> ' . $dep_name . ' <br/>
               <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been approved and  Waiting for HSE Manager Approval';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;
    }



    $notifydata = [

        'text' => $notifyMessage,

        'url' => $notifyUrl,

        'type' => 1,

        'notifygroup' => '',

        'employee' => $notfiy_id,

        'mobilelink' => $insp_auto_id,
        'module_auto_id' => 1,

    ];


    insertNotifications($notifydata);
    // exit;








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

function sendWeeklyInspNotification($ins_id)
{

    $ci = &get_instance();
    global $risk_rating, $obs_type_list;
    ////email   
    $insp_admin = ['7'];
    $insp_super = ['2'];
    $insp_assigner = ['5'];
    $insp_approver = ['6'];
    $insp_final_approver = ['8'];
    ////
    $option['where'] = ['insp_item_auto_id' => $ins_id];

    $option['return_type'] = 'row';

    $option['join'] = [

        LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_WEEKLY_ITEMS . '.insp_reporter_id', 'LEFT'],
        EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],


        LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_WEEKLY_ITEMS . '.insp_assigner_id', 'LEFT'],
        EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

        INSP_AUDIT_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_WEEKLY_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
        'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_WEEKLY_ITEMS . '.fk_item_cat_id', 'LEFT'],




    ];



    $aOption = $option + $option;

    $atardetails = $ci->common_model->getAlldata(INSP_WEEKLY_ITEMS, [
        '*',
        'insp_main.ins_id',
        'ins_cate_mas.category as category_name',
        'Reporter.EMP_NAME` as Reporter',
        'Reporter.EMP_EMAIL_ID` as reporter_email_id',
        'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
        'FN_COMP_NAME(insp_comp_id) as comp_name',
        'FN_AREA_NAME(insp_area_id) as area_name',
        'FN_BUILD_NAME(insp_building_id) as building_name',
        'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
        'FN_PROJECT_NAME(insp_project_id) as proj_name',
        'Reporter.EMP_NAME as empName',
        'Hod.EMP_NAME as assignee',
        'Hod.EMP_EMAIL_ID as assignee_email',
        'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
    ], $aOption);

    $option_img['where'] = [
        'fk_insp_main_id' => $ins_id,
        'insp_attach_status' => 'Y',
        'insp_file_type' => '1'
    ];
    $getimgusee = $ci->common_model->getAlldata(INSP_WEEKLY_IMAGE_SEE, ['insp_file_path,insp_filename'], $option_img);

    if ($atardetails != FALSE) {

        $insp_id = postData($atardetails, 'ins_id');
        $insp_auto_id  = postData($atardetails, 'insp_item_auto_id');
        $Reporter = postData($atardetails, 'Reporter');
        $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
        $reporter_email_id = postData($atardetails, 'reporter_email_id');
        $desig = postData($atardetails, 'desig');
        $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
        $insp_type_id = postData($atardetails, 'insp_type_id');
        $insp_risk_id = postData($atardetails, 'insp_risk_id');


        $insp_app_status = postData($atardetails, 'insp_app_status');
        $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
        $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
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

        $assignee_email = postData($atardetails, 'assignee_email');
        $supervisor_email = postData($atardetails, 'supervisor_email');
        $hsse_email = postData($atardetails, 'hsse_email');
        $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
        $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
        $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
        $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
        $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');
        $insp_hsse_es_id = postData($atardetails, 'insp_hsse_es_id');
        $Hsse_es_email = postData($atardetails, 'Hsse_es_email');

        $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));
    }


    $encAtar = encryptval($insp_auto_id);

    $notifyUrl = '';

    $employeeEmail = FALSE;

    $ptwFooterMailContent = '<br/>This is system generated E-mail, do not reply to this email.<br/>';

    $imgmsg = '';
    switch ($insp_app_status) {

        case '1':


            if (isset($getimgusee) && !empty($getimgusee)) {
                $im = 1;
                foreach ($getimgusee as $imguse) {
                    $imgfile = BASE_URL . $imguse->insp_file_path . $imguse->insp_filename;
                    $imgmsg .= '<b>Image-' . $im . ' </b>:<a href =' . $imgfile . '>Click Here</a><br/>';
                    $im++;
                }
            }
            $notifyMessage = 'New Initial Inspection has been created, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $obs_id . '<br/> 
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>Category : </b> ' . $hse_cat . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'New Inspection - ID - ' . $insp_id . ' has been created';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '4':

            $notifyMessage = 'Activity of the CA has been assigned, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/> 
           <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>';

            $notifyMessage .= '<b>Target Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/>';
            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been assigned';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '5':

            $notifyMessage = 'EPC E&S Manager has been waiting to approve, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
             <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been waiting for approval';

            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '7':

            $notifyMessage = 'EPC E&S Manager has been "Rejected" the action item :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
           <b>Reporter Designation : </b> ' . $desig . ' <br/>
           <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
           <b>EPC : </b> ' . $comp_name . ' <br/>
           <b>Area : </b> ' . $area_name . '<br/>
           <b>Building/Block/Direction: </b> ' . $building_name . '<br/>
           <b>Project : </b> ' . $proj_name . '<br/>
           <b>Department : </b> ' . $dep_name . ' <br/>
           <b>HSE Category : </b> ' . $hse_cat . ' <br/>';


            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been rejected';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '3':

            $notifyMessage = 'EPC E&S Manager has been "Approved" the action item and "Waiting for HSE Manager Approval" the Inspection, details are as follows: <br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
                <b>Reporter Name : </b> ' . $Reporter . ' <br/>
               <b>Reporter Designation : </b> ' . $desig . ' <br/>
               <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
               <b>EPC : </b> ' . $comp_name . ' <br/>
               <b>Area : </b> ' . $area_name . '<br/>
               <b>Building/Block/Direction: </b> ' . $building_name . '<br/>  
               <b>Project : </b> ' . $proj_name . '<br/>
               <b>Department : </b> ' . $dep_name . ' <br/>
               <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been approved and  Waiting for HSE Manager Approval';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;
    }



    $notifydata = [

        'text' => $notifyMessage,

        'url' => $notifyUrl,

        'type' => 1,

        'notifygroup' => '',

        'employee' => $notfiy_id,

        'mobilelink' => $insp_auto_id,
        'module_auto_id' => 1,

    ];


    insertNotifications($notifydata);
    // exit;








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

function sendWorkcampInspNotification($ins_id)
{

    $ci = &get_instance();
    global $risk_rating, $obs_type_list;
    ////email   
    $insp_admin = ['7'];
    $insp_super = ['2'];
    $insp_assigner = ['5'];
    $insp_approver = ['6'];
    $insp_final_approver = ['8'];
    ////
    $option['where'] = ['insp_item_auto_id' => $ins_id];

    $option['return_type'] = 'row';

    $option['join'] = [

        LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_WORKCAMP_ITEMS . '.insp_reporter_id', 'LEFT'],
        EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],


        LOGIN . ' AS LD2' => ['LD2.LOGIN_ID = ' . INSP_WORKCAMP_ITEMS . '.insp_assigner_id', 'LEFT'],
        EMPL . ' AS Hod' => ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'LEFT'],

        INSP_AUDIT_FLOW_SEE . ' AS insp_main' => ['insp_main.ins_auto_id = ' . INSP_WORKCAMP_ITEMS . '.fk_insp_main_auto_id', 'LEFT'],
        'insp_audit_category_master AS ins_cate_mas' => ['ins_cate_mas.id = ' . INSP_WORKCAMP_ITEMS . '.fk_item_cat_id', 'LEFT'],




    ];



    $aOption = $option + $option;

    $atardetails = $ci->common_model->getAlldata(INSP_WORKCAMP_ITEMS, [
        '*',
        'insp_main.ins_id',
        'ins_cate_mas.category as category_name',
        'Reporter.EMP_NAME` as Reporter',
        'Reporter.EMP_EMAIL_ID` as reporter_email_id',
        'FN_COMP_NAME(insp_owner_eng) as owner_eng_name',
        'FN_COMP_NAME(insp_comp_id) as comp_name',
        'FN_AREA_NAME(insp_area_id) as area_name',
        'FN_BUILD_NAME(insp_building_id) as building_name',
        'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
        'FN_PROJECT_NAME(insp_project_id) as proj_name',
        'Reporter.EMP_NAME as empName',
        'Hod.EMP_NAME as assignee',
        'Hod.EMP_EMAIL_ID as assignee_email',
        'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
    ], $aOption);

    $option_img['where'] = [
        'fk_insp_main_id' => $ins_id,
        'insp_attach_status' => 'Y',
        'insp_file_type' => '1'
    ];
    $getimgusee = $ci->common_model->getAlldata(INSP_WORKCAMP_IMAGE_SEE, ['insp_file_path,insp_filename'], $option_img);

    if ($atardetails != FALSE) {

        $insp_id = postData($atardetails, 'ins_id');
        $insp_auto_id  = postData($atardetails, 'insp_item_auto_id');
        $Reporter = postData($atardetails, 'Reporter');
        $insp_reporter_id = postData($atardetails, 'insp_reporter_id');
        $reporter_email_id = postData($atardetails, 'reporter_email_id');
        $desig = postData($atardetails, 'desig');
        $insp_report_datetime = date('d-m-Y H:i:s', strtotime(postData($atardetails, 'insp_report_datetime')));
        $insp_type_id = postData($atardetails, 'insp_type_id');
        $insp_risk_id = postData($atardetails, 'insp_risk_id');


        $insp_app_status = postData($atardetails, 'insp_app_status');
        $insp_assigner_id = postData($atardetails, 'insp_assigner_id');
        $insp_supervisor_id = postData($atardetails, 'insp_supervisor_id');
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

        $assignee_email = postData($atardetails, 'assignee_email');
        $supervisor_email = postData($atardetails, 'supervisor_email');
        $hsse_email = postData($atardetails, 'hsse_email');
        $insp_assigner_target_date = postData($atardetails, 'insp_assigner_target_date');
        $insp_assigner_desc = postData($atardetails, 'insp_assigner_desc');
        $insp_hsse_es_type_id = postData($atardetails, 'insp_hsse_es_type_id');
        $insp_hsse_es_appr_rej_desc = postData($atardetails, 'insp_hsse_es_appr_rej_desc');
        $insp_hsse_appr_rej_desc = postData($atardetails, 'insp_hsse_appr_rej_desc');
        $insp_hsse_es_id = postData($atardetails, 'insp_hsse_es_id');
        $Hsse_es_email = postData($atardetails, 'Hsse_es_email');

        $insp_supervisor_date = date('d-m-Y', strtotime(postData($atardetails, 'insp_supervisor_date')));
    }


    $encAtar = encryptval($insp_auto_id);

    $notifyUrl = '';

    $employeeEmail = FALSE;

    $ptwFooterMailContent = '<br/>This is system generated E-mail, do not reply to this email.<br/>';

    $imgmsg = '';
    switch ($insp_app_status) {

        case '1':


            if (isset($getimgusee) && !empty($getimgusee)) {
                $im = 1;
                foreach ($getimgusee as $imguse) {
                    $imgfile = BASE_URL . $imguse->insp_file_path . $imguse->insp_filename;
                    $imgmsg .= '<b>Image-' . $im . ' </b>:<a href =' . $imgfile . '>Click Here</a><br/>';
                    $im++;
                }
            }
            $notifyMessage = 'New Initial Inspection has been created, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $obs_id . '<br/> 
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>Category : </b> ' . $hse_cat . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'New Inspection - ID - ' . $insp_id . ' has been created';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '4':

            $notifyMessage = 'Activity of the CA has been assigned, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/> 
           <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>';

            $notifyMessage .= '<b>Target Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/>';
            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been assigned';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '5':

            $notifyMessage = 'EPC E&S Manager has been waiting to approve, details are as follows :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
             <b>Reporter Name : </b> ' . $Reporter . ' <br/>
            <b>Reporter Designation : </b> ' . $desig . ' <br/>
            <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
            <b>EPC : </b> ' . $comp_name . ' <br/>
            <b>Area : </b> ' . $area_name . '<br/>
            <b>Building/Block/Direction : </b> ' . $building_name . '<br/>
            <b>Project : </b> ' . $proj_name . '<br/>
            <b>Department : </b> ' . $dep_name . ' <br/>
            <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been waiting for approval';

            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;

        case '7':

            $notifyMessage = 'EPC E&S Manager has been "Rejected" the action item :<br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
            <b>Reporter Name : </b> ' . $Reporter . ' <br/>
           <b>Reporter Designation : </b> ' . $desig . ' <br/>
           <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
           <b>EPC : </b> ' . $comp_name . ' <br/>
           <b>Area : </b> ' . $area_name . '<br/>
           <b>Building/Block/Direction: </b> ' . $building_name . '<br/>
           <b>Project : </b> ' . $proj_name . '<br/>
           <b>Department : </b> ' . $dep_name . ' <br/>
           <b>HSE Category : </b> ' . $hse_cat . ' <br/>';


            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been rejected';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_assigner_id;
            $emailId = $assignee_email;
            break;

        case '3':

            $notifyMessage = 'EPC E&S Manager has been "Approved" the action item and "Waiting for HSE Manager Approval" the Inspection, details are as follows: <br/><br/><b>Inspection ID : </b> ' . $insp_id . '<br/>
                <b>Reporter Name : </b> ' . $Reporter . ' <br/>
               <b>Reporter Designation : </b> ' . $desig . ' <br/>
               <b>Reported Date & Time : </b> ' . $insp_report_datetime . ' <br/>
               <b>EPC : </b> ' . $comp_name . ' <br/>
               <b>Area : </b> ' . $area_name . '<br/>
               <b>Building/Block/Direction: </b> ' . $building_name . '<br/>  
               <b>Project : </b> ' . $proj_name . '<br/>
               <b>Department : </b> ' . $dep_name . ' <br/>
               <b>HSE Category : </b> ' . $hse_cat . ' <br/>';

            $notifyMessage .= '<b>Targeted Date to complete CA : </b> ' . $insp_assigner_target_date . ' <br/><b>Description entered by Assigner : </b> ' . $insp_assigner_desc . ' <br/><b>Description entered by Approver : </b> ' . $insp_hsse_es_appr_rej_desc . ' <br/>';

            $notifyUrl = 'inspection/audit/view/' . $encAtar;
            $subject = 'Inspection - ID - ' . $insp_id . ' has been approved and  Waiting for HSE Manager Approval';
            $employeeEmail = TRUE;
            $notfiy_id = $insp_reporter_id;
            $emailId = $reporter_email_id;
            break;
    }



    $notifydata = [

        'text' => $notifyMessage,

        'url' => $notifyUrl,

        'type' => 1,

        'notifygroup' => '',

        'employee' => $notfiy_id,

        'mobilelink' => $insp_auto_id,
        'module_auto_id' => 1,

    ];


    insertNotifications($notifydata);
    // exit;








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
