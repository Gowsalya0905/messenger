<?php
defined("BASEPATH") or exit("No direct script access allowed");

class API_Integration extends CI_Controller{

     public function __construct()
    {
        parent::__construct();

        $this->load->library('curl');
		$this->load->model("common/common_model", "common");
		$this->load->helper("common_helper");
    }

	// $response = '{
	// 	"Message": "Data Received successfully",
	// 	"Data": [
	// 		{
	// 			"EmployeeNo": "18628",
	// 			"EmployeeType": "Worker",
	// 			"EmployeeName": "Chandresh Joshi",
	// 			"Gender": "Male",
	// 			"EmailID": "emp@cresent.com",
	// 			"DateofBirth": "13-04-1969",
	// 			"JoiningDate": "13-04-1969",
	// 			"PhoneNumber": "98765432101",
	// 			"PastExperience": "10",
	// 			"Nationality": "Indian",
	// 			"Role": "HR",
	// 			"Designation": "admin des",
	// 			"Plant": "Unit 1",
	// 			"Location": "Salap",
	// 			"Department": "dept new"
	// 		},
	// 		{
	// 			"EmployeeNo": "18624",
	// 			"EmployeeType": "Worker",
	// 			"EmployeeName": "Kabilesh Patel Joshi",
	// 			"Gender": "Female",
	// 			"EmailID": "emp2@cresent.com",
	// 			"DateofBirth": "13-04-1969",
	// 			"JoiningDate": "13-04-1969",
	// 			"PhoneNumber": "98765432101",
	// 			"PastExperience": "10",
	// 			"Nationality": "Indian",
	// 			"Role": "HR",
	// 			"Designation": "admin des",
	// 			"Plant": "Unit 1",
	// 			"Location": "Salap",
	// 			"Department": "dept new"
	// 		}
	// 	]
	// }';

    public function index()
	{

		ini_set('max_execution_time', 0);

		$currentData =date('Ymd');
		// $fromdate =date('Ymd');
		$fromdate ='20231101';
		//apiDate 20240220
		$url = 'https://app.welspun.com/webdata/api/HSE/SamayMaster?FromDate='.$fromdate.'&ToDate='.$currentData.'&Reportstype=DELTA';
		$username = 'WELSAFE';
		$password = 'XA4C*0Yatra!eTj';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		// echo '<pre>';
		// print_r(($response));exit;
		if(curl_errno($ch)) {
			$error_message = curl_error($ch);
		} else {
			$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($http_status_code == 200) {
			
			$result = json_decode($response);
				$ApiData = $result->Data;
				// echo '<pre>';
				// print_r(($ApiData));exit;
				if(!empty($ApiData)){
					$i=1;
					foreach($ApiData as $api){
						$InsertData=[
							
							"EmployeeNo"=> $api->EmployeeNo,
							"EmployeeType"=> $api->EmployeeType,
							"EmployeeName"=> $api->EmployeeName,
							"Gender"=> $api->Gender,
							"EmailID"=> $api->EmailID,
							"DateofBirth"=> $api->DateofBirth,
							"JoiningDate"=> $api->JoiningDate,
							"PhoneNumber"=> $api->PhoneNumber,
							"PastExperience"=>$api->PastExperience,
							"Nationality" => $api->Nationality,
							"Role "=> $api->Role,
							"Designation"=> $api->Designation,
							"Plant"=> $api->Plant,
							"Location"=> $api->Location,
							"Department"=> $api->Department,
							'api_date'=>date('Y-m-d'),
							'api_status'=>0,
						];
						$ApiInsertData[]=$InsertData;
						
					}
					$ApiEmpInsert = $this->db->insert_batch('api_employee_data',$ApiInsertData);
					$UpdateEmployee = $this->UpdateEmployeedata();


				}else{
					echo 'No Data';exit;
				}
			}else{
				echo 'Error - '.$http_status_code;
			}
		}
		curl_close($ch);
}

public function UpdateEmployeedata(){

	ini_set('max_execution_time', 0);

	$this->db->select('*');
	$this->db->from('api_employee_data');
	$this->db->where('api_status','0');
	$result = $this->db->get()->result_array();

	// $this->employeeTableBkup();
	// $this->loginTableBkup();

	// echo '<pre>';
	// print_r($result);exit;

	$em=1;
	if(!empty($result)){
	foreach($result as $res){

		if($res['EmployeeType']){
			$empTypeLower = strtolower($res['EmployeeType']);

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
				$emptypedata=[
					'emp_type' =>$res['EmployeeType'],
					'type_status' => 'Y'
				];
				$InsertemptypeData = $this->common->updateData('mas_employee_type',$emptypedata);
				$EmployeeTypeId = $InsertemptypeData;
			}
			
		}

		switch ($res['Gender']) {
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

		switch ($res['Nationality']) {
			case 'Indian':
			case 'indian':
				$Nationality ='1';
			break;
			default:
			$Nationality ='2';
		}
		

		if($res['Role']){
			$roleLower = strtolower($res['Role']);

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
				$roledata=[
					'USER_TYPE_NAME' =>$res['Role'],
					'USER_TYPE_STATUS' => 'Y'
				];
				$InsertemptypeData = $this->common->updateData('user_type',$roledata);
				$roleId = $InsertemptypeData;
			}
			
		}

		if($res['Designation']){
			$DesignationLower = strtolower($res['Designation']);

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
				$desgdata=[
					'DES_USER_TYPE'=>$roleId,
					'DESIGNATION_NAME' =>$res['Designation'],
					'DESIGNATION_STATUS  ' => 'Y',
				];
				$InsertdesgData = $this->common->updateData('designation',$desgdata);
				$desgId = $InsertdesgData;
			}
			
		}

		if($res['Plant']){
			$PlantLower = strtolower($res['Plant']);

			$optiontype['where'] = [
				'TERMINAL_STATUS  ' => 'Y',
				'LOWER(TERMINAL_NAME)' => $PlantLower, // Ensure case-insensitive comparison
			];
			$optiontype['return_type'] = 'row';
	
			$plant = $this->common->getAllData('terminal_management', ['*'], $optiontype);
	
			if ($plant) {
				$plantId = $plant->TER_AUTO_ID ;
			} else {
				$plantdata=[
					'TERMINAL_NAME' =>$res['Plant'],
					'TERMINAL_STATUS  ' => 'Y',
				];
				$InsertdesgData = $this->common->updateData('terminal_management',$plantdata);
				$plantId = $InsertdesgData;
			}
			
		}

		if($res['Location']){
			$locLower = strtolower($res['Location']);

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
				$locdata=[
					'FK_TERMINAL_ID'=>$plantId,
					'SPECIFIC_LOC_NAME' =>$res['Location'],
					'SPECIFIC_LOC_STATUS' => 'Y',
				];
				$InsertLocData = $this->common->updateData('specific_location',$locdata);
				$LocId = $InsertLocData;
			}
			
		}


