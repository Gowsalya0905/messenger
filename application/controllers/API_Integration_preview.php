<?php
defined("BASEPATH") or exit("No direct script access allowed");

class API_Integration_preview extends CI_Controller{

     public function __construct()
    {
        parent::__construct();

        $this->load->library('curl');
		$this->load->model("common/common_model", "common");
		$this->load->helper("common_helper");
    }


	public function validateDate($date, $format = 'd-m-Y') {
        $d = DateTime::createFromFormat($format, $date);
        $isValidFormat = $d && $d->format($format) === $date;
        if ($isValidFormat) {
            $currentDate = new DateTime();
            return $d <= $currentDate;
        }
        return false;
    }
	
	

    public function index()
	{

		ini_set('max_execution_time', 0);

		$currentData =date('Ymd');
		// $fromdate =date('Ymd');
		$fromdate ='20231101';
		//apiDate 20240220
		// $url = 'https://app.welspun.com/webdata/api/HSE/SamayMaster?FromDate='.$fromdate.'&ToDate='.$currentData.'&Reportstype=DELTA';
		// $username = 'WELSAFE';
		// $password = 'XA4C*0Yatra!eTj';
		// $ch = curl_init($url);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// $response = curl_exec($ch);
		// // echo '<pre>';
		// // print_r(($response));exit;
		// if(curl_errno($ch)) {
		// 	$error_message = curl_error($ch);
		// } else {
		// 	$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// 	if ($http_status_code == 200) {
			$response = '{
				"Message": "Data Received successfully",
				"Data": [
					{
						"EmployeeNo": "18628",
						"EmployeeType": "Staff - Staff",
						"EmployeeName": "Chandresh Joshi",
						"Gender": "Male",
						"EmailID": "emp@cresent.com",
						"DateofBirth": "13-04-1969",
						"JoiningDate": "13-04-1969",
						"PhoneNumber": "98765432101",
						"PastExperience": "10",
						"Nationality": "Indian",
						"Role": "HR",
						"Designation": "admin des",
						"Plant": "Unit 6",
						"Location": "Salap",
						"Department": "dept new"
					},
					{
						"EmployeeNo": "18624",
						"EmployeeType": "Worker",
						"EmployeeName": "Kabilesh Patel Joshi",
						"Gender": "Female",
						"EmailID": "emp2@cresent.com",
						"DateofBirth": "13-04-1969",
						"JoiningDate": "13-04-1969",
						"PhoneNumber": "98765432101",
						"PastExperience": "10",
						"Nationality": "Indian",
						"Role": "HR",
						"Designation": "admin des",
						"Plant": "Unit 1",
						"Location": "Salap",
						"Department": "dept new"
					}
				]
			}';
			$result = json_decode($response);
				$ApiData = $result->Data;
				// echo '<pre>';
				// print_r(($ApiData));exit;
				if(!empty($ApiData)){
					$i=1;
					foreach($ApiData as $api){


						//Employee Type
						$export_status = "Success"; $invalid_status = 0; $error = [];
						$EmployeeTypeId = $roleId = $desgId = $plantId = $LocId = $DeptId = NULL;

						if(empty($api->EmployeeNo)){
							$error[] = 'Employee No Required';
							$invalid_status = 1;
						}
						if(empty($api->EmployeeName)){
							$error[] = 'Employee Name Required';
							$invalid_status = 1;
						}

						if($api->EmployeeType){
							$empTypeLower = strtolower($api->EmployeeType);
				
							$optiontype['where'] = [
								'type_status' => 'Y',
								'LOWER(emp_type)' => $empTypeLower, // Ensure case-insensitive comparison
							];
							$optiontype['return_type'] = 'row';
					
							// Fetch the data from the database
							$searchType = $this->common->getAllData('mas_employee_type', ['*'], $optiontype);
					
							if ($searchType) {
								$EmployeeTypeId = $searchType->type_auto_id;
							} else {
								$error[] = 'Invalid Employee Type';
								$invalid_status = 1;
							}
						}

						
						switch ($api->Gender) {
							case 'Male':
							case 'male':
								$gender ='1';
							break;
							case 'Female':
							case 'female':
								$gender ='2';
							break;
							default:
							$gender ='3';
						}
				
						if($api->EmailID){
							if (!filter_var($api->EmailID, FILTER_VALIDATE_EMAIL)) {
								$error[] = 'Invalid Email ID';
								$invalid_status = 1;
							} 
						}
					
						$validate_DateofBirth = $this->validateDate($api->DateofBirth);
						if (!$validate_DateofBirth) {
							$error[] = 'Invalid Date of Birth';
							$invalid_status = 1;
						}
						
						$validate_JoiningDate = $this->validateDate($api->JoiningDate);
						if (!$validate_JoiningDate) {
							$error[] = 'Invalid Joining Date';
							$invalid_status = 1;
						}
						

						if (!filter_var($api->PhoneNumber, FILTER_VALIDATE_INT)) {
							$error[] = 'Invalid Phone Number';
							$invalid_status = 1;
						}

						if (!filter_var($api->PastExperience, FILTER_VALIDATE_INT)) {
							$error[] = 'Invalid Past Experience data';
							$invalid_status = 1;
						}
						
						switch ($api->Nationality) {
							case 'Indian':
							case 'indian':
								$Nationality ='1';
							break;
							default:
							$Nationality ='2';
						}

						if($api->Role){
							$roleLower = strtolower($api->Role);
				
							$optiontype['where'] = [
								'USER_TYPE_STATUS ' => 'Y',
								'LOWER(USER_TYPE_NAME)' => $roleLower, // Ensure case-insensitive comparison
							];
							$optiontype['return_type'] = 'row';
					
							// Fetch the data from the database
							$user_role = $this->common->getAllData('user_type', ['*'], $optiontype);
					
							if ($user_role) {
								$roleId = $user_role->USER_TYPE_ID ;
							} else {
								$error[] = 'Invalid Role';
								$invalid_status = 1;
							}
							
						}else{
							$error[] = 'Role is Required';
							$invalid_status = 1;
						}
				
						if($api->Designation){
							$DesignationLower = strtolower($api->Designation);
				
							$optiontype['where'] = [
								'DESIGNATION_STATUS  ' => 'Y',
								'DES_USER_TYPE'=>$roleId,
								'LOWER(DESIGNATION_NAME)' => $DesignationLower, // Ensure case-insensitive comparison
							];
							$optiontype['return_type'] = 'row';
					
							$designation = $this->common->getAllData('designation', ['*'], $optiontype);
					
							if ($designation) {
								$desgId = $designation->DESIGNATION_ID;
							} else {
								$error[] = 'Invalid Designation';
								$invalid_status = 1;
							}
							
						}else{
							$error[] = 'Designation is Required';
							$invalid_status = 1;
						}

						if($api->Plant){
							$PlantLower = strtolower($api->Plant);
				
							$optiontype['where'] = [
								'TERMINAL_STATUS  ' => 'Y',
								'LOWER(TERMINAL_NAME)' => $PlantLower, // Ensure case-insensitive comparison
							];
							$optiontype['return_type'] = 'row';
					
							$plant = $this->common->getAllData('terminal_management', ['*'], $optiontype);
					
							if ($plant) {
								$plantId = $plant->TER_AUTO_ID ;
							} else {
								$error[] = 'Invalid Plant';
								$invalid_status = 1;
							}
							
						}else{
							$error[] = 'Plant is Required';
							$invalid_status = 1;
						}
				
						if($api->Location){
							$locLower = strtolower($api->Location);
				
							$optiontype['where'] = [
								'SPECIFIC_LOC_STATUS' => 'Y',
								'FK_TERMINAL_ID'=>$plantId,
								'LOWER(SPECIFIC_LOC_NAME)' => $locLower, // Ensure case-insensitive comparison
							];
							$optiontype['return_type'] = 'row';
					
							$Loc = $this->common->getAllData('specific_location', ['*'], $optiontype);
					
							if ($plant) {
								$LocId = $Loc->SL_AUTO_ID  ;
							} else {
								$error[] = 'Invalid Location';
								$invalid_status = 1;
							}
							
						}else{
							$error[] = 'Location is Required';
							$invalid_status = 1;
						}
				
				
						if($api->Department){
							$deptLower = strtolower($api->Department);
				
							$optiontype['where'] = [
								'DEPT_STATUS' => 'Y',
								'FK_TERMINAL_ID'=>$plantId,
								'LOWER(DEPT_NAME)' => $deptLower, // Ensure case-insensitive comparison
							];
							$optiontype['return_type'] = 'row';
					
							$DEP = $this->common->getAllData('department', ['*'], $optiontype);
					
							if ($DEP) {
								$DeptId = $DEP->DEPT_AUTO_ID;
							} else {
								$error[] = 'Invalid Department';
								$invalid_status = 1;
							}
							
						}else{
							$error[] = 'Department is Required';
							$invalid_status = 1;
						}
				

						if(!empty($error)){
							$export_status=implode(', ',$error);
							unset($error);
						} 

						$InsertData=[
							
							"EmployeeNo"=> $api->EmployeeNo,
							"EmployeeType"=> $api->EmployeeType,
							"EmployeeTypeId"=> $EmployeeTypeId,
							"EmployeeName"=> $api->EmployeeName,
							"Gender"=> $api->Gender,
							"GenderId"=>$gender,
							"EmailID"=> $api->EmailID,
							"DateofBirth"=> $api->DateofBirth,
							"JoiningDate"=> $api->JoiningDate,
							"PhoneNumber"=> $api->PhoneNumber,
							"PastExperience"=>$api->PastExperience,
							"Nationality" => $api->Nationality,
							"NationalityID" => $Nationality,
							"Role "=> $api->Role,
							"RoleID"=>$roleId,
							"Designation"=> $api->Designation,
							"DesignationID"=> $desgId,
							"Plant"=> $api->Plant,
							"PlantID"=> $plantId,
							"Location"=> $api->Location,
							"LocationID"=> $LocId,
							"Department"=> $api->Department,
							"DepartmentID"=> $DeptId,
							'api_date'=>date('Y-m-d'),
							'api_status'=>0,
							'invalid_status'=> $invalid_status,
                    		'export_status'=>$export_status
						];
						$ApiInsertData[]=$InsertData;
					}
					$ApiEmpInsert = $this->db->insert_batch('api_employee_preview_data',$ApiInsertData);
					$UpdateEmployee = $this->UpdateEmployeedata();
					


				}else{
					echo 'No Data';exit;
				}
		// 	}else{
		// 		echo 'Error - '.$http_status_code;
		// 	}
		// }
		// curl_close($ch);
}

