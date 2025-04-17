<?php

defined('BASEPATH') or exit('No direct script access allowed');
// ini_set('display_errors', 1);
class Chart extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/common_model');
        // $this->load->model('dashboard_model');
        isLogin();
    }

    public function index()
    {

        //ini_set('display_errors', 1);
        global $dashPermission;

        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        // $calendarInfo = $this->getCalendarInfo();
        $data = array(
            // 'calendarInfo' => $calendarInfo,
            'view_file' => 'dashboard/chart',
            'title' => 'Dashboard',
            'current_menu' => 'Dashboard',
            'getCompany' => $dropcompany,
            'dropproject' => $dropproject,


            'headerfiles' => array(
                "css" => array(),
                "js" => array(
                    "assets/plugins/amcharts/amcharts.js",
                    "assets/plugins/amcharts/serial.js",
                    "assets/plugins/amcharts/pie.js",
                    "assets/plugins/amcharts/themes/light.js"
                ),
                "priority" => 'high'
            )
        );

        // echo "<pre>";
        // print_r($data);exit;

        $this->template->load_common_template($data);
    }

    //company
    public function getCompany_name($com_id)
    {
        $query = $this->db->select('company_full_name')
            ->from(MAS_COMP)
            ->where('company_status', 'Y')
            ->where('company_id', $com_id)
            ->get();
        $company_name = '';
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $company_name = $row->company_full_name;
        }

        return $company_name;
    }

    //area
    public function getArea_name($area_id)
    {
        $query = $this->db->select('area_name')
            ->from(MAS_AREA)
            ->where('area_id', $area_id)
            ->get();

        $area_name = '';
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $area_name = $row->area_name;
        }

        return $area_name;
    }

    //building
    public function getBuilding_name($building_id)
    {
        $query = $this->db->select('building_name')
            ->from(MAS_BUILDING)
            ->where('building_id', $building_id)
            ->get();

        $building_name = '';
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $building_name = $row->building_name;
        }

        return $building_name;
    }

    //department
    public function getDepartment_name($dept_id)
    {

        $query = $this->db->select('dept_name')
            ->from(MAS_DEPT)
            ->where('dept_id', $dept_id)
            ->get();

        $department_name = '';
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $department_name = $row->dept_name;
        }

        return $department_name;
    }

    //project
    public function getProject_name($project_id)
    {
        $query = $this->db->select('project_name')
            ->from(MAS_PROJ)
            ->where('project_id', $project_id)
            ->get();

        $project_name = '';
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $project_name = $row->project_name;
        }

        return $project_name;
    }





    public function getalldashboardmetrics()
    {
        //Inspection
        global $dashPermission;
        global $status_val;
        $data = [];
        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();
        $getdashdata = $this->input->get();




        //observation
        $obs_closed = $status_val['obs_closed'];
        $select_obs = [
            "COALESCE(COUNT(*),0) as obs_count, COALESCE(ROUND((SUM(CASE WHEN obs_app_status = $obs_closed THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2),0) AS closure_percentage"
        ];
        $options_obs['return_type'] = 'row';



        if (in_array($user_type, $dashPermission['view_supadmin'])) {
            $options_obs['where']['obs_status'] =  'Y';
        } elseif (in_array($user_type, $dashPermission['view_ad'])) {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['view_assigner'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['fix'])) {
            $options_obs['orwhere']['obs_assigner_id'] =  $userid;
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['orwhere']['obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $dashPermission['approve'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['approve_final'])) {
            $options_obs['where']['obs_status'] =  'Y';
            // $options_obs['where']['obs_comp_id'] =  $user_clid;
        } else {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_reporter_id'] =  $userid;
        }

        //filter
        if ($getdashdata['Company'] > 0  && $getdashdata['Company'] != 'null') {
            $options_obs['where']["obs_comp_id"] = (int)$getdashdata['Company'];
        }
        if ($getdashdata['Area'] > 0  && $getdashdata['Area'] != 'null') {
            $options_obs['where']["obs_area_id"] = (int)$getdashdata['Area'];
        }
        if ($getdashdata['Building'] > 0  && $getdashdata['Building'] != 'null') {
            $options_obs['where']["obs_building_id"] = (int)$getdashdata['Building'];
        }
        if ($getdashdata['Department'] > 0  && $getdashdata['Department'] != 'null') {
            $options_obs['where']["obs_dept_id"] = (int)$getdashdata['Department'];
        }
        if ($getdashdata['Project'] > 0  && $getdashdata['Project'] != 'null') {
            $options_obs['where']["obs_project_id"] = (int)$getdashdata['Project'];
        }

        if ($getdashdata['Start_Date'] != 'null' && $getdashdata['Start_Date'] != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($getdashdata['Start_Date']));
            $options_obs['where']['CREATED_ON >='] = $thstartdate;
        }
        if ($getdashdata['End_Date'] != 'null' && $getdashdata['End_Date'] != '') {
            $enddate = date('Y-m-d', strtotime($getdashdata['End_Date']));
            $options_obs['where']['CREATED_ON <='] = $enddate . ' 23:59:59';
        }
        $data['observation'] = $this->common_model->getAlldata(OBS_MAIN_SEE, $select_obs, $options_obs);
        echo json_encode($data);
    }


    //Observation
    public function observationtotalmonthwise()
    {
        global $dashPermission;
        global $dashboard_orange, $atar_color_code;
        global $status_val;
        $data = [];

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();
        $getdashdata = $this->input->get();

        $select_obs = [
            "MONTHNAME(CREATED_ON) as month,
            COALESCE(COUNT(*),0) as total"
        ];
        $options_obs['group_by'] = 'month';
        $options_obs['return_type'] = 'result';

        if (in_array($user_type, $dashPermission['view_supadmin'])) {
            $options_obs['where']['obs_status'] =  'Y';
        } elseif (in_array($user_type, $dashPermission['view_ad'])) {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['view_assigner'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['fix'])) {
            $options_obs['orwhere']['obs_assigner_id'] =  $userid;
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['orwhere']['obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $dashPermission['approve'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['approve_final'])) {
            $options_obs['where']['obs_status'] =  'Y';
            // $options_obs['where']['obs_comp_id'] =  $user_clid;
        } else {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_reporter_id'] =  $userid;
        }

        //filter
        if ($getdashdata['Company'] > 0  && $getdashdata['Company'] != 'null') {
            $options_obs['where']["obs_comp_id"] = (int)$getdashdata['Company'];
        }
        if ($getdashdata['Area'] > 0  && $getdashdata['Area'] != 'null') {
            $options_obs['where']["obs_area_id"] = (int)$getdashdata['Area'];
        }
        if ($getdashdata['Building'] > 0  && $getdashdata['Building'] != 'null') {
            $options_obs['where']["obs_building_id"] = (int)$getdashdata['Building'];
        }
        if ($getdashdata['Department'] > 0  && $getdashdata['Department'] != 'null') {
            $options_obs['where']["obs_dept_id"] = (int)$getdashdata['Department'];
        }
        if ($getdashdata['Project'] > 0  && $getdashdata['Project'] != 'null') {
            $options_obs['where']["obs_project_id"] = (int)$getdashdata['Project'];
        }

        if ($getdashdata['Start_Date'] != 'null' && $getdashdata['Start_Date'] != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($getdashdata['Start_Date']));
            $options_obs['where']['CREATED_ON >='] = $thstartdate;
        }
        if ($getdashdata['End_Date'] != 'null' && $getdashdata['End_Date'] != '') {
            $enddate = date('Y-m-d', strtotime($getdashdata['End_Date']));
            $options_obs['where']['CREATED_ON <='] = $enddate . ' 23:59:59';
        }

        $chartData = $this->common_model->getAlldata(OBS_MAIN_SEE, $select_obs, $options_obs);
        $monthDetails = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        if (!empty($chartData)) {
            foreach ($chartData as $key => $valdata) {
                if (isset($valdata->month)) {
                    $data[$valdata->month] = (array) $valdata;
                }
            }
        }
        foreach ($monthDetails as $key => $value) {

            if (isset($data[$value])) {
                $chartDataArray['label'][] = $value;
                $chartDataArray['total'][] = $data[$value]['total'];
            } else {
                $chartDataArray['label'][] = $value;
                $chartDataArray['total'][] = 0;
            }
        }

        if (isset($getdashdata['Company']) && $getdashdata['Company'] > 0) {
            $getdashdata['CompanyName'] = $this->getCompany_name($getdashdata['Company']);
        }
        if (isset($getdashdata['Area']) && $getdashdata['Area'] > 0) {
            $getdashdata['AreaName'] = $this->getArea_name($getdashdata['Area']);
        }
        if (isset($getdashdata['Building']) && $getdashdata['Building'] > 0) {
            $getdashdata['BuildingName'] = $this->getBuilding_name($getdashdata['Building']);
        }
        if (isset($getdashdata['Department']) && $getdashdata['Department'] > 0) {
            $getdashdata['DepartmentName'] = $this->getDepartment_name($getdashdata['Department']);
        }
        if (isset($getdashdata['Project']) && $getdashdata['Project'] > 0) {
            $getdashdata['ProjectName'] = $this->getProject_name($getdashdata['Project']);
        }


        $data = array(
            'obs_single_bar' => $chartDataArray,
            'dashboard_blue' => $dashboard_orange,
            'atar_color_code' => $atar_color_code,
            'getdashdata' => $getdashdata,
        );
        //echo "<pre>";print_r($data);exit;
        $this->load->view('dashboard_chart/observation_month_total', $data);
    }
    public function observationstatustotal()
    {
        global $dashPermission;

        global $atar_color_code;
        global $status_val;
        $data = [];

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();
        $getdashdata = $this->input->get();

        $select_obs = [
            "
            SUM(CASE WHEN obs_app_status = '1' THEN 1 ELSE 0 END) as Pending,
            SUM(CASE WHEN obs_app_status = '3' THEN 1 ELSE 0 END) as Observation_Closed,
            SUM(CASE WHEN obs_app_status = '4' THEN 1 ELSE 0 END) as CA_Pending,
            SUM(CASE WHEN obs_app_status = '5' THEN 1 ELSE 0 END) as Manager_Approval,
            SUM(CASE WHEN obs_app_status = '7' THEN 1 ELSE 0 END) as Manager_Rejected,
            SUM(CASE WHEN obs_app_status = '8' THEN 1 ELSE 0 END) as Overdue,
            SUM(CASE WHEN obs_app_status = '9' THEN 1 ELSE 0 END) as HSSE_Manager_Approval,
            SUM(CASE WHEN obs_app_status = '10' THEN 1 ELSE 0 END) as HSSE_Manager_Rejected,"

        ];
        $options_obs['return_type'] = 'row';


        if (in_array($user_type, $dashPermission['view_supadmin'])) {
            $options_obs['where']['obs_status'] =  'Y';
        } elseif (in_array($user_type, $dashPermission['view_ad'])) {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['view_assigner'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['fix'])) {
            $options_obs['orwhere']['obs_assigner_id'] =  $userid;
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['orwhere']['obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $dashPermission['approve'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['approve_final'])) {
            $options_obs['where']['obs_status'] =  'Y';
            // $options_obs['where']['obs_comp_id'] =  $user_clid;
        } else {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_reporter_id'] =  $userid;
        }

        //filter
        if ($getdashdata['Company'] > 0  && $getdashdata['Company'] != 'null') {
            $options_obs['where']["obs_comp_id"] = (int)$getdashdata['Company'];
        }
        if ($getdashdata['Area'] > 0  && $getdashdata['Area'] != 'null') {
            $options_obs['where']["obs_area_id"] = (int)$getdashdata['Area'];
        }
        if ($getdashdata['Building'] > 0  && $getdashdata['Building'] != 'null') {
            $options_obs['where']["obs_building_id"] = (int)$getdashdata['Building'];
        }
        if ($getdashdata['Department'] > 0  && $getdashdata['Department'] != 'null') {
            $options_obs['where']["obs_dept_id"] = (int)$getdashdata['Department'];
        }
        if ($getdashdata['Project'] > 0  && $getdashdata['Project'] != 'null') {
            $options_obs['where']["obs_project_id"] = (int)$getdashdata['Project'];
        }

        if ($getdashdata['Start_Date'] != 'null' && $getdashdata['Start_Date'] != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($getdashdata['Start_Date']));
            $options_obs['where']['CREATED_ON >='] = $thstartdate;
        }
        if ($getdashdata['End_Date'] != 'null' && $getdashdata['End_Date'] != '') {
            $enddate = date('Y-m-d', strtotime($getdashdata['End_Date']));
            $options_obs['where']['CREATED_ON <='] = $enddate . ' 23:59:59';
        }


        $obs = $this->common_model->getAlldata(OBS_MAIN_SEE, $select_obs, $options_obs);
        if (
            empty($obs->Pending) &&
            empty($obs->Observation_Closed) &&
            empty($obs->CA_Pending) &&
            empty($obs->Manager_Approval) &&
            empty($obs->Manager_Rejected) &&
            empty($obs->Overdue) &&
            empty($obs->HSSE_Manager_Approval) &&
            empty($obs->HSSE_Manager_Rejected)
        ) {
            $obs_status = [];
        } else {
            $obs_status = [
                array('status' => 'Waiting for Supervisor Action', 'value' => !empty($obs->Pending) ? (int)$obs->Pending : 0),
                array('status' => 'Observation Closed', 'value' => !empty($obs->Observation_Closed) ? (int)$obs->Observation_Closed : 0),
                array('status' => 'CA Pending', 'value' => !empty($obs->CA_Pending) ? (int)$obs->CA_Pending : 0),
                array('status' => 'EPC E&S Manager Approval', 'value' => !empty($obs->Manager_Approval) ? (int)$obs->Manager_Approval : 0),
                array('status' => 'EPC E&S Manager Rejected', 'value' => !empty($obs->Manager_Rejected) ? (int)$obs->Manager_Rejected : 0),
                array('status' => 'Overdue', 'value' => !empty($obs->Overdue) ? (int)$obs->Overdue : 0),
                array('status' => 'HSSE Manager Approval', 'value' => !empty($obs->HSSE_Manager_Approval) ? (int)$obs->HSSE_Manager_Approval : 0),
                array('status' => 'HSSE Manager Rejected', 'value' => !empty($obs->HSSE_Manager_Rejected) ? (int)$obs->HSSE_Manager_Rejected : 0),
            ];
        }

        if (isset($getdashdata['Company']) && $getdashdata['Company'] > 0) {
            $getdashdata['CompanyName'] = $this->getCompany_name($getdashdata['Company']);
        }
        if (isset($getdashdata['Area']) && $getdashdata['Area'] > 0) {
            $getdashdata['AreaName'] = $this->getArea_name($getdashdata['Area']);
        }
        if (isset($getdashdata['Building']) && $getdashdata['Building'] > 0) {
            $getdashdata['BuildingName'] = $this->getBuilding_name($getdashdata['Building']);
        }
        if (isset($getdashdata['Department']) && $getdashdata['Department'] > 0) {
            $getdashdata['DepartmentName'] = $this->getDepartment_name($getdashdata['Department']);
        }
        if (isset($getdashdata['Project']) && $getdashdata['Project'] > 0) {
            $getdashdata['ProjectName'] = $this->getProject_name($getdashdata['Project']);
        }


        $data = array(
            'obs_status' => $obs_status,

            'atar_color_code' => $atar_color_code,
            'getdashdata' => $getdashdata,
        );
        $this->load->view('dashboard_chart/observation_status_pie', $data);
    }
    public function observationstatusCategorywise()
    {
        global $dashPermission;
        global $atar_color_code;
        global $status_val;
        $data = [];

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();
        $getdashdata = $this->input->get();


        $impOptn['where'] = [
            'hse_status' => 'Y'
        ];
        $getAllimp = $this->common_model->getAlldata(MAS_HSE, ['hse_id,hse_cat'], $impOptn);
        $impactdet = customFormDropDown($getAllimp, 'hse_id', 'hse_cat');

        $select_obs = [
            "MONTHNAME(CREATED_ON) as month,
            obs_cat_id as obs_cat_id,
            COALESCE(COUNT(*),0) as total"
        ];
        $monthDetails = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $options_obs['group_by'] = 'obs_cat_id,month';
        $options_obs['return_type'] = 'result';

        if (in_array($user_type, $dashPermission['view_supadmin'])) {
            $options_obs['where']['obs_status'] =  'Y';
        } elseif (in_array($user_type, $dashPermission['view_ad'])) {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['view_assigner'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['fix'])) {
            $options_obs['orwhere']['obs_assigner_id'] =  $userid;
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['orwhere']['obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $dashPermission['approve'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['approve_final'])) {
            $options_obs['where']['obs_status'] =  'Y';
            // $options_obs['where']['obs_comp_id'] =  $user_clid;
        } else {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_reporter_id'] =  $userid;
        }

        //filter
        if ($getdashdata['Company'] > 0  && $getdashdata['Company'] != 'null') {
            $options_obs['where']["obs_comp_id"] = (int)$getdashdata['Company'];
        }
        if ($getdashdata['Area'] > 0  && $getdashdata['Area'] != 'null') {
            $options_obs['where']["obs_area_id"] = (int)$getdashdata['Area'];
        }
        if ($getdashdata['Building'] > 0  && $getdashdata['Building'] != 'null') {
            $options_obs['where']["obs_building_id"] = (int)$getdashdata['Building'];
        }
        if ($getdashdata['Department'] > 0  && $getdashdata['Department'] != 'null') {
            $options_obs['where']["obs_dept_id"] = (int)$getdashdata['Department'];
        }
        if ($getdashdata['Project'] > 0  && $getdashdata['Project'] != 'null') {
            $options_obs['where']["obs_project_id"] = (int)$getdashdata['Project'];
        }

        if ($getdashdata['Start_Date'] != 'null' && $getdashdata['Start_Date'] != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($getdashdata['Start_Date']));
            $options_obs['where']['CREATED_ON >='] = $thstartdate;
        }
        if ($getdashdata['End_Date'] != 'null' && $getdashdata['End_Date'] != '') {
            $enddate = date('Y-m-d', strtotime($getdashdata['End_Date']));
            $options_obs['where']['CREATED_ON <='] = $enddate . ' 23:59:59';
        }

        $chartData = $this->common_model->getAlldata(OBS_MAIN_SEE, $select_obs, $options_obs);


        foreach ($monthDetails as $month) {
            $monthWiseData[$month] = [];
        }

        if (!empty($chartData)) {
            foreach ($chartData as $valdata) {
                if (isset($valdata->obs_cat_id) && isset($valdata->month)) {
                    $monthWiseData[$valdata->month][$valdata->obs_cat_id] = (int)$valdata->total;
                }
            }
        }

        // Prepare the data for charting
        $finalChartData = [];
        foreach ($impactdet as $impactID => $impactName) {
            $seriesData = [];
            foreach ($monthDetails as $month) {
                $seriesData[] = isset($monthWiseData[$month][$impactID]) ? $monthWiseData[$month][$impactID] : 0;
            }
            $finalChartData[] = [
                'name' => $impactName,
                'data' => $seriesData
            ];
        }


        if (isset($getdashdata['Company']) && $getdashdata['Company'] > 0) {
            $getdashdata['CompanyName'] = $this->getCompany_name($getdashdata['Company']);
        }
        if (isset($getdashdata['Area']) && $getdashdata['Area'] > 0) {
            $getdashdata['AreaName'] = $this->getArea_name($getdashdata['Area']);
        }
        if (isset($getdashdata['Building']) && $getdashdata['Building'] > 0) {
            $getdashdata['BuildingName'] = $this->getBuilding_name($getdashdata['Building']);
        }
        if (isset($getdashdata['Department']) && $getdashdata['Department'] > 0) {
            $getdashdata['DepartmentName'] = $this->getDepartment_name($getdashdata['Department']);
        }
        if (isset($getdashdata['Project']) && $getdashdata['Project'] > 0) {
            $getdashdata['ProjectName'] = $this->getProject_name($getdashdata['Project']);
        }

        $data = array(
            'obs_status_bar' => $finalChartData,
            'obsactdet' => $impactdet,
            'atar_color_code' => $atar_color_code,
            'getdashdata' => $getdashdata,
            'monthDetails' => $monthDetails
        );

        $this->load->view('dashboard_chart/observation_category_status', $data);
    }

    public function observationrisktotal()
    {
        global $dashPermission;
        global $risk_color_code;
        global $status_val;
        $data = [];

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();
        $getdashdata = $this->input->get();

        $select_obs = [
            "
            SUM(CASE WHEN obs_risk_id = '1' THEN 1 ELSE 0 END) as low,
            SUM(CASE WHEN obs_risk_id = '2' THEN 1 ELSE 0 END) as medium,
            SUM(CASE WHEN obs_risk_id = '3' THEN 1 ELSE 0 END) as high,"

        ];
        $options_obs['return_type'] = 'row';


        if (in_array($user_type, $dashPermission['view_supadmin'])) {
            $options_obs['where']['obs_status'] =  'Y';
        } elseif (in_array($user_type, $dashPermission['view_ad'])) {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['view_assigner'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['fix'])) {
            $options_obs['orwhere']['obs_assigner_id'] =  $userid;
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['orwhere']['obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $dashPermission['approve'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['approve_final'])) {
            $options_obs['where']['obs_status'] =  'Y';
            // $options_obs['where']['obs_comp_id'] =  $user_clid;
        } else {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_reporter_id'] =  $userid;
        }

        //filter
        if ($getdashdata['Company'] > 0  && $getdashdata['Company'] != 'null') {
            $options_obs['where']["obs_comp_id"] = (int)$getdashdata['Company'];
        }
        if ($getdashdata['Area'] > 0  && $getdashdata['Area'] != 'null') {
            $options_obs['where']["obs_area_id"] = (int)$getdashdata['Area'];
        }
        if ($getdashdata['Building'] > 0  && $getdashdata['Building'] != 'null') {
            $options_obs['where']["obs_building_id"] = (int)$getdashdata['Building'];
        }
        if ($getdashdata['Department'] > 0  && $getdashdata['Department'] != 'null') {
            $options_obs['where']["obs_dept_id"] = (int)$getdashdata['Department'];
        }
        if ($getdashdata['Project'] > 0  && $getdashdata['Project'] != 'null') {
            $options_obs['where']["obs_project_id"] = (int)$getdashdata['Project'];
        }

        if ($getdashdata['Start_Date'] != 'null' && $getdashdata['Start_Date'] != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($getdashdata['Start_Date']));
            $options_obs['where']['CREATED_ON >='] = $thstartdate;
        }
        if ($getdashdata['End_Date'] != 'null' && $getdashdata['End_Date'] != '') {
            $enddate = date('Y-m-d', strtotime($getdashdata['End_Date']));
            $options_obs['where']['CREATED_ON <='] = $enddate . ' 23:59:59';
        }


        $obs = $this->common_model->getAlldata(OBS_MAIN_SEE, $select_obs, $options_obs);
        if (
            empty($obs->low) &&
            empty($obs->medium) &&
            empty($obs->high)
        ) {
            $obs_status = [];
        } else {
            $obs_status = [
                array('status' => 'Low', 'value' => !empty($obs->low) ? (int)$obs->low : 0),
                array('status' => 'Medium', 'value' => !empty($obs->medium) ? (int)$obs->medium : 0),
                array('status' => 'High', 'value' => !empty($obs->high) ? (int)$obs->high : 0),
            ];
        }

        if (isset($getdashdata['Company']) && $getdashdata['Company'] > 0) {
            $getdashdata['CompanyName'] = $this->getCompany_name($getdashdata['Company']);
        }
        if (isset($getdashdata['Area']) && $getdashdata['Area'] > 0) {
            $getdashdata['AreaName'] = $this->getArea_name($getdashdata['Area']);
        }
        if (isset($getdashdata['Building']) && $getdashdata['Building'] > 0) {
            $getdashdata['BuildingName'] = $this->getBuilding_name($getdashdata['Building']);
        }
        if (isset($getdashdata['Department']) && $getdashdata['Department'] > 0) {
            $getdashdata['DepartmentName'] = $this->getDepartment_name($getdashdata['Department']);
        }
        if (isset($getdashdata['Project']) && $getdashdata['Project'] > 0) {
            $getdashdata['ProjectName'] = $this->getProject_name($getdashdata['Project']);
        }


        $data = array(
            'obs_status' => $obs_status,

            'risk_color_code' => $risk_color_code,
            'getdashdata' => $getdashdata,
        );
        $this->load->view('dashboard_chart/observation_type_pie', $data);
    }
    public function observationtypetotal()
    {

        global $dashPermission;
        global $obstype_color_status_bar;
        global $status_val;
        $data = [];
        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();
        $getdashdata = $this->input->get();

        $select_obs = [
            "
            SUM(CASE WHEN obs_type_id = '1' THEN 1 ELSE 0 END) as Unsafe_Act,
            SUM(CASE WHEN obs_type_id = '2' THEN 1 ELSE 0 END) as Unsafe_Con,
            SUM(CASE WHEN obs_type_id = '3' THEN 1 ELSE 0 END) as Positive,"
        ];

        $options_obs['return_type'] = 'row';
        if (in_array($user_type, $dashPermission['view_supadmin'])) {
            $options_obs['where']['obs_status'] =  'Y';
        } elseif (in_array($user_type, $dashPermission['view_ad'])) {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['view_assigner'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['fix'])) {
            $options_obs['orwhere']['obs_assigner_id'] =  $userid;
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['orwhere']['obs_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $dashPermission['approve'])) {
            $options_obs['where']['obs_status'] =  'Y';
            //$options_obs['where']['obs_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $dashPermission['approve_final'])) {
            $options_obs['where']['obs_status'] =  'Y';
            // $options_obs['where']['obs_comp_id'] =  $user_clid;
        } else {
            $options_obs['where']['obs_status'] =  'Y';
            $options_obs['where']['obs_reporter_id'] =  $userid;
        }

        //filter
        if ($getdashdata['Company'] > 0  && $getdashdata['Company'] != 'null') {
            $options_obs['where']["obs_comp_id"] = (int)$getdashdata['Company'];
        }
        if ($getdashdata['Area'] > 0  && $getdashdata['Area'] != 'null') {
            $options_obs['where']["obs_area_id"] = (int)$getdashdata['Area'];
        }
        if ($getdashdata['Building'] > 0  && $getdashdata['Building'] != 'null') {
            $options_obs['where']["obs_building_id"] = (int)$getdashdata['Building'];
        }
        if ($getdashdata['Department'] > 0  && $getdashdata['Department'] != 'null') {
            $options_obs['where']["obs_dept_id"] = (int)$getdashdata['Department'];
        }
        if ($getdashdata['Project'] > 0  && $getdashdata['Project'] != 'null') {
            $options_obs['where']["obs_project_id"] = (int)$getdashdata['Project'];
        }

        if ($getdashdata['Start_Date'] != 'null' && $getdashdata['Start_Date'] != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($getdashdata['Start_Date']));
            $options_obs['where']['CREATED_ON >='] = $thstartdate;
        }
        if ($getdashdata['End_Date'] != 'null' && $getdashdata['End_Date'] != '') {
            $enddate = date('Y-m-d', strtotime($getdashdata['End_Date']));
            $options_obs['where']['CREATED_ON <='] = $enddate . ' 23:59:59';
        }

        $insp = $this->common_model->getAlldata(OBS_MAIN_SEE, $select_obs, $options_obs);
        if (empty($insp->Unsafe_Act) && empty($insp->Unsafe_Con) && empty($insp->Positive)) {
            $insp_status = [];
        } else {
            $insp_status = [
                array('status' => 'Unsafe Act',  'value' => !empty($insp->Unsafe_Act) ? (int)$insp->Unsafe_Act : 0),
                array('status' => 'Unsafe Condition',  'value' => !empty($insp->Unsafe_Con) ? (int)$insp->Unsafe_Con : 0),
                array('status' => 'Others(Positive/Good obs)',  'value' => !empty($insp->Positive) ? (int)$insp->Positive : 0),
            ];
        }

        if (isset($getdashdata['Company']) && $getdashdata['Company'] > 0) {
            $getdashdata['CompanyName'] = $this->getCompany_name($getdashdata['Company']);
        }
        if (isset($getdashdata['Area']) && $getdashdata['Area'] > 0) {
            $getdashdata['AreaName'] = $this->getArea_name($getdashdata['Area']);
        }
        if (isset($getdashdata['Building']) && $getdashdata['Building'] > 0) {
            $getdashdata['BuildingName'] = $this->getBuilding_name($getdashdata['Building']);
        }
        if (isset($getdashdata['Department']) && $getdashdata['Department'] > 0) {
            $getdashdata['DepartmentName'] = $this->getDepartment_name($getdashdata['Department']);
        }
        if (isset($getdashdata['Project']) && $getdashdata['Project'] > 0) {
            $getdashdata['ProjectName'] = $this->getProject_name($getdashdata['Project']);
        }


        $data = array(
            'insp_status' => $insp_status,
            'insp_color_status_bar' => $obstype_color_status_bar,
            'getdashdata' => $getdashdata,
        );
        $this->load->view('dashboard_chart/observation_type_donut_pie', $data);
    }
}