		if($res['Department']){
			$deptLower = strtolower($res['Department']);

			$optiontype['where'] = [
				'DEPT_STATUS' => 'Y',
				'FK_TERMINAL_ID'=>$plantId,
				'LOWER(DEPT_NAME)' => $deptLower, // Ensure case-insensitive comparison
			];
			$optiontype['return_type'] = 'row';
	
			$DEP = $this->common->getAllData('department', ['*'], $optiontype);
	
			if ($DEP) {
				$DeptId = $DEP->DEPT_AUTO_ID   ;
			} else {
				$Deptdata=[
					'DEPT_STATUS' => 'Y',
					'FK_TERMINAL_ID'=>$plantId,
					'DEPT_NAME' =>$res['Department'],
					
				];
				$InsertDeptData = $this->common->updateData('department',$Deptdata);
				$DeptId = $InsertDeptData;
			}
			
		}


	

		
		$EmailAddress = $res['EmailID'];
		$EmployeeName = $res['EmployeeName'];
		$DateofBirth = date('d-m-Y',strtotime($res['DateofBirth']));
		$Dateofjoining = date('d-m-Y',strtotime($res['JoiningDate']));
		$PastExperience = $res['PastExperience'];
		$PhoneNumber = $res['PhoneNumber'];
		$Status = 'P';
		$UpdateEmpData=[
			'EMP_ID'=>$res['EmployeeNo'],
			'EMP_USERTYPE_ID'=>$roleId,
			'EMP_NAME'=>$EmployeeName,
			'EMP_GENDER'=>$gender,
			'EMP_NATIONALITY'=>$Nationality,
			'EMP_NATIONALITY_OTHER'=>($Nationality =='2')?$res['Nationality']:'',
			'EMP_DESIGNATION_ID'=>$desgId,
			'EMP_VERT_ID'=>$plantId,
			'EMP_UNIT_ID'=>$LocId,
			'EMP_DEPT_ID'=>$DeptId,
			'EMP_EMAIL_ID'=>$EmailAddress,
			'EMP_TYPE'=>$EmployeeTypeId,
			'EMP_BIRTH_DATE'=>$DateofBirth,
			'EMP_JOINING_DATE'=>$Dateofjoining,
			'PAST_EXPERIENCE'=>$PastExperience,
			'PHONE_NUMBER'=>$PhoneNumber,
			'EMP_LOGIN_STATUS'=>$Status,
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
			$apiEmployee = $this->common->updateData('api_employee_data',['api_status'=>'1'],['EmployeeNo'=>$res['EmployeeNo']]);
		}else{
			$UpdateEmpData['EMP_ID']=$res['EmployeeNo'];
			$UpdateEmpData['EMP_USERTYPE_ID']=$roleId;
			$UpdateEmpData['EMP_DESIGNATION_ID']=$desgId;
			$updateEmployeeData = $this->common->updateData('employee_management',$UpdateEmpData);

			$userData =[
				'USER_REF_ID' => $updateEmployeeData,
				'USER_TYPE_ID' => $roleId,
				'USER_LOGIN_TYP' => '0',
				'USER_DESINATION_ID' => $desgId,
				'USERNAME' => $res['EmployeeNo'],
				'ENCRYPT_PASSWORD' => md5($res['EmployeeNo']),
				'USER_LOG_STATUS' => 'Y',
				'ORG_PWD' => $res['EmployeeNo'],
				'USER_LOG_STATUS' => ($Status =='E')?'Y':'N'
			];

			$InsertEmpData = $this->common->updateData('login_details',$userData);

			$apiEmployee = $this->common->updateData('api_employee_data',['api_status'=>'2'],['EmployeeNo'=>$res['EmployeeNo']]);

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