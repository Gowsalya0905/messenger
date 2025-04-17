<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Spatie\SimpleExcel\SimpleExcelWriter as SimpleExcelWriter;

class Training extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        isLogin();
        $this->load->model('training/training_model', 'atar');
        $this->load->helper('training_helper');
    }

    public function trainingInfo()
    {
        $getdashdata = $this->input->get();
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        global $status_drop, $risk_rating, $obs_type_list, $status_main;

        $Status = !empty($getdashdata['Status']) ? $getdashdata['Status'] : 0;
        $company_id = !empty($getdashdata['company_id']) ? $getdashdata['company_id'] : 0;
        $area_id = !empty($getdashdata['area_id']) ? $getdashdata['area_id'] : 0;
        $building_id = !empty($getdashdata['building_id']) ? $getdashdata['building_id'] : 0;
        $department_id = !empty($getdashdata['department_id']) ? $getdashdata['department_id'] : 0;
        $project_id = !empty($getdashdata['project_id']) ? $getdashdata['project_id'] : 0;
        $hse_cat = !empty($getdashdata['hse_cat']) ? $getdashdata['hse_cat'] : 0;
        $risk_id = !empty($getdashdata['risk_id']) ? $getdashdata['risk_id'] : 0;
        $obs_type = !empty($getdashdata['obs_type']) ? $getdashdata['obs_type'] : 0;
        $Start_Date = !empty($getdashdata['Start_Date']) ? $getdashdata['Start_Date'] : '';
        $End_Date = !empty($getdashdata['End_Date']) ? $getdashdata['End_Date'] : '';
        $Month = !empty($getdashdata['Month']) ? $getdashdata['Month'] : '';
        $emp_id = !empty($getdashdata['emp_id']) ? decryptval($getdashdata['emp_id']) : '';
        $hsecatOptn['where'] = [
            'hse_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['*'], $hsecatOptn);
        $drophsecat = customFormDropDown($getAllhsecat, 'hse_id', 'hse_cat', 'Select HSE Category');


      
        ///////resp

        $table = EMPL . ' as r';
        $selects = ['LD2.LOGIN_ID', "CONCAT(r.EMP_NAME, ' - ', r.EMP_ID) as EMP_NAME"];
        $option['join'][LOGIN . ' as LD2'] = ['LD2.USER_REF_ID = r.EMP_AUTO_ID', 'left'];
        $option['return_type'] = 'result';
        $option['where'] = [
            'r.EMP_STATUS' => 'Y'
        ];
        if (!is_admin()) {
            $option['where'] = [
                'r.EMP_COMP_ID' => $user_clid,
                'r.EMP_STATUS' => 'Y'
            ];
        }
        $hir_flow_details = $this->common_model->getAlldata($table, $selects, $option);
       
        $getex_drop = customFormDropDown($hir_flow_details, "LOGIN_ID", "EMP_NAME", "Select Employee");

        //
        $data = [
            'pageTitle' => 'Training Management',
            'view_file' => 'training/training/list_form',
            'site_title' => 'Training Management',
            'current_menu' => 'Observation List',
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'drophsecat' => $drophsecat,          
            'getex_drop' => $getex_drop,
            'status_drop_proj' => $status_drop,
            'status_main' => $status_main,
            'getdashdata' => $getdashdata,
            'risk_rating' => $risk_rating,
            'obs_type_list' => $obs_type_list,
            'ajaxurl' => 'training/training/listtraining?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&hse_cat=' . $hse_cat . '&risk_id=' . $risk_id . '&obs_type=' . $obs_type . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date . '&Month=' . $Month . '&emp_id=' . $emp_id,
        ];
        $this->template->load_table_exp_template($data);
    }
    public function listtraining()
    {
        global $trPermission, $risk_rating_batch, $obs_type_list, $status_main;
        $getdashdata = $this->input->get();

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = TR_FLOW_SEE . ' as h';
        $column_order = array(
            null,
            'tr_uni_id',
            'FN_COMP_NAME(tr_comp_id)',
            'FN_AREA_NAME(h.tr_area_id)',
            'FN_BUILD_NAME(h.tr_building_id)',
            'FN_PROJECT_NAME(h.tr_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.tr_dept_id)',
            "DATE_FORMAT(tr_report_datetime,'%d-%m-%Y %H:%i:%s')",          
            'FN_OBS_STATUS(tr_app_status)',
            null
        );

        $column_search = array(
            'tr_uni_id',
            'FN_COMP_NAME(tr_comp_id)',
            'FN_AREA_NAME(h.tr_area_id)',
            'FN_BUILD_NAME(h.tr_building_id)',
            'FN_PROJECT_NAME(h.tr_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.tr_dept_id)',  
            "DATE_FORMAT(tr_report_datetime,'%d-%m-%Y %H:%i:%s')",           
            'FN_OBS_STATUS(tr_app_status)'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
        $mappedData = [];
        $request = $this->input->post();


        $searchextra = postData($request, 'searchextra');
        if ($searchextra != FALSE && count($searchextra) > 0) {
            foreach ($searchextra as $search) {
                $sName = postData($search, 'name');
                $sValue = postData($search, 'value');
                $mappedData[$sName] = $sValue;
            }
        }


        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $emp_name = postData($mappedData, 'emp_name');
            $hse_cat = postData($mappedData, 'hse_cat');
            $tr_type_id = postData($mappedData, 'tr_type');
            $risk_id = postData($mappedData, 'risk_id');
            $startdate = postData($mappedData, 'start_date');
            $enddate = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');
            $searchMainStatus = postData($mappedData, 'mainStatus');

            $options['where_new'] = [];
            $where = [];
            if (isset($getdashdata['Month']) && $getdashdata['Month'] != 'null' && $getdashdata['Month'] != '') {
                $options['where_new']['MONTHNAME(h.CREATED_ON)'] = $getdashdata['Month'];
            }
            if ($company_id > 0) {
                $options['where_new']['h.tr_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $options['where_new']['h.tr_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $options['where_new']['h.tr_building_id'] = $building_id;
            }
            if ($department_id > 0) {
                $options['where_new']['h.tr_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $options['where_new']['h.tr_project_id'] = $project_id;
            }
            if ($hse_cat > 0) {
                $options['where_new']['h.tr_cat_id'] = $hse_cat;
            }
            if ($tr_type_id > 0) {
                $options['where_new']['h.tr_type_id'] = $tr_type_id;
            }
            if ($risk_id > 0) {
                $options['where_new']['h.tr_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $options['where_new']['h.tr_assigner_id'] = $emp_name;
            }
            if ($searchStatus >= 0) {
                $options['where_new']["h.tr_app_status"] = $searchStatus;
            }

            if ($searchMainStatus == 0) {
                $options['where_new']["h.tr_app_status !="] = '3';
            } else if ($searchMainStatus == 1) {
                $options['where_new']["h.tr_app_status"] = '3';
            }

            if ($startdate != '') {
                $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

                $options['where_new']['h.CREATED_ON >='] = $thstartdate;
            }
            if ($enddate != '') {
                $enddate = date('Y-m-d', strtotime($enddate));
                $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
            }
        }

        ///////////////////////////filter end


        if (in_array($user_type, $trPermission['view_supadmin'])) {
            $options['where_new']['tr_status'] =  'Y';
        } elseif (in_array($user_type, $trPermission['view_ad'])) {
            $options['where_new']['tr_status'] =  'Y';
            $options['where_new']['tr_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $trPermission['view_assigner'])) {
            $options['where_new']['tr_status'] =  'Y';
            // $options['where_new']['tr_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $trPermission['fix'])) {
            $options['orwhere_new']['tr_assigner_id'] =  $userid;
            $options['where_new']['tr_status'] =  'Y';
            $options['orwhere_new']['tr_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $trPermission['approve'])) {
            $options['where_new']['tr_status'] =  'Y';
            //$options['where_new']['tr_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $trPermission['approve_final'])) {
            $options['where_new']['tr_status'] =  'Y';
            // $options['where_new']['tr_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['tr_status'] =  'Y';
            $options['where_new']['tr_reporter_id'] =  $userid;
        }




        // print_r( $where);exit;

        $options['select'] = [
            'h.tr_auto_id',
            'h.tr_uni_id',      
            'h.tr_app_status',  
       
            'h.tr_reporter_id',
            'FN_COMP_NAME(tr_comp_id) as comp_name',
            'FN_AREA_NAME(tr_area_id) as area_name',
            'FN_BUILD_NAME(tr_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(tr_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.tr_project_id) as proj_name',             
            'h.tr_report_datetime',
            'FN_OBS_STATUS(h.tr_app_status) as status_Name'
        ];



        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        // echo $this->db->last_query();
        // exit;
        $finalDatas = [];
        $i = 0;

        if (isset($listUsee) && !empty($listUsee)) {
            foreach ($listUsee as $ltKey => $ltVal) {
                $i++;
                $action = $tr_risk = $tr_type = '';
                $id = encryptval($ltVal->tr_auto_id);

                $stat = $ltVal->tr_app_status;
                $tr_reporter_id = $ltVal->tr_reporter_id;
                if (((in_array($user_type, $trPermission['view_supadmin'])) || (in_array($user_type, $trPermission['view_ad']) || $userid == $tr_reporter_id)) && ($stat == 1 || $stat == 0)) {

                    $action .= " " . anchor('training/training/addHmp/' . $id, '<i class="fa fa-edit"></i>', array('class' => '', 'title' => 'Edit'));
                }
                if (in_array($user_type, $trPermission['view_supadmin']) || (in_array($user_type, $trPermission['view_ad']))) {

                    $action .= "  " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deletetraining', 'title' => 'Delete', 'delid' => $id));
                }

                $action .= " " . anchor('training/training/view/' . $id, '<i class="fa fa-eye"></i>', array('title' => 'view'));
                $action .= " " . anchor('training/training/addAttendance/' . $id, '<i class="fas fa-id-card" aria-hidden="true"></i>', array('class' => '', 'title' => 'Mark Attendance'));

                $action .= " " . anchor('training/training/pdf/' . $id, '<i class="fas fa-file-pdf" aria-hidden="true"></i>', array('class' => '', 'title' => 'PDF', 'target' => '_blank'));
                $action .= " " . anchor('training/training/AssignEmployeeView/' . $id, '<i class="fa fa-user"></i>', array('title' => 'Assigned List'));

                // $target = $ltVal->tr_assigner_target_date;

                // if (date('d-m-Y', strtotime($target)) == '01-01-1970') {
                //     $tar = "-";
                // } else {
                //     $tar = date('d-m-Y', strtotime($target));
                // }

            
                $rows = [];
                $rows[] = $ltVal->tr_uni_id;
                $rows[] = ucfirst($ltVal->comp_name);
                $rows[] = ucfirst($ltVal->area_name);
                $rows[] = ucfirst($ltVal->building_name);
                $rows[] = ucfirst($ltVal->dep_name);
                $rows[] = ucfirst($ltVal->proj_name);                     
                // $rows[] = !empty($ltVal->Hod) ? ucfirst($ltVal->Hod) : '-';
                // $rows[] = $tar;
                $rows[] = date('d-m-Y H:i:s', strtotime($ltVal->tr_report_datetime));
                $rows[] = $ltVal->status_Name;
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

    public function exportexcel()
    {
        $request = $this->input->get();
        global $obsPermission, $risk_rating, $obs_type_list;
        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();
        $company_id = postData($request, 'company_id');
        $area_id = postData($request, 'area_id');
        $building_id = postData($request, 'building_id');
        $department_id = postData($request, 'department_id');
        $project_id = postData($request, 'project_id');
        $emp_name = postData($request, 'emp_name');
        $hse_cat = postData($request, 'hse_cat');
        $obs_type_id = postData($request, 'obs_type');
        $risk_id = postData($request, 'risk_id');
        $startdate = postData($request, 'start_date');
        $enddate = postData($request, 'end_date');
        $searchStatus = postData($request, 'NotifyStatus');
        $searchMainStatus = postData($request, 'mainStatus');
        $Month = postData($request, 'Month');
        $table = OBS_FLOW_SEE . ' as h';
        $column_order = array(
            null,
            'obs_id',
            'FN_COMP_NAME(obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(obs_app_status)',
            null
        );

        $column_search = array(
            'obs_id',
            'FN_COMP_NAME(obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(obs_app_status)'
        );

        $order = array('h.CREATED_ON' => 'desc');


        $options['where_new'] = [];
        $where = [];
        if ($company_id > 0) {
            $options['where_new']['h.obs_comp_id'] = $company_id;
        }
        if ($area_id > 0) {
            $options['where_new']['h.obs_area_id'] = $area_id;
        }
        if ($building_id > 0) {
            $options['where_new']['h.obs_building_id'] = $building_id;
        }
        if ($department_id > 0) {
            $options['where_new']['h.obs_dept_id'] = $department_id;
        }
        if ($project_id > 0) {
            $options['where_new']['h.obs_project_id'] = $project_id;
        }
        if ($hse_cat > 0) {
            $options['where_new']['h.obs_cat_id'] = $hse_cat;
        }

        if ($obs_type_id > 0) {
            $options['where_new']['h.obs_type_id'] = $obs_type_id;
        }
        if ($risk_id > 0) {
            $options['where_new']['h.obs_risk_id'] = $risk_id;
        }
        if ($emp_name > 0) {
            $options['where_new']['h.obs_assigner_id'] = $emp_name;
        }

        if ($searchStatus > 0) {
            $options['where_new']["h.obs_app_status"] = $searchStatus;
        }

        if ($searchMainStatus == 0) {
            $options['where_new']["h.obs_app_status !="] = '3';
        } else if ($searchMainStatus == 1) {
            $options['where_new']["h.obs_app_status"] = '3';
        }


        if ($startdate != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

            $options['where_new']['h.CREATED_ON >='] = $thstartdate;
        }
        if ($enddate != '') {
            $enddate = date('Y-m-d', strtotime($enddate));
            $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
        }
        if (isset($Month) && $Month != 'null' && $Month != '') {
            $options['where_new']['MONTHNAME(h.CREATED_ON)'] = $Month;
        }

        ///////////////////////////filter end


        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $options['where_new']['obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $options['where_new']['obs_status'] =  'Y';
            $options['where_new']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $options['where_new']['obs_status'] =  'Y';
            // $options['where_new']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $options['orwhere_new']['obs_assigner_id'] =  $userid;
            $options['where_new']['obs_status'] =  'Y';
            $options['orwhere_new']['obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $options['where_new']['obs_status'] =  'Y';
            //$options['where_new']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $options['where_new']['obs_status'] =  'Y';
            // $options['where_new']['obs_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['obs_status'] =  'Y';
            $options['where_new']['obs_reporter_id'] =  $userid;
        }



        // print_r( $where);exit;

        $options['select'] = [
            'h.obs_auto_id',
            'h.obs_id',
            'h.obs_assigner_target_date',
            'h.obs_app_status',
            'h.obs_risk_id',
            'h.obs_type_id',
            'FN_COMP_NAME(obs_comp_id) as comp_name',
            'FN_AREA_NAME(obs_area_id) as area_name',
            'FN_BUILD_NAME(obs_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(obs_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.obs_project_id) as proj_name',
            'FN_HSE_CAT_NAME(h.obs_cat_id) as hse_cat_name',
            'Hod.EMP_NAME as Hod',
            'h.obs_report_datetime',
            'FN_OBS_STATUS(h.obs_app_status) as status_Name'
        ];

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];


        $result = $this->common_model->get_exportdata(
            $table,
            $column_order,
            $column_search,
            $order,
            $where,
            $options
        );


        $header = [
            'SL.No.',
            'Observation ID',
            'EPC',
            'Area',
            'Building/Block/Department',
            'Department',
            'Project',
            'HSE Category',
            'Observation Type',
            'Risk Rating',
            'Responsible Person Name',
            'Target Date',
            'Reported Date & Time',
            'Status',
        ];

        $i = 1;

        foreach ($result as $data) {

            // $id = $data->EMP_AUTO_ID;

            $target = $data->obs_assigner_target_date;

            if (date('d-m-Y', strtotime($target)) == '01-01-1970') {
                $tar = "";
            } else {
                $tar = date('d-m-Y', strtotime($target));
            }
            $obs_risk_id = isset($data->obs_risk_id) ? $data->obs_risk_id : '';
            $obs_type_id = isset($data->obs_type_id) ? $data->obs_type_id : '';
            if (isset($risk_rating[$obs_risk_id])) {
                $obs_risk = $risk_rating[$obs_risk_id];
            } else {
                $obs_risk = '';
            }

            if ($obs_type_id) {
                $obs_type = $obs_type_list[$obs_type_id];
            }


            $row = [];
            $row[] = $i;
            $row[] = $data->obs_id;
            $row[] = ucfirst($data->comp_name);
            $row[] = ucfirst($data->area_name);
            $row[] = ucfirst($data->building_name);
            $row[] = ucfirst($data->dep_name);
            $row[] = ucfirst($data->proj_name);
            $row[] = ucfirst($data->hse_cat_name);
            $row[] = $obs_type;
            $row[] = $obs_risk;
            $row[] = ucfirst($data->Hod);
            $row[] = $tar;
            $row[] = date('d-m-Y H:i:s', strtotime($data->obs_report_datetime));
            $row[] = strip_tags($data->status_Name);
            $exportData[] = $row;
            $i++;
        }

        $writer = SimpleExcelWriter::streamDownload('Observation Details.xlsx')
            ->addHeader($header)
            ->addRows(
                $exportData
            );
    }

    public function exportpdf()
    {


        $request = $this->input->get();

        global $obsPermission;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();
        $company_id = postData($request, 'company_id');
        $area_id = postData($request, 'area_id');
        $building_id = postData($request, 'building_id');
        $department_id = postData($request, 'department_id');
        $project_id = postData($request, 'project_id');
        $emp_name = postData($request, 'emp_name');
        $hse_cat = postData($request, 'hse_cat');
        $obs_type_id = postData($request, 'obs_type');
        $risk_id = postData($request, 'risk_id');
        $startdate = postData($request, 'start_date');
        $enddate = postData($request, 'end_date');
        $searchStatus = postData($request, 'NotifyStatus');
        $searchMainStatus = postData($request, 'mainStatus');
        $Month = postData($request, 'Month');
        $table = OBS_FLOW_SEE . ' as h';
        $column_order = array(
            null,
            'obs_id',
            'FN_COMP_NAME(obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(obs_app_status)',
            null
        );

        $column_search = array(
            'obs_id',
            'FN_COMP_NAME(obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(obs_app_status)'
        );

        $order = array('h.CREATED_ON' => 'desc');


        $options['where_new'] = [];
        $where = [];
        if ($company_id > 0) {
            $options['where_new']['h.obs_comp_id'] = $company_id;
        }
        if ($area_id > 0) {
            $options['where_new']['h.obs_area_id'] = $area_id;
        }
        if ($building_id > 0) {
            $options['where_new']['h.obs_building_id'] = $building_id;
        }

        if ($department_id > 0) {
            $options['where_new']['h.obs_dept_id'] = $department_id;
        }
        if ($project_id > 0) {
            $options['where_new']['h.obs_project_id'] = $project_id;
        }
        if ($hse_cat > 0) {
            $options['where_new']['h.obs_cat_id'] = $hse_cat;
        }
        if ($obs_type_id > 0) {
            $options['where_new']['h.obs_type_id'] = $obs_type_id;
        }
        if ($risk_id > 0) {
            $options['where_new']['h.obs_risk_id'] = $risk_id;
        }
        if ($emp_name > 0) {
            $options['where_new']['h.obs_assigner_id'] = $emp_name;
        }
        if ($searchStatus > 0) {
            $options['where_new']["h.obs_app_status"] = $searchStatus;
        }

        if ($searchMainStatus == 0) {
            $options['where_new']["h.obs_app_status !="] = '3';
        } else if ($searchMainStatus == 1) {
            $options['where_new']["h.obs_app_status"] = '3';
        }

        if ($startdate != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

            $options['where_new']['h.CREATED_ON >='] = $thstartdate;
        }
        if ($enddate != '') {
            $enddate = date('Y-m-d', strtotime($enddate));
            $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
        }
        if (isset($Month) && $Month != 'null' && $Month != '') {
            $options['where_new']['MONTHNAME(h.CREATED_ON)'] = $Month;
        }

        ///////////////////////////filter end


        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $options['where_new']['obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $options['where_new']['obs_status'] =  'Y';
            $options['where_new']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $options['where_new']['obs_status'] =  'Y';
            // $options['where_new']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $options['orwhere_new']['obs_assigner_id'] =  $userid;
            $options['where_new']['obs_status'] =  'Y';
            $options['orwhere_new']['obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $options['where_new']['obs_status'] =  'Y';
            //$options['where_new']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $options['where_new']['obs_status'] =  'Y';
            // $options['where_new']['obs_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['obs_status'] =  'Y';
            $options['where_new']['obs_reporter_id'] =  $userid;
        }



        // print_r( $where);exit;

        $options['select'] = [
            'h.obs_auto_id',
            'h.obs_id',
            'h.obs_assigner_target_date',
            'h.obs_app_status',
            'h.obs_risk_id',
            'h.obs_type_id',
            'FN_COMP_NAME(obs_comp_id) as comp_name',
            'FN_AREA_NAME(obs_area_id) as area_name',
            'FN_BUILD_NAME(obs_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(obs_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.obs_project_id) as proj_name',
            'FN_HSE_CAT_NAME(h.obs_cat_id) as hse_cat_name',
            'Hod.EMP_NAME as Hod',
            'h.obs_report_datetime',
            'FN_OBS_STATUS(h.obs_app_status) as status_Name'
        ];

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];


        $result = $this->common_model->get_exportdata(
            $table,
            $column_order,
            $column_search,
            $order,
            $where,
            $options
        );



        $header = [
            'SL.No.',
            'Observation ID',
            'EPC',
            'Area',
            'Building/Block/Department',
            'Department',
            'Project',
            'HSE Category',
            'Observation Type',
            'Risk Rating',
            'Responsible Person Name',
            'Target Date',
            'Reported Date & Time',
            'Status',
        ];



        $data = array(
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Observation Details",
        );

        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,

        ];

        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';
        $html = $this->load->view('training/training/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        $filename = "Observation Details.pdf";
        $mpdf->Output($filename, 'D');
    }
    public function addHmp($id = '')
    {
        $did = decryptval($id);
        global $owner_list, $obs_type_list, $risk_rating, $owner_engineer_list, $EPC_list;
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $hsecatOptn['where'] = [
            'hse_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['*'], $hsecatOptn);
        $drophsecat = customFormDropDown($getAllhsecat, 'hse_id', 'hse_cat', 'Select HSE Category');

        $mastypeOptn['where'] = [
            'type_status' => 'Y'
        ];
        $getmastype = $this->common_model->getAlldata(TRN_TYPE_MAS, ['*'], $mastypeOptn);
        $dropmastype = customFormDropDown($getmastype, 'id', 'type', 'Select Traning Type');



        $designOptn['where'] = [
            'DESIGNATION_STATUS' => 'Y'
        ];
        $designOptn['join'][UTYPE . ' as UT'] = ['UT.USER_TYPE_ID = DES_USER_TYPE', 'left'];
        $designall = $this->common_model->getAlldata(DESIG, ['DESIGNATION_ID', 'CONCAT(DESIGNATION_NAME, " - (", USER_TYPE_NAME, ")") AS DESIGNATION_NAME_USER_TYPE'], $designOptn);

        $dropdesig = customFormDropDown($designall, 'DESIGNATION_ID', 'DESIGNATION_NAME_USER_TYPE', 'Select Designation');

        $getUseeActdatas = $getAllBfimage = $getvistActdatas = [];

        if ($did != '') {
            $getUseeActdatas = $this->atar->getUseeDetails_project(['tr_auto_id' => $did], 'row');
            $getvistActdatas = $this->atar->getvisitorDetails_project(['fk_tr_main_auto_id' => $did, 'emp_type' => '2'], 'result');
            $getAllBfimage = $this->getBeforeImage($did);
        }
        // echo '<pre>'; print_r($getvistActdatas);die;
        $data = array(
            'view_file' => 'training/training/add_form',
            'current_menu' => 'Add Observation',
            'getProgramdatas' => $getUseeActdatas,
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'visitdata' => $getvistActdatas,
            "getAllBfimage" => $getAllBfimage,
            'hsecatDetails' => $drophsecat,
            'dropmastype' => $dropmastype,
            'dropdesig' => $dropdesig,
            'owner_list' => $owner_list,
            'owner_engineer_list' => $owner_engineer_list,
            'EPC_list' => $EPC_list,
            'obs_type_list' => $obs_type_list,
            'risk_rating' => $risk_rating
        );


        $this->template->load_common_template($data);
    }

    public function getBeforeImage($did = "")
    {
        $where = [
            "fk_obs_main_id" => $did,

            "obs_file_type" => 1,

            "obs_attach_status" => "Y",
        ];

        $option["where"] = $where;

        $option["return_type"] = "result";

        return $details = $this->common_model->getAlldata(
            OBS_IMG_SEE,
            ["*"],
            $option
        );
    }

    public function saveHmp($id = '')
    {
  
      
        $did = decryptval($id);

        $trDatas = $this->input->post('pro');
        $action_type = $this->input->post('action_type');
    
        // echo '<pre>'; print_r($trDatas);die;
  
        if ($action_type) {
            $this->form_validation->set_rules('pro[tr_owner_id]', 'Owner', 'required');
            $this->form_validation->set_rules('pro[tr_owner_eng]', 'Owner Engineering Name', 'required|trim');
            $this->form_validation->set_rules('pro[tr_epc_id]', 'EPC', 'required|trim');
            $this->form_validation->set_rules('pro[tr_comp_id]', 'Company', 'required|trim');
            // $this->form_validation->set_rules('pro[tr_area_id]', 'Area', 'required|trim');
            // $this->form_validation->set_rules('pro[tr_building_id]', 'Building', 'required|trim');
            // $this->form_validation->set_rules('pro[tr_dept_id]', 'Department', 'required|trim');           
            $this->form_validation->set_rules('pro[tr_venue_desc]', 'Venue Description', 'required');
     
        } else {
       
            $this->form_validation->set_rules('pro[tr_comp_id]', 'Company', 'required|trim');
            $this->form_validation->set_rules('pro[tr_area_id]', 'Area', 'required|trim');
            $this->form_validation->set_rules('pro[tr_building_id]', 'Building', 'required|trim');
        }



        if ($this->form_validation->run()) {

            $prev_tr_app_status = postData($trDatas, 'tr_app_status');
            $tr_app_status = $action_type;
            $date_time = postData($trDatas, 'tr_report_datetime');
            $tr_date = !empty(postData($trDatas, 'tr_date'))
                ? date('Y-m-d H:i:s', strtotime(postData($trDatas, 'tr_date')))
                : NULL;
                
            

            $prodatas = [
                'tr_reporter_id' => postData($trDatas, 'tr_reporter_id'),
                'tr_reporter_type_id' => postData($trDatas, 'tr_reporter_role_id'),
                'tr_reporter_desg_id' => postData($trDatas, 'user_desgination_id'),
                'tr_report_datetime' => postData($trDatas, 'tr_report_datetime'), 
                 'tr_owner_id' => postData($trDatas, 'tr_owner_id'),
                'tr_owner_eng' => postData($trDatas, 'tr_owner_eng'),
                'tr_epc_id' => postData($trDatas, 'tr_epc_id'),
                'tr_comp_id' => postData($trDatas, 'tr_comp_id'),
                'tr_area_id' => postData($trDatas, 'tr_area_id'),
                'tr_building_id' => postData($trDatas, 'tr_building_id'),
                'tr_dept_id' => postData($trDatas, 'tr_dept_id'),
                'tr_project_id' => postData($trDatas, 'tr_project_id'),         
                'tr_start_date' => postData($trDatas, 'sdate'),         
                'tr_end_date' => postData($trDatas, 'edate'),         
                'tr_start_time' => postData($trDatas, 'theorystarttime'),         
                'tr_end_time' => postData($trDatas, 'practicalstarttime'),         
                'tr_topics' => postData($trDatas, 'training_topics'),         
                'tr_conducted_by' => postData($trDatas, 'tr_conducted_by'),         
                'tr_venue_desc' => postData($trDatas, 'tr_venue_desc'),      
                    
                 'tr_emp_cmp_id' => implode(',', postData($trDatas, 'tr_emp_company_id')),          
                'tr_emp_desig_id' =>   implode(',',postData($trDatas, 'tr_emp_design_id')),            
                'tr_emp_ids'  => implode(',', postData($trDatas, 'tr_emp_ids')),  
                'tr_app_status' => $tr_app_status
            ];


            if ($did != '') {
                $upWhere = [
                    'tr_auto_id' => $did
                ];
                $updtProfile = $this->common_model->updateData(TR_FLOW_SEE, $prodatas, $upWhere);             
                $updtProfile = $did;
            } else {
                $projectName = getProjectName(postData($trDatas, 'tr_project_id'));
                //$projNameThreeLetters = strtoupper(substr($projectName, 0, 3));
                $projNameThreeLetters = 'AHK';
                $areaShortName = getAreaShortName(postData($trDatas, 'tr_area_id'));
                $areaShortTwoLetters = strtoupper(substr($areaShortName, 0, 2));

                $building_block_dirName = getBuildingName(postData($trDatas, 'tr_building_id'));
                $build_block_dirFirstLetter = strtoupper(substr($building_block_dirName, 0, 1));

                $getuniqueId = gettrNumber($projNameThreeLetters, $areaShortTwoLetters, $build_block_dirFirstLetter, postData($trDatas, 'tr_project_id'));
                $prodatas['tr_uni_id'] = $getuniqueId;
                $prodatas['tr_report_datetime'] = date('Y-m-d H:i:s', strtotime($date_time));
                $updtProfile = $this->common_model->updateData(TR_FLOW_SEE, $prodatas);
               
            }

            $visitorData = [];
            foreach ($trDatas as $key => $value) {
           
                if (is_array($value) && isset($value['tr_vistor_name'])) {
                    echo '<pre>'; print_r($key);
                    echo '<pre>'; print_r($value);
                    // Prepare data for the current visitor
                    $visitorData = [
                       
                        'tr_visitor_name' => trim($value['tr_vistor_name']),
                        'tr_visitor_desig' => trim($value['tr_vistor_deign']),
                        'tr_visitor_cmp_name' => trim($value['tr_vistor_comp_name']),
                        'tr_visitor_iq_number' => trim($value['tr_visitor_iqama_num']),
                        'tr_visitor_contact_number' => trim($value['tr_visitor_contact_num']),
                        'tr_visitor_email_id' => trim($value['tr_visitor_email']),
                        'emp_type' => 2 
                    ];            
                
                    if (isset($value['tr_ev_auto_id']) && !empty($value['tr_ev_auto_id'])) {
                        $visitorData['fk_tr_main_auto_id'] = $did;
                       
                        $this->common_model->updateData('tr_emp_visitor_details', $visitorData, ['tr_ev_auto_id' => $value['tr_ev_auto_id']]);
                        
                    } else {
                        $visitorData[ 'fk_tr_main_auto_id'] = $updtProfile;
                        $this->common_model->updateData('tr_emp_visitor_details', $visitorData);
                    }
                }
            }
            

             
            $visitorData1 = [];

            $companyIds = implode(',', postData($trDatas, 'tr_emp_company_id'));
            $designIds = implode(',', postData($trDatas, 'tr_emp_design_id'));
     
            if (!empty($trDatas['tr_emp_ids']) && !empty($trDatas['tr_emp_design_id']) && !empty($companyIds)) {
                foreach ($trDatas['tr_emp_ids'] as $index => $empId) {                     
              
                       
                        $visitorData1[] = [
                            'fk_tr_main_auto_id' => $updtProfile,
                            'fk_tr_emp_id' => $empId, 
                            'fk_tr_emp_desig_id' => $designIds,  
                            'fk_tr_emp_cmp_id' => $companyIds,  
                            'emp_type' => 1,  
                        ];
                  
                        $this->common_model->updateData('tr_emp_visitor_details', $visitorData1[$index]);                         
                }
            }

                  
            //////get main id
            if ($did != '') {
                $gen_id = postData($trDatas, 'tr_auto_id');
            } else {
                $gen_id = $getuniqueId;
            }
            $optionMainId["return_type"] = "row";
            $optionMainId["where"] = ["tr_auto_id" => $gen_id];
            $getAutoIddata = $this->common_model->getAlldata(TR_FLOW_SEE, ["tr_auto_id"], $optionMainId);
            $getAutoId = $getAutoIddata->tr_auto_id;

            ///////          

        

            if ($updtProfile) {

                if ($did != '') {
                    if ($action_type) {
                        // sendObsNotification($getAutoId);
                    }
                    $this->session->set_flashdata('trainingflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Updated</div>');
                } else {
                    if ($action_type) {
                        // sendObsNotification($getAutoId);
                        $this->session->set_flashdata('trainingflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Created</div>');
                    } else {
                        $this->session->set_flashdata('trainingflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Drafted</div>');
                    }
                }
                redirect('training/training/trainingInfo');
            } else {
                if ($did != '') {
                    $this->session->set_flashdata('trainingflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Updated</div>');
                } else {
                    $this->session->set_flashdata('trainingflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Created</div>');
                }
                redirect('training/training/trainingInfo');
            }
        } else {
            $this->addHmp($id);
        }
    }


    public function pdf($id = '')
    {

        $editData = $getuincident = $getuafterincident  = $getObsReassign = FALSE;
        if ($id != '') {
            $decryptUseeId = decryptval($id);
            $editData = $this->atar->getUseeDetails_project(['tr_auto_id' => $decryptUseeId], 'row');
            $getuincident = $this->atar->getUseeImageBefore_project($decryptUseeId);
            $getuafterallincident = $this->atar->getUseeactImageAfter_project($decryptUseeId, '');
            $getObsReassign = $this->atar->getObsDataReassign_project($decryptUseeId);
            $getObsAssign = $this->atar->getObsDataassign_project($decryptUseeId);
            $getObsApproval = $this->atar->getObsDataapproval_project($decryptUseeId);
            $getObsApprovalFinal = $this->atar->getObsDataapprovalfinal_project($decryptUseeId);
            $getObsActionTaken = $this->atar->getObsDataactiontaken_project($decryptUseeId);
        }
        //echo $this->db->last_query();exit;
        //  print_r($editData);
        $data = [
            'editData' => $editData,
            'getuincidentimage' => $getuincident,
            'getuafterimage' => $getuafterincident,
            'getuafterallimage' => $getuafterallincident,
            'getObsReassign' => $getObsReassign,
            'getObsAssign' => $getObsAssign,
            'getObsApproval' => $getObsApproval,
            'getObsApprovalFinal' => $getObsApprovalFinal,
            'getObsActionTaken' => $getObsActionTaken,

        ];

        $atarNumber = postData($editData, 'obs_id');
        $html2 = $this->load->view('training/training/obs_pdf', $data, true);
        // echo $html2;
        // exit;
        $mpdf2 = $this->pdf->ptwload();
        $mpdf2->setAutoTopMargin = 'stretch';
        $das = $mpdf2->WriteHTML($html2);
        $currenttime = date('d-m-Y');
        $folder_name = "training";
        $file_path = "assets/images/modules/training/pdf/" . $folder_name . "/";
        if (!file_exists($file_path)) {
            if (!mkdir($file_path, 0777, true)) {
                chmod($file_path, 0777);
            }
        }
        $path2 = $atarNumber . '.pdf';
        $name = $atarNumber . '.pdf';
        $mpdf2->Output($path2, "D");
    }

    public function view($id = '')
    {

        $editData = $getuincident = $getuafterincident  = $getObsReassign = FALSE;
        if ($id != '') {

            $decryptUseeId = decryptval($id);
            $editData = $this->atar->getUseeDetails_project(['tr_auto_id' => $decryptUseeId], 'row');
            $getuincident = $this->atar->getUseeImageBefore_project($decryptUseeId);
            $getuafterallincident = $this->atar->getUseeactImageAfter_project($decryptUseeId, '');
            $getObsReassign = $this->atar->getObsDataReassign_project($decryptUseeId);
            $getObsAssign = $this->atar->getObsDataassign_project($decryptUseeId);
            $getObsApproval = $this->atar->getObsDataapproval_project($decryptUseeId);
            $getObsApprovalFinal = $this->atar->getObsDataapprovalfinal_project($decryptUseeId);
            $getObsActionTaken = $this->atar->getObsDataactiontaken_project($decryptUseeId);
        }
// echo print_r($editData);die;
        $data = [
            'view_file' => 'training/training/view_report',
            'current_menu' => 'View Observation',
            'editData' => $editData,
            'getuincidentimage' => $getuincident,
            'getuafterimage' => $getuafterincident,
            'getuafterallimage' => $getuafterallincident,
            'getObsReassign' => $getObsReassign,
            'getObsAssign' => $getObsAssign,
            'getObsApproval' => $getObsApproval,
            'getObsApprovalFinal' => $getObsApprovalFinal,
            'getObsActionTaken' => $getObsActionTaken,

        ];

        $this->template->load_common_template($data);
    }


    
    public function addAttendance($id = '') {

        $decryptUseeId = decryptval($id);

        $getEmployeedatas = $editData = $TrainerName = $sessDataemp = $sessDatauser = [];
   
        $editData = $this->atar->getUseeDetails_project(['tr_auto_id' => $decryptUseeId], 'row');

        $empOptns['where'] = [
         'traemp.fk_tr_main_auto_id' => $decryptUseeId
       ];

       $empOptns['join'] = [
       'login_details AS LD' => ['LD.LOGIN_ID = traemp.fk_tr_emp_id', 'LEFT'],
       'employee_management AS Attendemp' => ['Attendemp.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],
       ];


       $empOptns['return_type'] = 'result';
       $getEmployeedatas = $this->common_model->getAlldata('tr_emp_visitor_details as traemp', ['traemp.*,Attendemp.EMP_NAME,Attendemp.EMP_ID,FN_GET_DESIGNATION_NAME(Attendemp.EMP_DESIGNATION_ID) as design_name,FN_COMP_NAME(Attendemp.EMP_COMP_ID)'], $empOptns);

       // echo $this->db->last_query();
    //    echo '<pre>';print_r($editData);exit;

       $sessData = $this->session->emp_details;
       $sessDatauser = $this->session->userinfo;

       $data = array(
           'view_file' => 'training/training/attendance_form',
           'current_menu' => 'Add Attendance',
           'getEmployeedatas' => $getEmployeedatas,
           'editData' => $editData,
           'sessDataemp' => $sessData,
           'sessDatauser' => $sessDatauser,
       );

       $this->template->load_common_template($data);
   }
    public function AssignEmployeeView($id = '') {

        $decryptUseeId = decryptval($id);

        $getEmployeedatas = $editData = $TrainerName = $sessDataemp = $sessDatauser = [];
   
        $editData = $this->atar->getUseeDetails_project(['tr_auto_id' => $decryptUseeId], 'row');

        $empOptns['where'] = [
         'traemp.fk_tr_main_auto_id' => $decryptUseeId
       ];

       $empOptns['join'] = [
       'login_details AS LD' => ['LD.LOGIN_ID = traemp.fk_tr_emp_id', 'LEFT'],
       'employee_management AS Attendemp' => ['Attendemp.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],
       ];


       $empOptns['return_type'] = 'result';
       $getEmployeedatas = $this->common_model->getAlldata('tr_emp_visitor_details as traemp', ['traemp.*,Attendemp.EMP_NAME,Attendemp.EMP_ID,FN_GET_DESIGNATION_NAME(Attendemp.EMP_DESIGNATION_ID) as design_name,FN_COMP_NAME(Attendemp.EMP_COMP_ID)'], $empOptns);

       // echo $this->db->last_query();
    //    echo '<pre>';print_r($editData);exit;

       $sessData = $this->session->emp_details;
       $sessDatauser = $this->session->userinfo;

       $data = array(
           'view_file' => 'training/training/emp_vist_list_form',
           'current_menu' => 'Add Attendance',
           'getEmployeedatas' => $getEmployeedatas,
           'editData' => $editData,
           'ajaxurl' => 'training/exam/AssignEmploeeList/' . $decryptUseeId,
       );
   $this->template->load_common_template($data);
   }


   public function AssignEmploeeList($id)
   {
       global $trPermission;
       $did = decryptval($id);
       $getdashdata = $this->input->get();

       $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
       $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
       $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
       $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

       $userid = getCurrentUserid();


       $table = TR_VISIT_DETAILS . ' as tv';
       $column_order = array(
           null,
           'h.tr_uni_id',
           'FN_COMP_NAME(tr_comp_id)',
           'FN_AREA_NAME(h.tr_area_id)',
           'FN_BUILD_NAME(h.tr_building_id)',
           'FN_PROJECT_NAME(h.tr_project_id)',
           'FN_GET_DEPARTMENT_NAME(h.tr_dept_id)',
           "DATE_FORMAT(tr_report_datetime,'%d-%m-%Y %H:%i:%s')",          
           'FN_OBS_STATUS(tr_app_status)',
           null
       );

       $column_search = array(
           'h.tr_uni_id',
           'FN_COMP_NAME(tr_comp_id)',
           'FN_AREA_NAME(h.tr_area_id)',
           'FN_BUILD_NAME(h.tr_building_id)',
           'FN_PROJECT_NAME(h.tr_project_id)',
           'FN_GET_DEPARTMENT_NAME(h.tr_dept_id)',  
           "DATE_FORMAT(tr_report_datetime,'%d-%m-%Y %H:%i:%s')",           
           'FN_OBS_STATUS(tr_app_status)'
       );

       $order = array('tv.tr_ev_auto_id' => 'desc');

       /////Filter
       $mappedData = [];
       $request = $this->input->post();


       $searchextra = postData($request, 'searchextra');
       if ($searchextra != FALSE && count($searchextra) > 0) {
           foreach ($searchextra as $search) {
               $sName = postData($search, 'name');
               $sValue = postData($search, 'value');
               $mappedData[$sName] = $sValue;
           }
       }


       if ($mappedData != FALSE && count($mappedData) > 0) {

           $company_id = postData($mappedData, 'company_id');
           $area_id = postData($mappedData, 'area_id');
           $building_id = postData($mappedData, 'building_id');
           $department_id = postData($mappedData, 'department_id');
           $project_id = postData($mappedData, 'project_id');
           $emp_name = postData($mappedData, 'emp_name');
           $hse_cat = postData($mappedData, 'hse_cat');
           $tr_type_id = postData($mappedData, 'tr_type');
           $risk_id = postData($mappedData, 'risk_id');
           $startdate = postData($mappedData, 'start_date');
           $enddate = postData($mappedData, 'end_date');
           $searchStatus = postData($mappedData, 'NotifyStatus');
           $searchMainStatus = postData($mappedData, 'mainStatus');

           $options['where_new'] = [];
           $where = [];
           if (isset($getdashdata['Month']) && $getdashdata['Month'] != 'null' && $getdashdata['Month'] != '') {
               $options['where_new']['MONTHNAME(h.CREATED_ON)'] = $getdashdata['Month'];
           }
           if ($company_id > 0) {
               $options['where_new']['h.tr_comp_id'] = $company_id;
           }
           if ($area_id > 0) {
               $options['where_new']['h.tr_area_id'] = $area_id;
           }
           if ($building_id > 0) {
               $options['where_new']['h.tr_building_id'] = $building_id;
           }
           if ($department_id > 0) {
               $options['where_new']['h.tr_dept_id'] = $department_id;
           }
           if ($project_id > 0) {
               $options['where_new']['h.tr_project_id'] = $project_id;
           }
           if ($hse_cat > 0) {
               $options['where_new']['h.tr_cat_id'] = $hse_cat;
           }
           if ($tr_type_id > 0) {
               $options['where_new']['h.tr_type_id'] = $tr_type_id;
           }
           if ($risk_id > 0) {
               $options['where_new']['h.tr_risk_id'] = $risk_id;
           }
           if ($emp_name > 0) {
               $options['where_new']['h.tr_assigner_id'] = $emp_name;
           }
           if ($searchStatus >= 0) {
               $options['where_new']["h.tr_app_status"] = $searchStatus;
           }

      

           if ($startdate != '') {
               $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

               $options['where_new']['h.CREATED_ON >='] = $thstartdate;
           }
           if ($enddate != '') {
               $enddate = date('Y-m-d', strtotime($enddate));
               $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
           }
       }

       ///////////////////////////filter end


       if (in_array($user_type, $trPermission['view_supadmin'])) {
           $options['where_new']['tr_status'] =  'Y';
       } elseif (in_array($user_type, $trPermission['view_ad'])) {
           $options['where_new']['tr_status'] =  'Y';
           $options['where_new']['tr_comp_id'] =  $user_clid;
       } elseif (in_array($user_type, $trPermission['view_assigner'])) {
           $options['where_new']['tr_status'] =  'Y';
           // $options['where_new']['tr_comp_id'] =  $user_clid;
       } elseif (in_array($user_type, $trPermission['fix'])) {
           $options['orwhere_new']['tr_assigner_id'] =  $userid;
           $options['where_new']['tr_status'] =  'Y';
           $options['orwhere_new']['tr_reporter_id'] =  $userid;
       } elseif (in_array($user_type, $trPermission['approve'])) {
           $options['where_new']['tr_status'] =  'Y';
           //$options['where_new']['tr_comp_id'] =  $user_clid;
       } elseif (in_array($user_type, $trPermission['approve_final'])) {
           $options['where_new']['tr_status'] =  'Y';
           // $options['where_new']['tr_comp_id'] =  $user_clid;
       } else {
           $options['where_new']['tr_status'] =  'Y';
           $options['where_new']['tr_reporter_id'] =  $userid;
       }



       $where = [
        'tv.fk_tr_main_auto_id' => $did,       
       ];

       // print_r( $where);exit;

       $options['select'] = [        
           'h.tr_auto_id',
           'h.tr_uni_id',      
           'h.tr_app_status',        
           'h.tr_reporter_id',
           'FN_COMP_NAME(tr_comp_id) as comp_name',
           'FN_AREA_NAME(tr_area_id) as area_name',
           'FN_BUILD_NAME(tr_building_id) as building_name',
           'FN_GET_DEPARTMENT_NAME(tr_dept_id) as dep_name',
           'FN_PROJECT_NAME(h.tr_project_id) as proj_name',        
         
           'h.tr_report_datetime',
           'FN_OBS_STATUS(h.tr_app_status) as status_Name'
       ];


       $options['join'][TR_FLOW_SEE . ' as h'] = ['h.tr_auto_id = tv.fk_tr_main_auto_id', 'left'];
       $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
       // echo $this->db->last_query();
       // exit;

       echo '<pre>'; print_r($listUsee); die;
       $finalDatas = [];
       $i = 0;

       if (isset($listUsee) && !empty($listUsee)) {
           foreach ($listUsee as $ltKey => $ltVal) {
               $i++;
               $action = $tr_risk = $tr_type = '';
               $id = encryptval($ltVal->tr_auto_id);

               $stat = $ltVal->tr_app_status;
               $tr_reporter_id = $ltVal->tr_reporter_id;
               if (((in_array($user_type, $trPermission['view_supadmin'])) || (in_array($user_type, $trPermission['view_ad']) || $userid == $tr_reporter_id)) && ($stat == 1 || $stat == 0)) {

                   $action .= " " . anchor('training/training/addHmp/' . $id, '<i class="fa fa-edit"></i>', array('class' => '', 'title' => 'Edit'));
               }
               if (in_array($user_type, $trPermission['view_supadmin']) || (in_array($user_type, $trPermission['view_ad']))) {

                   $action .= "  " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deletetraining', 'title' => 'Delete', 'delid' => $id));
               }

               $action .= " " . anchor('training/training/view/' . $id, '<i class="fa fa-eye"></i>', array('title' => 'view'));
               $action .= " " . anchor('training/training/addAttendance/' . $id, '<i class="fas fa-id-card" aria-hidden="true"></i>', array('class' => '', 'title' => 'Mark Attendance'));

               $action .= " " . anchor('training/training/pdf/' . $id, '<i class="fas fa-file-pdf" aria-hidden="true"></i>', array('class' => '', 'title' => 'PDF', 'target' => '_blank'));
               $action .= " " . anchor('training/exam/AssignEmployeeView/' . $id, '<i class="fa fa-user"></i>', array('title' => 'Assigned List'));

               // $target = $ltVal->tr_assigner_target_date;

               // if (date('d-m-Y', strtotime($target)) == '01-01-1970') {
               //     $tar = "-";
               // } else {
               //     $tar = date('d-m-Y', strtotime($target));
               // }

           
               $rows = [];
               $rows[] = $ltVal->tr_uni_id;
               $rows[] = ucfirst($ltVal->comp_name);
               $rows[] = ucfirst($ltVal->area_name);
               $rows[] = ucfirst($ltVal->building_name);
               $rows[] = ucfirst($ltVal->dep_name);
               $rows[] = ucfirst($ltVal->proj_name);                     
               // $rows[] = !empty($ltVal->Hod) ? ucfirst($ltVal->Hod) : '-';
               // $rows[] = $tar;
               $rows[] = date('d-m-Y H:i:s', strtotime($ltVal->tr_report_datetime));
               $rows[] = $ltVal->status_Name;
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



   public function saveAttendance($id = '') {

    // echo '<pre>'; print_r($_POST);exit;
    $did = decryptval($id);

    $inspDatas = $this->input->post('inspec');
    $inspecCheckDet = $this->input->post('nom');

    $trainer_login_id = postData($inspDatas, 'trainer_login_id');

if($inspDatas['attendance_method'] == 1){

    foreach ($inspecCheckDet as $daKey => $daVal) {

        $inspeAttendance = postData($daVal, 'attendance');
        $NomEmpAutoId = postData($daVal, 'training_auto_id');
        
        if($inspeAttendance == 2){
            $SubtypData = [
                'attendance_submit_id' => $trainer_login_id,
                'attendance_submit_date' => date('Y-m-d'),
                'tr_attendence_status' => $inspeAttendance,
            ];

            $this->common_model->updateData('tr_emp_visitor_details', $SubtypData, ['tr_ev_auto_id' => $NomEmpAutoId]);
        }else{
            $SubtypData = [
                'attendance_submit_id' => $trainer_login_id,
                'attendance_submit_date' => date('Y-m-d'),
                'tr_attendence_status' => $inspeAttendance
            ];

            $this->common_model->updateData('tr_emp_visitor_details', $SubtypData, ['tr_ev_auto_id' => $NomEmpAutoId]);
       
        }

        $SubtypDataatt = [
                'img_attendance_submit_method' => '1',
        ];

        $this->common_model->updateData(TR_FLOW_SEE, $SubtypDataatt, ['tr_auto_id' => $did]);

    }
    
}else if($inspDatas['attendance_method'] == 2){

    if (isset($_FILES['attendance_file']) && !empty($_FILES['attendance_file']['name'])) {
       
        $uploadPath = 'assets/images/modules/training/attendence/'; 
        $uploadedFile = uploadImage('attendance_file', $uploadPath);  // FIXED: Pass the string, not $_FILES array
    
        if ($uploadedFile !== false) {
          
            $updateUploadActData = [
                'img_attendance_submit_id' => $_SESSION['userinfo']->LOGIN_ID,
                'img_attendance_submit_date' => date('Y-m-d'),
                'tr_filename' => basename($uploadedFile),  // Get file name correctly
                'tr_filetype' => mime_content_type(FCPATH . $uploadedFile),  // Get file type
                'tr_file_ext' => pathinfo($uploadedFile, PATHINFO_EXTENSION), // Get extension
                'tr_file_size' => filesize(FCPATH . $uploadedFile),  // Get file size
                'tr_file_path' => $uploadedFile,
                'img_attendance_submit_method' => 2
            ];
    
            $this->common_model->updateData(TR_FLOW_SEE, $updateUploadActData, ['tr_auto_id' => $did]);

              $attenStatus = [
                'attendance_method' => '2',
               ];

               $this->common_model->updateData('tr_emp_visitor_details', $attenStatus, ['fk_tr_main_auto_id' => $did]);

        }
    }
   
}
    $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Attendance Updated Successfully</div>');

    redirect('training/training/trainingInfo');

}


    public function deleteData()
    {
        $deleteId = $this->input->post('delid');

        $updtDelete = $this->common_model->updateData(OBS_FLOW_SEE, ['obs_status' => 'N'], ['obs_auto_id' => decryptval($deleteId)]);
        $updtDeleteImg = $this->common_model->updateData(OBS_IMG_SEE, ['obs_attach_status' => 'N'], ['fk_obs_main_id' => decryptval($deleteId)]);


        if ($updtDelete) {

            $retData = [
                'status' => true,
                'msgs' => 'Observation has been deleted successfully'
            ];
        } else {



            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Observation! Try Again later.'
            ];
        }
        echo json_encode($retData);
        exit();
    }
}