public function UpdateEmployeedata(){

	ini_set('max_execution_time', 0);

	$this->db->select('*');
	$this->db->from('api_employee_preview_data');
	$this->db->where('api_status','0');
	$this->db->where('invalid_status','0');
	$result = $this->db->get()->result_array();

	// $this->employeeTableBkup();
	// $this->loginTableBkup();

	// echo '<pre>';
	// print_r($result);exit;

	$em=1;
	if(!empty($result)){
	foreach($result as $res){

		$UpdateEmpData=[
			'EMP_ID'=>$res['EmployeeNo'],
			'EMP_USERTYPE_ID'=>$res['RoleID'],
			'EMP_NAME'=>$res['EmployeeName'],
			'EMP_GENDER'=>$res['GenderId'],
			'EMP_NATIONALITY'=>$res['NationalityID'],
			'EMP_NATIONALITY_OTHER'=>($res['NationalityID'] =='2')?$res['EMP_NATIONALITY_OTHER']:'',
			'EMP_DESIGNATION_ID'=>$res['DesignationID'],
			'EMP_VERT_ID'=>$res['PlantID'],
			'EMP_UNIT_ID'=>$res['LocationID'],
			'EMP_DEPT_ID'=>$res['DepartmentID'],
			'EMP_EMAIL_ID'=>$res['EmailID'],
			'EMP_TYPE'=>$res['EmployeeTypeId'],
			'EMP_BIRTH_DATE'=>$res['DateofBirth'],
			'EMP_JOINING_DATE'=>$res['JoiningDate'],
			'PAST_EXPERIENCE'=>$res['PastExperience'],
			'PHONE_NUMBER'=>$res['PhoneNumber'],
			
		];
		
		$opt['where']=[
			'EMP_ID' => $res['EmployeeNo']
		];

		$checkEmp = $this->common->getAllData('employee_management',['*'],$opt);
		
		if($checkEmp){
			$updateWhere=[
				'EMP_ID' => $res['EmployeeNo']
			];
			$updateEmployeeData = $this->common->updateData('employee_management',$UpdateEmpData,$updateWhere);
			
			$userData =[
				'USER_TYPE_ID' => $res['RoleID'],
				'USER_DESINATION_ID' => $res['DesignationID'],
			];

			$InsertEmpData = $this->common->updateData('login_details',$userData,['USERNAME'=>$res['EmployeeNo']]);

			$apiEmployee = $this->common->updateData('api_employee_preview_data',['api_status'=>'1'],['EmployeeNo'=>$res['EmployeeNo']]);
		}else{
			$UpdateEmpData['EMP_LOGIN_STATUS']='P';
			$updateEmployeeData = $this->common->updateData('employee_management',$UpdateEmpData);
			$apiEmployee = $this->common->updateData('api_employee_preview_data',['api_status'=>'2'],['EmployeeNo'=>$res['EmployeeNo']]);

			if($EmailAddress){
			$idata["messageText"] =
                'Login credential has been created for you to access Cresent, by ADMIN team. Find your below credentials :<br><br>' .
                "<b>URL</b> : " .BASE_URL ."<br>" .
                "<b>User Name</b> : " .$res['EmployeeNo'] ."<br>" .
				"<b>Password</b> : " .$res['EmployeeNo'] .
                "<br><br>This is system generated E-mail, do not reply to this email.";
            $idata["emplmailID"] = $EmailAddress;
            $idata["emplName"] = $res['EmployeeName'];
            $idata["subject"] = "CRESENT - EMPLOYEE REGISTRATION";
            $idata["mainModule"] = "masterMod";
            // internalNotificationOverall($idata);
			}
		}
		
		echo '<pre> - Empcount - '.$em++.' ----- ';
		print_r($res);
	}//exit;
	}else{
		echo 'No Employee';
	}

}

