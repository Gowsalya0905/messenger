<?php

defined('BASEPATH') or exit('No direct script access allowed');

require LUMAPIPATH . 'libraries/REST_Controller.php';

class Operational_master extends REST_Controller
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
    public function company_master_get()
    {
        $option1['where'] = [
            'company_status' => 'Y',
        ];
        $company_master = $this->common->getAlldata('master_company', ['*'], $option1);
        if (!empty($company_master)) {

            $message = array(
                'status' => TRUE,
                'company_master' => $company_master
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

    public function area_master_post()
    {
        $company_id = $this->post('company_id');
        $option1['where'] = [
            'area.area_status' => 'Y',
            'area.company_id' => $company_id,
        ];
        $area_master = $this->common->getAlldata('master_area as area', ['area.*,FN_COMP_NAME(area.company_id) as company_name'], $option1);
        if (!empty($area_master)) {

            $message = array(
                'status' => TRUE,
                'area_master' => $area_master
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

    public function building_master_post()
    {
        $company_id = $this->post('company_id');
        $area_id = $this->post('area_id');
        $option1['where'] = [
            'building.building_status' => 'Y',
            'building.company_id' => $company_id,
            'building.area_id' => $area_id,
        ];
        $building_master = $this->common->getAlldata('master_building as building', ['building.*,FN_COMP_NAME(building.company_id) as company_name,FN_AREA_NAME(building.area_id) as area_name'], $option1);
        if (!empty($building_master)) {

            $message = array(
                'status' => TRUE,
                'building_master' => $building_master
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

    public function department_master_post()
    {
        $company_id = $this->post('company_id');
        $area_id = $this->post('area_id');
        $option1['where'] = [
            'dept.dept_status' => 'Y',
            'dept.company_id' => $company_id,
            'dept.area_id' => $area_id,
        ];
        $dept_master = $this->common->getAlldata('master_department as dept', ['dept.*,FN_COMP_NAME(dept.company_id) as company_name,FN_AREA_NAME(dept.area_id) as area_name'], $option1);
        if (!empty($dept_master)) {

            $message = array(
                'status' => TRUE,
                'department_master' => $dept_master
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

    public function project_master_get()
    {
        $option1['where'] = [
            'project_status' => 'Y',
        ];
        $project_master = $this->common->getAlldata('master_project', ['*'], $option1);
        if (!empty($project_master)) {

            $message = array(
                'status' => TRUE,
                'project_master' => $project_master
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
}
