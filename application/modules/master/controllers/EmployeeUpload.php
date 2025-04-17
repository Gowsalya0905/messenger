<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeUpload extends CI_Controller {

    public function __construct() {
        parent::__construct();
        error_reporting(1);
        isLogin();
    }

    public function index($fdata = ['upload_error' => '']) {

        $data = array(
            'view_file' => 'employee/upload_employee',
            'site_title' => 'Employee ',
            'current_menu' => 'Upload Employee ',
        );
        $formData = $fdata + $data;


        $this->template->load_table_template($formData);
    }
    
   public function get_file_extensions($file_name) {
   $ext =  pathinfo($file_name, PATHINFO_EXTENSION);
   return '.'.$ext;
   }
   
   public function readExcel($filePath = ''){
       $this->load->library('PHPExcel');
                $extension = $this->get_file_extensions($filePath);
                if (strtolower($extension) == '.csv') {
                    $inputFileType = 'CSV';
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $excel = $objReader->load($filePath);
                } else if (strtolower($extension) == '.xls') {
                    $reader = PHPExcel_IOFactory::createReader('Excel5');
                    $reader->setReadDataOnly(true);
                    $path = (FCPATH . $fileName);
                    $excel = $reader->load($path);
                } else {
                    $reader = PHPExcel_IOFactory::createReader('Excel2007');
                    $reader->setReadDataOnly(true);
                    $path = (FCPATH . $filePath);
                    $excel = $reader->load($path);
                }

                return $sheet = $excel->getActiveSheet()->toArray(null, true, true, true);
   }

    public function uploadfile() {
        $userType = getCurrentUserGroupId();
        $userid = getCurrentUserid();
//        echo '<pre>';
//        print_r($_FILES);
        
        $mpath = 'public/module/employee/';
        if (!file_exists(FCPATH . $mpath)) {
            mkdir(FCPATH . $mpath, 0755, true);
        }

        $uploadPath = 'public/module/employee/' . date('Ymd') . '/';
        $allowedTyp = "xls|csv|xlsx";

        $mpath = $imgUploadpath;
        if (!file_exists(FCPATH . $mpath)) {
            mkdir(FCPATH . $mpath, 0755, true);
        }


        $config['upload_path'] = FCPATH . $uploadPath;
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path'], 0755);
        }

        $config['allowed_types'] = $allowedTyp;
        $config['encrypt_name'] = FALSE;
//        $config['overwrite'] = TRUE;
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('upload_documents')) {
            $error = $this->upload->display_errors();
            $data['upload_error'] = $error;
            $this->index($data);
        } else {
            $fileData = $this->upload->data();

            $uploadpath = $path;
            $returndata['uploadname'] = $fileData['file_name'];
            $returndata['uploadorigname'] = $fileData['orig_name'];
            $returndata['uploadpath'] = $uploadPath;
            $returndata['uploadtype'] = $fileData['file_type'];
            $returndata['uploadextension'] = $fileData['file_ext'];
            $returndata['filesize'] = $fileData['file_size'];
            $returndata['client_name'] = $fileData['client_name'];
            $returndata['orig_name'] = $fileData['orig_name'];
            $returndata['is_image'] = $fileData['is_image'];
            $returndata['image_type'] = $fileData['image_type'];
            $returndata['image_width'] = $fileData['image_width'];
            $returndata['image_height'] = $fileData['image_height'];
            $returndata['image_size_str'] = $fileData['image_size_str'];
        }
        if (count($returndata) > 0) {
            $fileName = $returndata['uploadname'];
            $filePath = $returndata['uploadpath'].$fileName;
            $uploadData = [
                'UPLOAD_FILE_NAME' => $fileName,
                'UPLOAD_FILE_PATH' => $returndata['uploadpath'],
                'LOGIN_ID' => $userid,
                'STATUS' => 1,
            ];
            $where = [
                'UPLOAD_FILE_NAME' => $fileName,
                'UPLOAD_FILE_PATH' => $returndata['uploadpath'],
                'LOGIN_ID' => $userid,
            ];
            $updateId = $this->common_model->updateInfo('TEMP_EMP_UPLOAD_FILE', $uploadData, $returnid = 'UPLOAD_ID', $where);

            if ($updateId) {
                $sheet = $this->readExcel($filePath);
                
                if($sheet != FALSE && count($sheet) > 0){
           
            
                    foreach($sheet as $row){
                        $aRow = $row;
                        $row = (object)$aRow;
                        $colEmpno = 'A';
                        $colName = 'B';
                        $colGender =  'C';
                        $colNationality =  'D';
                        $colDesignation =  'E';
                        // $colDepart =  'F';
                        // $colLoc =  'G';
                        // $colEmailId = 'H';
                        // $colCompCerName = 'I';
                        // $colCompCer = 'J';
                        // $colCompCerStartDate = 'K';
                        // $colCompCerEndDate = 'L';
                        // $colOtherCompCerName ='M';
                        // $colOtherCerName = 'N';
                        // $colOtherCerStartDate = 'O';
                        // $colOtherCerEndDate = 'P';
                        
                        $empNo = postData($row,$colEmpno);
                        $empName = postData($row,$colName);
                        $empGender = postData($row,$colGender);
                        $empNationality = postData($row,$colNationality);
                        $empDesignation = postData($row,$colDesignation);
                        $empDepart = postData($row,$colDepart);
                        $empLoc = postData($row,$colLoc);
                        $empEmailId = postData($row,$colEmailId);
                        $empCompCerName = postData($row,$colCompCerName);
                        $empCompCer = postData($row,$colCompCer);
                       // $empCompCerStartDate = postData($row,$colCompCerStartDate);
                      //  $empCompCerEndDate = postData($row,$colCompCerEndDate);
                        $empOtherCompCerName = postData($row,$colOtherCompCerName);
                        $empOtherCerName = postData($row,$colOtherCerName);
                      //  $empOtherCerStartDate = postData($row,$colOtherCerStartDate);
                      //  $empOtherCerEndDate = postData($row,$colOtherCerEndDate);
			$empCompCerStartDate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(postData($row,$colCompCerStartDate)));
                        $empCompCerEndDate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(postData($row,$colCompCerEndDate)));

 			$empOtherCerStartDate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(postData($row,$colOtherCerStartDate)));
                        $empOtherCerEndDate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(postData($row,$colOtherCerEndDate)));
                       
                         
                        if($empNo != 'EmpNo' && $colName !='Name'){
                            
                           // $cmpCertSDate = convertDate($empCompCerStartDate, $format='Y-m-d');
                          //  $cmpCertEDate = convertDate($empCompCerEndDate, $format='Y-m-d');
                          //  $otherCmpCertSDate = convertDate($empOtherCerStartDate, $format='Y-m-d');
                          //  $otherCmpCertEDate = convertDate($empOtherCerEndDate, $format='Y-m-d');

				$cmpCertSDate = $empCompCerStartDate;
                            $cmpCertEDate = $empCompCerEndDate;
                            $otherCmpCertSDate = $empOtherCerStartDate;
                            $otherCmpCertEDate = $empOtherCerEndDate;
                            
                            $insertData = [
                                'UPLOAD_ID'=>$updateId,
                                'EMP_ID'=>$empNo,
                                'EMP_NAME'=>$empName,
                                'EMP_GENDER'=>$empGender,
                                'EMP_NATIONALITY'=>$empNationality,
                                'EMP_DESIGNATION'=>$empDesignation,
                                'EMP_DEPARTMENT'=>$empDepart,
                                'EMP_LOCATION'=>$empLoc,
                                'EMP_EMAIL_ID'=>$empEmailId,
                                'EMP_COMP_CERT_NAME'=>$empCompCerName,
                                'COMP_CERT_FILE'=>$empCompCer,
                                'COMP_CERT_START_DATE'=>$cmpCertSDate,
                                'COMP_CERT_END_DATE'=>$cmpCertEDate,
                                'OTHER_COMP_CERT_NAME'=>$empOtherCompCerName,
                                'OTHER_COMP_CERT_FILE'=>$empOtherCerName,
                                'OTHER_COMP_CERT_START_DATE'=>$otherCmpCertSDate,
                                'OTHER_COMP_CERT_END_DATE'=>$otherCmpCertEDate,
                                'CREATED_BY'=>$userid,
                                'IS_DELETED'=>'0',
                                'IS_TRASH'=>'N',
                                'IS_DUPLICATE'=>'0',
                                'IS_IMPORTED'=>'0',
                                'CREATED_ON'=>date('Y-m-d H:i:s')
                            ];
                            $where = [
                                'UPLOAD_ID'=>$updateId,
                                'EMP_ID'=>$empNo,
                                'EMP_NAME'=>$empName,
                                'EMP_GENDER'=>$empGender,
                                'EMP_NATIONALITY'=>$empNationality,
                                'EMP_DESIGNATION'=>$empDesignation,
                                'EMP_DEPARTMENT'=>$empDepart,
                                'EMP_LOCATION'=>$empLoc,
                                'EMP_EMAIL_ID'=>$empEmailId,
                                'EMP_COMP_CERT_NAME'=>$empCompCerName,
                                'COMP_CERT_FILE'=>$empCompCer,
                                'COMP_CERT_START_DATE'=>$cmpCertSDate,
                                'COMP_CERT_END_DATE'=>$cmpCertEDate,
                                'OTHER_COMP_CERT_NAME'=>$empOtherCompCerName,
                                'OTHER_COMP_CERT_FILE'=>$empOtherCerName,
                                'OTHER_COMP_CERT_START_DATE'=>$otherCmpCertSDate,
                                'OTHER_COMP_CERT_END_DATE'=>$otherCmpCertEDate,
                                'CREATED_BY'=>$userid,
                            ];
                            $optionsEmp = [
                                'EMP_ID'=>$empNo
                            ];
                            
                            $this->db->select('*');
                            $this->db->from(EMPL);
                            foreach ($optionsEmp as $wKey => $wVal) {
                              $this->db->where($wKey, $wVal);
                            }
                            $this->db->or_where('EMP_EMAIL_ID', $empEmailId);
                            
                            $result = $this->db->get();
                            $getEmpInfo = ($result != false && $result->num_rows() > 0) ? $result->row() : FALSE;
                            if($getEmpInfo != FALSE){
                                $insertData['IS_DUPLICATE'] = '1';
                            }
                           
                            $insertId = $this->common_model->updateInfo('TEMP_EMP_UPLOAD_DATA', $insertData, $returnid = 'UPLOAD_DATA_ID', $where);
                        }
                       
                    }
                }
                $redirectURL = BASE_URL.'master/EmployeeUpload/previewData/'. encryptval($updateId);
                 $this->session->set_flashdata('uploadDatamsg', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Success!</span> Employee data has been uploaded and waiting for your confirmation. </div>');
                redirect($redirectURL);
            }else{
                $this->session->set_flashdata('uploadDatamsg', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Sorry!</span> Employee data  uploaded failed. </div>');
                 $redirectURL = BASE_URL.'master/EmployeeUpload';
                redirect($redirectURL);
            }
        }else{
              $this->session->set_flashdata('uploadDatamsg', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Sorry!</span> Employee data  uploaded failed. </div>');
                 $redirectURL = BASE_URL.'master/EmployeeUpload';
                redirect($redirectURL);
        }
    }
    
    public function previewData($id=''){
        $showBtn = TRUE;
        if($id !=''){
            //$decId = decryptval($id);
            $decId = 1;
            $tab = 'TEMP_EMP_UPLOAD_FILE';
            $optionsEmp['where'] = [
                'IS_IMPORTED'=> '1',
                'UPLOAD_ID'=>$decId,
            ];
             $getuploadedDataInfo = $this->common_model->getAlldata($tab, ['*'], $optionsEmp);
        //echo $this->db->last_query();exit;
             $showBtn = ($getuploadedDataInfo != FALSE) ? TRUE : FALSE;
        }
        
        $data = array(
            'view_file' => 'employee/preview_upload_employee',
            'site_title' => 'Employee ',
            'current_menu' => 'List Employee ',
            'editId' => $id,
            'showImportBtn' => $showBtn,
            'ajaxurl' => 'master/EmployeeUpload/preview_upload_employee/'.$id,
        );

        $this->template->load_table_template($data);
    }
    
    public function preview_upload_employee($id=''){
      //  $decId = decryptval($id);
        $decId = 1;
        if($decId > 0){
            $table = 'TEMP_EMP_UPLOAD_DATA';
        $column_order = array('EMP_ID','EMP_NAME', 'EMP_GENDER', 'EMP_NATIONALITY','EMP_DESIGNATION','EMP_DEPARTMENT','EMP_LOCATION','EMP_EMAIL_ID','IS_DUPLICATE');
        $column_search = array('EMP_ID','EMP_NAME', 'EMP_GENDER', 'EMP_NATIONALITY','EMP_DESIGNATION','EMP_DEPARTMENT','EMP_LOCATION','EMP_EMAIL_ID','IS_DUPLICATE');
        $order = array('CREATED_ON' => 'desc');
        $where = [
            'IS_TRASH' => 'N',
            'UPLOAD_ID' => $decId,
            ];
        $optns = [];
        
        $listDept = $this->common_model->get_datatables($table, $column_order, $column_order, $order, $where,$optns);

        $finalDatas = [];
        if (isset($listDept) && !empty($listDept)) {
            foreach ($listDept as $ltKey => $ltVal) {
                
                 if($empTerminalId == 0){
                            $status = '2';
                        }elseif ($empDeptId == 0) {
                             $status = '3';
                        }elseif ($empDesignId == 0) {
                             $status = '4';
                        }else{
                            $status = '5';
                        }
                        
                        switch ($ltVal->IS_IMPORTED) {
                            case 1:
                                $stuatus = '<label class="btn btn-xs btn-success">Imported</label>';
                                break;
                            case 2:
                                $stuatus = '<label class="btn btn-xs btn-danger">Error : Location doesn\'t exist</label>';
                                break;
                            case 3:
                                $stuatus = '<label class="btn btn-xs btn-danger">Error : Department doesn\'t exist</label>';
                                break;
                            case 4:
                                $stuatus = '<label class="btn btn-xs btn-danger">Error : Designation doesn\'t exist</label>';
                                break;
                             case 5:
                                $stuatus = '<label class="btn btn-xs btn-danger">Unknown Error</label>';
                                break;

                            default:
                                 $stuatus = '<label class="btn btn-xs btn-warning">Pending</label>';
                                break;
                        }

               
               
                $stuatus = ($ltVal->IS_DUPLICATE == 1 && $ltVal->IS_IMPORTED == 0) ? '<label class="btn btn-xs btn-warning">Duplicate</label>':$stuatus;
                $rows = [];
                $rows[] = $ltVal->EMP_ID;
                $rows[] = $ltVal->EMP_NAME;
                $rows[] = $ltVal->EMP_GENDER;
                $rows[] = $ltVal->EMP_NATIONALITY;
                $rows[] = $ltVal->EMP_DESIGNATION;
                $rows[] = $ltVal->EMP_DEPARTMENT;
                $rows[] = $ltVal->EMP_LOCATION;
                $rows[] = $ltVal->EMP_EMAIL_ID;
                $rows[] = $stuatus;
                $rows[] = $ltVal->IS_DUPLICATE;
                $rows[] = $ltVal->IS_IMPORTED;
                
               
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where),
            "data" => $finalDatas,
        );
        //output to json format
        echo json_encode($output);
        }
    }
    
    private function uploadImg($mainCertFile,$filepath){
            if (!file_exists(FCPATH . $filepath)) {
                $ch = curl_init();
                $source = $mainCertFile;
                curl_setopt($ch, CURLOPT_URL, $source);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $data = curl_exec($ch);
                curl_close($ch);
                $file = fopen(FCPATH . $filepath, "w+");
                fputs($file, $data);
                fclose($file);
                return true;
           }else if(file_exists(FCPATH . $filepath)){
               return true;
           }else{
               return false;
           }
    }
    
    public function importEmployee(){
        $id = $this->input->post('editId');
        $importType = $this->input->post('import_type');
        //$decId = decryptval($id);
        $decId = 1;
       
        if($decId > 0){
           
         
        $optionsEmp['where'] = [
               'IS_TRASH' => 'N',
               'UPLOAD_ID' => $decId,
//               'IS_IMPORTED' => '0',
            ];
            $optionsEmp['return_type'] = 'result';
             if($importType == 'edit'){
              $optionsEmp['where_in'] = [
                  'IS_DUPLICATE'=>['0','1']
              ];
            }else{
                $optionsEmp['where']['IS_DUPLICATE'] = '0';
            }
            $getuploadedDataInfo = $this->common_model->getAlldata('TEMP_EMP_UPLOAD_DATA', ['*'], $optionsEmp);
        // echo $this->db->last_query();exit;    
           
            if($getuploadedDataInfo != FALSE && count($getuploadedDataInfo) > 0){
                foreach ($getuploadedDataInfo as $row){
                    
                        $empid = postData($row,'EMP_ID');
                        $empDup = postData($row,'IS_DUPLICATE');
                        $empName = postData($row,'EMP_NAME');
                        $empGender = postData($row,'EMP_GENDER');
                        $empNationality = postData($row,'EMP_NATIONALITY');
                        $empDesign = postData($row,'EMP_DESIGNATION');
                        $empDept = postData($row,'EMP_DEPARTMENT');
                        $empLoc = postData($row,'EMP_LOCATION');
                        $empEmailId = postData($row,'EMP_EMAIL_ID');
                        $empMainCertName = postData($row,'EMP_COMP_CERT_NAME');
                        $empMainCertSDate = postData($row,'COMP_CERT_START_DATE');
                        $empMainCertEDate = postData($row,'COMP_CERT_END_DATE');
                        
                        $empOtherCertName = postData($row,'OTHER_COMP_CERT_NAME');
                        $empOtherCertSDate = postData($row,'OTHER_COMP_CERT_START_DATE');
                        $empOtherCertEDate = postData($row,'OTHER_COMP_CERT_END_DATE');
                        
                        $empUploadId = postData($row,'UPLOAD_DATA_ID');
                        $empUploadDataId = postData($row,'UPLOAD_ID');
                        
                        /*Gender*/ 
                       $optionsGen['return_type'] = 'row';
                       $optionsGen['where'] = [
                           'LOWER(GENDER_NAME)'=> strtolower($empGender)
                       ];
                       $getGenderInfo = $this->common_model->getAlldata(GENDER, ['GENDER_ID'],$optionsGen);
                       $empGenderId = ($getGenderInfo != FALSE) ? postData($getGenderInfo,'GENDER_ID') : 3;
                       /*Nationality*/
                       $optionsGen['return_type'] = 'row';
                       $optionsGen['where'] = [
                           'LOWER(NATIONALITY_NAME)'=> strtolower($empNationality)
                       ];
                      
                        $getNationalityInfo = $this->common_model->getAlldata(NATION, ['NATIONALITY_ID'],$optionsGen);
                        
                        $empNationalityId = ($getNationalityInfo != FALSE) ? postData($getNationalityInfo,'NATIONALITY_ID') : 2;
                        
                      /*Location*/  
                        $termOptn['return_type'] = 'row';
                        $termOptn['where'] = [
                        'TERMINAL_STATUS' => 'Y',
                        'LOWER(TERMINAL_SHORTNAME)' =>strtolower($empLoc)
                        ];
                    $getAllterminal = $this->common_model->getAlldata(TERM, ['TER_AUTO_ID'], $termOptn);
                    if($getAllterminal == FALSE){
                        $empTerminalId = 0;
//                        $getuniqueId = uniqueRefId('TER', TER_MAN, 'TER_AUTO_ID');
//                        $terData = [
//                            'TERMINAL_ID'=>$getuniqueId,
//                            'TERMINAL_NAME'=>$empLoc,
//                            'TERMINAL_SHORTNAME'=>$empLoc,
//                        ];
//                        $empTerminalId =$this->common_model->updateData(TERM, $terData,[],'');
                    }else{
                        $empTerminalId =  postData($getAllterminal,'TER_AUTO_ID');
                    }
                    
                    
                     /*Department*/ 
                    
                     $optionDept['where'] = [
                        'DEPT_STATUS' => 'Y',
                        'LOWER(DEPT_NAME)' =>strtolower($empDept)
                        ];
                    $optionDept['return_type'] = 'row';
                    $getDeptInfo = $this->common_model->getAlldata(DEPT, ['DEPT_AUTO_ID'], $optionDept);
                    if($getDeptInfo == FALSE){
                        $empDeptId = 0;
//                        $getuniqueId = uniqueRefId('DEPT', DEPT, 'DEPT_AUTO_ID');
//                        $insertData = [
//                            'DEPT_ID'=>$getuniqueId,
//                            'FK_TERMINAL_ID'=>1,
//                            'DEPT_NAME'=>$empDept,
//                            'DEPT_STATUS'=>'Y',
//                        ];
//                        $empDeptId =$this->common_model->updateData(DEPT, $insertData,[],'');
                    }else{
                        $empDeptId =  postData($getDeptInfo,'DEPT_AUTO_ID');
                    }
                    
                    /*Designation*/ 
                    
                     $optionDesign['where'] = [
                        'DESIGNATION_STATUS' => 'Y',
                        'LOWER(DESIGNATION_NAME)' =>strtolower($empDesign)
                        ];
                    $optionDesign['return_type'] = 'row';
                    $getDesigInfo = $this->common_model->getAlldata(DESIG, ['DESIGNATION_ID'], $optionDesign);
                    if($getDesigInfo == FALSE){
                        $empDesignId = 0;
//                        $getuniqueId = uniqueRefId('DES', DESIG, 'DESIGNATION_ID');
//                        $insertData = [
//                            'DES_GENERATE_ID'=>$getuniqueId,
//                            'DESIGNATION_NAME'=>$empDesign,
//                            'DESIGNATION_STATUS'=>'Y',
//                        ];
//                        $empDesignId =$this->common_model->updateData(DESIG, $insertData,[],'');
                    }else{
                        $empDesignId =  postData($getDesigInfo,'DESIGNATION_ID');
                    }
                    
//                    echo 'Location -->'. $empTerminalId; echo '<br/>';
//                    echo  'Department -->'.$empDeptId;echo '<br/>';
//                    echo  'Designation -->'.$empDesignId;echo '<br/>';
//                    
//                    
                  if($empTerminalId > 0 && $empDeptId > 0  && $empDesignId > 0){
                    
                    $insertEmpData = [
                        'EMP_ID'=> $empid,
                        'EMP_NAME'=> $empName,
                        'EMP_GENDER'=> $empGenderId,
                        'EMP_NATIONALITY'=> $empNationalityId,
                        'EMP_DESIGNATION_ID'=> $empDesignId,
                        'EMP_DEPT_ID'=> $empDeptId,
                        'EMP_LOC_ID'=> $empTerminalId,
                        'EMP_LOC_NAME'=> $empLoc,
                        'EMP_EMAIL_ID'=> $empEmailId,
                        'CERT_STATUS'=> 'N',
                        'EMP_STATUS'=> 'Y',
                    ];
                    if($empNationalityId == 2){
                        $insertEmpData['EMP_NATIONALITY_OTHER'] = $empNationality;
                    }
                    if($empDup == 0){
                        $insertEmpData['EMP_LOGIN_STATUS'] = 'P';
                    }
                    $empWhere = [
                        'EMP_ID'=> $empid,
                        'EMP_STATUS'=> 'Y',
                    ];
                    
                    $empId = $this->common_model->updateInfo(EMPL,$insertEmpData,'EMP_AUTO_ID',$empWhere);
                                     
                    $mainCertFile = postData($row,'COMP_CERT_FILE');
                    if($mainCertFile !=''){
                        $fileext = $this->get_file_extensions($mainCertFile);
                        $newFilename = 'main_cert_'.$empid.'_'.time().$fileext;
                        $fpath = "assets/images/modules/employee/";
                        $destination = $fpath . $newFilename;
                        $this->uploadImg($mainCertFile,$destination);
                       
                       $insertCertidet =[
                           'CERT_USER_TYPE' => 'EMP',
                           'CERT_TYPE' => 'MAIN',
                           'CERT_NAME' => $empMainCertName,
                           'CERT_FILE_NAME' =>$newFilename,
                           'CERT_PATH' => $fpath,
                           'CERT_EXT' => $fileext,
                           'CERT_SIZE' =>0,
                           'CERT_START_DATE' => $empMainCertSDate,
                           'CERT_END_DATE' => $empMainCertEDate,
                           'CERT_USER_ID' => $empId,
                           'CREATED_ON' => date('Y-m-d H:i:s'),
                           'CERT_COMMON_STATUS' => 'Y',
                       ];
                       $certiWhere =[
                           'CERT_USER_ID' => $empId,
                           'CERT_TYPE' => 'MAIN',
                           'CERT_USER_TYPE' => 'EMP',
                       ];
                        
                       $updtCerti = $this->common_model->updateInfo(COMP_CERTI,$insertCertidet,'COMP_CERT_ID',$certiWhere);
                       
                    }
                    
                    $otherCertFile = postData($row,'OTHER_COMP_CERT_FILE');
                    if($otherCertFile !=''){
                        $fileext = $this->get_file_extensions($otherCertFile);
                        $newFilename = 'other_cert_'.$empid.'_'.time().$fileext;
                         $fpath = "assets/images/modules/employee/";
                        $destination = $fpath . $newFilename;
                        $this->uploadImg($otherCertFile,$destination);
                         $insertCertidet =[
                           'CERT_USER_TYPE' => 'EMP',
                           'CERT_TYPE' => 'OTHER',
                           'CERT_NAME' => $empOtherCertName,
                           'CERT_FILE_NAME' =>$newFilename,
                           'CERT_PATH' => $fpath,
                           'CERT_EXT' => $fileext,
                           'CERT_SIZE' =>0,
                           'CERT_START_DATE' => $empOtherCertSDate,
                           'CERT_END_DATE' => $empOtherCertEDate,
                           'CERT_USER_ID' => $empId,
                           'CREATED_ON' => date('Y-m-d H:i:s'),
                           'CERT_COMMON_STATUS' => 'Y',
                       ];
                       $certiWhere =[
                           'CERT_USER_ID' => $empId,
                           'CERT_TYPE' => 'OTHER',
                           'CERT_USER_TYPE' => 'EMP',
                       ];
                        
                       $updtCerti = $this->common_model->updateInfo(COMP_CERTI,$insertCertidet,'COMP_CERT_ID',$certiWhere);
                    }
                    
                    
                    if($empId > 0){
                        $upData = [
                            'IS_IMPORTED'=>'1'
                        ];
                        $upWhere = [
                            'UPLOAD_DATA_ID'=>$empUploadId
                        ];
                        $updtUpload = $this->common_model->updateData('TEMP_EMP_UPLOAD_DATA',$upData,$upWhere);
                        
                        
                       
                        
                        
                        if($mainCertFile !=''){
                            $empCertUpdate = [
                                'CERT_STATUS'=>'Y'
                            ];
                            $eWhere = [
                                'EMP_AUTO_ID'=>$empId
                            ];

                             $updtEmpData = $this->common_model->updateData(EMPL,$empCertUpdate,$eWhere);
                        }
                     }
                    }else{
                      
                        if($empTerminalId == 0){
                            $status = '2';
                        }elseif ($empDeptId == 0) {
                             $status = '3';
                        }elseif ($empDesignId == 0) {
                             $status = '4';
                        }else{
                            $status = '5';
                        }
                       
                       
                        $upData = [
                            'IS_IMPORTED'=>$status
                        ];
                        $upWhere = [
                            'UPLOAD_DATA_ID'=>$empUploadId
                        ];
                        $updtUpload = $this->common_model->updateData('TEMP_EMP_UPLOAD_DATA',$upData,$upWhere);
                    }
                    
                     $upDatafileData = [
                            'IS_IMPORTED'=>'1'
                        ];
                        $upDatafileWhere = [
                            'UPLOAD_ID'=>$empUploadDataId
                        ];
                        $updtUpload = $this->common_model->updateData('TEMP_EMP_UPLOAD_FILE',$upDatafileData,$upDatafileWhere);
                    
                    
                    
                   
                       
                }
//                echo '<pre>';
//                   echo $empId;
//                   exit;
                     $this->session->set_flashdata('uploadDatamsg', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Success!</span> Employee data  imported successfully. </div>');
             $redirectURL = BASE_URL.'master/EmployeeUpload/previewData/'.$id;
             redirect($redirectURL);
            }else{
                $this->session->set_flashdata('uploadDatamsg', '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Warning!</span> No data to import. </div>');
             $redirectURL = BASE_URL.'master/EmployeeUpload/previewData/'.$id;
             redirect($redirectURL);
            }
            
        }else{
             $this->session->set_flashdata('uploadDatamsg', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Sorry!</span> Employee data  imported failed. </div>');
             $redirectURL = BASE_URL.'master/EmployeeUpload';
             redirect($redirectURL);
        }
       
    }
}
