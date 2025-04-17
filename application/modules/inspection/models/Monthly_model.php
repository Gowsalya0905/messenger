<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Monthly_model extends CI_Model
{

    public function __construct()
    {
        $this->load->model('common/common_model', 'common');
        parent::__construct();
    }



    public function getInspImagBefore_project($did = '')
    {
        $where = [
            'fk_img_item_id' => $did,
            'insp_file_type' => '1',
            'insp_attach_status' => 'Y'
        ];
      
        $option['where'] = $where;
        $option['return_type'] = 'result';
        return $details = $this->common->getAlldata(INSP_MONTH_IMAGE_SEE, ['*'], $option);
    }

    
    public function getInspactImageAfter_project($did)
    {
        $where = [
            'fk_insp_main_id' => $did,
            'insp_file_type' => '3',
            'insp_attach_status' => 'Y'
        ];
       
        $option['where'] = $where;
        $option['return_type'] = 'result';
        return $details = $this->common->getAlldata(INSP_MONTH_IMAGE_SEE, ['*'], $option);
    }

    public function getAtarDetails_project($where, $type, $option = [])
    {
        $option['where'] = $where;
        $option['return_type'] = $type;
        $option['join'] = [

            LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . INSP_MONTH_ITEMS . '.insp_reporter_id', 'LEFT'],
            EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],


            'insp_monthly_category_master AS ins_cate_mas'=>['ins_cate_mas.id = '.INSP_MONTH_ITEMS.'.fk_item_cat_id','LEFT'],
            'insp_monthly_subcategory_master AS ins_subcate_mas'=>['ins_subcate_mas.id = '.INSP_MONTH_ITEMS.'.fk_item_subcat_id','LEFT'],
            'insp_monthly_subcategorydata_master AS ins_subcatedata_mas'=>['ins_subcatedata_mas.id = '.INSP_MONTH_ITEMS.'.fk_item_subcatdata_id','LEFT'],
              INSP_MONTH_FLOW_SEE.' AS insp_main'=>['insp_main.ins_auto_id = '.INSP_MONTH_ITEMS.'.fk_insp_main_auto_id','LEFT'],

        ];
        $aOption = $option + $option;

        return $details = $this->common->getAlldata(INSP_MONTH_ITEMS, [
            '*',
            'insp_main.ins_id',
            'Reporter.EMP_NAME` as Reporter',
            'FN_COMP_NAME(insp_comp_id) as comp_name',
            'FN_AREA_NAME(insp_area_id) as area_name',
            'FN_BUILD_NAME(insp_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
            'FN_PROJECT_NAME(insp_project_id) as proj_name',
            // 'FN_HSE_CAT_NAME(obs_cat_id) as hse_cat',
            'Reporter.EMP_NAME as empName',       
            // 'Unithead.EMP_NAME as assignee',
            'FN_GET_DESIGNATION_NAME(insp_reporter_desg_id) as desig',
            'ins_cate_mas.category as category_name',
            'ins_subcate_mas.subcategory as sub_category_name',
            'ins_subcatedata_mas.subcategorydata as sub_category_data_name',
          
        ], $aOption);
    }



    public function getInspDataReassign_project($did = "")
    {
        $where = [
            "fk_insp_auto_main_id" => $did,
            "insp_assigned_type_id" => 2,
        ];

        $option["where"] = $where;

        $option["return_type"] = "row";

        $option["join"] = [
            LOGIN . " AS LD6" => [
                "LD6.LOGIN_ID = " . INSP_MONTH_ESL_LOG . ".fk_insp_supervisor_login_id",
                "LEFT",
            ],
            EMPL . " AS repman" => [
                "repman.EMP_AUTO_ID = LD6.USER_REF_ID",
                "LEFT",
            ],

            LOGIN . " AS LD7" => [
                "LD7.LOGIN_ID = " . INSP_MONTH_ESL_LOG . ".fk_new_assigner_id",
                "LEFT",
            ],
            EMPL . " AS aiemp" => [
                "aiemp.EMP_AUTO_ID = LD7.USER_REF_ID",
                "LEFT",
            ],
        ];

        $aOption = $option + $option;

        return $details = $this->common_model->getAlldata(
            INSP_MONTH_ESL_LOG,
            [
                "log_created_date,fk_insp_new_target_datetime,fk_insp_supervisor_desc",
                "repman.EMP_NAME as repman_reassign_name",
                "FN_ROLE_NAME(fk_insp_supervisor_role_id) as repman_role_reassign",
                "FN_GET_DESIGNATION_NAME(fk_insp_supervisor_des_id) as repman_desg_reassign",

                "aiemp.EMP_NAME as aiemp_name_reassign",
            ],
            $aOption
        );
    }

    public function getInspDataassign_project($did = "")
    {
        $where = [
            "fk_insp_auto_main_id" => $did,
            "insp_assigned_type_id" => 1,
        ];

        $option["where"] = $where;

        $option["return_type"] = "row";

        $option["join"] = [
            LOGIN . " AS LD6" => [
                "LD6.LOGIN_ID = " . INSP_MONTH_ESL_LOG . ".fk_insp_supervisor_login_id",
                "LEFT",
            ],
            EMPL . " AS repman" => [
                "repman.EMP_AUTO_ID = LD6.USER_REF_ID",
                "LEFT",
            ],

            LOGIN . " AS LD7" => [
                "LD7.LOGIN_ID = " . INSP_MONTH_ESL_LOG . ".fk_new_assigner_id",
                "LEFT",
            ],
            EMPL . " AS aiemp" => [
                "aiemp.EMP_AUTO_ID = LD7.USER_REF_ID",
                "LEFT",
            ],
        ];

        $aOption = $option + $option;

        return $details = $this->common_model->getAlldata(
            INSP_MONTH_ESL_LOG,
            [
                "log_created_date,fk_insp_new_target_datetime,fk_insp_supervisor_desc",
                "repman.EMP_NAME as repman_reassign_name",
                "FN_ROLE_NAME(fk_insp_supervisor_role_id) as repman_role_reassign",
                "FN_GET_DESIGNATION_NAME(fk_insp_supervisor_des_id) as repman_desg_reassign",

                "aiemp.EMP_NAME as aiemp_name_reassign",
            ],
            $aOption
        );
    }

    public function getInspDataapproval_project($did = "")
    {
        $option["where"] = [
            "fk_insp_app_id" => $did,
        ];
        $option["where_in"] = [
            "approval_type_id" => [1, 2],
        ];
        $option["return_type"] = "result";
        $option["join"] = [
            LOGIN . " AS LD6" => [
                "LD6.LOGIN_ID = " . INSP_MONTH_APP_ESL_LOG . ".approver_login_id",
                "LEFT",
            ],
            EMPL . " AS repman" => [
                "repman.EMP_AUTO_ID = LD6.USER_REF_ID",
                "LEFT",
            ],

            LOGIN . " AS LD7" => [
                "LD7.LOGIN_ID = " . INSP_MONTH_APP_ESL_LOG . ".fk_assigner_login_id",
                "LEFT",
            ],
            EMPL . " AS aiemp" => [
                "aiemp.EMP_AUTO_ID = LD7.USER_REF_ID",
                "LEFT",
            ],
        ];
        $aOption = $option + $option;
        return $details = $this->common_model->getAlldata(
            INSP_MONTH_APP_ESL_LOG,
            [
                "approval_type_id,fk_assigner_datetime,assigned_target_date,assigner_desc,fk_approver_app_desc,fk_approver_rej_desc,approver_report_dt",
                "repman.EMP_NAME as approver_name",
                "FN_ROLE_NAME(approver_role_id) as approver_role",
                "FN_GET_DESIGNATION_NAME(approver_des_id) as approver_name_desig",
                "aiemp.EMP_NAME as assigner_name",
            ],
            $aOption
        );
    }

    public function getInspDataapprovalfinal_project($did = "")
    {
        $option["where"] = [
            "fk_insp_app_id" => $did,
        ];
        $option["where_in"] = [
            "approval_type_id" => [3, 4],
        ];
        $option["return_type"] = "result";
        $option["join"] = [
            LOGIN . " AS LD6" => [
                "LD6.LOGIN_ID = " . INSP_MONTH_APP_ESL_LOG . ".approver_login_id",
                "LEFT",
            ],
            EMPL . " AS repman" => [
                "repman.EMP_AUTO_ID = LD6.USER_REF_ID",
                "LEFT",
            ],

            LOGIN . " AS LD7" => [
                "LD7.LOGIN_ID = " . INSP_MONTH_APP_ESL_LOG . ".fk_assigner_login_id",
                "LEFT",
            ],
            EMPL . " AS aiemp" => [
                "aiemp.EMP_AUTO_ID = LD7.USER_REF_ID",
                "LEFT",
            ],
        ];
        $aOption = $option + $option;
        return $details = $this->common_model->getAlldata(
            INSP_MONTH_APP_ESL_LOG,
            [
                "approval_type_id,fk_assigner_datetime,assigned_target_date,assigner_desc,fk_approver_app_desc,fk_approver_rej_desc,approver_report_dt",
                "repman.EMP_NAME as approver_name",
                "FN_ROLE_NAME(approver_role_id) as approver_role",
                "FN_GET_DESIGNATION_NAME(approver_des_id) as approver_name_desig",
                "aiemp.EMP_NAME as assigner_name",
            ],
            $aOption
        );
    }

    public function getInspDataactiontaken_project($did = "")
    {
        $option["where"] = [
            "fk_insp_auto_main_id" => $did,
        ];

        $option["return_type"] = "result";
        $option["join"] = [
            LOGIN . " AS LD6" => [
                "LD6.LOGIN_ID = " . INSP_MONTH_ACTION_LOG . ".fk_insp_assigner_login_id",
                "LEFT",
            ],
            EMPL . " AS repman" => [
                "repman.EMP_AUTO_ID = LD6.USER_REF_ID",
                "LEFT",
            ],
            INSP_MONTH_IMAGE_SEE . " AS insimg" => [ 
                "insimg.fk_ca_log_id = " . INSP_MONTH_ACTION_LOG . ".ca_log_id",
                "LEFT",
            ],
        ];
        $option['group_by'] = 'ca_log_id';
        $aOption = $option + $option;
        return $details = $this->common_model->getAlldata(
            INSP_MONTH_ACTION_LOG,
            [
                "fk_insp_ca_desc,fk_insp_ca_datetime",
                "repman.EMP_NAME as assigner_name",
                "FN_ROLE_NAME(fk_insp_assigner_role_id) as assigner_role",
                "FN_GET_DESIGNATION_NAME(fk_insp_assigner_des_id) as assigner_name_desig",
                "GROUP_CONCAT(insimg.insp_att_id) AS insp_att_ids"
            ],
            $aOption
        );
    }

    public function getapiObsDetails_project($where, $type, $option = [])
    {
        $option['where'] = $where;
        $option['return_type'] = $type;
        $option['join'] = [

            LOGIN . ' AS LD' => ['LD.LOGIN_ID =  obs.obs_reporter_id', 'LEFT'],
            EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],


            LOGIN . ' AS LD1' => ['LD1.LOGIN_ID =  obs.obs_supervisor_id', 'LEFT'],
            EMPL . ' AS Hsse' => ['Hsse.EMP_AUTO_ID = LD1.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD5' => ['LD5.LOGIN_ID =  obs.obs_assigner_id', 'LEFT'],
            EMPL . ' AS Unithead' => ['Unithead.EMP_AUTO_ID = LD5.USER_REF_ID', 'LEFT'],


            OBS_RISK_LOG . ' AS RL' => [
                "RL.fk_obs_risk_auto_main_id =  obs.obs_auto_id  AND RL.obs_risk_assigned_type_id = 1",
                "LEFT"
            ]

        ];
        $aOption = $option + $option;

        return $details = $this->common->getAlldata(OBS_FLOW_SEE . ' as obs', [
            'obs.obs_id as Observation_id',
            'Reporter.EMP_NAME` as Reporter',
            'FN_GET_DESIGNATION_NAME(obs.obs_reporter_desg_id) as reporter_designation',
            'DATE_FORMAT(obs.obs_report_datetime,"%d-%m-%Y %H:%i:%s") as reported_date_time',
            'obs.obs_owner_id',
            'obs.obs_owner_eng',
            'obs.obs_epc_id',
            'FN_COMP_NAME(obs.obs_comp_id) as comp_name',
            'obs.obs_comp_id',
            'FN_AREA_NAME(obs.obs_area_id) as area_name',
            'obs.obs_area_id',
            'FN_BUILD_NAME(obs.obs_building_id) as building_name',
            'obs.obs_building_id',
            'FN_GET_DEPARTMENT_NAME(obs.obs_dept_id) as dep_name',
            'obs.obs_dept_id',
            'FN_PROJECT_NAME(obs.obs_project_id) as proj_name',
            'obs.obs_project_id',
            'FN_HSE_CAT_NAME(obs.obs_cat_id) as hse_cat',
            'obs.obs_cat_id',
            'DATE_FORMAT(obs.obs_date,"%d-%m-%Y %H:%i:%s") as observation_date',
            'obs.obs_type_id',
            'obs.obs_risk_id',
            'obs.obs_desc as observation_description'
        ], $aOption);
    }
}
