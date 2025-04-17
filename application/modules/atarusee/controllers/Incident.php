<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Spatie\SimpleExcel\SimpleExcelWriter as SimpleExcelWriter;

class Incident extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        isLogin();
        $this->load->model('atarusee/atarusee_model', 'atar');
        $this->load->helper('obs_helper');
    }

    public function incidentInfo()
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
            'pageTitle' => 'Observation Management',
            'view_file' => 'atarusee/incident/list_form',
            'site_title' => 'Observation Management',
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
            'ajaxurl' => 'atarusee/incident/listIncident?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&hse_cat=' . $hse_cat . '&risk_id=' . $risk_id . '&obs_type=' . $obs_type . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date . '&Month=' . $Month . '&emp_id=' . $emp_id,
        ];
        $this->template->load_table_exp_template($data);
    }
    public function listIncident()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list, $status_main;
        $getdashdata = $this->input->get();

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


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
            $obs_type_id = postData($mappedData, 'obs_type');
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
            if ($searchStatus >= 0) {
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
            'h.obs_reporter_id',
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

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        // echo $this->db->last_query();
        // exit;
        $finalDatas = [];
        $i = 0;

        if (isset($listUsee) && !empty($listUsee)) {
            foreach ($listUsee as $ltKey => $ltVal) {
                $i++;
                $action = $obs_risk = $obs_type = '';
                $id = encryptval($ltVal->obs_auto_id);

                $stat = $ltVal->obs_app_status;
                $obs_reporter_id = $ltVal->obs_reporter_id;
                if (((in_array($user_type, $obsPermission['view_supadmin'])) || (in_array($user_type, $obsPermission['view_ad']) || $userid == $obs_reporter_id)) && ($stat == 1 || $stat == 0)) {

                    $action .= " " . anchor('atarusee/incident/addHmp/' . $id, '<i class="fa fa-edit"></i>', array('class' => '', 'title' => 'Edit'));
                }
                if (in_array($user_type, $obsPermission['view_supadmin']) || (in_array($user_type, $obsPermission['view_ad']))) {

                    $action .= "  " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteIncident', 'title' => 'Delete', 'delid' => $id));
                }

                $action .= " " . anchor('atarusee/incident/view/' . $id, '<i class="fa fa-eye"></i>', array('title' => 'view'));
                $action .= " " . anchor('atarusee/incident/pdf/' . $id, '<i class="fas fa-file-pdf" aria-hidden="true"></i>', array('class' => '', 'title' => 'PDF', 'target' => '_blank'));

                $target = $ltVal->obs_assigner_target_date;

                if (date('d-m-Y', strtotime($target)) == '01-01-1970') {
                    $tar = "-";
                } else {
                    $tar = date('d-m-Y', strtotime($target));
                }

                $obs_risk_id = isset($ltVal->obs_risk_id) ? $ltVal->obs_risk_id : '';
                $obs_type_id = isset($ltVal->obs_type_id) ? $ltVal->obs_type_id : '';
                if (isset($risk_rating_batch[$obs_risk_id])) {
                    $obs_risk_data = $risk_rating_batch[$obs_risk_id];
                    $obs_risk = '<label class="btn btn-xs bg-' . $obs_risk_data['class'] . '">' . $obs_risk_data['label'] . '</label>';
                } else {
                    $obs_risk = '-';
                }

                if ($obs_type_id) {
                    $obs_type = $obs_type_list[$obs_type_id];
                }
                $rows = [];
                $rows[] = $ltVal->obs_id;
                $rows[] = ucfirst($ltVal->comp_name);
                $rows[] = ucfirst($ltVal->area_name);
                $rows[] = ucfirst($ltVal->building_name);
                $rows[] = ucfirst($ltVal->dep_name);
                $rows[] = ucfirst($ltVal->proj_name);
                $rows[] = ucfirst($ltVal->hse_cat_name);
                $rows[] = $obs_type;
                $rows[] = $obs_risk;
                $rows[] = !empty($ltVal->Hod) ? ucfirst($ltVal->Hod) : '-';
                $rows[] = $tar;
                $rows[] = date('d-m-Y H:i:s', strtotime($ltVal->obs_report_datetime));
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
        $html = $this->load->view('atarusee/incident/export_pdf', $data, true);
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


        $getUseeActdatas = $getAllBfimage = [];

        if ($did != '') {
            $getUseeActdatas = $this->atar->getUseeDetails_project(['obs_auto_id' => $did], 'row');
            $getAllBfimage = $this->getBeforeImage($did);
        }
        $data = array(
            'view_file' => 'atarusee/incident/add_form',
            'current_menu' => 'Add Observation',
            'getProgramdatas' => $getUseeActdatas,
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            "getAllBfimage" => $getAllBfimage,
            'hsecatDetails' => $drophsecat,
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
        $useeafterfixDatas = $this->input->post("useeafterfix");
        $ObsDatas = $this->input->post('pro');
        $action_type = $this->input->post('action_type');
        $risk_log_id = postData($ObsDatas, 'risk_log_id');
        echo "<pre>";
        print_r($useeafterfixDatas);
        exit;
        if ($action_type) {
            $this->form_validation->set_rules('pro[obs_owner_id]', 'Owner', 'required');
            $this->form_validation->set_rules('pro[obs_owner_eng]', 'Owner Engineering Name', 'required|trim');
            $this->form_validation->set_rules('pro[obs_epc_id]', 'EPC', 'required|trim');
            $this->form_validation->set_rules('pro[obs_comp_id]', 'Company', 'required|trim');
            $this->form_validation->set_rules('pro[obs_area_id]', 'Area', 'required|trim');
            $this->form_validation->set_rules('pro[obs_building_id]', 'Building', 'required|trim');
            $this->form_validation->set_rules('pro[obs_dept_id]', 'Department', 'required|trim');
            $this->form_validation->set_rules('pro[obs_project_id]', 'Project', 'required|trim');
            $this->form_validation->set_rules('pro[obs_cat_id]', 'Observation Category', 'required|trim');
            $this->form_validation->set_rules('pro[obs_type_id]', 'Observation Type', 'required|trim');
            $this->form_validation->set_rules('pro[obs_date]', 'Observation Date', 'required');
            $this->form_validation->set_rules('pro[obs_desc]', 'Observation Description', 'required');
        } else {
            $this->form_validation->set_rules('pro[obs_comp_id]', 'Company', 'required|trim');
            $this->form_validation->set_rules('pro[obs_area_id]', 'Area', 'required|trim');
            $this->form_validation->set_rules('pro[obs_building_id]', 'Building', 'required|trim');
            $this->form_validation->set_rules('pro[obs_project_id]', 'Project', 'required|trim');
        }



        if ($this->form_validation->run()) {

            $date_time = postData($ObsDatas, 'obs_report_datetime');
            $obs_type_id = postData($ObsDatas, 'obs_type_id');
            $prev_obs_app_status = postData($ObsDatas, 'obs_app_status');
            $obs_app_status = $action_type;
            if ($obs_type_id == SAFE_OBS && $action_type) {
                $obs_app_status = HSSE_APPR;
            }

            $obs_date = !empty(postData($ObsDatas, 'obs_date'))
                ? date('Y-m-d H:i:s', strtotime(postData($ObsDatas, 'obs_date')))
                : NULL;
            $prodatas = [
                'obs_reporter_id' => postData($ObsDatas, 'obs_reporter_id'),
                'obs_reporter_type_id' => postData($ObsDatas, 'obs_reporter_role_id'),
                'obs_reporter_desg_id' => postData($ObsDatas, 'user_desgination_id'),

                'obs_owner_id' => postData($ObsDatas, 'obs_owner_id'),
                'obs_owner_eng' => postData($ObsDatas, 'obs_owner_eng'),
                'obs_epc_id' => postData($ObsDatas, 'obs_epc_id'),
                'obs_comp_id' => postData($ObsDatas, 'obs_comp_id'),
                'obs_area_id' => postData($ObsDatas, 'obs_area_id'),
                'obs_building_id' => postData($ObsDatas, 'obs_building_id'),
                'obs_dept_id' => postData($ObsDatas, 'obs_dept_id'),
                'obs_project_id' => postData($ObsDatas, 'obs_project_id'),

                'obs_cat_id' => postData($ObsDatas, 'obs_cat_id'),
                'obs_date' => $obs_date,
                'obs_type_id' => postData($ObsDatas, 'obs_type_id'),
                'obs_risk_id' => postData($ObsDatas, 'obs_risk_id'),
                'obs_desc' => postData($ObsDatas, 'obs_desc'),
                'obs_app_status' => $obs_app_status
            ];



            if ($did != '') {
                $upWhere = [
                    'obs_auto_id' => $did
                ];
                $updtProfile = $this->common_model->updateData(OBS_FLOW_SEE, $prodatas, $upWhere);

                //risk log
                if ($action_type) {
                    if ($risk_log_id) {
                        $upWhere = [
                            'obs_risk_assigned_type_id' => 1,
                            'risk_log_id' => $risk_log_id
                        ];
                    }
                    $risk_log = [
                        'fk_obs_risk_auto_main_id' => $did,
                        'obs_risk_assigned_type_id' => 1,
                        'fk_obs_main_stat' => $obs_app_status,
                        'risk_rating' => postData($ObsDatas, 'obs_risk_id'),
                        'fk_obs_emp_login_id' => postData($ObsDatas, 'obs_reporter_id'),
                        'fk_obs_emp_role_id' => postData($ObsDatas, 'obs_reporter_role_id'),
                        'fk_obs_emp_des_id' => postData($ObsDatas, 'user_desgination_id'),
                    ];
                    if ($risk_log_id) {
                        $this->common_model->updateData(OBS_RISK_LOG, $risk_log, $upWhere);
                    } else {
                        $this->common_model->updateData(OBS_RISK_LOG, $risk_log);
                    }
                }
            } else {
                $projectName = getProjectName(postData($ObsDatas, 'obs_project_id'));
                //$projNameThreeLetters = strtoupper(substr($projectName, 0, 3));
                $projNameThreeLetters = 'AHK';
                $areaShortName = getAreaShortName(postData($ObsDatas, 'obs_area_id'));
                $areaShortTwoLetters = strtoupper(substr($areaShortName, 0, 2));

                $building_block_dirName = getBuildingName(postData($ObsDatas, 'obs_building_id'));
                $build_block_dirFirstLetter = strtoupper(substr($building_block_dirName, 0, 1));

                $getuniqueId = getObsNumber($projNameThreeLetters, $areaShortTwoLetters, $build_block_dirFirstLetter, postData($ObsDatas, 'obs_project_id'));
                $prodatas['obs_id'] = $getuniqueId;
                $prodatas['obs_report_datetime'] = date('Y-m-d H:i:s', strtotime($date_time));
                $updtProfile = $this->common_model->updateData(OBS_FLOW_SEE, $prodatas);
                if ($action_type) {
                    $risk_log = [
                        'fk_obs_risk_auto_main_id' => $updtProfile,
                        'obs_risk_assigned_type_id' => 1,
                        'fk_obs_main_stat' => $obs_app_status,
                        'risk_rating' => postData($ObsDatas, 'obs_risk_id'),
                        'fk_obs_emp_login_id' => postData($ObsDatas, 'obs_reporter_id'),
                        'fk_obs_emp_role_id' => postData($ObsDatas, 'obs_reporter_role_id'),
                        'fk_obs_emp_des_id' => postData($ObsDatas, 'user_desgination_id'),
                    ];
                    $this->common_model->updateData(OBS_RISK_LOG, $risk_log);
                }
            }

            //////get main id
            if ($did != '') {
                $gen_id = postData($ObsDatas, 'obs_main_id');
            } else {
                $gen_id = $getuniqueId;
            }
            $optionMainId["return_type"] = "row";
            $optionMainId["where"] = ["obs_id" => $gen_id];
            $getAutoIddata = $this->common_model->getAlldata(OBS_FLOW_SEE, ["obs_auto_id"], $optionMainId);
            $getAutoId = $getAutoIddata->obs_auto_id;

            ///////          

            //////multiple image start

            $useeafterfixDatas = $this->input->post("useeafterfix");


            if (isset($useeafterfixDatas) && !empty($useeafterfixDatas)) {
                $imgDatasother = [];
                if (isset($_FILES) && !empty($_FILES) && isset($_FILES['useeafterfix']['name']) && !empty($_FILES['useeafterfix']['name'])) {
                    $imgDatasother = uploadMultipleimage(
                        $_FILES,
                        "assets/images/modules/atarusee/images/incident/before/",
                        "useeafterfix"
                    );
                }

                $imgKey = 0;

                foreach ($useeafterfixDatas as $cKey => $cVal) {
                    $insertOthercerti = [
                        "obs_file_type" => 1,
                    ];

                    /////////image upload start

                    if (isset($imgDatasother[$imgKey]) && !empty($imgDatasother[$imgKey])) {
                        $imgUplname = $imgDatasother[$imgKey]["uploadname"];
                        $imgUplpath = $imgDatasother[$imgKey]["uploadpath"];
                        $imgUplext = $imgDatasother[$imgKey]["uploadextension"];
                        $imgUplsize = $imgDatasother[$imgKey]["filesize"];
                        $imgUpltype = $imgDatasother[$imgKey]["uploadtype"];
                        $insertOthercerti["obs_filename"] = $imgUplname;
                        $insertOthercerti["obs_file_path"] = $imgUplpath;
                        $insertOthercerti["obs_file_ext"] = $imgUplext;
                        $insertOthercerti["obs_file_size"] = $imgUplsize;
                        $insertOthercerti["obs_filetype"] = $imgUpltype;
                    } else {
                        $userImgs = $this->input->post("other_user_img");
                        $uplImgdatas = $userImgs[$imgKey];
                    }

                    $imgKey++;
                    if (isset($insertOthercerti["obs_filename"]) && $insertOthercerti["obs_filename"] != "" && $insertOthercerti["obs_filename"] != NULL) {

                        if ($did != "") {
                            $othercertiWhere = [
                                "obs_file_type" => 1,
                            ];
                            $othCompIDedit = postData($cVal, "other_compCerti_ID2");
                            if (isset($othCompIDedit) && !empty($othCompIDedit)) {
                                $othercertiWhere["obs_att_id"] = $othCompIDedit;
                                $othercertiWhere["fk_obs_main_id"] = $getAutoId;
                                $updateOthercert = $this->common_model->updateData(
                                    OBS_IMG_SEE,
                                    $insertOthercerti,
                                    $othercertiWhere
                                );
                            } else {

                                $insertOthercerti["fk_obs_main_id"] = $getAutoId;
                                $updateOthercert = $this->common_model->updateData(
                                    OBS_IMG_SEE,
                                    $insertOthercerti
                                );
                            }
                        } else {
                            $insertOthercerti["fk_obs_main_id"] = $getAutoId;

                            $updateOthercert = $this->common_model->updateData(
                                OBS_IMG_SEE,
                                $insertOthercerti
                            );
                        }
                    }
                }
            }
            //////multiple image end

            if ($updtProfile) {

                if ($did != '') {
                    if ($action_type) {
                        sendObsNotification($getAutoId);
                    }
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Updated</div>');
                } else {
                    if ($action_type) {
                        sendObsNotification($getAutoId);
                        $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Created</div>');
                    } else {
                        $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Drafted</div>');
                    }
                }
                redirect('atarusee/incident/incidentInfo');
            } else {
                if ($did != '') {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Updated</div>');
                } else {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Created</div>');
                }
                redirect('atarusee/incident/incidentInfo');
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
            $editData = $this->atar->getUseeDetails_project(['obs_auto_id' => $decryptUseeId], 'row');
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
        $html2 = $this->load->view('atarusee/incident/obs_pdf', $data, true);
        // echo $html2;
        // exit;
        $mpdf2 = $this->pdf->ptwload();
        $mpdf2->setAutoTopMargin = 'stretch';
        $das = $mpdf2->WriteHTML($html2);
        $currenttime = date('d-m-Y');
        $folder_name = "incident";
        $file_path = "assets/images/modules/atarusee/pdf/" . $folder_name . "/";
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
            $editData = $this->atar->getUseeDetails_project(['obs_auto_id' => $decryptUseeId], 'row');
            $getuincident = $this->atar->getUseeImageBefore_project($decryptUseeId);
            $getuafterallincident = $this->atar->getUseeactImageAfter_project($decryptUseeId, '');
            $getObsReassign = $this->atar->getObsDataReassign_project($decryptUseeId);
            $getObsAssign = $this->atar->getObsDataassign_project($decryptUseeId);
            $getObsApproval = $this->atar->getObsDataapproval_project($decryptUseeId);
            $getObsApprovalFinal = $this->atar->getObsDataapprovalfinal_project($decryptUseeId);
            $getObsActionTaken = $this->atar->getObsDataactiontaken_project($decryptUseeId);
        }

        $data = [
            'view_file' => 'atarusee/incident/view_report',
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

    public function saveStatus($id = '')
    {
        global $cronTo_Supervisor_Escalation_Times;

        $did = $id;


        $statusDatas = $this->input->post('usee');
        $sesData = $_SESSION['user_details'];

        $obs_supervisor_type_id = getCurrentUserGroupId();
        $obs_supervisor_id = getCurrentUserid();

        $unitDatasId = postData($statusDatas, 'obs_assigner_id');

        $optWhr['where'] = [
            'LOGIN_ID' => $unitDatasId
        ];
        $optWhr['return_type'] = 'row';
        $gethoddatas = $this->common_model->getAlldata(USER_LOG, ['*'], $optWhr);
        $hodDatasGroupId = $gethoddatas->USER_TYPE_ID;

        $givenDate = date(
            "Y-m-d",
            strtotime(postData($statusDatas, "obs_assigner_target_date"))
        );
        $currenttime = date("H:i:s");
        $currentdate = date("Y-m-d");

        /*SAME DAY AS NEW TARGET DATE*/
        $timestamp1 = strtotime($givenDate);
        $timestamp2 = strtotime(date("Y-m-d"));

        if ($timestamp1 === $timestamp2) {
            $finalTargetDateTime = $givenDate . " 23:59:00";
        } else {

            $finalTargetDateTime = $givenDate . " " . $currenttime;
        }

        /*SAME DAY AS NEW TARGET DATE*/

        $cronDate = date("Y-m-d");

        ///target date with current time escalation
        ///////atar cron count
        $optWhrcnt['where'] = [
            'obs_auto_id' => $did
        ];
        $optWhr['return_type'] = 'row';
        $getStatdatascronsent = $this->common_model->getAlldata(OBS_FLOW_SEE, ['*'], $optWhrcnt);


        $cron_sent_times = $getStatdatascronsent[0]->cron_sent_times;

        ////////times of cron      

        if ($cron_sent_times < $cronTo_Supervisor_Escalation_Times) {
            $sentCnt = $cron_sent_times + 1;
        } else {
            $sentCnt = $cron_sent_times;
        }
        $stat = '4';
        if ($sentCnt == 1) {
            $stat = postData($statusDatas, 'obs_app_statusS');
        }

        /////end      
        $useedatas = [
            'obs_risk_id' => postData($statusDatas, 'obs_risk_id'),
            'obs_supervisor_id' => $obs_supervisor_id,
            'obs_supervisor_type_id' => $obs_supervisor_type_id,
            'obs_supervisor_risk_id' => postData($statusDatas, 'obs_risk_id'),
            'obs_supervisor_desc' => postData($statusDatas, 'assigner_desc'),
            'obs_supervisor_date' => $currentdate,

            'obs_assigner_target_date' => date('Y-m-d', strtotime(postData($statusDatas, 'obs_assigner_target_date'))),
            'obs_assigner_type_id' => $hodDatasGroupId,
            'obs_assigner_id' => $unitDatasId,

            'obs_app_status' => (int)$stat,

            "obs_final_tar_date_time" => $finalTargetDateTime,
            "cron_sent_date" => $cronDate,
            "cron_sent_times" => $sentCnt,
        ];

        if ($did != '') {
            $upWhere = [
                'obs_auto_id' => $did
            ];
            $updtProfile = $this->common_model->updateData(OBS_FLOW_SEE, $useedatas, $upWhere);
        }

        if ($updtProfile) {
            //escalation

            $givenDate = date("Y-m-d", strtotime(postData($statusDatas, "obs_assigner_target_date")));
            $givenDateTime = new DateTime($givenDate);
            $currentDateTime = new DateTime();
            $givenDateTime->setTime($currentDateTime->format("H"), $currentDateTime->format("i"), $currentDateTime->format("s"));
            $finalTargetDateTime = $givenDateTime->format("Y-m-d H:i:s");
            $prevStatus = postData($statusDatas, "obs_app_status");

            if ($prevStatus == 8) {
                $atar_assigned_type_id = 2;
            } else {
                $atar_assigned_type_id = 1;
            }


            $useeCAEscalation = [
                "fk_obs_auto_main_id" => $did,
                "fk_obs_main_stat" => $prevStatus,
                "fk_obs_supervisor_login_id" => $obs_supervisor_id,
                "fk_obs_supervisor_role_id" => $obs_supervisor_type_id,
                "fk_obs_supervisor_des_id" => postData($sesData, 'DESIGNATIONID'),
                "fk_obs_supervisor_desc" => postData($statusDatas, "assigner_desc"),

                "fk_new_assigner_type_id" => $hodDatasGroupId,
                "fk_new_assigner_id" => $unitDatasId,
                "fk_obs_new_target_date" => date("Y-m-d", strtotime(postData($statusDatas, "obs_assigner_target_date"))),
                "fk_obs_new_target_datetime" => $finalTargetDateTime,
                "obs_assigned_type_id" => $atar_assigned_type_id,
            ];

            $updtPr = $this->common_model->updateData(OBS_ESL_SEE, $useeCAEscalation);


            //Risk LOg

            $risk_log = [
                'fk_obs_risk_auto_main_id' => $did,
                'obs_risk_assigned_type_id' => $atar_assigned_type_id + 1,
                'fk_obs_main_stat' => $prevStatus,
                'risk_rating' => postData($statusDatas, 'obs_risk_id'),
                'fk_obs_emp_login_id' => $obs_supervisor_id,
                'fk_obs_emp_role_id' => $obs_supervisor_type_id,
                'fk_obs_emp_des_id' => postData($sesData, 'DESIGNATIONID'),
            ];

            $this->common_model->updateData(OBS_RISK_LOG, $risk_log);

            /////end
            ///status 4 notification
            $optWhr['where'] = [
                'obs_auto_id' => $did
            ];
            $optWhr['return_type'] = 'row';
            $getStatdatas = $this->common_model->getAlldata(OBS_FLOW_SEE, ['*'], $optWhr);
            $StatId = $getStatdatas->obs_app_status;
            if ($StatId == 4) {
                sendObsNotification($did);
            }
            //////
            if ($did != '') {
                $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Updated</div>');
            } else {
                $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Created</div>');
            }
            redirect('atarusee/incident/incidentInfo');
        } else {
            if ($did != '') {
                $this->session->set_flashdata('incidentflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Updated</div>');
            } else {
                $this->session->set_flashdata('incidentflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Created</div>');
            }
            redirect('atarusee/incident/incidentInfo');
        }
    }
    public function saveCADetails($id = '')
    {

        $did = $id;
        $sesData = $_SESSION['user_details'];
        $afterFixDatas = $this->input->post('useeafterfix');
        $this->form_validation->set_rules('useeafterfix[obs_assigner_desc]', 'Description', 'required|trim');

        if ($this->form_validation->run()) {
            $useeCA = [
                'obs_assigner_ca_submitted_date' => date('Y-m-d'),
                'obs_app_status' => 5,
                'obs_assigner_desc' => postData($afterFixDatas, 'obs_assigner_desc')
            ];

            $current_role = $_SESSION['role_id'];
            //action taken log 
            $useeCAlog = [
                'fk_obs_auto_main_id' => $did,
                'fk_obs_assigner_login_id' => $_SESSION['userinfo']->LOGIN_ID,
                'fk_obs_assigner_role_id' => $current_role,
                'fk_obs_assigner_des_id' => postData($sesData, 'DESIGNATIONID'),
                'fk_obs_ca_desc' => postData($afterFixDatas, 'obs_assigner_desc'),
                'fk_obs_ca_datetime' => date('Y-m-d H:i:s'),
            ];
            $ca_log_id = $this->common_model->updateData(OBS_ACT_LOG, $useeCAlog);

            if (isset($_FILES) && !empty($_FILES)) {
                $imgUActDatasImage = uploadMultipleimage($_FILES, 'assets/images/modules/atarusee/images/incident/after/', 'useeafterfix');
            }

            if ($imgUActDatasImage != FALSE && count($imgUActDatasImage) > 0) {
                foreach ($imgUActDatasImage as $fileAct) {
                    $updateUploadActData = [
                        'fk_obs_main_id' => $did,
                        'obs_file_type' => '3',
                        'fk_ca_log_id' => $ca_log_id,
                        'obs_assigner_id' => $_SESSION['userinfo']->LOGIN_ID,
                        'obs_filename' => postData($fileAct, 'uploadname'),
                        'obs_filetype' => postData($fileAct, 'uploadtype'),
                        'obs_file_ext' => postData($fileAct, 'uploadextension'),
                        'obs_file_size' => postData($fileAct, 'filesize'),
                        'obs_file_path' => postData($fileAct, 'uploadpath')
                    ];

                    $this->common_model->updateData(OBS_IMG_SEE, $updateUploadActData);
                }
            }
            if ($did != '') {
                $upWhere = [
                    'obs_auto_id' => $did
                ];
                $updtProfile = $this->common_model->updateData(OBS_FLOW_SEE, $useeCA, $upWhere);
            } else {
                $updtProfile = $this->common_model->updateData(OBS_FLOW_SEE, $useeCA);
            }

            if ($updtProfile) {
                sendObsNotification($did);
                if ($did != '') {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Updated</div>');
                } else {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Created</div>');
                }
                redirect('atarusee/incident/incidentInfo');
            } else {
                if ($did != '') {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Updated</div>');
                } else {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Created</div>');
                }
                redirect('atarusee/incident/incidentInfo');
            }
        } else {
            $this->incidentInfo($id);
        }
    }
    public function saveApproveDetails($id = '')
    {

        $did = $id;

        $afterFixapproveDatas = $this->input->post('useeapprove');
        $approve = postData($afterFixapproveDatas, 'approve');
        $reject = postData($afterFixapproveDatas, 'reject');
        $this->form_validation->set_rules('useeapprove[obs_hsse_es_type_id]', 'Reason', 'required|trim');

        if ($this->form_validation->run()) {
            //     print_r($afterFixDatas);exit;
            if ($approve == "Approve") {
                $useeApprove = [
                    'obs_app_status' => 9,
                    'obs_hsse_es_id' => postData($afterFixapproveDatas, "fk_obs_so_login_id"),
                    'obs_hsse_es_type_id' => postData($afterFixapproveDatas, "fk_obs_so_role_id"),
                    'obs_hsse_es_appr_rej_desc' => postData($afterFixapproveDatas, 'obs_hsse_es_type_id')
                ];
                $useeApproveNew = [
                    "fk_obs_app_id" => $did,

                    "approval_type_id" => 1,

                    "fk_assigner_login_id" => postData($afterFixapproveDatas, "aisi_login_id"),
                    "fk_assigner_role_id" => postData($afterFixapproveDatas, "aisi_role_id"),
                    "fk_assigner_des_id" => postData($afterFixapproveDatas, "aisi_des_id"),
                    "assigner_desc" => postData($afterFixapproveDatas, "aisi_desc"),

                    "approver_login_id" => postData($afterFixapproveDatas, "fk_obs_so_login_id"),
                    "approver_role_id" => postData($afterFixapproveDatas, "fk_obs_so_role_id"),
                    "approver_des_id" => postData($afterFixapproveDatas, "fk_obs_so_des_id"),
                    "fk_approver_app_desc" => postData($afterFixapproveDatas, "obs_hsse_es_type_id"),


                    "approver_report_dt" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "aisi_report_dt"))),
                    "fk_assigner_datetime" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "fk_obs_so_datetime"))),
                    "assigned_target_date" => date("Y-m-d", strtotime(postData($afterFixapproveDatas, "aisi_target_date"))),
                ];
            } elseif ($reject == "Reject") {
                $useeApprove = [
                    'obs_app_status' => 7,
                    'obs_hsse_es_id' => postData($afterFixapproveDatas, "fk_obs_so_login_id"),
                    'obs_hsse_es_type_id' => postData($afterFixapproveDatas, "fk_obs_so_role_id"),
                    'obs_hsse_es_appr_rej_desc' => postData($afterFixapproveDatas, 'obs_hsse_es_type_id')
                ];
                $useeApproveNew = [
                    "fk_obs_app_id" => $did,

                    "approval_type_id" => 2,
                    "fk_assigner_login_id" => postData($afterFixapproveDatas, "aisi_login_id"),
                    "fk_assigner_role_id" => postData($afterFixapproveDatas, "aisi_role_id"),
                    "fk_assigner_des_id" => postData($afterFixapproveDatas, "aisi_des_id"),
                    "assigner_desc" => postData($afterFixapproveDatas, "aisi_desc"),


                    "approver_login_id" => postData($afterFixapproveDatas, "fk_obs_so_login_id"),
                    "approver_role_id" => postData($afterFixapproveDatas, "fk_obs_so_role_id"),
                    "approver_des_id" => postData($afterFixapproveDatas, "fk_obs_so_des_id"),
                    "fk_approver_rej_desc" => postData($afterFixapproveDatas, "obs_hsse_es_type_id"),

                    "approver_report_dt" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "aisi_report_dt"))),
                    "fk_assigner_datetime" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "fk_obs_so_datetime"))),
                    "assigned_target_date" => date("Y-m-d", strtotime(postData($afterFixapproveDatas, "aisi_target_date"))),
                ];
            }


            if ($did != '') {
                $upWhere = [
                    'obs_auto_id' => $did
                ];
                $updtProfile = $this->common_model->updateData(OBS_FLOW_SEE, $useeApprove, $upWhere);
                $updtProfiles = $this->common_model->updateData(OBS_APP_ESL_SEE, $useeApproveNew);
            }

            if ($updtProfile) {
                sendObsNotification($did);
                if ($did != '') {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Updated</div>');
                } else {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Created</div>');
                }
                redirect('atarusee/incident/incidentInfo');
            } else {
                if ($did != '') {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Updated</div>');
                } else {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Created</div>');
                }
                redirect('atarusee/incident/incidentInfo');
            }
        } else {
            $this->incidentInfo($id);
        }
    }

    public function saveFinalApproveDetails($id = '')
    {

        $did = $id;

        $afterFixapproveDatas = $this->input->post('useeapprove');
        $approve = postData($afterFixapproveDatas, 'approve');
        $reject = postData($afterFixapproveDatas, 'reject');
        $this->form_validation->set_rules('useeapprove[obs_hsse_type_id]', 'Reason', 'required|trim');

        if ($this->form_validation->run()) {

            if ($approve == "Approve") {
                $useeApprove = [
                    'obs_app_status' => 3,
                    'is_closed' => 1,
                    'closed_date' => date('Y-m-d'),
                    'obs_hsse_id' => postData($afterFixapproveDatas, "fk_obs_so_login_id"),
                    'obs_hsse_type_id' => postData($afterFixapproveDatas, "fk_obs_so_role_id"),
                    'obs_hsse_appr_rej_desc' => postData($afterFixapproveDatas, 'obs_hsse_type_id')
                ];
                $useeApproveNew = [
                    "fk_obs_app_id" => $did,

                    "approval_type_id" => 3,

                    "fk_assigner_login_id" => postData($afterFixapproveDatas, "aisi_login_id"),
                    "fk_assigner_role_id" => postData($afterFixapproveDatas, "aisi_role_id"),
                    "fk_assigner_des_id" => postData($afterFixapproveDatas, "aisi_des_id"),
                    "assigner_desc" => postData($afterFixapproveDatas, "aisi_desc"),


                    "approver_login_id" => postData($afterFixapproveDatas, "fk_obs_so_login_id"),
                    "approver_role_id" => postData($afterFixapproveDatas, "fk_obs_so_role_id"),
                    "approver_des_id" => postData($afterFixapproveDatas, "fk_obs_so_des_id"),
                    "fk_approver_app_desc" => postData($afterFixapproveDatas, "obs_hsse_type_id"),


                    "approver_report_dt" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "aisi_report_dt"))),
                    "fk_assigner_datetime" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "fk_obs_so_datetime"))),
                    "assigned_target_date" => date("Y-m-d", strtotime(postData($afterFixapproveDatas, "aisi_target_date"))),
                ];
            } elseif ($reject == "Reject") {
                $useeApprove = [
                    'obs_app_status' => 10,
                    'obs_hsse_id' => postData($afterFixapproveDatas, "fk_obs_so_login_id"),
                    'obs_hsse_type_id' => postData($afterFixapproveDatas, "fk_obs_so_role_id"),
                    'obs_hsse_appr_rej_desc' => postData($afterFixapproveDatas, 'obs_hsse_type_id')
                ];
                $useeApproveNew = [
                    "fk_obs_app_id" => $did,

                    "approval_type_id" => 4,
                    "fk_assigner_login_id" => postData($afterFixapproveDatas, "aisi_login_id"),
                    "fk_assigner_role_id" => postData($afterFixapproveDatas, "aisi_role_id"),
                    "fk_assigner_des_id" => postData($afterFixapproveDatas, "aisi_des_id"),
                    "assigner_desc" => postData($afterFixapproveDatas, "aisi_desc"),


                    "approver_login_id" => postData($afterFixapproveDatas, "fk_obs_so_login_id"),
                    "approver_role_id" => postData($afterFixapproveDatas, "fk_obs_so_role_id"),
                    "approver_des_id" => postData($afterFixapproveDatas, "fk_obs_so_des_id"),
                    "fk_approver_rej_desc" => postData($afterFixapproveDatas, "obs_hsse_type_id"),

                    "approver_report_dt" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "aisi_report_dt"))),
                    "fk_assigner_datetime" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "fk_obs_so_datetime"))),
                    "assigned_target_date" => date("Y-m-d", strtotime(postData($afterFixapproveDatas, "aisi_target_date"))),
                ];
            }

            //  print_r($useeApprove);exit;
            if ($did != '') {
                $upWhere = [
                    'obs_auto_id' => $did
                ];
                $updtProfile = $this->common_model->updateData(OBS_FLOW_SEE, $useeApprove, $upWhere);
                $updtProfiles = $this->common_model->updateData(OBS_APP_ESL_SEE, $useeApproveNew);
            }

            if ($updtProfile) {
                sendObsNotification($did);
                if ($did != '') {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Updated</div>');
                } else {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Observation has been Created</div>');
                }
                redirect('atarusee/incident/incidentInfo');
            } else {
                if ($did != '') {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Updated</div>');
                } else {
                    $this->session->set_flashdata('incidentflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Observation cannot be Created</div>');
                }
                redirect('atarusee/incident/incidentInfo');
            }
        } else {
            $this->incidentInfo($id);
        }
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
