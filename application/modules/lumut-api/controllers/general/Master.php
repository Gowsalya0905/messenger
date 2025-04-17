<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require LUMAPIPATH . 'libraries/REST_Controller.php';

class Master extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('common/common_model');
        $this->load->helper('main_helper');
        $this->load->library('pdf');
        $this->common_model->api_log($this->post());
    }

    public function getVertical_get() {

        $getAllvertical =[];

        $verOptn['where'] = [
            'ver_status' => 'Y'
        ];
        $getAllvertical = $this->common_model->getAlldata(MAS_VER, ['*'], $verOptn);

        if (!empty($getAllvertical)) {
            $message = array(
                'status' => TRUE,
                'message' => 'Success',
                'getAllvertical' => $getAllvertical,
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    public function getUnit_post() {

        $vertical_id = $this->post('vertical_id');

        if($vertical_id !=''){
            $unitOptn['where'] = [
                'unit_status' => 'Y',
                'fk_ver_id' => $vertical_id
            ];
        }else{
            $unitOptn['where'] = [
                'unit_status' => 'Y'
            ];
        }

        $getAllunit = $this->common_model->getAlldata(MAS_UNI, ['*'], $unitOptn);

        if (!empty($getAllunit)) {
            $message = array(
                'status' => TRUE,
                'message' => 'Success',
                'getAllunit' => $getAllunit,
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    public function getLocation_post() {

        $vertical_id = $this->post('vertical_id');
        $unit_id = $this->post('unit_id');

        if($vertical_id !='' && $unit_id !=''){
            $locOptn['where'] = [
                'loc_status' => 'Y',
                'fk_ver_id' => $vertical_id,
                'fk_unit_id' => $unit_id
            ];
        }else{
            $locOptn['where'] = [
                'loc_status' => 'Y',
            ];
        }

        $getAllocation = $this->common_model->getAlldata(MAS_LOC, ['*'], $locOptn);

        if (!empty($getAllocation)) {
            $message = array(
                'status' => TRUE,
                'message' => 'Success',
                'getAllocation' => $getAllocation,
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    public function getPlant_post() {

        $vertical_id = $this->post('vertical_id');
        $unit_id = $this->post('unit_id');
        $location_id = $this->post('location_id');

        if($vertical_id !='' && $unit_id !='' && $location_id !=''){
            $pltOptn['where'] = [
                'plant_status' => 'Y',
                'fk_ver_id' => $vertical_id,
                'fk_unit_id' => $unit_id,
                'fk_loc_id' => $location_id,
            ];
        }else{
            $pltOptn['where'] = [
                'plant_status' => 'Y',
            ];
        }

        $getAllplant = $this->common_model->getAlldata(MAS_PLT, ['*'], $pltOptn);

        if (!empty($getAllplant)) {
            $message = array(
                'status' => TRUE,
                'message' => 'Success',
                'getAllplant' => $getAllplant,
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    public function getZone_post() {

        $vertical_id = $this->post('vertical_id');
        $unit_id = $this->post('unit_id');
        $location_id = $this->post('location_id');
        $plant_id = $this->post('plant_id');

        if($vertical_id !='' && $unit_id !='' && $location_id !='' && $plant_id !=''){
            $zoneOptn['where'] = [
                'zone_status' => 'Y',
                'fk_ver_id' => $vertical_id,
                'fk_unit_id' => $unit_id,
                'fk_loc_id' => $location_id,
                'fk_plant_id' => $plant_id,
            ];
        }else{
            $zoneOptn['where'] = [
                'zone_status' => 'Y',
            ];
        }

        $getAllzone = $this->common_model->getAlldata(MAS_ZNE, ['*'], $zoneOptn);

        if (!empty($getAllzone)) {
            $message = array(
                'status' => TRUE,
                'message' => 'Success',
                'getAllzone' => $getAllzone,
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    public function getSubZone_post() {

        $vertical_id = $this->post('vertical_id');
        $unit_id = $this->post('unit_id');
        $location_id = $this->post('location_id');
        // $plant_id = $this->post('plant_id');
        // $zone_id = $this->post('zone_id');

        // if($vertical_id !='' && $unit_id !='' && $location_id !='' && $plant_id !='' && $zone_id !=''){
            if($vertical_id !='' && $unit_id !='' && $location_id !=''){
            $szoneOptn['where'] = [
                'sub_zone_status' => 'Y',
                'fk_ver_id' => $vertical_id,
                'fk_unit_id' => $unit_id,
                'fk_loc_id' => $location_id,
                // 'fk_plant_id' => $plant_id,
                // 'fk_zone_id' => $zone_id,
            ];
        }else{
            $szoneOptn['where'] = [
                'sub_zone_status' => 'Y',
            ];
        }

        $getAllsubzone = $this->common_model->getAlldata(MAS_SZE, ['*'], $szoneOptn);

        if (!empty($getAllsubzone)) {
            $message = array(
                'status' => TRUE,
                'message' => 'Success',
                'getAllsubzone' => $getAllsubzone,
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    public function getDepartment_post() {

        $vertical_id = $this->post('vertical_id');
        $unit_id = $this->post('unit_id');
        $location_id = $this->post('location_id');
        $plant_id = $this->post('plant_id');
        $zone_id = $this->post('zone_id');

        $getAlldept =[];
        
        $deptOptn['where'] = [
            'dept_status' => 'Y',
            'fk_ver_id' => $vertical_id,
            'fk_unit_id' => $unit_id,
            'fk_loc_id' => $location_id,
            'fk_plant_id' => $plant_id,
            'fk_zone_id' => $zone_id,
        ];




        $getAlldept = $this->common_model->getAlldata(MAS_DEPT, ['*'], $deptOptn);

        if (!empty($getAlldept)) {
            $message = array(
                'status' => TRUE,
                'message' => 'Success',
                'getAlldept' => $getAlldept,
                
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function getEmployee_get(){

        $obsEmpData = getEmployeeDetails("");

        if (!empty($obsEmpData)) {
            $message = array(
                'status' => TRUE,
                'message' => 'Success',
                'obsEmpData' => $obsEmpData,
                
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }


    public function getAreaInManager_post(){
        $deptid = $this->post('deptid');

        $dropInsptypedata = getAreaManagerDetails("",$deptid);

        if (!empty($dropInsptypedata)) {
            $message = array(
                'status' => TRUE,
                'message' => 'Success',
                'dropInsptypedata' => $dropInsptypedata,
                
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
        
        
    }

    public function obsMaster_get() {
        
        
        $getAlltype = $getAllseverity = $getAllagency = $getAllresposible = [];

        $typeOptn['where'] = [
            'obs_type_status' => 'Y'
        ];
        $getAlltype = $this->common_model->getAlldata(OBS_TYPE, ['*'], $typeOptn);

        $severOptn['where'] = [
            'obs_sev_status' => 'Y'
        ];
        $getAllseverity = $this->common_model->getAlldata(OBS_SEV, ['*'], $severOptn);

        $agencyOptn['where'] = [
            'obs_age_type_status' => 'Y'
        ];
        $getAllagency = $this->common_model->getAlldata(OBS_AGE, ['*'], $agencyOptn);

        $resOptn['where'] = [
            'obs_res_type_status' => 'Y'
        ];
        $getAllresposible = $this->common_model->getAlldata(OBS_RES, ['*'], $resOptn);

        
        
 

        if (!empty($getAlltype)|| !empty($getAllseverity) || !empty($getAllagency) || !empty($getAllresposible)) {
            $message = array(
                'status' => TRUE,
                'message' => 'Success',
                'getAlltype' => $getAlltype,
                'getAllseverity' => $getAllseverity,
                'getAllagency' => $getAllagency,
                'getAllresposible' => $getAllresposible
                
                
            );
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
        } else {
            $message = array(
                'status' => FALSE,
                'message' => 'No Data'
            );

            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

}
