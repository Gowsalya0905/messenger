<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;
use Spatie\SimpleExcel\SimpleExcelWriter as SimpleExcelWriter;

class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        isLogin();
        $this->load->model('report/report_model', 'report');
        $this->load->helper('obs_helper');
    }
    // Observation Tracker start//
    public function obs_tracker()
    {
        $getdashdata = $this->input->get();
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        global $status_drop, $risk_rating, $obs_type_list;

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
        $fac_injury = !empty($getdashdata['fac_injury']) ? decryptval($getdashdata['fac_injury']) : '';
        $hsecatOptn['where'] = [
            'hse_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['*'], $hsecatOptn);
        $drophsecat = customFormDropDown($getAllhsecat, 'hse_id', 'hse_cat', 'Select HSE Category');

        ///////resp
        $table = EMPL . ' as r';
        $selects = ['LD2.LOGIN_ID ,r.*'];
        $option['join'][LOGIN . ' as LD2'] = ['LD2.USER_REF_ID = r.EMP_AUTO_ID', 'left'];
        $option['return_type'] = 'result';
        if (!is_admin()) {
            $option['where'] = [
                'r.EMP_COMP_ID' => $user_clid
            ];
        }
        $hir_flow_details = $this->common_model->getAlldata($table, $selects, $option);
        $getex_drop = customFormDropDown($hir_flow_details, "LOGIN_ID", "EMP_NAME", "Select Employee");

        //
        $data = [
            'pageTitle' => 'Observation Management',
            'view_file' => 'report/obs_tracker/list_form',
            'site_title' => 'Observation Management',
            'current_menu' => 'Observation List',
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'drophsecat' => $drophsecat,
            'getex_drop' => $getex_drop,
            'status_drop_proj' => $status_drop,
            'getdashdata' => $getdashdata,
            'risk_rating' => $risk_rating,
            'obs_type_list' => $obs_type_list,
            'ajaxurl' => 'report/listobstracker?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&hse_cat=' . $hse_cat . '&risk_id=' . $risk_id . '&obs_type=' . $obs_type . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date . '&Month=' . $Month . '&emp_id=' . $emp_id . '&fac_injury=' . $fac_injury,
        ];
        $this->template->load_table_exp_template($data);
    }
    public function listobstracker()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;
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
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
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
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
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
            $fac_injury = postData($mappedData, 'fac_injury');
            $obs_type_id = postData($mappedData, 'obs_type');
            $risk_id = postData($mappedData, 'risk_id');
            $startdate = postData($mappedData, 'start_date');
            $enddate = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');

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
            if ($fac_injury > 0) {
                $options['where_new']['h.obs_fac_id'] = $fac_injury;
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
            $options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $options['where_new']['h.obs_status'] =  'Y';
            $options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            //$options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            // $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_reporter_id'] =  $userid;
        }

        $options['where_new']['h.obs_app_status !='] = '0';



        // print_r( $where);exit;

        $options['select'] = [
            'h.*',
            'FN_COMP_NAME(obs_comp_id) as comp_name',
            'FN_AREA_NAME(obs_area_id) as area_name',
            'FN_BUILD_NAME(obs_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(obs_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.obs_project_id) as proj_name',
            'FN_HSE_CAT_NAME(h.obs_cat_id) as hse_cat_name',
            'FN_OBS_FAC_NAME(h.obs_fac_id) as hse_inj_name',
            'Hod.EMP_NAME as Hod',
            'EMP1.EMP_NAME as reporter_name',
            'h.obs_report_datetime',
            'FN_GET_DESIGNATION_NAME(h.obs_reporter_desg_id) as reporter_desig',
            'FN_OBS_STATUS(h.obs_app_status) as status_Name'
        ];

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];

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

                if ((in_array($user_type, $obsPermission['view_ad']) || is_admin()) && ($stat == 1 || $stat == 0)) {

                    $action .= " " . anchor('report/Obs_tracker/addHmp/' . $id, '<i class="fa fa-edit"></i>', array('class' => '', 'title' => 'Edit'));
                }
                if (in_array($user_type, $obsPermission['view_ad']) || is_admin()) {

                    $action .= "  " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteIncident', 'title' => 'Delete', 'delid' => $id));
                }


                $action .= " " . anchor('report/Obs_tracker/view/' . $id, '<i class="fa fa-eye"></i>', array('title' => 'view'));
                $action .= " " . anchor('report/Obs_tracker/pdf/' . $id, '<i class="fas fa-file-pdf" aria-hidden="true"></i>', array('class' => '', 'title' => 'PDF', 'target' => '_blank'));

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
                $obs_year = $obs_month = '';
                if ($ltVal->obs_date) {
                    $obs_date_y_m = new DateTime($ltVal->obs_date);
                    $obs_year = $obs_date_y_m->format('Y');
                    $obs_month = $obs_date_y_m->format('M');
                }
                if ($ltVal->is_closed) {
                    $obs_open_closed_sts = '<label class="btn btn-xs bg-success">Closed</label>';
                } else {
                    $obs_open_closed_sts = '<label class="btn btn-xs bg-danger">Open</label>';
                }
                $pending_days = $closure_day = $delay = 0;
                if ($ltVal->is_closed) {
                    $pending_days = 0;
                } else {
                    if (!empty($ltVal->obs_date)) {
                        $obs_date = strtotime($ltVal->obs_date);
                        $current_date = strtotime(date('Y-m-d'));
                        $pending_days = ceil(($current_date - $obs_date) / (60 * 60 * 24));
                    } else {
                        $pending_days = 0;
                    }
                }

                if (!empty($ltVal->obs_date)) {
                    if (!empty($ltVal->obs_assigner_target_date)) {
                        $obs_date = strtotime($ltVal->obs_date);
                        $obs_tar = strtotime($ltVal->obs_assigner_target_date);
                        $closure_day = ceil(($obs_tar - $obs_date) / (60 * 60 * 24));
                    } else {
                        $closure_day = 0;
                    }
                } else {
                    $closure_day  = 0;
                }

                if (!empty($ltVal->obs_assigner_target_date)) {
                    if (!empty($ltVal->closed_date)) {
                        $obs_tar = strtotime($ltVal->obs_assigner_target_date);
                        $closed_date = strtotime($ltVal->closed_date);
                        $delay = ceil(($closed_date - $obs_tar) / (60 * 60 * 24));
                    } else {
                        $delay = 0;
                    }
                } else {
                    $delay  = 0;
                }


                $rows = [];
                $rows[] = $ltVal->obs_id;
                $rows[] = ucfirst($obs_year);
                $rows[] = ucfirst($obs_month);
                $rows[] = !empty($ltVal->obs_date) ? date('d-m-Y', strtotime($ltVal->obs_date)) : '-';
                $rows[] = ucfirst($ltVal->hse_cat_name);
                $rows[] = ucfirst($ltVal->obs_desc);
                $rows[] = $obs_risk;
                $rows[] = ucfirst($ltVal->obs_supervisor_desc);
                $rows[] = $obs_type;
                $rows[] = $ltVal->hse_inj_name;
                $rows[] = !empty($ltVal->Hod) ? ucfirst($ltVal->Hod) : '-';
                $rows[] = !empty($ltVal->obs_assigner_target_date) ? date('d-m-Y', strtotime($ltVal->obs_assigner_target_date)) : '-';
                $rows[] = !empty($ltVal->closed_date) ? date('d-m-Y', strtotime($ltVal->closed_date)) : '-';
                $rows[] = $obs_open_closed_sts;
                $rows[] = $ltVal->reporter_desig;
                $rows[] = $ltVal->reporter_name;
                $rows[] = $pending_days;
                $rows[] = $closure_day;
                $rows[] = $delay;
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
    public function obstracker_exportexcel()
    {
        $request = $this->input->get();
        global $obsPermission, $risk_rating, $obs_type_list;
        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();
        $company_id = postData($request, 'searchextra');
        $area_id = postData($request, 'area_id');
        $building_id = postData($request, 'building_id');
        $department_id = postData($request, 'department_id');
        $project_id = postData($request, 'project_id');
        $emp_name = postData($request, 'emp_name');
        $fac_injury = postData($request, 'fac_injury');
        $hse_cat = postData($request, 'hse_cat');
        $obs_type_id = postData($request, 'obs_type');
        $risk_id = postData($request, 'risk_id');
        $startdate = postData($request, 'start_date');
        $enddate = postData($request, 'end_date');
        $searchStatus = postData($request, 'NotifyStatus');
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
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
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
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
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

        if ($fac_injury > 0) {
            $options['where_new']['h.obs_fac_id'] = $fac_injury;
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
        if ($startdate != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

            $options['where_new']['h.CREATED_ON >='] = $thstartdate;
        }
        if ($enddate != '') {
            $enddate = date('Y-m-d', strtotime($enddate));
            $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
        }


        ///////////////////////////filter end


        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $options['where_new']['h.obs_status'] =  'Y';
            $options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            //$options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            // $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_reporter_id'] =  $userid;
        }
        $options['where_new']['h.obs_app_status !='] = '0';


        // print_r( $where);exit;

        $options['select'] = [
            'h.*',
            'FN_COMP_NAME(obs_comp_id) as comp_name',
            'FN_AREA_NAME(obs_area_id) as area_name',
            'FN_BUILD_NAME(obs_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(obs_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.obs_project_id) as proj_name',
            'FN_HSE_CAT_NAME(h.obs_cat_id) as hse_cat_name',
            'FN_OBS_FAC_NAME(h.obs_fac_id) as hse_inj_name',
            'Hod.EMP_NAME as Hod',
            'EMP1.EMP_NAME as reporter_name',
            'h.obs_report_datetime',
            'FN_GET_DESIGNATION_NAME(h.obs_reporter_desg_id) as reporter_desig',
            'FN_OBS_STATUS(h.obs_app_status) as status_Name'
        ];

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];

        $result = $this->common_model->get_exportdata(
            $table,
            $column_order,
            $column_search,
            $order,
            $where,
            $options
        );
        $data['obs_tracker'] = $result;
        $data['risk_rating'] = $risk_rating;
        $data['obs_type_list'] = $obs_type_list;
        $this->load->view('report/obs_tracker/generate_excel', $data);
    }
    // Observation Tracker end//

    // HSE-category start//
    public function hse_category()
    {
        $getdashdata = $this->input->get();
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        global $status_drop, $risk_rating, $obs_type_list;

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
        $fac_injury = !empty($getdashdata['fac_injury']) ? decryptval($getdashdata['fac_injury']) : '';
        $hsecatOptn['where'] = [
            'hse_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_id,hse_cat'], $hsecatOptn);
        $drophsecat = customFormDropDown($getAllhsecat, 'hse_id', 'hse_cat', 'Select HSE Category');

        ///////resp
        $table = EMPL . ' as r';
        $selects = ['LD2.LOGIN_ID ,r.*'];
        $option['join'][LOGIN . ' as LD2'] = ['LD2.USER_REF_ID = r.EMP_AUTO_ID', 'left'];
        $option['return_type'] = 'result';
        if (!is_admin()) {
            $option['where'] = [
                'r.EMP_COMP_ID' => $user_clid
            ];
        }
        $hir_flow_details = $this->common_model->getAlldata($table, $selects, $option);
        $getex_drop = customFormDropDown($hir_flow_details, "LOGIN_ID", "EMP_NAME", "Select Employee");

        //
        $data = [
            'pageTitle' => 'HSE-Category',
            'view_file' => 'report/hse_category/list_form',
            'site_title' => 'HSE-Category',
            'current_menu' => 'HSE-Category List',
            'getAllhsecat' => $getAllhsecat,
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'drophsecat' => $drophsecat,
            'getex_drop' => $getex_drop,
            'status_drop_proj' => $status_drop,
            'getdashdata' => $getdashdata,
            'risk_rating' => $risk_rating,
            'obs_type_list' => $obs_type_list,
            'ajaxurl' => 'report/list_hse_category?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&hse_cat=' . $hse_cat . '&risk_id=' . $risk_id . '&obs_type=' . $obs_type . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date . '&Month=' . $Month . '&emp_id=' . $emp_id . '&fac_injury=' . $fac_injury,
        ];
        $this->template->load_table_exp_template($data);
    }
    public function list_hse_category()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = MAS_HSE . ' as HC';
        $column_order = array(
            null,
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_PLANT_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
        $mappedData = [];
        $request = $this->input->post();
        $currentYear = date('Y');
        $currentMonth = date('m');
        $searchextra = postData($request, 'searchextra');
        if ($searchextra != FALSE && count($searchextra) > 0) {
            foreach ($searchextra as $search) {
                $sName = postData($search, 'name');
                $sValue = postData($search, 'value');
                $mappedData[$sName] = $sValue;
            }
        }
        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $data['start_month'] = 1;
        $data['end_month'] = $currentMonth;
        $data['start_year'] = $currentYear;
        $data['end_year'] = $currentYear;

        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $emp_name = postData($mappedData, 'emp_name');
            $hse_cat = postData($mappedData, 'hse_cat');
            $fac_injury = postData($mappedData, 'fac_injury');
            $obs_type_id = postData($mappedData, 'obs_type');
            $risk_id = postData($mappedData, 'risk_id');
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');

            $obs_options['where_new'] = [];
            $options['where_new'] = [];
            $where = [];

            if ($company_id > 0) {
                $obs_options['where_new']['h.obs_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $obs_options['where_new']['h.obs_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $obs_options['where_new']['h.obs_building_id'] = $building_id;
            }

            if ($department_id > 0) {
                $obs_options['where_new']['h.obs_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $obs_options['where_new']['h.obs_project_id'] = $project_id;
            }
            if ($hse_cat > 0) {
                $obs_options['where_new']['h.obs_cat_id'] = $hse_cat;
            }
            if ($fac_injury > 0) {
                $obs_options['where_new']['h.obs_fac_id'] = $fac_injury;
            }
            if ($obs_type_id > 0) {
                $obs_options['where_new']['h.obs_type_id'] = $obs_type_id;
            }
            if ($risk_id > 0) {
                $obs_options['where_new']['h.obs_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $obs_options['where_new']['h.obs_assigner_id'] = $emp_name;
            }

            if ($searchStatus >= 0) {
                $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            }
            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');
                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');
                list($month, $year) = explode('-', $endmonthyr);
                $data['end_month'] = $month;
                $data['end_year'] = $year;
            }
        }

        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $obs_options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            // $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_reporter_id'] =  $userid;
        }
        $joinConditions = [];
        foreach ($obs_options['where_new'] as $key => $value) {
            if (is_array($value)) {
                // For conditions like h.obs_comp_id = 1
                $joinConditions[] = "$key = '$value'";
            } else {
                // Handle cases where the value is more complex (e.g., ">= 0")
                $joinConditions[] = "$key = '$value'";
            }
        }
        $joinConditionsString = implode(' AND ', $joinConditions);

        ///////////////////////////filter end


        $options['select'] = [
            'HC.hse_cat',
            'MONTH(h.obs_date) AS obs_month',
            'YEAR(h.obs_date) AS obs_year',
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'SUM(CASE WHEN h.obs_type_id = "1" THEN 1 ELSE 0 END) AS ua_count',
            'SUM(CASE WHEN h.obs_type_id = "2" THEN 1 ELSE 0 END) AS uc_count',
            'SUM(CASE WHEN h.obs_type_id = "3" THEN 1 ELSE 0 END) AS safe_count',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND  DATE(h.obs_date) <= "' . $endDate . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND DATE(h.obs_date) <= "' . $endDate . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'YEAR(h.obs_date)', 'MONTH(h.obs_date)'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        $hsecatOptn['where'] = [
            'hse_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_cat,hse_id'], $hsecatOptn);

        $yearMonths = [];
        for ($year = $data['start_year']; $year <= $data['end_year']; $year++) {
            $start = ($year == $data['start_year']) ? $data['start_month'] : 1;  // Start month for the first year
            $end = ($year == $data['end_year']) ? $data['end_month'] : 12;  // End month for the last year

            for ($month = $start; $month <= $end; $month++) {
                $yearMonths[] = [
                    'year' => $year,
                    'month' => $month
                ];
            }
        }


        $categories = [];
        foreach ($getAllhsecat as $category) {
            $categories[$category->hse_cat] = [
                'hse_id' => $category->hse_id,
                'year_month_wise' => [],
                'overall_open_count' => 0,
                'overall_closed_count' => 0,
                'overall_ua_count' => 0,
                'overall_uc_count' => 0,
                'overall_safe_count' => 0,
                'overall_total' => 0,
            ];

            foreach ($yearMonths as $ym) {
                $categories[$category->hse_cat]['year_month_wise'][$ym['year']][$ym['month']] = [
                    'open_count' => 0,
                    'closed_count' => 0,
                    'ua_count' => 0,
                    'uc_count' => 0,
                    'safe_count' => 0,
                    'total' => 0
                ];
            }
        }

        foreach ($listUsee as $entry) {
            if (isset($categories[$entry->hse_cat])) {
                $category = &$categories[$entry->hse_cat];
                if (!isset($category['year_month_wise'][$entry->obs_year])) {
                    $category['year_month_wise'][$entry->obs_year] = [];
                }

                if (!isset($category['year_month_wise'][$entry->obs_year][$entry->obs_month])) {
                    $category['year_month_wise'][$entry->obs_year][$entry->obs_month] = [
                        'open_count' => 0,
                        'closed_count' => 0,
                        'ua_count' => 0,
                        'uc_count' => 0,
                        'safe_count' => 0,
                        'total' => 0
                    ];
                }

                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['open_count'] += $entry->open_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['closed_count'] += $entry->closed_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['ua_count'] += $entry->ua_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['uc_count'] += $entry->uc_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['safe_count'] += $entry->safe_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['total'] += $entry->total;

                $category['overall_open_count'] += $entry->open_count;
                $category['overall_closed_count'] += $entry->closed_count;
                $category['overall_ua_count'] += $entry->ua_count;
                $category['overall_uc_count'] += $entry->uc_count;
                $category['overall_safe_count'] += $entry->safe_count;
                $category['overall_total'] += $entry->total;
            }
        }

        $data['data'] = $categories;

        $this->load->view('report/hse_category/view_table', $data);
    }
    public function hse_category_exportexcel()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = MAS_HSE . ' as HC';
        $column_order = array(
            null,
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_PLANT_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
        $mappedData = [];
        $mappedData = $this->input->get();

        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $data['start_month'] = 1;
        $data['end_month'] = $currentMonth;
        $data['start_year'] = $currentYear;
        $data['end_year'] = $currentYear;
        $obs_options['where_new'] = [];
        $options['where_new'] = [];
        $where = [];
        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $emp_name = postData($mappedData, 'emp_name');
            $hse_cat = postData($mappedData, 'hse_cat');
            $fac_injury = postData($mappedData, 'fac_injury');
            $obs_type_id = postData($mappedData, 'obs_type');
            $risk_id = postData($mappedData, 'risk_id');
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');



            if ($company_id > 0) {
                $obs_options['where_new']['h.obs_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $obs_options['where_new']['h.obs_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $obs_options['where_new']['h.obs_building_id'] = $building_id;
            }

            if ($department_id > 0) {
                $obs_options['where_new']['h.obs_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $obs_options['where_new']['h.obs_project_id'] = $project_id;
            }
            if ($hse_cat > 0) {
                $obs_options['where_new']['h.obs_cat_id'] = $hse_cat;
            }
            if ($fac_injury > 0) {
                $obs_options['where_new']['h.obs_fac_id'] = $fac_injury;
            }
            if ($obs_type_id > 0) {
                $obs_options['where_new']['h.obs_type_id'] = $obs_type_id;
            }
            if ($risk_id > 0) {
                $obs_options['where_new']['h.obs_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $obs_options['where_new']['h.obs_assigner_id'] = $emp_name;
            }

            if ($searchStatus >= 0) {
                $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            }
            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');
                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');
                list($month, $year) = explode('-', $endmonthyr);
                $data['end_month'] = $month;
                $data['end_year'] = $year;
            }
        }
        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $obs_options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            // $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_reporter_id'] =  $userid;
        }


        $joinConditions = [];
        foreach ($obs_options['where_new'] as $key => $value) {
            if (is_array($value)) {
                // For conditions like h.obs_comp_id = 1
                $joinConditions[] = "$key = '$value'";
            } else {
                // Handle cases where the value is more complex (e.g., ">= 0")
                $joinConditions[] = "$key = '$value'";
            }
        }
        $joinConditionsString = implode(' AND ', $joinConditions);
        // echo "<pre>";
        // print_r($joinConditionsString);
        // exit;
        ///////////////////////////filter end


        $options['select'] = [
            'HC.hse_cat',
            'MONTH(h.obs_date) AS obs_month',
            'YEAR(h.obs_date) AS obs_year',
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'SUM(CASE WHEN h.obs_type_id = "1" THEN 1 ELSE 0 END) AS ua_count',
            'SUM(CASE WHEN h.obs_type_id = "2" THEN 1 ELSE 0 END) AS uc_count',
            'SUM(CASE WHEN h.obs_type_id = "3" THEN 1 ELSE 0 END) AS safe_count',
            'SUM(CASE WHEN h.obs_cat_id > 0 THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND  DATE(h.obs_date) <= "' . $endDate . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND DATE(h.obs_date) <= "' . $endDate . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'YEAR(h.obs_date)', 'MONTH(h.obs_date)'];
        // $options['group_by'] = ['HC.hse_id'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        $hsecatOptn['where'] = [
            'hse_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_cat,hse_id'], $hsecatOptn);

        $yearMonths = [];
        for ($year = $data['start_year']; $year <= $data['end_year']; $year++) {
            $start = ($year == $data['start_year']) ? $data['start_month'] : 1;  // Start month for the first year
            $end = ($year == $data['end_year']) ? $data['end_month'] : 12;  // End month for the last year

            for ($month = $start; $month <= $end; $month++) {
                $yearMonths[] = [
                    'year' => $year,
                    'month' => $month
                ];
            }
        }
        $categories = [];
        foreach ($getAllhsecat as $category) {
            $categories[$category->hse_cat] = [
                'hse_id' => $category->hse_id,
                'year_month_wise' => [],
                'overall_open_count' => 0,
                'overall_closed_count' => 0,
                'overall_ua_count' => 0,
                'overall_uc_count' => 0,
                'overall_safe_count' => 0,
                'overall_total' => 0,
            ];

            foreach ($yearMonths as $ym) {
                $categories[$category->hse_cat]['year_month_wise'][$ym['year']][$ym['month']] = [
                    'open_count' => 0,
                    'closed_count' => 0,
                    'ua_count' => 0,
                    'uc_count' => 0,
                    'safe_count' => 0,
                    'total' => 0
                ];
            }
        }

        foreach ($listUsee as $entry) {
            if (isset($categories[$entry->hse_cat])) {
                $category = &$categories[$entry->hse_cat];
                if (!isset($category['year_month_wise'][$entry->obs_year])) {
                    $category['year_month_wise'][$entry->obs_year] = [];
                }

                if (!isset($category['year_month_wise'][$entry->obs_year][$entry->obs_month])) {
                    $category['year_month_wise'][$entry->obs_year][$entry->obs_month] = [
                        'open_count' => 0,
                        'closed_count' => 0,
                        'ua_count' => 0,
                        'uc_count' => 0,
                        'safe_count' => 0,
                        'total' => 0
                    ];
                }

                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['open_count'] += $entry->open_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['closed_count'] += $entry->closed_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['ua_count'] += $entry->ua_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['uc_count'] += $entry->uc_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['safe_count'] += $entry->safe_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['total'] += $entry->total;

                $category['overall_open_count'] += $entry->open_count;
                $category['overall_closed_count'] += $entry->closed_count;
                $category['overall_ua_count'] += $entry->ua_count;
                $category['overall_uc_count'] += $entry->uc_count;
                $category['overall_safe_count'] += $entry->safe_count;
                $category['overall_total'] += $entry->total;
            }
        }

        $data['data'] = $categories;
        // echo "<pre>";
        // print_r($data);
        // exit;
        $this->load->view('report/hse_category/generate_excel', $data);
    }
    public function hse_category_chart()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = MAS_HSE . ' as HC';
        $column_order = array(
            null,
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_PLANT_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)'
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

        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $data['start_month'] = 1;
        $data['end_month'] = $currentMonth;
        $data['start_year'] = $currentYear;
        $data['end_year'] = $currentYear;
        $obs_options['where_new'] = [];
        $options['where_new'] = [];
        $where = [];
        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $emp_name = postData($mappedData, 'emp_name');
            $obs_type_id = postData($mappedData, 'obs_type');
            $fac_injury = postData($mappedData, 'fac_injury');
            $risk_id = postData($mappedData, 'risk_id');
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');



            if ($company_id > 0) {
                $obs_options['where_new']['h.obs_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $obs_options['where_new']['h.obs_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $obs_options['where_new']['h.obs_building_id'] = $building_id;
            }

            if ($department_id > 0) {
                $obs_options['where_new']['h.obs_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $obs_options['where_new']['h.obs_project_id'] = $project_id;
            }

            if ($obs_type_id > 0) {
                $obs_options['where_new']['h.obs_type_id'] = $obs_type_id;
            }
            if ($fac_injury > 0) {
                $obs_options['where_new']['h.obs_fac_id'] = $fac_injury;
            }
            if ($risk_id > 0) {
                $obs_options['where_new']['h.obs_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $obs_options['where_new']['h.obs_assigner_id'] = $emp_name;
            }

            if ($searchStatus >= 0) {
                $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            }
            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');
                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');
                list($month, $year) = explode('-', $endmonthyr);
                $data['end_month'] = $month;
                $data['end_year'] = $year;
            }
        }
        if ($request['hse_id']) {
            $obs_options['where_new']['h.obs_cat_id'] = $request['hse_id'];
        }
        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $obs_options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            // $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_reporter_id'] =  $userid;
        }


        $joinConditions = [];
        foreach ($obs_options['where_new'] as $key => $value) {
            if (is_array($value)) {
                // For conditions like h.obs_comp_id = 1
                $joinConditions[] = "$key = '$value'";
            } else {
                // Handle cases where the value is more complex (e.g., ">= 0")
                $joinConditions[] = "$key = '$value'";
            }
        }
        $joinConditionsString = implode(' AND ', $joinConditions);
        // echo "<pre>";
        // print_r($joinConditionsString);
        // exit;
        ///////////////////////////filter end


        $options['select'] = [
            'HC.hse_cat',
            'MONTH(h.obs_date) AS obs_month',
            'SUM(CASE WHEN h.obs_cat_id > 0 THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND  DATE(h.obs_date) <= "' . $endDate . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND DATE(h.obs_date) <= "' . $endDate . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'MONTH(h.obs_date)'];
        // $options['group_by'] = ['HC.hse_id'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        $hsecatOptn['where'] = [
            'hse_status' => 'Y',
            'hse_id' => $request['hse_id']
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_cat,hse_id'], $hsecatOptn);



        $categories = [];
        foreach ($getAllhsecat as $category) {
            $categories[$category->hse_cat] = [
                'hse_id' => $category->hse_id,
                'hse_cat' => $category->hse_cat,
                'month_wise' => [],
                'overall_total' => 0,
            ];

            for ($month = 1; $month <= 12; $month++) {
                $categories[$category->hse_cat]['month_wise'][$month] = [
                    'total' => 0
                ];
            }
        }

        foreach ($listUsee as $entry) {
            if (isset($categories[$entry->hse_cat])) {
                $category = &$categories[$entry->hse_cat];

                if ($entry->obs_month) {
                    $category['month_wise'][$entry->obs_month]['total'] += $entry->total;
                }
                $category['overall_total'] += $entry->total;
            }
        }

        $data['data'] = $category;

        $this->load->view('report/hse_category/hse_chart', $data);
    }
    public function total_hse_category_chart()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = MAS_HSE . ' as HC';
        $column_order = array(
            null,
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_PLANT_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)'
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

        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $data['start_month'] = 1;
        $data['end_month'] = $currentMonth;
        $data['start_year'] = $currentYear;
        $data['end_year'] = $currentYear;
        $obs_options['where_new'] = [];
        $options['where_new'] = [];
        $where = [];
        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $obs_cat_id = postData($mappedData, 'obs_cat_id');
            $emp_name = postData($mappedData, 'emp_name');
            $obs_type_id = postData($mappedData, 'obs_type');
            $fac_injury = postData($mappedData, 'fac_injury');
            $risk_id = postData($mappedData, 'risk_id');
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');



            if ($company_id > 0) {
                $obs_options['where_new']['h.obs_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $obs_options['where_new']['h.obs_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $obs_options['where_new']['h.obs_building_id'] = $building_id;
            }

            if ($department_id > 0) {
                $obs_options['where_new']['h.obs_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $obs_options['where_new']['h.obs_project_id'] = $project_id;
            }
            if ($obs_cat_id) {
                $obs_options['where_new']['h.obs_cat_id'] = $obs_cat_id;
            }
            if ($obs_type_id > 0) {
                $obs_options['where_new']['h.obs_type_id'] = $obs_type_id;
            }
            if ($fac_injury > 0) {
                $obs_options['where_new']['h.obs_fac_id'] = $fac_injury;
            }
            if ($risk_id > 0) {
                $obs_options['where_new']['h.obs_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $obs_options['where_new']['h.obs_assigner_id'] = $emp_name;
            }

            if ($searchStatus >= 0) {
                $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            }
            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');
                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');
                list($month, $year) = explode('-', $endmonthyr);
                $data['end_month'] = $month;
                $data['end_year'] = $year;
            }
        }

        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $obs_options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            // $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_reporter_id'] =  $userid;
        }


        $joinConditions = [];
        foreach ($obs_options['where_new'] as $key => $value) {
            if (is_array($value)) {
                // For conditions like h.obs_comp_id = 1
                $joinConditions[] = "$key = '$value'";
            } else {
                // Handle cases where the value is more complex (e.g., ">= 0")
                $joinConditions[] = "$key = '$value'";
            }
        }
        $joinConditionsString = implode(' AND ', $joinConditions);
        // echo "<pre>";
        // print_r($joinConditionsString);
        // exit;
        ///////////////////////////filter end


        $options['select'] = [
            'HC.hse_cat',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND  DATE(h.obs_date) <= "' . $endDate . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND DATE(h.obs_date) <= "' . $endDate . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id'];
        // $options['group_by'] = ['HC.hse_id'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $data['data'] = $listUsee;
        $this->load->view('report/hse_category/total_hse_cat_chart', $data);
    }

    public function monthly_hse_category_chart()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = MAS_HSE . ' as HC';
        $column_order = array(
            null,
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_PLANT_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)'
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

        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $data['start_month'] = 1;
        $data['end_month'] = $currentMonth;
        $data['start_year'] = $currentYear;
        $data['end_year'] = $currentYear;
        $obs_options['where_new'] = [];
        $options['where_new'] = [];
        $where = [];
        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $obs_cat_id = postData($mappedData, 'obs_cat_id');
            $emp_name = postData($mappedData, 'emp_name');
            $obs_type_id = postData($mappedData, 'obs_type');
            $fac_injury = postData($mappedData, 'fac_injury');
            $risk_id = postData($mappedData, 'risk_id');
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');



            if ($company_id > 0) {
                $obs_options['where_new']['h.obs_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $obs_options['where_new']['h.obs_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $obs_options['where_new']['h.obs_building_id'] = $building_id;
            }

            if ($department_id > 0) {
                $obs_options['where_new']['h.obs_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $obs_options['where_new']['h.obs_project_id'] = $project_id;
            }
            if ($obs_cat_id) {
                $obs_options['where_new']['h.obs_cat_id'] = $obs_cat_id;
            }
            if ($obs_type_id > 0) {
                $obs_options['where_new']['h.obs_type_id'] = $obs_type_id;
            }
            if ($fac_injury > 0) {
                $obs_options['where_new']['h.obs_fac_id'] = $fac_injury;
            }
            if ($risk_id > 0) {
                $obs_options['where_new']['h.obs_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $obs_options['where_new']['h.obs_assigner_id'] = $emp_name;
            }

            if ($searchStatus >= 0) {
                $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            }
            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');
                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');
                list($month, $year) = explode('-', $endmonthyr);
                $data['end_month'] = $month;
                $data['end_year'] = $year;
            }
        }

        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $obs_options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            // $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_reporter_id'] =  $userid;
        }


        $joinConditions = [];
        foreach ($obs_options['where_new'] as $key => $value) {
            if (is_array($value)) {
                // For conditions like h.obs_comp_id = 1
                $joinConditions[] = "$key = '$value'";
            } else {
                // Handle cases where the value is more complex (e.g., ">= 0")
                $joinConditions[] = "$key = '$value'";
            }
        }
        $joinConditionsString = implode(' AND ', $joinConditions);
        // echo "<pre>";
        // print_r($joinConditionsString);
        // exit;
        ///////////////////////////filter end


        $options['select'] = [
            'HC.hse_cat',
            'MONTH(h.obs_date) AS obs_month',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND  DATE(h.obs_date) <= "' . $endDate . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND DATE(h.obs_date) <= "' . $endDate . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id'];
        // $options['group_by'] = ['HC.hse_id'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $data['data'] = $listUsee;
        echo "<pre>";
        print_r($data['data']);
        exit;
        $this->load->view('report/hse_category/monthly_hse_cat_chart', $data);
    }
    // HSE-category  end//

    // Injury start//
    public function fac_injury()
    {
        $getdashdata = $this->input->get();
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        global $status_drop, $risk_rating, $obs_type_list;

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
        $fac_injury = !empty($getdashdata['fac_injury']) ? decryptval($getdashdata['fac_injury']) : '';
        $hsecatOptn['where'] = [
            'hse_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_id,hse_cat'], $hsecatOptn);
        $drophsecat = customFormDropDown($getAllhsecat, 'hse_id', 'hse_cat', 'Select HSE Category');


        ///////resp
        $table = EMPL . ' as r';
        $selects = ['LD2.LOGIN_ID ,r.*'];
        $option['join'][LOGIN . ' as LD2'] = ['LD2.USER_REF_ID = r.EMP_AUTO_ID', 'left'];
        $option['return_type'] = 'result';
        if (!is_admin()) {
            $option['where'] = [
                'r.EMP_COMP_ID' => $user_clid
            ];
        }
        $hir_flow_details = $this->common_model->getAlldata($table, $selects, $option);
        $getex_drop = customFormDropDown($hir_flow_details, "LOGIN_ID", "EMP_NAME", "Select Employee");

        //
        $data = [
            'pageTitle' => 'HSE-Category',
            'view_file' => 'report/hse_category/list_form',
            'site_title' => 'HSE-Category',
            'current_menu' => 'HSE-Category List',
            'getAllhsecat' => $getAllhsecat,
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'drophsecat' => $drophsecat,
            'getex_drop' => $getex_drop,
            'status_drop_proj' => $status_drop,
            'getdashdata' => $getdashdata,
            'risk_rating' => $risk_rating,
            'obs_type_list' => $obs_type_list,
            'ajaxurl' => 'report/list_hse_category?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&hse_cat=' . $hse_cat . '&risk_id=' . $risk_id . '&obs_type=' . $obs_type . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date . '&Month=' . $Month . '&emp_id=' . $emp_id . '&fac_injury=' . $fac_injury,
        ];
        $this->template->load_table_exp_template($data);
    }
    public function list_fac_injury()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = MAS_HSE . ' as HC';
        $column_order = array(
            null,
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_PLANT_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
        $mappedData = [];
        $request = $this->input->post();
        $currentYear = date('Y');
        $currentMonth = date('m');
        $searchextra = postData($request, 'searchextra');
        if ($searchextra != FALSE && count($searchextra) > 0) {
            foreach ($searchextra as $search) {
                $sName = postData($search, 'name');
                $sValue = postData($search, 'value');
                $mappedData[$sName] = $sValue;
            }
        }
        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $data['start_month'] = 1;
        $data['end_month'] = $currentMonth;
        $data['start_year'] = $currentYear;
        $data['end_year'] = $currentYear;

        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $emp_name = postData($mappedData, 'emp_name');
            $hse_cat = postData($mappedData, 'hse_cat');
            $fac_injury = postData($mappedData, 'fac_injury');
            $obs_type_id = postData($mappedData, 'obs_type');
            $risk_id = postData($mappedData, 'risk_id');
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');

            $obs_options['where_new'] = [];
            $options['where_new'] = [];
            $where = [];

            if ($company_id > 0) {
                $obs_options['where_new']['h.obs_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $obs_options['where_new']['h.obs_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $obs_options['where_new']['h.obs_building_id'] = $building_id;
            }

            if ($department_id > 0) {
                $obs_options['where_new']['h.obs_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $obs_options['where_new']['h.obs_project_id'] = $project_id;
            }
            if ($hse_cat > 0) {
                $obs_options['where_new']['h.obs_cat_id'] = $hse_cat;
            }
            if ($fac_injury > 0) {
                $obs_options['where_new']['h.obs_fac_id'] = $fac_injury;
            }
            if ($obs_type_id > 0) {
                $obs_options['where_new']['h.obs_type_id'] = $obs_type_id;
            }
            if ($risk_id > 0) {
                $obs_options['where_new']['h.obs_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $obs_options['where_new']['h.obs_assigner_id'] = $emp_name;
            }

            if ($searchStatus >= 0) {
                $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            }
            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');
                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');
                list($month, $year) = explode('-', $endmonthyr);
                $data['end_month'] = $month;
                $data['end_year'] = $year;
            }
        }
        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $obs_options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            // $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_reporter_id'] =  $userid;
        }


        $joinConditions = [];
        foreach ($obs_options['where_new'] as $key => $value) {
            if (is_array($value)) {
                // For conditions like h.obs_comp_id = 1
                $joinConditions[] = "$key = '$value'";
            } else {
                // Handle cases where the value is more complex (e.g., ">= 0")
                $joinConditions[] = "$key = '$value'";
            }
        }
        $joinConditionsString = implode(' AND ', $joinConditions);

        ///////////////////////////filter end


        $options['select'] = [
            'HC.hse_cat',
            'MONTH(h.obs_date) AS obs_month',
            'YEAR(h.obs_date) AS obs_year',
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'SUM(CASE WHEN h.obs_type_id = "1" THEN 1 ELSE 0 END) AS ua_count',
            'SUM(CASE WHEN h.obs_type_id = "2" THEN 1 ELSE 0 END) AS uc_count',
            'SUM(CASE WHEN h.obs_type_id = "3" THEN 1 ELSE 0 END) AS safe_count',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND  DATE(h.obs_date) <= "' . $endDate . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND DATE(h.obs_date) <= "' . $endDate . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'YEAR(h.obs_date)', 'MONTH(h.obs_date)'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        $hsecatOptn['where'] = [
            'hse_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_cat,hse_id'], $hsecatOptn);

        $yearMonths = [];
        for ($year = $data['start_year']; $year <= $data['end_year']; $year++) {
            $start = ($year == $data['start_year']) ? $data['start_month'] : 1;  // Start month for the first year
            $end = ($year == $data['end_year']) ? $data['end_month'] : 12;  // End month for the last year

            for ($month = $start; $month <= $end; $month++) {
                $yearMonths[] = [
                    'year' => $year,
                    'month' => $month
                ];
            }
        }


        $categories = [];
        foreach ($getAllhsecat as $category) {
            $categories[$category->hse_cat] = [
                'hse_id' => $category->hse_id,
                'year_month_wise' => [],
                'overall_open_count' => 0,
                'overall_closed_count' => 0,
                'overall_ua_count' => 0,
                'overall_uc_count' => 0,
                'overall_safe_count' => 0,
                'overall_total' => 0,
            ];

            foreach ($yearMonths as $ym) {
                $categories[$category->hse_cat]['year_month_wise'][$ym['year']][$ym['month']] = [
                    'open_count' => 0,
                    'closed_count' => 0,
                    'ua_count' => 0,
                    'uc_count' => 0,
                    'safe_count' => 0,
                    'total' => 0
                ];
            }
        }

        foreach ($listUsee as $entry) {
            if (isset($categories[$entry->hse_cat])) {
                $category = &$categories[$entry->hse_cat];
                if (!isset($category['year_month_wise'][$entry->obs_year])) {
                    $category['year_month_wise'][$entry->obs_year] = [];
                }

                if (!isset($category['year_month_wise'][$entry->obs_year][$entry->obs_month])) {
                    $category['year_month_wise'][$entry->obs_year][$entry->obs_month] = [
                        'open_count' => 0,
                        'closed_count' => 0,
                        'ua_count' => 0,
                        'uc_count' => 0,
                        'safe_count' => 0,
                        'total' => 0
                    ];
                }

                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['open_count'] += $entry->open_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['closed_count'] += $entry->closed_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['ua_count'] += $entry->ua_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['uc_count'] += $entry->uc_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['safe_count'] += $entry->safe_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['total'] += $entry->total;

                $category['overall_open_count'] += $entry->open_count;
                $category['overall_closed_count'] += $entry->closed_count;
                $category['overall_ua_count'] += $entry->ua_count;
                $category['overall_uc_count'] += $entry->uc_count;
                $category['overall_safe_count'] += $entry->safe_count;
                $category['overall_total'] += $entry->total;
            }
        }

        $data['data'] = $categories;

        $this->load->view('report/hse_category/view_table', $data);
    }
    public function fac_injury_exportexcel()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = MAS_HSE . ' as HC';
        $column_order = array(
            null,
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_PLANT_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
        $mappedData = [];
        $mappedData = $this->input->get();

        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $data['start_month'] = 1;
        $data['end_month'] = $currentMonth;
        $data['start_year'] = $currentYear;
        $data['end_year'] = $currentYear;
        $obs_options['where_new'] = [];
        $options['where_new'] = [];
        $where = [];
        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $emp_name = postData($mappedData, 'emp_name');
            $hse_cat = postData($mappedData, 'hse_cat');
            $fac_injury = postData($mappedData, 'fac_injury');
            $obs_type_id = postData($mappedData, 'obs_type');
            $risk_id = postData($mappedData, 'risk_id');
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');



            if ($company_id > 0) {
                $obs_options['where_new']['h.obs_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $obs_options['where_new']['h.obs_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $obs_options['where_new']['h.obs_building_id'] = $building_id;
            }

            if ($department_id > 0) {
                $obs_options['where_new']['h.obs_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $obs_options['where_new']['h.obs_project_id'] = $project_id;
            }
            if ($hse_cat > 0) {
                $obs_options['where_new']['h.obs_cat_id'] = $hse_cat;
            }
            if ($fac_injury > 0) {
                $obs_options['where_new']['h.obs_fac_id'] = $fac_injury;
            }
            if ($obs_type_id > 0) {
                $obs_options['where_new']['h.obs_type_id'] = $obs_type_id;
            }
            if ($risk_id > 0) {
                $obs_options['where_new']['h.obs_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $obs_options['where_new']['h.obs_assigner_id'] = $emp_name;
            }

            if ($searchStatus >= 0) {
                $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            }
            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');
                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');
                list($month, $year) = explode('-', $endmonthyr);
                $data['end_month'] = $month;
                $data['end_year'] = $year;
            }
        }
        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $obs_options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            // $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_reporter_id'] =  $userid;
        }


        $joinConditions = [];
        foreach ($obs_options['where_new'] as $key => $value) {
            if (is_array($value)) {
                // For conditions like h.obs_comp_id = 1
                $joinConditions[] = "$key = '$value'";
            } else {
                // Handle cases where the value is more complex (e.g., ">= 0")
                $joinConditions[] = "$key = '$value'";
            }
        }
        $joinConditionsString = implode(' AND ', $joinConditions);
        // echo "<pre>";
        // print_r($joinConditionsString);
        // exit;
        ///////////////////////////filter end


        $options['select'] = [
            'HC.hse_cat',
            'MONTH(h.obs_date) AS obs_month',
            'YEAR(h.obs_date) AS obs_year',
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'SUM(CASE WHEN h.obs_type_id = "1" THEN 1 ELSE 0 END) AS ua_count',
            'SUM(CASE WHEN h.obs_type_id = "2" THEN 1 ELSE 0 END) AS uc_count',
            'SUM(CASE WHEN h.obs_type_id = "3" THEN 1 ELSE 0 END) AS safe_count',
            'SUM(CASE WHEN h.obs_cat_id > 0 THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND  DATE(h.obs_date) <= "' . $endDate . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND DATE(h.obs_date) <= "' . $endDate . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'YEAR(h.obs_date)', 'MONTH(h.obs_date)'];
        // $options['group_by'] = ['HC.hse_id'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        $hsecatOptn['where'] = [
            'hse_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_cat,hse_id'], $hsecatOptn);

        $yearMonths = [];
        for ($year = $data['start_year']; $year <= $data['end_year']; $year++) {
            $start = ($year == $data['start_year']) ? $data['start_month'] : 1;  // Start month for the first year
            $end = ($year == $data['end_year']) ? $data['end_month'] : 12;  // End month for the last year

            for ($month = $start; $month <= $end; $month++) {
                $yearMonths[] = [
                    'year' => $year,
                    'month' => $month
                ];
            }
        }
        $categories = [];
        foreach ($getAllhsecat as $category) {
            $categories[$category->hse_cat] = [
                'hse_id' => $category->hse_id,
                'year_month_wise' => [],
                'overall_open_count' => 0,
                'overall_closed_count' => 0,
                'overall_ua_count' => 0,
                'overall_uc_count' => 0,
                'overall_safe_count' => 0,
                'overall_total' => 0,
            ];

            foreach ($yearMonths as $ym) {
                $categories[$category->hse_cat]['year_month_wise'][$ym['year']][$ym['month']] = [
                    'open_count' => 0,
                    'closed_count' => 0,
                    'ua_count' => 0,
                    'uc_count' => 0,
                    'safe_count' => 0,
                    'total' => 0
                ];
            }
        }

        foreach ($listUsee as $entry) {
            if (isset($categories[$entry->hse_cat])) {
                $category = &$categories[$entry->hse_cat];
                if (!isset($category['year_month_wise'][$entry->obs_year])) {
                    $category['year_month_wise'][$entry->obs_year] = [];
                }

                if (!isset($category['year_month_wise'][$entry->obs_year][$entry->obs_month])) {
                    $category['year_month_wise'][$entry->obs_year][$entry->obs_month] = [
                        'open_count' => 0,
                        'closed_count' => 0,
                        'ua_count' => 0,
                        'uc_count' => 0,
                        'safe_count' => 0,
                        'total' => 0
                    ];
                }

                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['open_count'] += $entry->open_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['closed_count'] += $entry->closed_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['ua_count'] += $entry->ua_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['uc_count'] += $entry->uc_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['safe_count'] += $entry->safe_count;
                $category['year_month_wise'][$entry->obs_year][$entry->obs_month]['total'] += $entry->total;

                $category['overall_open_count'] += $entry->open_count;
                $category['overall_closed_count'] += $entry->closed_count;
                $category['overall_ua_count'] += $entry->ua_count;
                $category['overall_uc_count'] += $entry->uc_count;
                $category['overall_safe_count'] += $entry->safe_count;
                $category['overall_total'] += $entry->total;
            }
        }

        $data['data'] = $categories;
        // echo "<pre>";
        // print_r($data);
        // exit;
        $this->load->view('report/hse_category/generate_excel', $data);
    }
    public function fac_injury_chart()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = MAS_HSE . ' as HC';
        $column_order = array(
            null,
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_PLANT_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)'
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

        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $data['start_month'] = 1;
        $data['end_month'] = $currentMonth;
        $data['start_year'] = $currentYear;
        $data['end_year'] = $currentYear;
        $obs_options['where_new'] = [];
        $options['where_new'] = [];
        $where = [];
        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $emp_name = postData($mappedData, 'emp_name');
            $obs_type_id = postData($mappedData, 'obs_type');
            $fac_injury = postData($mappedData, 'fac_injury');
            $risk_id = postData($mappedData, 'risk_id');
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');



            if ($company_id > 0) {
                $obs_options['where_new']['h.obs_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $obs_options['where_new']['h.obs_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $obs_options['where_new']['h.obs_building_id'] = $building_id;
            }

            if ($department_id > 0) {
                $obs_options['where_new']['h.obs_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $obs_options['where_new']['h.obs_project_id'] = $project_id;
            }

            if ($obs_type_id > 0) {
                $obs_options['where_new']['h.obs_type_id'] = $obs_type_id;
            }
            if ($fac_injury > 0) {
                $obs_options['where_new']['h.obs_fac_id'] = $fac_injury;
            }
            if ($risk_id > 0) {
                $obs_options['where_new']['h.obs_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $obs_options['where_new']['h.obs_assigner_id'] = $emp_name;
            }

            if ($searchStatus >= 0) {
                $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            }
            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');
                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');
                list($month, $year) = explode('-', $endmonthyr);
                $data['end_month'] = $month;
                $data['end_year'] = $year;
            }
        }
        if ($request['hse_id']) {
            $obs_options['where_new']['h.obs_cat_id'] = $request['hse_id'];
        }
        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $obs_options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            // $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_reporter_id'] =  $userid;
        }


        $joinConditions = [];
        foreach ($obs_options['where_new'] as $key => $value) {
            if (is_array($value)) {
                // For conditions like h.obs_comp_id = 1
                $joinConditions[] = "$key = '$value'";
            } else {
                // Handle cases where the value is more complex (e.g., ">= 0")
                $joinConditions[] = "$key = '$value'";
            }
        }
        $joinConditionsString = implode(' AND ', $joinConditions);
        // echo "<pre>";
        // print_r($joinConditionsString);
        // exit;
        ///////////////////////////filter end


        $options['select'] = [
            'HC.hse_cat',
            'MONTH(h.obs_date) AS obs_month',
            'SUM(CASE WHEN h.obs_cat_id > 0 THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND  DATE(h.obs_date) <= "' . $endDate . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND DATE(h.obs_date) <= "' . $endDate . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'MONTH(h.obs_date)'];
        // $options['group_by'] = ['HC.hse_id'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        $hsecatOptn['where'] = [
            'hse_status' => 'Y',
            'hse_id' => $request['hse_id']
        ];
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_cat,hse_id'], $hsecatOptn);



        $categories = [];
        foreach ($getAllhsecat as $category) {
            $categories[$category->hse_cat] = [
                'hse_id' => $category->hse_id,
                'hse_cat' => $category->hse_cat,
                'month_wise' => [],
                'overall_total' => 0,
            ];

            for ($month = 1; $month <= 12; $month++) {
                $categories[$category->hse_cat]['month_wise'][$month] = [
                    'total' => 0
                ];
            }
        }

        foreach ($listUsee as $entry) {
            if (isset($categories[$entry->hse_cat])) {
                $category = &$categories[$entry->hse_cat];

                if ($entry->obs_month) {
                    $category['month_wise'][$entry->obs_month]['total'] += $entry->total;
                }
                $category['overall_total'] += $entry->total;
            }
        }

        $data['data'] = $category;

        $this->load->view('report/hse_category/hse_chart', $data);
    }
    public function total_fac_injury_chart()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = MAS_HSE . ' as HC';
        $column_order = array(
            null,
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_PLANT_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)'
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

        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $data['start_month'] = 1;
        $data['end_month'] = $currentMonth;
        $data['start_year'] = $currentYear;
        $data['end_year'] = $currentYear;
        $obs_options['where_new'] = [];
        $options['where_new'] = [];
        $where = [];
        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $obs_cat_id = postData($mappedData, 'obs_cat_id');
            $emp_name = postData($mappedData, 'emp_name');
            $obs_type_id = postData($mappedData, 'obs_type');
            $fac_injury = postData($mappedData, 'fac_injury');
            $risk_id = postData($mappedData, 'risk_id');
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');



            if ($company_id > 0) {
                $obs_options['where_new']['h.obs_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $obs_options['where_new']['h.obs_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $obs_options['where_new']['h.obs_building_id'] = $building_id;
            }

            if ($department_id > 0) {
                $obs_options['where_new']['h.obs_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $obs_options['where_new']['h.obs_project_id'] = $project_id;
            }
            if ($obs_cat_id) {
                $obs_options['where_new']['h.obs_cat_id'] = $obs_cat_id;
            }
            if ($obs_type_id > 0) {
                $obs_options['where_new']['h.obs_type_id'] = $obs_type_id;
            }
            if ($fac_injury > 0) {
                $obs_options['where_new']['h.obs_fac_id'] = $fac_injury;
            }
            if ($risk_id > 0) {
                $obs_options['where_new']['h.obs_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $obs_options['where_new']['h.obs_assigner_id'] = $emp_name;
            }

            if ($searchStatus >= 0) {
                $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            }
            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');
                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');
                list($month, $year) = explode('-', $endmonthyr);
                $data['end_month'] = $month;
                $data['end_year'] = $year;
            }
        }

        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $obs_options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            // $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_reporter_id'] =  $userid;
        }


        $joinConditions = [];
        foreach ($obs_options['where_new'] as $key => $value) {
            if (is_array($value)) {
                // For conditions like h.obs_comp_id = 1
                $joinConditions[] = "$key = '$value'";
            } else {
                // Handle cases where the value is more complex (e.g., ">= 0")
                $joinConditions[] = "$key = '$value'";
            }
        }
        $joinConditionsString = implode(' AND ', $joinConditions);
        // echo "<pre>";
        // print_r($joinConditionsString);
        // exit;
        ///////////////////////////filter end


        $options['select'] = [
            'HC.hse_cat',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND  DATE(h.obs_date) <= "' . $endDate . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND DATE(h.obs_date) <= "' . $endDate . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id'];
        // $options['group_by'] = ['HC.hse_id'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $data['data'] = $listUsee;
        $this->load->view('report/hse_category/total_hse_cat_chart', $data);
    }

    public function monthly_fac_injury_chart()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = MAS_HSE . ' as HC';
        $column_order = array(
            null,
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_PLANT_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
            'FN_OBS_INJ_NAME(h.obs_fac_id)',
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)'
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

        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $data['start_month'] = 1;
        $data['end_month'] = $currentMonth;
        $data['start_year'] = $currentYear;
        $data['end_year'] = $currentYear;
        $obs_options['where_new'] = [];
        $options['where_new'] = [];
        $where = [];
        if ($mappedData != FALSE && count($mappedData) > 0) {

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $obs_cat_id = postData($mappedData, 'obs_cat_id');
            $emp_name = postData($mappedData, 'emp_name');
            $obs_type_id = postData($mappedData, 'obs_type');
            $fac_injury = postData($mappedData, 'fac_injury');
            $risk_id = postData($mappedData, 'risk_id');
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');



            if ($company_id > 0) {
                $obs_options['where_new']['h.obs_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $obs_options['where_new']['h.obs_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $obs_options['where_new']['h.obs_building_id'] = $building_id;
            }

            if ($department_id > 0) {
                $obs_options['where_new']['h.obs_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $obs_options['where_new']['h.obs_project_id'] = $project_id;
            }
            if ($obs_cat_id) {
                $obs_options['where_new']['h.obs_cat_id'] = $obs_cat_id;
            }
            if ($obs_type_id > 0) {
                $obs_options['where_new']['h.obs_type_id'] = $obs_type_id;
            }
            if ($fac_injury > 0) {
                $obs_options['where_new']['h.obs_fac_id'] = $fac_injury;
            }
            if ($risk_id > 0) {
                $obs_options['where_new']['h.obs_risk_id'] = $risk_id;
            }
            if ($emp_name > 0) {
                $obs_options['where_new']['h.obs_assigner_id'] = $emp_name;
            }

            if ($searchStatus >= 0) {
                $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            }
            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');
                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');
                list($month, $year) = explode('-', $endmonthyr);
                $data['end_month'] = $month;
                $data['end_year'] = $year;
            }
        }

        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['fix'])) {
            $obs_options['orwhere_new']['h.obs_assigner_id'] =  $userid;
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['orwhere_new']['h.obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $obsPermission['approve'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['approve_final'])) {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            // $obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
        } else {
            $obs_options['where_new']['h.obs_status'] =  'Y';
            $obs_options['where_new']['h.obs_reporter_id'] =  $userid;
        }

        $joinConditions = [];
        foreach ($obs_options['where_new'] as $key => $value) {
            if (is_array($value)) {
                // For conditions like h.obs_comp_id = 1
                $joinConditions[] = "$key = '$value'";
            } else {
                // Handle cases where the value is more complex (e.g., ">= 0")
                $joinConditions[] = "$key = '$value'";
            }
        }
        $joinConditionsString = implode(' AND ', $joinConditions);
        // echo "<pre>";
        // print_r($joinConditionsString);
        // exit;
        ///////////////////////////filter end


        $options['select'] = [
            'HC.hse_cat',
            'MONTH(h.obs_date) AS obs_month',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND  DATE(h.obs_date) <= "' . $endDate . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.obs_date) >= "' . $startDate . '" AND DATE(h.obs_date) <= "' . $endDate . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id'];
        // $options['group_by'] = ['HC.hse_id'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $data['data'] = $listUsee;
        echo "<pre>";
        print_r($data['data']);
        exit;
        $this->load->view('report/hse_category/monthly_hse_cat_chart', $data);
    }
    // Injury  end//
}