public function employeeTableBkup(){
	$this->load->dbutil();
	$prefs = array(
		'tables'      => array('employee_management'),     
		'format'      => 'sql',             
		// 'filename'    => 'my_db_backup.sql'
		);
	$backup =& $this->dbutil->backup($prefs); 
	$db_name = 'Employee-'. date("Y-m-d-H-i-s") .'.sql';
	// $path =  dirname("/").'var/www/html/public_html/assets/table/';
	$path =  "public/assets/table_bkup/employee/";
	$save = $path.$db_name;

	$this->load->helper('file');
	write_file($save, $backup); 
	return true;
	// $this->load->helper('download');
	// force_download($db_name, $backup);
}

public function loginTableBkup(){
	$this->load->dbutil();
	$prefs = array(
		'tables'      => array('login_details'),     
		'format'      => 'sql',             
		// 'filename'    => 'my_db_backup.sql'
		);
	$backup =& $this->dbutil->backup($prefs); 
	$db_name = 'Login-'. date("Y-m-d-H-i-s") .'.sql';
	// $path =  dirname("/").'var/www/html/public_html/assets/table/';
	$path =  "public/assets/table_bkup/login/";
	$save = $path.$db_name;

	$this->load->helper('file');
	write_file($save, $backup); 
	return true;
	// $this->load->helper('download');
	// force_download($db_name, $backup);
}
}