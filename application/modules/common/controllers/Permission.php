<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends CI_Controller {

    public function __construct() {
        parent::__construct();
        isLogin();
         $this->load->helper('permission_helper');
    }

    public function getUserType($return='drop') {

        $options['where'] = [
            'USER_TYPE_STATUS'=>'Y'
        ];
        $options['where_not_in'] = [
            'USER_TYPE_ID'=>[1,2]
        ];
        $options['return_type'] = 'result';
        $actvity = $this->common_model->getAlldata(UTYPE, ['USER_TYPE_ID', 'USER_TYPE_NAME'], $options, $limit = "", $offset = "", $orderby = "", $disporder = "");
        if ($return == 'drop') {
            return $dropdownDetails = customFormDropDown($actvity, 'USER_TYPE_ID', 'USER_TYPE_NAME', 'Select User Type');
        } else {
            return $actvity;
        }
    }

    public function index() {

      

        $userType = $this->getUserType();
        $data = [
            'userTypeInfo' => $userType,
            'view_file' => 'permission/add_form',
            'current_menu' => 'user_permission',
        ];
        
        $this->template->load_common_template($data);
    }
    
    public function savePermission() {
        
        $user_type = $this->input->post('user_type');
        $checklistmenu = $this->input->post('checklistmenu');
        $this->form_validation->set_rules('checklistmenu[]', 'checklist', 'required|trim');
        $this->form_validation->set_rules('user_type', 'User Type', 'required|trim');
        
        if ($this->form_validation->run() == true) {
            if(is_array($checklistmenu)){
                $update = FALSE;
                $table = PER_MS;
                $returnId = 'PERMISSION_ID';
                $updateData = [
                    'PERMISSION_STATUS'=>'N',
                ];
                $updateWhere = [
                    'DESIGNATION_ID'=>$user_type,
                   
                ];
                $this->common_model->updateInfo($table, $updateData, $returnId, $updateWhere);
                  foreach ($checklistmenu as $menuId => $options) {
                    
                    if($user_type !=''){
                        
                       
                        $where = [
                            'DESIGNATION_ID'=>$user_type,
                            'MENU_ID'=>$menuId,
                        ];
                        $data = [
                            'DESIGNATION_ID'=>$user_type,
                            'MENU_ID'=>$menuId,
                            'PERMISSION_STATUS'=>'Y',
                            'ADD_PER'=>'N',
                            'EDIT_PER'=>'N',
                            'VIEW_PER'=>'N',
                            'DEL_PER'=>'N',
                            'PRINT_PER'=>'N',
                        ];
                        
                        if(is_array($options) && count($options) > 0){
                            foreach($options as $key => $val){
                                switch ($key) {
                                    case 'add':
                                        $data['ADD_PER'] = 'Y';
                                        break;
                                    case 'edit':
                                        $data['EDIT_PER'] = 'Y';
                                        break;
                                    case 'view':
                                        $data['VIEW_PER'] = 'Y';
                                        break;
                                    case 'delete':
                                        $data['DEL_PER'] = 'Y';
                                        break;
                                    case 'print':
                                        $data['PRINT_PER'] = 'Y';
                                        break;

                                    default:
                                        break;
                                }
                            }
                        }
                       
                       $update =  $this->common_model->updateInfo($table, $data, $returnId, $where);
                     
                       $update = TRUE;
                      
                    }else{
                        $this->session->set_flashdata('permission_msg', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Please enter the correct information...</div>');
                    }
                    
               }
             if($update){
                           $this->session->set_flashdata('permission_msg', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><span>Success!</span> User Type Permission Updated</div>');
                       }else{
                           $this->session->set_flashdata('permission_msg', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Try again later...</div>');
                       }      
            redirect('common/permission');
            return true;
            }else{
                $this->session->set_flashdata('permission_msg', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Invalid format...</div>');
            }

        }else{
             $this->index();
        }
        
    }
    
    public function getPermissionDetails(){
       $perType = $this->input->post('perType');
       $perId = $this->input->post('perId');
       $aResponse = [];
       $list = [];
       $aResponse['status']= FALSE;
       switch ($perType) {
           case 'user_type':
               $table = PER_MS;
               $param = "MENU_ID,(CASE WHEN `ADD_PER` = 'Y' THEN 1 ELSE 0 END) as ADD_PER,(CASE WHEN `EDIT_PER` = 'Y' THEN 1 ELSE 0 END) as EDIT_PER,(CASE WHEN `DEL_PER` = 'Y' THEN 1 ELSE 0 END) as DEL_PER,(CASE WHEN `VIEW_PER` = 'Y' THEN 1 ELSE 0 END) as VIEW_PER,(CASE WHEN `PRINT_PER` = 'Y' THEN 1 ELSE 0 END) as PRINT_PER";
               $options =[
                   'return_type'=>'result'
               ];
               $options['where'] = [
                   'DESIGNATION_ID'=>$perId,
                   'PERMISSION_STATUS'=>'Y'
               ];
               
               
              $list= $this->common_model->getAlldata($table,$param,$options);
             
              $aResponse['status']= TRUE;
               break;
           
           
           default:
               break;
       }
       
       $aResponse['message']= 'Permission Details';
       $aResponse['list']= $list;
       echo json_encode($aResponse);
    }
}
