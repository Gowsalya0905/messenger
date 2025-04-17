<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Main extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        error_reporting(1);
    }


    public function getArea_project($company)
    {
        $this->db->select('area_id,area_name');
        $this->db->where('area_status', 'Y');
        $this->db->where('company_id', $company);
        return $this->db->get(MAS_AREA)->result_array();
    }

    public function getBuilding_project($area)
    {
        $this->db->select('building_id,building_name');
        $this->db->where('building_status', 'Y');
        $this->db->where('area_id', $area);
        return $this->db->get(MAS_BUILDING)->result_array();
    }

    public function getrcategory_project($type)
    {
        $this->db->select('id,category');
        $this->db->where('category_status', 'Y');
        $this->db->where('fk_type_id', $type);
        return $this->db->get(TRN_CAT_MAS)->result_array();
    }


    public function getDepartment_project($area)
    {
        $this->db->select('dept_id,dept_name');
        $this->db->where('dept_status', 'Y');
        $this->db->where('area_id', $area);
        return $this->db->get(MAS_DEPT)->result_array();
    }


    public function getCompanyemp_project($comp_id)
    {

        $this->db->select('USER_TYPE_NAME,LD.LOGIN_ID,EMP.EMP_NAME as EMP_NAME,EMP.EMP_ID');
        $this->db->where('LD.USER_LOG_STATUS', 'Y');
        $this->db->where('EMP.EMP_LOGIN_STATUS', 'E');
        $this->db->where('EMP.EMP_COMP_ID', $comp_id);
        $this->db->join(EMPL . ' as EMP', 'EMP.EMP_AUTO_ID = LD.USER_REF_ID', 'left');
        $this->db->join(UTYPE . ' as TYP', 'TYP.USER_TYPE_ID = LD.USER_TYPE_ID', 'left');
        return $this->db->get(LOGIN . ' as LD')->result_array();
    }

    public function AreaDetails()
    {

        $company = $this->input->post('company');
        $areaDetails = $this->getArea_project($company);
        $output = '';
        $output .= '<option  value="">Select Area</option>';
        foreach ($areaDetails as $sp) {

            $output .= '<option  value="'  . $sp['area_id'] . '">' . $sp['area_name'] . '</option>';
        }
        echo $output;
        exit();
    }

    public function BuildingDetails()
    {

        $area = $this->input->post('area');
        $buildingDetails = $this->getBuilding_project($area);
        $output = '';
        $output .= '<option  value="">Select Building</option>';
        foreach ($buildingDetails as $sp) {

            $output .= '<option  value="'  . $sp['building_id'] . '">' . $sp['building_name'] . '</option>';
        }
        echo $output;
        exit();
    }

    public function trainingCategoryDetails()
    {

        $type = $this->input->post('type');
        $categoryDetails = $this->getrcategory_project($type);
        $output = '';
        $output .= '<option  value="">Select Type Category</option>';
        foreach ($categoryDetails as $sp) {

            $output .= '<option  value="'  . $sp['id'] . '">' . $sp['category'] . '</option>';
        }
        echo $output;
        exit();
    }

    public function DepartmentDetails()
    {

        $area = $this->input->post('area');
        $departmentDetails = $this->getDepartment_project($area);
        $output = '';
        $output .= '<option  value="">Select Department</option>';
        foreach ($departmentDetails as $sp) {

            $output .= '<option  value="'  . $sp['dept_id'] . '">' . $sp['dept_name'] . '</option>';
        }
        echo $output;
        exit();
    }



    public function CompanyEmployeeDetails()
    {

        $company_id = $this->input->post('company_id');
        $assigneeDetails = $this->getCompanyemp_project($company_id);
        $output = '';
        $output .= '<option  value="">Select Assignee</option>';
        foreach ($assigneeDetails as $sp) {

            $output .= '<option  value="'  . $sp['LOGIN_ID'] . '">' . $sp['EMP_NAME'] . ' - ( ' . $sp['EMP_ID'] . ' )' . '</option>';
        }
        $output .= '</select></div>';
        echo $output;
        exit();
    }


    public function EmployeeDetails()
    {
        $company = $this->input->post('company');
        $designation = $this->input->post('designation');
        

        if (empty($designation)) {
            $designation = null;  
        }
 
        $employeeDetails = $this->getEmployeesByCompanyAndDesignation($company, $designation);
       
        $output = '<option value="">Select Employee</option>';
        foreach ($employeeDetails as $employee) {
            $output .= '<option value="' . $employee['LOGIN_ID'] . '">' . $employee['EMP_NAME'] .' - ( ' . $employee['EMP_ID'] . ' )' .  '</option>';
        }
    
        echo $output;
        exit();
    }
    
    public function getEmployeesByCompanyAndDesignation($company, $designation)
    {
        $this->db->select('LD.LOGIN_ID, EMP.EMP_NAME as EMP_NAME, EMP.EMP_ID');
        $this->db->where('LD.USER_LOG_STATUS', 'Y');
        $this->db->where('EMP.EMP_LOGIN_STATUS', 'E');
    
        // Handle company being an array
        if (is_array($company)) {
            $this->db->where_in('EMP.EMP_COMP_ID', $company);
        } else {
            $this->db->where('EMP.EMP_COMP_ID', $company);
        }
        
        // Handle designation being an array (or empty)
        if ($designation !== null) {
            if (is_array($designation)) {
                $this->db->where_in('EMP.EMP_DESIGNATION_ID', $designation);
            } else {
                $this->db->where('EMP.EMP_DESIGNATION_ID', $designation);
            }
        }
        
        $this->db->join(EMPL . ' as EMP', 'EMP.EMP_AUTO_ID = LD.USER_REF_ID', 'left');
        $this->db->join(UTYPE . ' as TYP', 'TYP.USER_TYPE_ID = LD.USER_TYPE_ID', 'left');
        
        return $this->db->get(LOGIN . ' as LD')->result_array();
    }
    

}
