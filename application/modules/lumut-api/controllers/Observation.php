<?php

defined('BASEPATH') or exit('No direct script access allowed');

require LUMAPIPATH . 'libraries/REST_Controller.php';

class Observation extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model('common/common_model', 'common');
        $this->load->model('atarusee/atarusee_model', 'atar');
        $this->load->helper('obs_helper');
    }
    //masters
    public function hse_category_master_get()
    {
        $option1['where'] = [
            'hse_status' => 'Y',
        ];
        $hse_cat_master = $this->common->getAlldata('master_hse_category', ['*'], $option1);
        if (!empty($hse_cat_master)) {

            $message = array(
                'status' => TRUE,
                'hse_cat_master' => $hse_cat_master
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function obs_type_master_get()
    {
        global $obs_type_list;

        if (!empty($obs_type_list)) {
            $obs_type_master = [];
            foreach ($obs_type_list as $key => $value) {
                if ($key !== '') {
                    $obs_type_master[] = [
                        'obs_type_id' => $key,
                        'obs_type' => $value
                    ];
                }
            }

            $message = [
                'status' => TRUE,
                'obs_type_master' => $obs_type_master
            ];
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            // No data response
            $message = [
                'status' => FALSE,
                'message' => 'No Data'
            ];
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function risk_rating_master_get()
    {
        global $risk_rating;

        if (!empty($risk_rating)) {
            $risk_rating_master = [];
            foreach ($risk_rating as $key => $value) {
                if ($key !== '') {
                    $risk_rating_master[] = [
                        'risk_rating_id' => $key,
                        'risk_rating' => $value
                    ];
                }
            }

            $message = [
                'status' => TRUE,
                'risk_rating_master' => $risk_rating_master
            ];
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            // No data response
            $message = [
                'status' => FALSE,
                'message' => 'No Data'
            ];
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function company_based_emp_dropdown_post()
    {

        $company_id = $this->post('company_id');
        $this->db->select('LD.LOGIN_ID,EMP.EMP_NAME as EMP_NAME,USER_TYPE_NAME as DESIG');
        $this->db->where('LD.USER_LOG_STATUS', 'Y');
        $this->db->where('EMP.EMP_LOGIN_STATUS', 'E');
        $this->db->where('EMP.EMP_COMP_ID', $company_id);
        $this->db->join(EMPL . ' as EMP', 'EMP.EMP_AUTO_ID = LD.USER_REF_ID', 'left');
        $this->db->join(UTYPE . ' as TYP', 'TYP.USER_TYPE_ID = LD.USER_TYPE_ID', 'left');
        $assigneeDetails =  $this->db->get(LOGIN . ' as LD')->result_array();

        if (!empty($assigneeDetails)) {

            $message = array(
                'status' => TRUE,
                'assignee' => $assigneeDetails
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    //masters

    public function observation_save_post()
    {
        // ini_set('display_errors' ,'1');
        $postData = $this->post();
        $id =  $postData['id'];
        $savesta = $postData['savesta'];
        $prodatas = [
            'ATAR_REPORTER_TYPE_ID' => $postData['ATAR_REPORTER_ROLE_ID'],
            'ATAR_REPORTER_ID' => $postData['ATAR_REPORTER_ID'] ?? null,
            'ATAR_REPORTER_DESG_ID' => $postData['USER_DESINATION_ID'] ?? null,

            'ATAR_REPORT_DATETIME' => $postData['ATAR_REPORT_DATETIME'] ?? null,
            'ATAR_INC_CAT' => $postData['inc_cat'] ?? null,
            'ATAR_SUB' => $postData['sub'] ?? null,
            'ATAR_DEPT_ID' => (isset($postData['dept']) && !empty($postData['dept'])) ? implode(',', $postData['dept']) : '',
            'ATAR_CUST_NAME' => isset($postData['cus_name']) ? implode(',', $postData['cus_name']) : '',
            'ATAR_PLACE' => $postData['occ'] ?? null,
            'ATAR_DATE_TIME' => $postData['date_time'] ?? null,
            'ATAR_EMP' => $postData['emp_name'] ?? null,
            'ATAR_STAFF_ID' => $postData['staff_id'] ?? null,
            'ATAR_VEH_NUM' => $postData['sgh'] ?? null,
            'ATAR_CON_SHIP' => $postData['consi_ship'] ?? null,
            'ATAR_WIT_NAME' => $postData['wit_name'] ?? null,
            'ATAR_WIT_NUM' => $postData['wit_contact'] ?? null,
            'ATAR_MAWB' => $postData['mawb'] ?? null,
            'ATAR_HAWB' => $postData['hawb'] ?? null,
            'ATAR_TOT' => $postData['tot_quan'] ?? null,
            'ATAR_WEIGHT' => $postData['weight'] ?? null,
            'ATAR_QUANTITY' => $postData['quant_damage'] ?? null,
            'ATAR_INS' => $postData['ins_policy'] ?? null,
            'ATAR_CUST_REF' => $postData['cust_ref'] ?? null,
            'ATAR_CUST_PERMIT' => $postData['cust_permit'] ?? null,
            'ATAR_DESCR' => $postData['inc_des'] ?? null,
            'ATAR_PROB' => $postData['prob'] ?? null,
            'ATAR_SGSA' => $postData['std_pro'] ?? null,
            'ATAR_WHERE' => $postData['where'] ?? null,
            'ATAR_WHEN' => $postData['when'] ?? null,
            'ATAR_HOW' => $postData['how'] ?? null,
            'ATAR_ROOT_CAUSE' => isset($postData['why_analysis']) ? implode(',', array_map('trim', $postData['why_analysis'])) : '',
            'ATAR_FINAL_ROOT_CAUSE' => $postData['final_analysis'] ?? null,
            'INJ_DESC' => $postData['inj_des'] ?? null,
            'INJ_BODY_PART' => $postData['body_parts'] ?? null,
        ];

        if ($savesta == 1) {
            $prodatas['ATAR_APP_STATUS'] = 1;
            $prodatas['ATAR_APP_CLAIM_STATUS'] = 1;
        } else {
            $prodatas['ATAR_APP_STATUS'] = 9; //initial form drafted
            $prodatas['ATAR_APP_CLAIM_STATUS'] = 1;
        }

        if ($id != '') {
            $upWhere = [
                'ATAR_AUTO_ID' => $id
            ];
            $updtProfile = $this->common_model->updateData('atar_main_flow_usee', $prodatas, $upWhere);
            $updateid = $id;
            $where = [
                "ATAR_FILE_TYPE" => 1,
                "FK_ATAR_MAIN_ID" => $id,
            ];

            $delete = $this->common->DeleteData('atar_image_upload_usee', $where);
        } else {
            $getuniqueId = getAtarNumber_usee('SGSAI');
            $prodatas['ATAR_ID'] = $getuniqueId;
            $updtProfile = $this->common_model->updateData('atar_main_flow_usee', $prodatas);
            $updateid = $updtProfile;
        }
        if (isset($postData['why_analysis']) && is_array($postData['why_analysis'])) {
            $this->common_model->deleteData('atar_usee_root', ['atar_ref_id' => $id]);
            foreach ($postData['why_analysis'] as $index => $why) {
                $why_analysis_id = $postData['why_analysis_id'][$index] ?? null;
                $whyData = [
                    'atar_ref_id' => $updateid,
                    'why' => trim($why),
                ];
                $this->common_model->updateData('atar_usee_root', $whyData);
            }
        }
    }
}
