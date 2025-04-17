<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Report_model extends CI_Model
{

    public function __construct()
    {
        $this->load->model('common/common_model', 'common');
        parent::__construct();
    }



    public function getUseeImageBefore_project($did = '')
    {
        $where = [
            'FK_OBS_MAIN_ID' => $did,
            'OBS_FILE_TYPE' => 1,
            'OBS_ATTACH_STATUS' => 'Y'
        ];
        $option['where'] = $where;
        $option['return_type'] = 'result';
        return $details = $this->common->getAlldata(OBS_IMG_SEE, ['*'], $option);
    }



    public function getUseeactImageAfter_project($did = '', $obs_assigner_id)
    {
        $where = [
            'fk_obs_main_id' => $did,
            'obs_file_type' => '3',
            'obs_attach_status' => 'Y'
        ];
        if ($obs_assigner_id) {
            $where['obs_assigner_id'] = $obs_assigner_id;
        }
        $option['where'] = $where;
        $option['return_type'] = 'result';
        return $details = $this->common->getAlldata(OBS_IMG_SEE, ['*'], $option);
    }

    public function getUseeDetails_project($where, $type, $option = [])
    {
        $option['where'] = $where;
        $option['return_type'] = $type;
        $option['join'] = [

            LOGIN . ' AS LD' => ['LD.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_reporter_id', 'LEFT'],
            EMPL . ' AS Reporter' => ['Reporter.EMP_AUTO_ID = LD.USER_REF_ID', 'LEFT'],


            LOGIN . ' AS LD1' => ['LD1.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_supervisor_id', 'LEFT'],
            EMPL . ' AS Hsse' => ['Hsse.EMP_AUTO_ID = LD1.USER_REF_ID', 'LEFT'],

            LOGIN . ' AS LD5' => ['LD5.LOGIN_ID = ' . OBS_FLOW_SEE . '.obs_assigner_id', 'LEFT'],
            EMPL . ' AS Unithead' => ['Unithead.EMP_AUTO_ID = LD5.USER_REF_ID', 'LEFT'],


            OBS_RISK_LOG . ' AS RL' => [
                "RL.fk_obs_risk_auto_main_id = " . OBS_FLOW_SEE . ".obs_auto_id  AND RL.obs_risk_assigned_type_id = 1",
                "LEFT"
            ],


        ];
        $aOption = $option + $option;

        return $details = $this->common->getAlldata(OBS_FLOW_SEE, [
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
            'Hsse.EMP_NAME as supervisor',
            'Unithead.EMP_NAME as assignee',
            'FN_GET_DESIGNATION_NAME(obs_reporter_desg_id) as desig',
            'RL.risk_log_id'
        ], $aOption);
    }



    public function getObsDataReassign_project($did = "")
    {
        $where = [
            "fk_obs_auto_main_id" => $did,
            "OBS_assigned_type_id" => 2,
        ];

        $option["where"] = $where;

        $option["return_type"] = "result";

        $option["join"] = [
            LOGIN . " AS LD6" => [
                "LD6.LOGIN_ID = " . OBS_ESL_SEE . ".fk_obs_supervisor_login_id",
                "LEFT",
            ],
            EMPL . " AS repman" => [
                "repman.EMP_AUTO_ID = LD6.USER_REF_ID",
                "LEFT",
            ],

            LOGIN . " AS LD7" => [
                "LD7.LOGIN_ID = " . OBS_ESL_SEE . ".fk_new_assigner_id",
                "LEFT",
            ],
            EMPL . " AS aiemp" => [
                "aiemp.EMP_AUTO_ID = LD7.USER_REF_ID",
                "LEFT",
            ],
        ];

        $aOption = $option + $option;

        return $details = $this->common_model->getAlldata(
            OBS_ESL_SEE,
            [
                "log_created_date,fk_obs_new_target_datetime,fk_obs_supervisor_desc",
                "repman.EMP_NAME as repman_reassign_name",
                "FN_ROLE_NAME(fk_obs_supervisor_role_id) as repman_role_reassign",
                "FN_GET_DESIGNATION_NAME(fk_obs_supervisor_des_id) as repman_desg_reassign",

                "aiemp.EMP_NAME as aiemp_name_reassign",
            ],
            $aOption
        );
    }

    public function getObsDataassign_project($did = "")
    {
        $where = [
            "fk_obs_auto_main_id" => $did,
            "OBS_assigned_type_id" => 1,
        ];

        $option["where"] = $where;

        $option["return_type"] = "row";

        $option["join"] = [
            LOGIN . " AS LD6" => [
                "LD6.LOGIN_ID = " . OBS_ESL_SEE . ".fk_obs_supervisor_login_id",
                "LEFT",
            ],
            EMPL . " AS repman" => [
                "repman.EMP_AUTO_ID = LD6.USER_REF_ID",
                "LEFT",
            ],

            LOGIN . " AS LD7" => [
                "LD7.LOGIN_ID = " . OBS_ESL_SEE . ".fk_new_assigner_id",
                "LEFT",
            ],
            EMPL . " AS aiemp" => [
                "aiemp.EMP_AUTO_ID = LD7.USER_REF_ID",
                "LEFT",
            ],
        ];

        $aOption = $option + $option;

        return $details = $this->common_model->getAlldata(
            OBS_ESL_SEE,
            [
                "log_created_date,fk_obs_new_target_datetime,fk_obs_supervisor_desc",
                "repman.EMP_NAME as repman_reassign_name",
                "FN_ROLE_NAME(fk_obs_supervisor_role_id) as repman_role_reassign",
                "FN_GET_DESIGNATION_NAME(fk_obs_supervisor_des_id) as repman_desg_reassign",

                "aiemp.EMP_NAME as aiemp_name_reassign",
            ],
            $aOption
        );
    }

    public function getObsDataapproval_project($did = "")
    {
        $option["where"] = [
            "fk_obs_app_id" => $did,
        ];
        $option["where_in"] = [
            "approval_type_id" => [1, 2],
        ];
        $option["return_type"] = "result";
        $option["join"] = [
            LOGIN . " AS LD6" => [
                "LD6.LOGIN_ID = " . OBS_APP_ESL_SEE . ".approver_login_id",
                "LEFT",
            ],
            EMPL . " AS repman" => [
                "repman.EMP_AUTO_ID = LD6.USER_REF_ID",
                "LEFT",
            ],

            LOGIN . " AS LD7" => [
                "LD7.LOGIN_ID = " . OBS_APP_ESL_SEE . ".fk_assigner_login_id",
                "LEFT",
            ],
            EMPL . " AS aiemp" => [
                "aiemp.EMP_AUTO_ID = LD7.USER_REF_ID",
                "LEFT",
            ],
        ];
        $aOption = $option + $option;
        return $details = $this->common_model->getAlldata(
            OBS_APP_ESL_SEE,
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

    public function getObsDataapprovalfinal_project($did = "")
    {
        $option["where"] = [
            "fk_obs_app_id" => $did,
        ];
        $option["where_in"] = [
            "approval_type_id" => [3, 4],
        ];
        $option["return_type"] = "result";
        $option["join"] = [
            LOGIN . " AS LD6" => [
                "LD6.LOGIN_ID = " . OBS_APP_ESL_SEE . ".approver_login_id",
                "LEFT",
            ],
            EMPL . " AS repman" => [
                "repman.EMP_AUTO_ID = LD6.USER_REF_ID",
                "LEFT",
            ],

            LOGIN . " AS LD7" => [
                "LD7.LOGIN_ID = " . OBS_APP_ESL_SEE . ".fk_assigner_login_id",
                "LEFT",
            ],
            EMPL . " AS aiemp" => [
                "aiemp.EMP_AUTO_ID = LD7.USER_REF_ID",
                "LEFT",
            ],
        ];
        $aOption = $option + $option;
        return $details = $this->common_model->getAlldata(
            OBS_APP_ESL_SEE,
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

    public function getObsDataactiontaken_project($did = "")
    {
        $option["where"] = [
            "fk_obs_auto_main_id" => $did,
        ];

        $option["return_type"] = "result";
        $option["join"] = [
            LOGIN . " AS LD6" => [
                "LD6.LOGIN_ID = " . OBS_ACT_LOG . ".fk_obs_assigner_login_id",
                "LEFT",
            ],
            EMPL . " AS repman" => [
                "repman.EMP_AUTO_ID = LD6.USER_REF_ID",
                "LEFT",
            ],
            OBS_IMG_SEE . " AS obsimg" => [ // Join with obs_image_upload
                "obsimg.fk_ca_log_id = " . OBS_ACT_LOG . ".ca_log_id",
                "LEFT",
            ],
        ];
        $option['group_by'] = 'ca_log_id';
        $aOption = $option + $option;
        return $details = $this->common_model->getAlldata(
            OBS_ACT_LOG,
            [
                "fk_obs_ca_desc,fk_obs_ca_datetime",
                "repman.EMP_NAME as assigner_name",
                "FN_ROLE_NAME(fk_obs_assigner_role_id) as assigner_role",
                "FN_GET_DESIGNATION_NAME(fk_obs_assigner_des_id) as assigner_name_desig",
                "GROUP_CONCAT(obsimg.obs_att_id) AS obs_att_ids"
            ],
            $aOption
        );
    }
}
