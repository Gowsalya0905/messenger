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
            'ajaxurl' => 'report/listobstracker?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&hse_cat=' . $hse_cat . '&risk_id=' . $risk_id . '&obs_type=' . $obs_type . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date . '&Month=' . $Month . '&emp_id=' . $emp_id,
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

            // if ($searchStatus >= 0) {
            //     $options['where_new']["h.obs_app_status"] = $searchStatus;
            // }

            $options['where_new']["h.obs_app_status !="] = 0;

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
            //$options['where_new']['h.obs_comp_id'] =  $user_clid;
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
                // $rows[] = '-';
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
            //$options['where_new']['h.obs_comp_id'] =  $user_clid;
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
        global $status_drop1, $risk_rating, $obs_type_list;

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
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_id,hse_cat'], $hsecatOptn);
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
            'status_drop_proj' => $status_drop1,
            'getdashdata' => $getdashdata,
            'risk_rating' => $risk_rating,
            'obs_type_list' => $obs_type_list,
            'ajaxurl' => 'report/list_hse_category?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&hse_cat=' . $hse_cat . '&risk_id=' . $risk_id . '&obs_type=' . $obs_type . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date . '&Month=' . $Month . '&emp_id=' . $emp_id,
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
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
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

        $startDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 1, 1)
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');

        $endDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 12, 31)
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');

        $data['start_month'] = 1;
        $data['end_month'] = 12;
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

                $startDate1 = DateTime::createFromFormat('m-Y', $startmonthyr)
                    ->setTime(0, 0, 0)
                    ->format('Y-m-01 H:i:s');

                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');

                $endDate1 = DateTime::createFromFormat('m-Y', $endmonthyr)
                    ->setTime(23, 59, 59)
                    ->format('Y-m-t H:i:s');

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
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
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
            'MONTH(h.CREATED_ON) AS obs_month',
            'YEAR(h.CREATED_ON) AS obs_year',
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'SUM(CASE WHEN h.obs_type_id = "1" THEN 1 ELSE 0 END) AS ua_count',
            'SUM(CASE WHEN h.obs_type_id = "2" THEN 1 ELSE 0 END) AS uc_count',
            'SUM(CASE WHEN h.obs_type_id = "3" THEN 1 ELSE 0 END) AS safe_count',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND  DATE(h.CREATED_ON) <= "' . $endDate1 . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND DATE(h.CREATED_ON) <= "' . $endDate1 . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'YEAR(h.CREATED_ON)', 'MONTH(h.CREATED_ON)'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        //    echo $this->db->last_query();
        if ($hse_cat > 0) {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y',
                'hse_id' =>  $hse_cat
            ];
        } else {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y'
            ];
        }
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
                $formattedMonth = str_pad($ym['month'], 2, '0', STR_PAD_LEFT); // Ensure zero-padded months
                $categories[$category->hse_cat]['year_month_wise'][$ym['year']][$formattedMonth] = [
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

                $obsMonthFormatted = str_pad($entry->obs_month, 2, '0', STR_PAD_LEFT); // Ensure zero-padded months

                if (!isset($category['year_month_wise'][$entry->obs_year][$obsMonthFormatted])) {
                    $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted] = [
                        'open_count' => 0,
                        'closed_count' => 0,
                        'ua_count' => 0,
                        'uc_count' => 0,
                        'safe_count' => 0,
                        'total' => 0
                    ];
                }

                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['open_count'] += $entry->open_count;
                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['closed_count'] += $entry->closed_count;
                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['ua_count'] += $entry->ua_count;
                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['uc_count'] += $entry->uc_count;
                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['safe_count'] += $entry->safe_count;
                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['total'] += $entry->total;


                $category['overall_open_count'] += $entry->open_count;
                $category['overall_closed_count'] += $entry->closed_count;
                $category['overall_ua_count'] += $entry->ua_count;
                $category['overall_uc_count'] += $entry->uc_count;
                $category['overall_safe_count'] += $entry->safe_count;
                $category['overall_total'] += $entry->total;
            }
        }

        $data['data'] = $categories;

        // echo '<pre>'; print_r($data);

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
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
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

        $startDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 1, 1)
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');

        $endDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 12, 31)
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');


        $data['start_month'] = 1;
        $data['end_month'] = 12;
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

                $startDate1 = DateTime::createFromFormat('m-Y', $startmonthyr)
                    ->setTime(0, 0, 0)
                    ->format('Y-m-01 H:i:s');

                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');

                $endDate1 = DateTime::createFromFormat('m-Y', $endmonthyr)
                    ->setTime(23, 59, 59)
                    ->format('Y-m-t H:i:s');

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
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
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
            'MONTH(h.CREATED_ON) AS obs_month',
            'YEAR(h.CREATED_ON) AS obs_year',
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'SUM(CASE WHEN h.obs_type_id = "1" THEN 1 ELSE 0 END) AS ua_count',
            'SUM(CASE WHEN h.obs_type_id = "2" THEN 1 ELSE 0 END) AS uc_count',
            'SUM(CASE WHEN h.obs_type_id = "3" THEN 1 ELSE 0 END) AS safe_count',
            'SUM(CASE WHEN h.obs_cat_id > 0 THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND  DATE(h.CREATED_ON) <= "' . $endDate1 . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND DATE(h.CREATED_ON) <= "' . $endDate1 . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'YEAR(h.CREATED_ON)', 'MONTH(h.CREATED_ON)'];
        // $options['group_by'] = ['HC.hse_id'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        if ($hse_cat > 0) {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y',
                'hse_id' =>  $hse_cat
            ];
        } else {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y'
            ];
        }

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
                $formattedMonth = str_pad($ym['month'], 2, '0', STR_PAD_LEFT); // Ensure zero-padded months
                $categories[$category->hse_cat]['year_month_wise'][$ym['year']][$formattedMonth] = [
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

                $obsMonthFormatted = str_pad($entry->obs_month, 2, '0', STR_PAD_LEFT); // Ensure zero-padded months

                if (!isset($category['year_month_wise'][$entry->obs_year][$obsMonthFormatted])) {
                    $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted] = [
                        'open_count' => 0,
                        'closed_count' => 0,
                        'ua_count' => 0,
                        'uc_count' => 0,
                        'safe_count' => 0,
                        'total' => 0
                    ];
                }

                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['open_count'] += $entry->open_count;
                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['closed_count'] += $entry->closed_count;
                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['ua_count'] += $entry->ua_count;
                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['uc_count'] += $entry->uc_count;
                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['safe_count'] += $entry->safe_count;
                $category['year_month_wise'][$entry->obs_year][$obsMonthFormatted]['total'] += $entry->total;


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
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
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

        $startDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 1, 1)
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');

        $endDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 12, 31)
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');


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

                $startDate1 = DateTime::createFromFormat('m-Y', $startmonthyr)
                    ->setTime(0, 0, 0)
                    ->format('Y-m-01 H:i:s');

                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');

                $endDate1 = DateTime::createFromFormat('m-Y', $endmonthyr)
                    ->setTime(23, 59, 59)
                    ->format('Y-m-t H:i:s');

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
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
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
            'MONTH(h.CREATED_ON) AS obs_month',
            'SUM(CASE WHEN h.obs_cat_id > 0 THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND  DATE(h.CREATED_ON) <= "' . $endDate1 . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND DATE(h.CREATED_ON) <= "' . $endDate1 . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'MONTH(h.CREATED_ON)'];
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
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
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

        $startDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 1, 1)
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');

        $endDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 12, 31)
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');


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

                $startDate1 = DateTime::createFromFormat('m-Y', $startmonthyr)
                    ->setTime(0, 0, 0)
                    ->format('Y-m-01 H:i:s');

                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');

                $endDate1 = DateTime::createFromFormat('m-Y', $endmonthyr)
                    ->setTime(23, 59, 59)
                    ->format('Y-m-t H:i:s');

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
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
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
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND  DATE(h.CREATED_ON) <= "' . $endDate1 . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND DATE(h.CREATED_ON) <= "' . $endDate1 . '"', 'left'];
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
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
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
        $startDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 1, 1)
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');

        $endDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 12, 31)
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');

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

                $startDate1 = DateTime::createFromFormat('m-Y', $startmonthyr)
                    ->setTime(0, 0, 0)
                    ->format('Y-m-01 H:i:s');

                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');

                $endDate1 = DateTime::createFromFormat('m-Y', $endmonthyr)
                    ->setTime(23, 59, 59)
                    ->format('Y-m-t H:i:s');

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
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
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
            'MONTH(h.CREATED_ON) AS obs_month',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND  DATE(h.CREATED_ON) <= "' . $endDate1 . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND DATE(h.CREATED_ON) <= "' . $endDate1 . '"', 'left'];
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

        $this->load->view('report/hse_category/monthly_hse_cat_chart', $data);
    }
    // HSE-category  end//


    // OBS Category Week Start

    public function obs_category_week()
    {
        $getdashdata = $this->input->get();
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        global $status_drop1, $risk_rating, $obs_type_list;

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
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_id,hse_cat'], $hsecatOptn);
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
            'pageTitle' => 'OBS-Category-Week',
            'view_file' => 'report/obs_category_week/list_form',
            'site_title' => 'OBS Category Week',
            'current_menu' => 'OBS Category Week',
            'getAllhsecat' => $getAllhsecat,
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'drophsecat' => $drophsecat,
            'getex_drop' => $getex_drop,
            'status_drop_proj' => $status_drop1,
            'getdashdata' => $getdashdata,
            'risk_rating' => $risk_rating,
            'obs_type_list' => $obs_type_list,
            'ajaxurl' => 'report/list_obs_category_week?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&hse_cat=' . $hse_cat . '&risk_id=' . $risk_id . '&obs_type=' . $obs_type . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date . '&Month=' . $Month . '&emp_id=' . $emp_id,
        ];
        $this->template->load_table_exp_template($data);
    }


    public function list_obs_category_week()
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
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
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

        $startDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 1, 1)
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');

        $endDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 12, 31)
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');


        $data['start_month'] = 1;
        $data['end_month'] = 12;
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

                $startDate1 = DateTime::createFromFormat('m-Y', $startmonthyr)
                    ->setTime(0, 0, 0)
                    ->format('Y-m-01 H:i:s');

                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');

                $endDate1 = DateTime::createFromFormat('m-Y', $endmonthyr)
                    ->setTime(23, 59, 59)
                    ->format('Y-m-t H:i:s');

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
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
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
            'WEEK(h.CREATED_ON) AS obs_week',
            'MONTH(h.CREATED_ON) AS obs_month',
            'YEAR(h.CREATED_ON) AS obs_year',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND  DATE(h.CREATED_ON) <= "' . $endDate1 . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND DATE(h.CREATED_ON) <= "' . $endDate1 . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'YEAR(h.CREATED_ON)', 'MONTH(h.CREATED_ON)', 'WEEK(h.CREATED_ON)',];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        if ($hse_cat > 0) {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y',
                'hse_id' =>  $hse_cat
            ];
        } else {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y'
            ];
        }
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_cat,hse_id'], $hsecatOptn);


        $yearWeeks = [];
        for ($year = $data['start_year']; $year <= $data['end_year']; $year++) {
            $start = ($year == $data['start_year']) ? $data['start_month'] : 1;  // Start month for the first year
            $end = ($year == $data['end_year']) ? $data['end_month'] : 12;  // End month for the last year

            for ($month = $start; $month <= $end; $month++) {
                $startDate = new DateTime("$year-$month-01"); // The first day of the month
                $endDate = new DateTime("$year-$month-01"); // Temporary end date
                $endDate->modify('last day of this month'); // Get the last day of the month

                // Get the week number for the first day of the month
                $startWeek = (int)$startDate->format('W'); // Week number of the first day of the month
                $endWeek = (int)$endDate->format('W'); // Week number of the last day of the month

                if ($month == 12 && $endWeek == 1) {
                    $endWeek = 52; // The last week of December should be the 52nd week of the current year
                }

                // Loop through the weeks of the month
                for ($week = $startWeek; $week <= $endWeek; $week++) {
                    $yearWeeks[] = [
                        'week' => $week
                    ];
                }
            }
        }



        $categories = [];
        foreach ($getAllhsecat as $category) {
            $categories[$category->hse_cat] = [
                'hse_id' => $category->hse_id,
                'year_month_wise' => [],

            ];

            foreach ($yearWeeks as $ym) {
                $categories[$category->hse_cat]['year_month_wise'][$ym['week']] = [
                    'total' => 0
                ];
            }
        }
        // echo "<pre>" . print_r($listUsee, true) . '</pre>';
        // exit;


        foreach ($listUsee as $entry) {
            if (isset($entry->obs_week) && $entry->obs_week == 0) {
                $entry->obs_week = 1;
            }
            if (isset($categories[$entry->hse_cat])) {
                $category = &$categories[$entry->hse_cat];


                if (!isset($category['year_month_wise'][$entry->obs_week])) {
                    $category['year_month_wise'][$entry->obs_week]['total'] = 0;
                }


                $category['year_month_wise'][$entry->obs_week]['total'] += $entry->total;
            }
        }

        foreach ($categories as &$category) {
            foreach ($category['year_month_wise'] as $week => &$weekData) {
                // Only keep the week if it has a non-zero total
                if ($weekData['total'] == 0) {
                    unset($category['year_month_wise'][$week]);  // Remove weeks with total 0
                }
            }
            // If no weeks remain, remove the year_month_wise key
            // if (empty($category['year_month_wise'])) {
            //     unset($category['year_month_wise']);
            // }
        }


        $data['data'] = $categories;
        // echo '<pre>' . print_r($categories, true) . '</pre>';

        // exit;
        $this->load->view('report/obs_category_week/view_table', $data);
    }

    public function obs_category__week_exportexcel()
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
            'Hod.EMP_NAME',
            "DATE_FORMAT(h.obs_report_datetime,'%d-%m-%Y %H:%i:%s')",
            "DATE_FORMAT(h.obs_supervisor_date,'%d-%m-%Y')",
            'FN_OBS_STATUS(h.obs_app_status)',
            null
        );

        $column_search = array(
            'h.obs_id',
            'FN_COMP_NAME(h.obs_comp_id)',
            'FN_AREA_NAME(h.obs_area_id)',
            'FN_BUILD_NAME(h.obs_building_id)',
            'FN_PROJECT_NAME(h.obs_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.obs_dept_id)',
            'FN_HSE_CAT_NAME(h.obs_cat_id)',
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
        // $searchextra = postData($request, 'searchextra');
        // if ($searchextra != FALSE && count($searchextra) > 0) {
        //     foreach ($searchextra as $search) {
        //         $sName = postData($search, 'name');
        //         $sValue = postData($search, 'value');
        //         $mappedData[$sName] = $sValue;
        //     }
        // }
        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');

        $startDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 1, 1)
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');

        $endDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 12, 31)
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');



        $data['start_month'] = 1;
        $data['end_month'] = 12;
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

                $startDate1 = DateTime::createFromFormat('m-Y', $startmonthyr)
                    ->setTime(0, 0, 0)
                    ->format('Y-m-01 H:i:s');

                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');

                $endDate1 = DateTime::createFromFormat('m-Y', $endmonthyr)
                    ->setTime(23, 59, 59)
                    ->format('Y-m-t H:i:s');

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
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
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
            'WEEK(h.CREATED_ON) AS obs_week',
            'MONTH(h.CREATED_ON) AS obs_month',
            'YEAR(h.CREATED_ON) AS obs_year',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND  DATE(h.CREATED_ON) <= "' . $endDate1 . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND DATE(h.CREATED_ON) <= "' . $endDate1 . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'YEAR(h.CREATED_ON)', 'MONTH(h.CREATED_ON)', 'WEEK(h.CREATED_ON)'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        if ($hse_cat > 0) {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y',
                'hse_id' =>  $hse_cat
            ];
        } else {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y'
            ];
        }
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_cat,hse_id'], $hsecatOptn);


        $yearWeeks = [];
        for ($year = $data['start_year']; $year <= $data['end_year']; $year++) {
            $start = ($year == $data['start_year']) ? $data['start_month'] : 1;  // Start month for the first year
            $end = ($year == $data['end_year']) ? $data['end_month'] : 12;  // End month for the last year

            for ($month = $start; $month <= $end; $month++) {
                $startDate = new DateTime("$year-$month-01"); // The first day of the month
                $endDate = new DateTime("$year-$month-01"); // Temporary end date
                $endDate->modify('last day of this month'); // Get the last day of the month

                $startWeek = (int)$startDate->format('W');
                $endWeek = (int)$endDate->format('W');

                if ($month == 12 && $endWeek == 1) {
                    $endWeek = 52;
                }

                for ($week = $startWeek; $week <= $endWeek; $week++) {
                    $yearWeeks[] = [
                        'week' => $week
                    ];
                }
            }
        }



        $categories = [];
        foreach ($getAllhsecat as $category) {
            $categories[$category->hse_cat] = [
                'hse_id' => $category->hse_id,
                'year_month_wise' => [],

            ];

            foreach ($yearWeeks as $ym) {
                $categories[$category->hse_cat]['year_month_wise'][$ym['week']] = [
                    'total' => 0
                ];
            }
        }



        foreach ($listUsee as $entry) {
            if (isset($entry->obs_week) && $entry->obs_week == 0) {
                $entry->obs_week = 1;
            }
            if (isset($categories[$entry->hse_cat])) {
                $category = &$categories[$entry->hse_cat];


                if (!isset($category['year_month_wise'][$entry->obs_week])) {
                    $category['year_month_wise'][$entry->obs_week]['total'] = 0;
                }

                $category['year_month_wise'][$entry->obs_week]['total'] += $entry->total;
            }
        }

        foreach ($categories as &$category) {
            foreach ($category['year_month_wise'] as $week => &$weekData) {
                if ($weekData['total'] == 0) {
                    unset($category['year_month_wise'][$week]);
                }
            }
        }


        $data['data'] = $categories;
        // echo '<pre>' . print_r($data['data'], true) . '</pre>';
        // exit;

        $this->load->view('report/obs_category_week/generate_excel', $data);
    }

    // OBS Category Week End



    // HSE OBS Tracker Graph Start
    public function hse_obs_tracker_graph()
    {
        $getdashdata = $this->input->get();
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        global $status_drop1, $risk_rating, $obs_type_list;

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


        $injcatOptn['where'] = [
            'inj_status' => 'Y'
        ];
        $getAllinjcat = $this->common_model->getAlldata(MAS_INJ, ['inj_id ,inj_cat'], $injcatOptn);
        $dropinjcat = customFormDropDown($getAllinjcat, 'inj_id', 'inj_cat', 'Select FAC Injury');

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
            'pageTitle' => 'HSE OBS Tracker Graph',
            'view_file' => 'report/hse_obs_tracker_graph/list_form',
            'site_title' => 'HSE OBS Tracker Graph',
            'current_menu' => 'HSE Obs Tracker Graph',
            'getAllhsecat' => $getAllhsecat,
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'drophsecat' => $drophsecat,
            'dropinjcat' => $dropinjcat,
            'getex_drop' => $getex_drop,
            'status_drop_proj' => $status_drop1,
            'getdashdata' => $getdashdata,
            'risk_rating' => $risk_rating,
            'obs_type_list' => $obs_type_list,
            'ajaxurl' => 'report/list_hse_obs_tracker_graph?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&hse_cat=' . $hse_cat . '&risk_id=' . $risk_id . '&obs_type=' . $obs_type . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date . '&Month=' . $Month . '&emp_id=' . $emp_id . '&fac_injury=' . $fac_injury,
        ];
        $this->template->load_table_exp_template($data);
    }

    public function list_hse_obs_tracker_graph()
    {
        ini_set('display_errors', '1');
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
        $startDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 1, 1)
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');

        $endDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 12, 31)
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');



        $data['start_month'] = 1;
        $data['end_month'] = 12;
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

            // $obs_options['where_new']["h.obs_app_status !="] = 0;


            // if ($searchStatus >= 0) {
            //     $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            // }
            // $options['where_new']["h.obs_app_status !="] = 0;

            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');

                $startDate1 = DateTime::createFromFormat('m-Y', $startmonthyr)
                    ->setTime(0, 0, 0)
                    ->format('Y-m-01 H:i:s');

                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');

                $endDate1 = DateTime::createFromFormat('m-Y', $endmonthyr)
                    ->setTime(23, 59, 59)
                    ->format('Y-m-t H:i:s');

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
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
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
            'WEEK(h.CREATED_ON) AS obs_week',
            'MONTH(h.CREATED_ON) AS obs_month',
            'YEAR(h.CREATED_ON) AS obs_year',
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND  DATE(h.CREATED_ON) <= "' . $endDate1 . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND DATE(h.CREATED_ON) <= "' . $endDate1 . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'YEAR(h.CREATED_ON)', 'MONTH(h.CREATED_ON)', 'WEEK(h.CREATED_ON)',];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        // echo '<pre>';print_r($listUsee);
        // echo $this->db->last_query();
        if ($hse_cat > 0) {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y',
                'hse_id' =>  $hse_cat
            ];
        } else {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y'
            ];
        }
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_cat,hse_id'], $hsecatOptn);

        $yearWeeks = [];
        for ($year = $data['start_year']; $year <= $data['end_year']; $year++) {
            $start = ($year == $data['start_year']) ? $data['start_month'] : 1;
            $end = ($year == $data['end_year']) ? $data['end_month'] : 12;

            for ($month = $start; $month <= $end; $month++) {
                $startDate = new DateTime("$year-$month-01");
                $endDate = new DateTime("$year-$month-01");
                $endDate->modify('last day of this month');

                $startWeek = (int)$startDate->format('W');

                $endWeek = (int)$endDate->format('W');

                if ($month == 12 && $endWeek == 1) {
                    $endWeek = 52;
                }

                for ($week = $startWeek; $week <= $endWeek; $week++) {
                    $yearWeeks[] = [
                        'week' => $week
                    ];
                }
            }
        }


        $categories = [];
        foreach ($getAllhsecat as $category) {
            $categories[$category->hse_cat] = [
                'hse_id' => $category->hse_id,
                'year_month_wise' => [],
                'overall_open_count' => 0,
                'overall_closed_count' => 0,
                'overall_total' => 0,
            ];

            foreach ($yearWeeks as $ym) {
                $categories[$category->hse_cat]['year_month_wise'][$ym['week']] = [
                    'open_count' => 0,
                    'closed_count' => 0,
                    'total' => 0
                ];
            }
        }



        foreach ($listUsee as $entry) {
            if (isset($entry->obs_week) && $entry->obs_week == 0) {
                $entry->obs_week = 1;
            }

            if (isset($categories[$entry->hse_cat])) {
                $category = &$categories[$entry->hse_cat];


                if (!isset($category['year_month_wise'][$entry->obs_week])) {

                    $category['year_month_wise'][$entry->obs_week] = [
                        'open_count' => 0,
                        'closed_count' => 0,
                        'total' => 0
                    ];
                }


                $category['year_month_wise'][$entry->obs_week]['open_count'] += $entry->open_count;
                $category['year_month_wise'][$entry->obs_week]['closed_count'] += $entry->closed_count;
                $category['year_month_wise'][$entry->obs_week]['total'] += $entry->total;

                $category['overall_open_count'] += $entry->open_count;
                $category['overall_closed_count'] += $entry->closed_count;
                $category['overall_total'] += $entry->total;
            }
        }

        foreach ($categories as &$category) {
            foreach ($category['year_month_wise'] as $week => &$weekData) {
                if ($weekData['total'] == 0) {
                    unset($category['year_month_wise'][$week]);
                }
            }
        }

        $data['data'] = $categories;
        // echo '<pre>' . print_r($data['data'], true) . '</pre>';
        // exit;
        $this->load->view('report/hse_obs_tracker_graph/view_table', $data);
    }

    public function hse_obs_tracker_exportexcel()
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
        // $searchextra = postData($request, 'searchextra');
        // if ($searchextra != FALSE && count($searchextra) > 0) {
        //     foreach ($searchextra as $search) {
        //         $sName = postData($search, 'name');
        //         $sValue = postData($search, 'value');
        //         $mappedData[$sName] = $sValue;
        //     }
        // }
        $data = [];
        $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');

        $startDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 1, 1)
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');

        $endDate1 = DateTime::createFromFormat('Y', $currentYear)
            ->setDate($currentYear, 12, 31)
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');



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

            // if ($searchStatus >= 0) {
            //     $obs_options['where_new']["h.obs_app_status"] = $searchStatus;
            // }
            $options['where_new']["h.obs_app_status !="] = 0;



            if ($startmonthyr != '') {
                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->format('Y-m-01');

                $startDate1 = DateTime::createFromFormat('m-Y', $startmonthyr)
                    ->setTime(0, 0, 0)
                    ->format('Y-m-01 H:i:s');

                list($month, $year) = explode('-', $startmonthyr);
                $data['start_month'] = $month;
                $data['start_year'] = $year;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->format('Y-m-t');

                $endDate1 = DateTime::createFromFormat('m-Y', $endmonthyr)
                    ->setTime(23, 59, 59)
                    ->format('Y-m-t H:i:s');

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
            //$obs_options['where_new']['h.obs_comp_id'] =  $user_clid;
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
            'WEEK(h.CREATED_ON) AS obs_week',
            'MONTH(h.CREATED_ON) AS obs_month',
            'YEAR(h.CREATED_ON) AS obs_year',
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'SUM(CASE WHEN h.obs_type_id > "0" THEN 1 ELSE 0 END) AS total',
        ];
        if (!empty($joinConditionsString)) {
            $options['join'][OBS_FLOW_SEE . ' as h'] = [
                'h.obs_cat_id = HC.hse_id AND h.obs_status = "Y" AND h.obs_app_status != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND  DATE(h.CREATED_ON) <= "' . $endDate1 . '"
                AND (' . $joinConditionsString . ')',
                'left'
            ];
        } else {
            $options['join'][OBS_FLOW_SEE . ' as h'] = ['h.obs_cat_id  = HC.hse_id  AND h.obs_status = "Y" and h.obs_app_status  != "0" AND DATE(h.CREATED_ON) >= "' . $startDate1 . '" AND DATE(h.CREATED_ON) <= "' . $endDate1 . '"', 'left'];
        }

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['HC.hse_id', 'YEAR(h.CREATED_ON)', 'MONTH(h.CREATED_ON)', 'WEEK(h.CREATED_ON)',];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        if ($hse_cat > 0) {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y',
                'hse_id' =>  $hse_cat
            ];
        } else {
            $hsecatOptn['where'] = [
                'hse_status' => 'Y'
            ];
        }
        $getAllhsecat = $this->common_model->getAlldata(MAS_HSE, ['hse_cat,hse_id'], $hsecatOptn);

        $yearWeeks = [];
        for ($year = $data['start_year']; $year <= $data['end_year']; $year++) {
            $start = ($year == $data['start_year']) ? $data['start_month'] : 1;
            $end = ($year == $data['end_year']) ? $data['end_month'] : 12;

            for ($month = $start; $month <= $end; $month++) {
                $startDate = new DateTime("$year-$month-01");
                $endDate = new DateTime("$year-$month-01");
                $endDate->modify('last day of this month');

                $startWeek = (int)$startDate->format('W');
                $endWeek = (int)$endDate->format('W');

                if ($month == 12 && $endWeek == 1) {
                    $endWeek = 52;
                }

                for ($week = $startWeek; $week <= $endWeek; $week++) {
                    $yearWeeks[] = [
                        'week' => $week
                    ];
                }
            }
        }


        $categories = [];
        foreach ($getAllhsecat as $category) {
            $categories[$category->hse_cat] = [
                'hse_id' => $category->hse_id,
                'year_month_wise' => [],
                'overall_open_count' => 0,
                'overall_closed_count' => 0,
                'overall_total' => 0,
            ];

            foreach ($yearWeeks as $ym) {
                $categories[$category->hse_cat]['year_month_wise'][$ym['week']] = [
                    'open_count' => 0,
                    'closed_count' => 0,
                    'total' => 0
                ];
            }
        }



        foreach ($listUsee as $entry) {
            if (isset($categories[$entry->hse_cat])) {
                $category = &$categories[$entry->hse_cat];


                if (!isset($category['year_month_wise'][$entry->obs_week])) {

                    $category['year_month_wise'][$entry->obs_week] = [
                        'open_count' => 0,
                        'closed_count' => 0,
                        'total' => 0
                    ];
                }


                $category['year_month_wise'][$entry->obs_week]['open_count'] += $entry->open_count;
                $category['year_month_wise'][$entry->obs_week]['closed_count'] += $entry->closed_count;
                $category['year_month_wise'][$entry->obs_week]['total'] += $entry->total;

                $category['overall_open_count'] += $entry->open_count;
                $category['overall_closed_count'] += $entry->closed_count;
                $category['overall_total'] += $entry->total;
            }
        }

        foreach ($categories as &$category) {
            foreach ($category['year_month_wise'] as $week => &$weekData) {
                if ($weekData['total'] == 0) {
                    unset($category['year_month_wise'][$week]);
                }
            }
        }

        $data['data'] = $categories;
        // echo '<pre>' . print_r($data['data'], true) . '</pre>';
        // exit;
        $this->load->view('report/hse_obs_tracker_graph/generate_excel', $data);
    }

    // HSE OBS Tracker Graph End


    // Status

    public function status()
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


        $injcatOptn['where'] = [
            'inj_status' => 'Y'
        ];
        $getAllinjcat = $this->common_model->getAlldata(MAS_INJ, ['inj_id ,inj_cat'], $injcatOptn);
        $dropinjcat = customFormDropDown($getAllinjcat, 'inj_id', 'inj_cat', 'Select FAC Injury');

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
            'pageTitle' => 'OBS Status',
            'view_file' => 'report/obs_status/list_form',
            'site_title' => 'OBS Status',
            'current_menu' => 'OBS Status',
            'getAllhsecat' => $getAllhsecat,
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'drophsecat' => $drophsecat,
            'dropinjcat' => $dropinjcat,
            'getex_drop' => $getex_drop,
            'status_drop_proj' => $status_drop,
            'getdashdata' => $getdashdata,
            'risk_rating' => $risk_rating,
            'obs_type_list' => $obs_type_list,
            'ajaxurl' => 'report/list_obs_status?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&hse_cat=' . $hse_cat . '&risk_id=' . $risk_id . '&obs_type=' . $obs_type . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date . '&Month=' . $Month . '&emp_id=' . $emp_id,
        ];
        $this->template->load_table_exp_template($data);
    }
    public function list_obs_status()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = OBS_FLOW_SEE . ' as h';
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
        //  $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        //  $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');

        $startDate = [];
        $endDate = [];
        $options['where_new'] = [];
        $where = [];

        $options['where_new'] = [
            'h.obs_status' => 'Y',
            'h.obs_app_status !=' => '0',
        ];

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
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');



            $obs_options['where_new'] = [];

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

            if ($startmonthyr != '') {

                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->setTime(00, 00, 00)->format('Y-m-01 H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) >='] = $startDate;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->setTime(23, 59, 59)->format('Y-m-t H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) <='] = $endDate;
            }
        }


        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            //$options['where_new']['h.obs_comp_id'] =  $user_clid;
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


        $options['select'] = [
            // 'YEAR(h.obs_date) AS obs_year',
            // 'MONTH(h.obs_date) AS obs_month',
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'SUM(CASE WHEN h.obs_type_id = "1" THEN 1 ELSE 0 END) AS ua_count',
            'SUM(CASE WHEN h.obs_type_id = "2" THEN 1 ELSE 0 END) AS uc_count',
            'COUNT(h.obs_id) AS total_count', // Total observations

            // Open count for 0-3 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS open_3_days_count',

            // Open count for 4-7 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) AS open_4to7_days_count',

            // Open count for 7-14 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 7 AND 14 THEN 1 ELSE 0 END) AS open_7to14_days_count',

            // Open count for 14-30 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 14 AND 30 THEN 1 ELSE 0 END) AS open_14to30_days_count',

            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) > 30 THEN 1 ELSE 0 END) AS open_more_than_30_days_count',


            'SUM(CASE WHEN h.is_closed = 0  AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 0 AND 3 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 7 AND 14 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 14 AND 30 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) > 30 THEN 1 ELSE 0 END) AS num_pending_days_count',

            // Close count for 0-3 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS close_3_days_count',

            // Close count for 4-7 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) AS close_4to7_days_count',

            // Close count for 7-14 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 7 AND 14 THEN 1 ELSE 0 END) AS close_7to14_days_count',

            // Close count for 14-30 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 14 AND 30 THEN 1 ELSE 0 END) AS close_14to30_days_count',

            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) > 30 THEN 1 ELSE 0 END) AS close_more_than_30_days_count',


            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 0 AND 3 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 7 AND 14 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 14 AND 30 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) > 30 THEN 1 ELSE 0 END) AS num_close_days_count',



            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 1 THEN 1 ELSE 0 END) AS jan_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 2 THEN 1 ELSE 0 END) AS feb_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 3 THEN 1 ELSE 0 END) AS mar_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 4 THEN 1 ELSE 0 END) AS apr_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 5 THEN 1 ELSE 0 END) AS may_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 6 THEN 1 ELSE 0 END) AS jun_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 7 THEN 1 ELSE 0 END) AS jul_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 8 THEN 1 ELSE 0 END) AS aug_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 9 THEN 1 ELSE 0 END) AS sep_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 10 THEN 1 ELSE 0 END) AS oct_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 11 THEN 1 ELSE 0 END) AS nov_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 12 THEN 1 ELSE 0 END) AS dec_open_count',



            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 1 THEN 1 ELSE 0 END) AS jan_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 2 THEN 1 ELSE 0 END) AS feb_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 3 THEN 1 ELSE 0 END) AS mar_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 4 THEN 1 ELSE 0 END) AS apr_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 5 THEN 1 ELSE 0 END) AS may_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 6 THEN 1 ELSE 0 END) AS jun_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 7 THEN 1 ELSE 0 END) AS jul_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 8 THEN 1 ELSE 0 END) AS aug_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 9 THEN 1 ELSE 0 END) AS sep_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 10 THEN 1 ELSE 0 END) AS oct_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 11 THEN 1 ELSE 0 END) AS nov_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 12 THEN 1 ELSE 0 END) AS dec_close_count',
        ];




        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['YEAR(h.CREATED_ON)', 'MONTH(h.CREATED_ON)'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        // echo $this->db->last_query();exit;



        $overallData = [
            'open_count' => 0,
            'closed_count' => 0,
            'ua_count' => 0,
            'uc_count' => 0,
            'total_count' => 0,

            'open_3_days_count' => 0,
            'open_4to7_days_count' => 0,
            'open_7to14_days_count' => 0,
            'open_14to30_days_count' => 0,
            'open_more_than_30_days_count' => 0,
            'num_pending_days_count' => 0,


            'close_3_days_count' => 0,
            'close_4to7_days_count' => 0,
            'close_7to14_days_count' => 0,
            'close_14to30_days_count' => 0,
            'close_more_than_30_days_count' => 0,
            'num_close_days_count' => 0,

            'jan_open_count' => 0,
            'feb_open_count' => 0,
            'mar_open_count' => 0,
            'apr_open_count' => 0,
            'may_open_count' => 0,
            'jun_open_count' => 0,
            'jul_open_count' => 0,
            'aug_open_count' => 0,
            'sep_open_count' => 0,
            'oct_open_count' => 0,
            'nov_open_count' => 0,
            'dec_open_count' => 0,
            'total_open_count' => 0,

            'jan_close_count' => 0,
            'feb_close_count' => 0,
            'mar_close_count' => 0,
            'apr_close_count' => 0,
            'may_close_count' => 0,
            'jun_close_count' => 0,
            'jul_close_count' => 0,
            'aug_close_count' => 0,
            'sep_close_count' => 0,
            'oct_close_count' => 0,
            'nov_close_count' => 0,
            'dec_close_count' => 0,
            'total_close_count' => 0,
        ];

        // Aggregate data from $listUsee
        if (is_array($listUsee) || is_object($listUsee)) {
            foreach ($listUsee as $entry) {
                $overallData['open_count'] += $entry->open_count;
                $overallData['closed_count'] += $entry->closed_count;
                $overallData['ua_count'] += $entry->ua_count;
                $overallData['uc_count'] += $entry->uc_count;
                $overallData['total_count'] += $entry->total_count;

                $overallData['open_3_days_count'] += $entry->open_3_days_count;
                $overallData['open_4to7_days_count'] += $entry->open_4to7_days_count;
                $overallData['open_7to14_days_count'] += $entry->open_7to14_days_count;
                $overallData['open_14to30_days_count'] += $entry->open_14to30_days_count;
                $overallData['open_more_than_30_days_count'] += $entry->open_more_than_30_days_count;
                $overallData['num_pending_days_count'] += $entry->num_pending_days_count;

                $overallData['close_3_days_count'] += $entry->close_3_days_count;
                $overallData['close_4to7_days_count'] += $entry->close_4to7_days_count;
                $overallData['close_7to14_days_count'] += $entry->close_7to14_days_count;
                $overallData['close_14to30_days_count'] += $entry->close_14to30_days_count;
                $overallData['close_more_than_30_days_count'] += $entry->close_more_than_30_days_count;
                $overallData['num_close_days_count'] += $entry->num_close_days_count;

                $overallData['jan_open_count'] += $entry->jan_open_count;
                $overallData['feb_open_count'] += $entry->feb_open_count;
                $overallData['mar_open_count'] += $entry->mar_open_count;
                $overallData['apr_open_count'] += $entry->apr_open_count;
                $overallData['may_open_count'] += $entry->may_open_count;
                $overallData['jun_open_count'] += $entry->jun_open_count;
                $overallData['jul_open_count'] += $entry->jul_open_count;
                $overallData['aug_open_count'] += $entry->aug_open_count;
                $overallData['sep_open_count'] += $entry->sep_open_count;
                $overallData['oct_open_count'] += $entry->oct_open_count;
                $overallData['nov_open_count'] += $entry->nov_open_count;
                $overallData['dec_open_count'] += $entry->dec_open_count;




                $overallData['jan_close_count'] += $entry->jan_close_count;
                $overallData['feb_close_count'] += $entry->feb_close_count;
                $overallData['mar_close_count'] += $entry->mar_close_count;
                $overallData['apr_close_count'] += $entry->apr_close_count;
                $overallData['may_close_count'] += $entry->may_close_count;
                $overallData['jun_close_count'] += $entry->jun_close_count;
                $overallData['jul_close_count'] += $entry->jul_close_count;
                $overallData['aug_close_count'] += $entry->aug_close_count;
                $overallData['sep_close_count'] += $entry->sep_close_count;
                $overallData['oct_close_count'] += $entry->oct_close_count;
                $overallData['nov_close_count'] += $entry->nov_close_count;
                $overallData['dec_close_count'] += $entry->dec_close_count;
            }
        }


        $overallData['open_percentage'] = $overallData['total_count'] > 0
            ? round(($overallData['open_count'] / $overallData['total_count']) * 100, 2)
            : 0.00;
        $overallData['closed_percentage'] = $overallData['total_count'] > 0
            ? round(($overallData['closed_count'] / $overallData['total_count']) * 100, 2)
            : 0.00;

        $overallData['ua_percentage'] = $overallData['total_count'] > 0
            ? round(($overallData['ua_count'] / $overallData['total_count']) * 100, 2)
            : 0.00;
        $overallData['uc_percentage'] = $overallData['total_count'] > 0
            ? round(($overallData['uc_count'] / $overallData['total_count']) * 100, 2)
            : 0.00;

        $total_percentage = $overallData['open_percentage'] + $overallData['closed_percentage'];


        $overallData['total_percentage'] = $total_percentage . '%';



        // Number of days pending
        $overallData['open_3_days_percentage'] = $overallData['num_pending_days_count'] > 0
            ? round(($overallData['open_3_days_count'] / $overallData['num_pending_days_count']) * 100, 2)
            : 0.00;

        $overallData['open_4to7_days_percentage'] = $overallData['num_pending_days_count'] > 0
            ? round(($overallData['open_4to7_days_count'] / $overallData['num_pending_days_count']) * 100, 2)
            : 0.00;

        $overallData['open_7to14_days_percentage'] = $overallData['num_pending_days_count'] > 0
            ? round(($overallData['open_7to14_days_count'] / $overallData['num_pending_days_count']) * 100, 2)
            : 0.00;

        $overallData['open_14to30_days_percentage'] = $overallData['num_pending_days_count'] > 0
            ? round(($overallData['open_14to30_days_count'] / $overallData['num_pending_days_count']) * 100, 2)
            : 0.00;

        $overallData['open_more_than_30_days_percentage'] = $overallData['num_pending_days_count'] > 0
            ? round(($overallData['open_more_than_30_days_count'] / $overallData['num_pending_days_count']) * 100, 2)
            : 0.00;

        $pending_days_percentage   = $overallData['open_3_days_percentage']  +  $overallData['open_4to7_days_percentage']  + $overallData['open_7to14_days_percentage']  +   $overallData['open_14to30_days_percentage'] + $overallData['open_more_than_30_days_percentage'] + $overallData['open_more_than_30_days_percentage'];

        $overallData['pending_days_percentage'] = $pending_days_percentage . '%';



        // Number of days to closure
        $overallData['close_3_days_percentage'] = $overallData['num_close_days_count'] > 0
            ? round(($overallData['open_3_days_count'] / $overallData['num_close_days_count']) * 100, 2)
            : 0.00;

        $overallData['close_4to7_days_percentage'] = $overallData['num_close_days_count'] > 0
            ? round(($overallData['close_4to7_days_count'] / $overallData['num_close_days_count']) * 100, 2)
            : 0.00;

        $overallData['close_7to14_days_percentage'] = $overallData['num_close_days_count'] > 0
            ? round(($overallData['close_7to14_days_count'] / $overallData['num_close_days_count']) * 100, 2)
            : 0.00;

        $overallData['close_14to30_days_percentage'] = $overallData['num_close_days_count'] > 0
            ? round(($overallData['close_14to30_days_count'] / $overallData['num_close_days_count']) * 100, 2)
            : 0.00;

        $overallData['close_more_than_30_days_percentage'] = $overallData['num_close_days_count'] > 0
            ? round(($overallData['close_more_than_30_days_count'] / $overallData['num_close_days_count']) * 100, 2)
            : 0.00;

        $close_days_percentage   = $overallData['close_3_days_percentage']  +  $overallData['close_4to7_days_percentage']  + $overallData['close_7to14_days_percentage']  +   $overallData['close_14to30_days_percentage'] + $overallData['close_more_than_30_days_percentage'] + $overallData['close_more_than_30_days_percentage'];

        $overallData['close_days_percentage'] = $close_days_percentage . '%';

        $overallData['total_open_count'] = $overallData['jan_open_count'] +
            $overallData['feb_open_count'] +
            $overallData['mar_open_count'] +
            $overallData['apr_open_count'] +
            $overallData['may_open_count'] +
            $overallData['jun_open_count'] +
            $overallData['jul_open_count'] +
            $overallData['aug_open_count'] +
            $overallData['sep_open_count'] +
            $overallData['oct_open_count'] +
            $overallData['nov_open_count'] +
            $overallData['dec_open_count'];


        $overallData['total_close_count'] = $overallData['jan_close_count'] +
            $overallData['feb_close_count'] +
            $overallData['mar_close_count'] +
            $overallData['apr_close_count'] +
            $overallData['may_close_count'] +
            $overallData['jun_close_count'] +
            $overallData['jul_close_count'] +
            $overallData['aug_close_count'] +
            $overallData['sep_close_count'] +
            $overallData['oct_close_count'] +
            $overallData['nov_close_count'] +
            $overallData['dec_close_count'];

        $data['overallData'] = $overallData;


        $this->load->view('report/obs_status/view_table', $data);
    }



    public function total_obs_status_count()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;
        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = OBS_FLOW_SEE . ' as h';
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
        // $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        // $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');


        $startDate = [];
        $endDate = [];

        $options['where_new'] = [];
        $where = [];

        $options['where_new'] = [
            'h.obs_status' => 'Y',
            'h.obs_app_status !=' => '0',
        ];

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
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');

            //  print_r($risk_id);die;

            $obs_options['where_new'] = [];;

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
            if ($startmonthyr != '') {

                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->setTime(00, 00, 00)->format('Y-m-01 H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) >='] = $startDate;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->setTime(23, 59, 59)->format('Y-m-t H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) <='] = $endDate;
            }
        }


        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            //$options['where_new']['h.obs_comp_id'] =  $user_clid;
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





        $options['select'] = [
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'COUNT(h.obs_id) AS total_count',
        ];


        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];
        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];

        // Group by year and month
        $options['group_by'] = ['MONTH(h.CREATED_ON)'];


        // Fetch data
        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);


        $data['listUsee'] = $listUsee;
        $this->load->view('report/obs_status/total_obs_status_chart', $data);
    }



    public function total_days_pending_chart()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;
        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = OBS_FLOW_SEE . ' as h';
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
        // $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        // $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $startDate = [];
        $endDate = [];

        $options['where_new'] = [];
        $where = [];

        $options['where_new'] = [
            'h.obs_status' => 'Y',
            'h.obs_app_status !=' => '0',
        ];

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
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');

            //  print_r($risk_id);die;

            $obs_options['where_new'] = [];;

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
            if ($startmonthyr != '') {

                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->setTime(00, 00, 00)->format('Y-m-01 H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) >='] = $startDate;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->setTime(23, 59, 59)->format('Y-m-t H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) <='] = $endDate;
            }
        }


        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            //$options['where_new']['h.obs_comp_id'] =  $user_clid;
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



        $options['select'] = [
            // 'YEAR(h.obs_date) AS obs_year',
            // 'MONTH(h.obs_date) AS obs_month',
            // Open count for 0-3 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS open_3_days_count',

            // Open count for 4-7 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) AS open_4to7_days_count',

            // Open count for 7-14 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 7 AND 14 THEN 1 ELSE 0 END) AS open_7to14_days_count',

            // Open count for 14-30 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 14 AND 30 THEN 1 ELSE 0 END) AS open_14to30_days_count',

            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) > 30 THEN 1 ELSE 0 END) AS open_more_than_30_days_count',

        ];



        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['MONTH(h.CREATED_ON)'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $data['listUsee'] = $listUsee;
        $this->load->view('report/obs_status/total_pending_days_chart', $data);
    }

    public function total_days_close_chart()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;
        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = OBS_FLOW_SEE . ' as h';
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
        // $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        // $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $startDate = [];
        $endDate = [];
        $options['where_new'] = [];
        $where = [];

        $options['where_new'] = [
            'h.obs_status' => 'Y',
            'h.obs_app_status !=' => '0',
        ];

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
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');

            //  print_r($risk_id);die;

            $obs_options['where_new'] = [];;

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

            if ($startmonthyr != '') {

                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->setTime(00, 00, 00)->format('Y-m-01 H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) >='] = $startDate;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->setTime(23, 59, 59)->format('Y-m-t H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) <='] = $endDate;
            }
        }


        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            //$options['where_new']['h.obs_comp_id'] =  $user_clid;
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






        $options['select'] = [

            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS close_3_days_count',

            // Close count for 4-7 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) AS close_4to7_days_count',

            // Close count for 7-14 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 7 AND 14 THEN 1 ELSE 0 END) AS close_7to14_days_count',

            // Close count for 14-30 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 14 AND 30 THEN 1 ELSE 0 END) AS close_14to30_days_count',

            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) > 30 THEN 1 ELSE 0 END) AS close_more_than_30_days_count',



        ];



        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['MONTH(h.CREATED_ON)'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $data['listUsee'] = $listUsee;
        $this->load->view('report/obs_status/total_close_days_chart', $data);
    }

    public function ua_uc_status()
    {
        global $obsPermission, $risk_rating_batch, $obs_type_list;
        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = OBS_FLOW_SEE . ' as h';
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
        // $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        // $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');
        $startDate = [];
        $endDate = [];
        $options['where_new'] = [];
        $where = [];

        $options['where_new'] = [
            'h.obs_status' => 'Y',
            'h.obs_app_status !=' => '0',
        ];

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
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');

            //  print_r($risk_id);die;

            $obs_options['where_new'] = [];;

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
            if ($startmonthyr != '') {

                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->setTime(00, 00, 00)->format('Y-m-01 H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) >='] = $startDate;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->setTime(23, 59, 59)->format('Y-m-t H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) <='] = $endDate;
            }
        }


        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            //$options['where_new']['h.obs_comp_id'] =  $user_clid;
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


        $options['select'] = [

            'SUM(CASE WHEN h.obs_type_id = "1" THEN 1 ELSE 0 END) AS ua_count',
            'SUM(CASE WHEN h.obs_type_id = "2" THEN 1 ELSE 0 END) AS uc_count',
            'COUNT(h.obs_id) AS total_count', // Total observations  
        ];

        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID AND LD2.LOGIN_ID IS NOT NULL', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID AND LD3.LOGIN_ID IS NOT NULL', 'left'];

        $options['group_by'] = ['MONTH(h.CREATED_ON)'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);


        $data['listUsee'] = $listUsee;

        $this->load->view('report/obs_status/ua_uc_status_chart', $data);
    }

    public function obs_status_exportexcel()

    {

        global $obsPermission, $risk_rating_batch, $obs_type_list;

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = OBS_FLOW_SEE . ' as h';
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
        $where = [];
        $mappedData = $this->input->get();

        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [];
        //  $startDate = DateTime::createFromFormat('m-Y', "01-$currentYear")->format('Y-m-01');
        //  $endDate = DateTime::createFromFormat('m-Y', "$currentMonth-$currentYear")->format('Y-m-t');

        $startDate = [];
        $endDate = [];

        $options['where_new'] = [];
        $where = [];

        $options['where_new'] = [
            'h.obs_status' => 'Y',
            'h.obs_app_status !=' => '0',
        ];

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
            $startmonthyr = postData($mappedData, 'start_date');
            $endmonthyr = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');

            //  print_r($risk_id);die;

            $obs_options['where_new'] = [];;

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
            if ($startmonthyr != '') {

                $startDate = DateTime::createFromFormat('m-Y', $startmonthyr)->setTime(00, 00, 00)->format('Y-m-01 H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) >='] = $startDate;
            }
            if ($endmonthyr != '') {
                $endDate = DateTime::createFromFormat('m-Y', $endmonthyr)->setTime(23, 59, 59)->format('Y-m-t H:i:s');
                $options['where_new']['DATE(h.CREATED_ON) <='] = $endDate;
            }
        }


        if (in_array($user_type, $obsPermission['view_supadmin'])) {
            $options['where_new']['h.obs_status'] =  'Y';
        } elseif (in_array($user_type, $obsPermission['view_ad'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            $options['where_new']['h.obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $obsPermission['view_assigner'])) {
            $options['where_new']['h.obs_status'] =  'Y';
            //$options['where_new']['h.obs_comp_id'] =  $user_clid;
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



        $options['select'] = [
            // 'YEAR(h.obs_date) AS obs_year',
            // 'MONTH(h.obs_date) AS obs_month',
            'SUM(CASE WHEN h.is_closed = 0 THEN 1 ELSE 0 END) AS open_count',
            'SUM(CASE WHEN h.is_closed = 1 THEN 1 ELSE 0 END) AS closed_count',
            'SUM(CASE WHEN h.obs_type_id = "1" THEN 1 ELSE 0 END) AS ua_count',
            'SUM(CASE WHEN h.obs_type_id = "2" THEN 1 ELSE 0 END) AS uc_count',
            'COUNT(h.obs_id) AS total_count', // Total observations

            // Open count for 0-3 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS open_3_days_count',

            // Open count for 4-7 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) AS open_4to7_days_count',

            // Open count for 7-14 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 7 AND 14 THEN 1 ELSE 0 END) AS open_7to14_days_count',

            // Open count for 14-30 days
            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 14 AND 30 THEN 1 ELSE 0 END) AS open_14to30_days_count',

            'SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) > 30 THEN 1 ELSE 0 END) AS open_more_than_30_days_count',


            'SUM(CASE WHEN h.is_closed = 0  AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 0 AND 3 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 7 AND 14 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 14 AND 30 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 0 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) > 30 THEN 1 ELSE 0 END) AS num_pending_days_count',

            // Close count for 0-3 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS close_3_days_count',

            // Close count for 4-7 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) AS close_4to7_days_count',

            // Close count for 7-14 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 7 AND 14 THEN 1 ELSE 0 END) AS close_7to14_days_count',

            // Close count for 14-30 days
            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 14 AND 30 THEN 1 ELSE 0 END) AS close_14to30_days_count',

            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) > 30 THEN 1 ELSE 0 END) AS close_more_than_30_days_count',


            'SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 0 AND 3 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 4 AND 7 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 7 AND 14 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) BETWEEN 14 AND 30 THEN 1 ELSE 0 END) +
            SUM(CASE WHEN h.is_closed = 1 AND DATEDIFF(DATE(h.obs_assigner_target_date), DATE(h.obs_date)) > 30 THEN 1 ELSE 0 END) AS num_close_days_count',



            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 1 THEN 1 ELSE 0 END) AS jan_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 2 THEN 1 ELSE 0 END) AS feb_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 3 THEN 1 ELSE 0 END) AS mar_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 4 THEN 1 ELSE 0 END) AS apr_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 5 THEN 1 ELSE 0 END) AS may_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 6 THEN 1 ELSE 0 END) AS jun_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 7 THEN 1 ELSE 0 END) AS jul_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 8 THEN 1 ELSE 0 END) AS aug_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 9 THEN 1 ELSE 0 END) AS sep_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 10 THEN 1 ELSE 0 END) AS oct_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 11 THEN 1 ELSE 0 END) AS nov_open_count',
            'SUM(CASE WHEN h.is_closed = 0 AND MONTH(h.CREATED_ON) = 12 THEN 1 ELSE 0 END) AS dec_open_count',


            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 1 THEN 1 ELSE 0 END) AS jan_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 2 THEN 1 ELSE 0 END) AS feb_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 3 THEN 1 ELSE 0 END) AS mar_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 4 THEN 1 ELSE 0 END) AS apr_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 5 THEN 1 ELSE 0 END) AS may_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 6 THEN 1 ELSE 0 END) AS jun_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 7 THEN 1 ELSE 0 END) AS jul_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 8 THEN 1 ELSE 0 END) AS aug_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 9 THEN 1 ELSE 0 END) AS sep_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 10 THEN 1 ELSE 0 END) AS oct_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 11 THEN 1 ELSE 0 END) AS nov_close_count',
            'SUM(CASE WHEN h.is_closed = 1 AND MONTH(h.CREATED_ON) = 12 THEN 1 ELSE 0 END) AS dec_close_count',
        ];


        $options['join'][LOGIN . ' as LD2'] = ['LD2.LOGIN_ID = h.obs_assigner_id', 'left'];
        $options['join'][EMPL . ' as Hod'] = ['Hod.EMP_AUTO_ID = LD2.USER_REF_ID', 'left'];

        $options['join'][LOGIN . ' as LD3'] = ['LD3.LOGIN_ID = h.obs_reporter_id', 'left'];
        $options['join'][EMPL . ' as EMP1'] = ['EMP1.EMP_AUTO_ID = LD3.USER_REF_ID', 'left'];
        $options['group_by'] = ['YEAR(h.CREATED_ON)', 'MONTH(h.CREATED_ON)'];


        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        // echo $this->db->last_query();
        // echo '<pre>';print_r($listUsee);exit;


        $overallData = [
            'open_count' => 0,
            'closed_count' => 0,
            'ua_count' => 0,
            'uc_count' => 0,
            'total_count' => 0,

            'open_3_days_count' => 0,
            'open_4to7_days_count' => 0,
            'open_7to14_days_count' => 0,
            'open_14to30_days_count' => 0,
            'open_more_than_30_days_count' => 0,
            'num_pending_days_count' => 0,


            'close_3_days_count' => 0,
            'close_4to7_days_count' => 0,
            'close_7to14_days_count' => 0,
            'close_14to30_days_count' => 0,
            'close_more_than_30_days_count' => 0,
            'num_close_days_count' => 0,

            'jan_open_count' => 0,
            'feb_open_count' => 0,
            'mar_open_count' => 0,
            'apr_open_count' => 0,
            'may_open_count' => 0,
            'jun_open_count' => 0,
            'jul_open_count' => 0,
            'aug_open_count' => 0,
            'sep_open_count' => 0,
            'oct_open_count' => 0,
            'nov_open_count' => 0,
            'dec_open_count' => 0,
            'total_open_count' => 0,


            'jan_close_count' => 0,
            'feb_close_count' => 0,
            'mar_close_count' => 0,
            'apr_close_count' => 0,
            'may_close_count' => 0,
            'jun_close_count' => 0,
            'jul_close_count' => 0,
            'aug_close_count' => 0,
            'sep_close_count' => 0,
            'oct_close_count' => 0,
            'nov_close_count' => 0,
            'dec_close_count' => 0,
            'total_close_count' => 0,
        ];

        // Aggregate data from $listUsee
        foreach ($listUsee as $entry) {
            $overallData['open_count'] += $entry->open_count;
            $overallData['closed_count'] += $entry->closed_count;
            $overallData['ua_count'] += $entry->ua_count;
            $overallData['uc_count'] += $entry->uc_count;
            $overallData['total_count'] += $entry->total_count;

            $overallData['open_3_days_count'] += $entry->open_3_days_count;
            $overallData['open_4to7_days_count'] += $entry->open_4to7_days_count;
            $overallData['open_7to14_days_count'] += $entry->open_7to14_days_count;
            $overallData['open_14to30_days_count'] += $entry->open_14to30_days_count;
            $overallData['open_more_than_30_days_count'] += $entry->open_more_than_30_days_count;
            $overallData['num_pending_days_count'] += $entry->num_pending_days_count;

            $overallData['close_3_days_count'] += $entry->close_3_days_count;
            $overallData['close_4to7_days_count'] += $entry->close_4to7_days_count;
            $overallData['close_7to14_days_count'] += $entry->close_7to14_days_count;
            $overallData['close_14to30_days_count'] += $entry->close_14to30_days_count;
            $overallData['close_more_than_30_days_count'] += $entry->close_more_than_30_days_count;
            $overallData['num_close_days_count'] += $entry->num_close_days_count;

            $overallData['jan_open_count'] += $entry->jan_open_count;
            $overallData['feb_open_count'] += $entry->feb_open_count;
            $overallData['mar_open_count'] += $entry->mar_open_count;
            $overallData['apr_open_count'] += $entry->apr_open_count;
            $overallData['may_open_count'] += $entry->may_open_count;
            $overallData['jun_open_count'] += $entry->jun_open_count;
            $overallData['jul_open_count'] += $entry->jul_open_count;
            $overallData['aug_open_count'] += $entry->aug_open_count;
            $overallData['sep_open_count'] += $entry->sep_open_count;
            $overallData['oct_open_count'] += $entry->oct_open_count;
            $overallData['nov_open_count'] += $entry->nov_open_count;
            $overallData['dec_open_count'] += $entry->dec_open_count;

            $overallData['jan_close_count'] += $entry->jan_close_count;
            $overallData['feb_close_count'] += $entry->feb_close_count;
            $overallData['mar_close_count'] += $entry->mar_close_count;
            $overallData['apr_close_count'] += $entry->apr_close_count;
            $overallData['may_close_count'] += $entry->may_close_count;
            $overallData['jun_close_count'] += $entry->jun_close_count;
            $overallData['jul_close_count'] += $entry->jul_close_count;
            $overallData['aug_close_count'] += $entry->aug_close_count;
            $overallData['sep_close_count'] += $entry->sep_close_count;
            $overallData['oct_close_count'] += $entry->oct_close_count;
            $overallData['nov_close_count'] += $entry->nov_close_count;
            $overallData['dec_close_count'] += $entry->dec_close_count;
        }


        $overallData['open_percentage'] = $overallData['total_count'] > 0
            ? round(($overallData['open_count'] / $overallData['total_count']) * 100, 2)
            : 0.00;
        $overallData['closed_percentage'] = $overallData['total_count'] > 0
            ? round(($overallData['closed_count'] / $overallData['total_count']) * 100, 2)
            : 0.00;

        $overallData['ua_percentage'] = $overallData['total_count'] > 0
            ? round(($overallData['ua_count'] / $overallData['total_count']) * 100, 2)
            : 0.00;
        $overallData['uc_percentage'] = $overallData['total_count'] > 0
            ? round(($overallData['uc_count'] / $overallData['total_count']) * 100, 2)
            : 0.00;

        $total_percentage = $overallData['open_percentage'] + $overallData['closed_percentage'];


        $overallData['total_percentage'] = $total_percentage . '%';



        // Number of days pending
        $overallData['open_3_days_percentage'] = $overallData['num_pending_days_count'] > 0
            ? round(($overallData['open_3_days_count'] / $overallData['num_pending_days_count']) * 100, 2)
            : 0.00;

        $overallData['open_4to7_days_percentage'] = $overallData['num_pending_days_count'] > 0
            ? round(($overallData['open_4to7_days_count'] / $overallData['num_pending_days_count']) * 100, 2)
            : 0.00;

        $overallData['open_7to14_days_percentage'] = $overallData['num_pending_days_count'] > 0
            ? round(($overallData['open_7to14_days_count'] / $overallData['num_pending_days_count']) * 100, 2)
            : 0.00;

        $overallData['open_14to30_days_percentage'] = $overallData['num_pending_days_count'] > 0
            ? round(($overallData['open_14to30_days_count'] / $overallData['num_pending_days_count']) * 100, 2)
            : 0.00;

        $overallData['open_more_than_30_days_percentage'] = $overallData['num_pending_days_count'] > 0
            ? round(($overallData['open_more_than_30_days_count'] / $overallData['num_pending_days_count']) * 100, 2)
            : 0.00;

        $pending_days_percentage   = $overallData['open_3_days_percentage']  +  $overallData['open_4to7_days_percentage']  + $overallData['open_7to14_days_percentage']  +   $overallData['open_14to30_days_percentage'] + $overallData['open_more_than_30_days_percentage'] + $overallData['open_more_than_30_days_percentage'];

        $overallData['pending_days_percentage'] = $pending_days_percentage . '%';



        // Number of days to closure
        $overallData['close_3_days_percentage'] = $overallData['num_close_days_count'] > 0
            ? round(($overallData['open_3_days_count'] / $overallData['num_close_days_count']) * 100, 2)
            : 0.00;

        $overallData['close_4to7_days_percentage'] = $overallData['num_close_days_count'] > 0
            ? round(($overallData['close_4to7_days_count'] / $overallData['num_close_days_count']) * 100, 2)
            : 0.00;

        $overallData['close_7to14_days_percentage'] = $overallData['num_close_days_count'] > 0
            ? round(($overallData['close_7to14_days_count'] / $overallData['num_close_days_count']) * 100, 2)
            : 0.00;

        $overallData['close_14to30_days_percentage'] = $overallData['num_close_days_count'] > 0
            ? round(($overallData['close_14to30_days_count'] / $overallData['num_close_days_count']) * 100, 2)
            : 0.00;

        $overallData['close_more_than_30_days_percentage'] = $overallData['num_close_days_count'] > 0
            ? round(($overallData['close_more_than_30_days_count'] / $overallData['num_close_days_count']) * 100, 2)
            : 0.00;

        $close_days_percentage   = $overallData['close_3_days_percentage']  +  $overallData['close_4to7_days_percentage']  + $overallData['close_7to14_days_percentage']  +   $overallData['close_14to30_days_percentage'] + $overallData['close_more_than_30_days_percentage'] + $overallData['close_more_than_30_days_percentage'];

        $overallData['close_days_percentage'] = $close_days_percentage . '%';

        $overallData['total_open_count'] = $overallData['jan_open_count'] +
            $overallData['feb_open_count'] +
            $overallData['mar_open_count'] +
            $overallData['apr_open_count'] +
            $overallData['may_open_count'] +
            $overallData['jun_open_count'] +
            $overallData['jul_open_count'] +
            $overallData['aug_open_count'] +
            $overallData['sep_open_count'] +
            $overallData['oct_open_count'] +
            $overallData['nov_open_count'] +
            $overallData['dec_open_count'];


        $overallData['total_close_count'] = $overallData['jan_close_count'] +
            $overallData['feb_close_count'] +
            $overallData['mar_close_count'] +
            $overallData['apr_close_count'] +
            $overallData['may_close_count'] +
            $overallData['jun_close_count'] +
            $overallData['jul_close_count'] +
            $overallData['aug_close_count'] +
            $overallData['sep_close_count'] +
            $overallData['oct_close_count'] +
            $overallData['nov_close_count'] +
            $overallData['dec_close_count'];

        $data['overallData'] = $overallData;

        $this->load->view('report/obs_status/generate_excel', $data);
    }
}
