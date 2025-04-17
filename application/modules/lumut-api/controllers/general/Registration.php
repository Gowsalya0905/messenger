<?php



defined('BASEPATH') OR exit('No direct script access allowed');



require LUMAPIPATH . 'libraries/REST_Controller.php';



class Registration extends REST_Controller {



    public function __construct() {

        parent::__construct();



        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key

        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key

        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key





        $this->load->library('upload');

        $this->load->model('common/common_model', 'common');

        $this->load->model('Email_model', 'emails');

        $this->load->helper('common_helper');

        $this->load->helper(array('url'));

        $this->common_model->api_log($this->post());

    }



     public function registrationSubmit_post() {

//        error_reporting(E_ERROR | E_PARSE);

        

        $contractor_name = $this->post('contractor_name', TRUE);

        $comp_name = $this->post('comp_name', TRUE);

        $cont_mail = $this->post('cont_mail', TRUE);

        $company_special = $this->post('company_special', TRUE);

        $mobile_num = $this->post('mobile_number', TRUE);

        $address = $this->post('address', TRUE);

        $city = $this->post('city', TRUE);

        $country = $this->post('country', TRUE);

        $postal_code = $this->post('postal_code', TRUE);

        $gender = $this->post('gender', TRUE);

        $nationality = $this->post('nationality', TRUE);

        $nationality_other = $this->post('nationality_other');

        $designation = $this->post('designation', TRUE);

        $password = $this->post('password', TRUE);

        

        $where_con = [

                 'CONT_EMAIL_ID' => $cont_mail,

                 'CON_LOGIN_STATUS' => 'E'

         ];

        $option['where'] = $where_con;

        $option['return_type'] = 'row';

        $checkContData = $this->common->getAlldata(CONT_DET, ['CONT_EMAIL_ID'], $option);

        if ($checkContData == FALSE) {

        $uniqContwhr = [];

        

       

         $inserEmpldatas = [

                'CONT_NAME' => $contractor_name,

                'CONT_COMPANY' => $comp_name,

                'CONT_EMAIL_ID' => $cont_mail,

                'CONT_SPECIAL' => $company_special,

                'CONT_PHONE_NO' => $mobile_num,

                'CONT_ADDRESS' => $address,

                'CONT_CITY' => $city,

                'CONT_COUNTRY' => $country,

                'CONT_POSTAL_CODE' => $postal_code,

                'CONT_GENDER' => $gender,

                'CONT_NATIONALITY' => $nationality,

                'CONT_NATIONALITY_OTHER' => $nationality_other,

                'CONT_DESIGNATION' => $designation,

                'CON_LOGIN_STATUS' => 'P'

            ];

         

           

            

         $where_con = [

                 'CONT_EMAIL_ID' => $cont_mail,

                 'CON_LOGIN_STATUS' => 'P'

         ];

        $option['where'] = $where_con;

        $option['return_type'] = 'row';

        $checkContInfo = $this->common->getAlldata(CONT_DET, ['*'], $option);

        

        if($checkContInfo != FALSE){

            $contrUniq = postData($checkContInfo,'CONT_ID');

            $updateWhere = [

                'CONT_EMAIL_ID' => $cont_mail,

                'CON_LOGIN_STATUS' => 'P'

            ];

            $this->common->updateData(CONT_DET,$inserEmpldatas,$updateWhere,'CONT_AUTO_ID');            $updtEmpl = postData($checkContInfo,'CONT_AUTO_ID');

        }else{

            $contrUniq = uniqueIntRefId('CONT', CONT_DET, 'CONT_AUTO_ID', 'CONT_AUTO_ID', $uniqContwhr);

           $inserEmpldatas['CONT_ID'] = $contrUniq;

            

           $updtEmpl = $this->common->updateData(CONT_DET,$inserEmpldatas);

           

            $updatePass = [

                'USER_REF_ID' => $updtEmpl,

                'USER_LOGIN_TYP' => 1,

                'USER_TYPE_ID' => 11,

                'USER_DESINATION_ID' => 0,

                'USERNAME' => $contrUniq,

                'ENCRYPT_PASSWORD' => md5($password),

                'USER_LOG_STATUS' => 'N',

                'ORG_PWD' => $password

            ];

            



            $otpInsert = $this->common->updateData(USER_LOG, $updatePass);

        }

           

            

           

        if ($updtEmpl > 0) {

            $this->load->model('Email_model', 'emails');

            $mail = $cont_mail;

 

            $where_con = [

                'CONT_EMAIL_ID' => $mail

            ];

            

            $option['where'] = $where_con;

            $option['return_type'] = 'row';

            $userdata = $this->common->getAlldata(CONT_DET, ['*'], $option);

             

           

            if (isset($userdata) && $userdata != '') {



                $otp = uniqueOTP();

                $updateOTPData = [

                    'CONT_OTP_MOBILE' => $otp

                ];

                $userWhere = [

                    'CONT_EMAIL_ID' => $cont_mail

                ];



                $userId = postData($userdata, 'CONT_AUTO_ID');

                //$userName = postData($userdata, 'CONT_ID');

                $userEncId = encryptval($userId);

                $otpUpdate = $this->common->updateData(CONT_DET, $updateOTPData, $userWhere);

               

                

                

                if ($otpUpdate != FALSE) {

                    $name = $contractor_name;

//                    <b>URL :</b> '.BASE_URL.'login<br/>

                    $LoginText .= '&nbsp;&nbsp;<b>Enter the Below OTP.To Enable Your Login Credential.</b><br>';

                    $LoginText .= '<div align="center" style="border: 1px solid green;

    padding: 5px;margin: 5px auto;width:100px;text-align:center;background-color:#ccc;"> <b> ' . $otp . '</b></div> <br>';

//                    $messageText .= 'Login credential has been created for you to access VAULT system (Lumut Port\'s HSE application) :. Find

//your below credentials : <br>';

//                    

//                    $messageText .= 'URL      : '.BASE_URL.' <br>';

//                    $messageText .= 'UserName :' . $userName . '  <br>';

//                    $messageText .= 'Password :' . $password . '  <br>';

//                    

//                    

//                    $messageText .= 'For any further information or clarification, Please do not hesitate to contact us. <br>';

//                    $messageText .= 'Have a nice day.<br><br>';

//                    $messageText .= '&nbsp;&nbsp;Thanks & Regards,<br>';

//                    $messageText .= ' <b>&nbsp;&nbsp;Lumut Port Support Team<b><br>';

//                    $messageText .= ' <b>&nbsp;&nbsp;Email</b>: lumutport@ardhas.com<br>';

                    $messageText = '';

                    $subject = 'WELSAFE - Registration Details';

                    $data = array(

                        'view_file' => 'common/email/contractor_registration',

                        'name' => $name,

                        'messageText' => $messageText,

                        'logintext' => $LoginText

                    );

                    $template = $this->template->load_email_template($data);



                    $this->emails->sendEmail($mail, $subject, $template, $file = "");

                }

            }

        }

        



       

        

        ///////////////////////////////////////////////////////////////////////

        if (!empty($updtEmpl) && $updtEmpl > 0) {

            $message = array(

                'userid' => (int)$updtEmpl,

                'username' => $contrUniq,

                'password' => $password,

                'otp' => (int)$otp,

                'status' => TRUE,

                'message' => 'Success! New Contractor Details has been registered'

            );



            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code

        } else {

            $message = array(

                'userid' => 0,

                'otp' => 0,

                'username' => '',

                'password' => '',

                'status' => FALSE,

                'message' => 'Sorry! New Contractor Details has not been registered'

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED); // NOT_FOUND (404) being the HTTP response code

        }

         }else{

             $message = array(

                'userid' =>0,

                'otp' => 0,

                'username' => '',

                'password' => '',

                'status' => FALSE,

                'message' => 'Sorry! Email Id already registred.'

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED); // NOT_FOUND (404) being the HTTP response code

         }

    }

    

    

    public function otpVerify_post() {

       // $email = $this->post('mail');

        $userid = $this->post('userid');

        $eotp = $this->post('eotp');

//echo "sdf";exit;

        $where = [

            'LD.FORGET_OTP_MOBILE' => $eotp,
            'LD.USER_REF_ID' => $userid,
            'LD.USER_LOGIN_TYP' => 0

        ];



        

        $option['where'] = $where;

        $option['return_type'] = 'row';

        $option['join'] = [

               LOGIN.' AS LD'=>['LD.USER_REF_ID = cont.EMP_AUTO_ID','LEFT']

        ];

        $userdata = $this->common->getAlldata(EMPL.' cont ', ['*'], $option);

       

        

        if (isset($userdata) && $userdata == TRUE) {

            $updateOTPData = [

                'EMP_LOGIN_STATUS' => 'E'

            ];

            $userWhere = [

                'EMP_AUTO_ID' => $userid

            ];


            $otpUpdate = $this->common->updateData(EMPL, $updateOTPData, $userWhere);

            $loginUpdateData = [
                'USER_LOG_STATUS'=>'Y'
            ];
            $loginId = postData($userdata,'LOGIN_ID');

            $loginUpdateDataWhere = [
                'LOGIN_ID' =>$loginId
            ];

            $loginUpdate = $this->common->updateData(LOGIN, $loginUpdateData, $loginUpdateDataWhere);

            
            if($otpUpdate != FALSE){

                 $name = postData($userdata,'EMP_NAME');

                 $userName = postData($userdata,'USERNAME');

                 $password = postData($userdata,'ORG_PWD');

                 $email = postData($userdata,'EMP_EMAIL_ID'); 

               

                    $messageText .= 'Login credential has been created for you to access WelSafe system. Find

your below credentials : <br><br>';

                    

                    $messageText .= 'URL      : '.BASE_URL.' <br>';

                    $messageText .= 'UserName : ' . $userName . '  <br>';

                    $messageText .= 'Password : ' . $password . '  <br><br>';

                    

                    

                    $messageText .= 'This is system generated E-mail, do not reply to this email. <br>';

                    

                    $subject = 'WELSAFE - Password Details';

                    $data = array(

                        'view_file' => 'common/email/otp_verification_contractor',

                        'name' => $name,

                        'messageText' => $messageText,

                        'footerText' => ''

                    );

                   $template = $this->template->load_email_template($data);



                   $this->emails->sendEmail($email, $subject, $template, $file = "");

            }

               

            

            $message = array(

                'status' => TRUE,

                'message' => 'Success',

                'displaymessage' => 'OTP verification success',

            );



            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code

        } else {



            $message = array(

                'status' => FALSE,

                'message' => 'No Data',

                'displaymessage' => 'OTP verification failed',

            );



            $this->set_response($message, REST_Controller::HTTP_CREATED); // NOT_FOUND (404) being the HTTP response code

        }

    }

    

    public function resendOtp_post() {

        $this->load->model('Email_model', 'emails');

        $mail = $this->post('mail');



        $where = [

            'EMP_EMAIL_ID' => $mail

        ];



        $option['where'] = $where;

        $option['return_type'] = 'row';

        $userdata = $this->common->getAlldata(EMPL, ['*'], $option);

        

        if (isset($userdata) && $userdata != '') {



            $otp = uniqueOTP();

            $updateOTPData = [

                'CONT_OTP_MOBILE' => $otp

            ];

            $userWhere = [

                'CONT_EMAIL_ID' => $mail

            ];



            $userId = postData($userdata, 'CONT_AUTO_ID');

            $userEncId = encryptval($userId);

            $otpUpdate = $this->common_model->updateData(CONT_DET, $updateOTPData, $userWhere);

          

            if ($otpUpdate != FALSE) {

                $first_name = postData($userdata, 'CONT_NAME');

                $last_name = '';

                $email = postData($userdata, 'CONT_EMAIL_ID');

               // $username = postData($userdata, 'username');

                $emailEnc = encryptval($email);

                $fname = ($first_name != '' && $last_name != '') ? $first_name . ' ' . $last_name : $first_name;

                $name = $fname;

                $messageText = ' We received a request to resend OTP. Here, we have attached the OTP code <br/> <div align="center" style="border: 1px solid green;

    padding: 5px;margin: 5px auto;width:100px;text-align:center;background-color:#ccc;"> <b> ' . $otp . '</b></div>';

                $footerText = ' <br><br/> Thanks & Regards <br> ' . SITE_TITLE . ' Team';



                $subject = SITE_TITLE . ' - OTP Verification';



                $data = array(

                    'view_file' => 'common/email/otp_verification_contractor',

                    'name' => $name,

                    'title' => SITE_TITLE,

                    'messageText' => $messageText,

                    'footerText' => '',

                    'loginURL' => '',

                );

                

                // print_R($data);exit;



                $template = $this->template->load_email_template($data);



                $sendmail = $this->emails->sendEmail($email, $subject, $template, $file = "");

               // if ($sendmail) {

                    $message = array(

                        'otp' => $otp,

                        'status' => TRUE,

                        'message' => 'Success',

                        'displaymessage' => 'Email sent',

                    );

                //}

            } else {

                $message = array(

                    'status' => FALSE,

                    'message' => 'No Data',

                    'displaymessage' => 'Email sent',

                );

            }



            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code

        } else {



            $message = array(

                'status' => FALSE,

                'message' => 'No Data'

            );



            $this->set_response($message, REST_Controller::HTTP_CREATED); // NOT_FOUND (404) being the HTTP response code

        }

    }

    

    public function forget_pass_post() {

        $this->load->model('Email_model', 'emails');

        $username = $this->post('username');

        $where = [

            'USERNAME' => $username

        ];

        $option['where'] = $where;

        $option['return_type'] = 'row';

        $logindata = $this->common->getAlldata(LOGIN, ['*'], $option);

        

        $userType = postData($logindata,'USER_TYPE_ID');

        $userRefId = postData($logindata,'USER_REF_ID');

        $userLogId = postData($logindata,'LOGIN_ID');

        

            $where = [

                'EMP_AUTO_ID' => $userRefId

            ];



            $option['where'] = $where;

            $option['return_type'] = 'row';

            $userdata = $this->common->getAlldata(EMPL, ['*'], $option);

             $name = postData($userdata, 'EMP_NAME');

             $email = postData($userdata, 'EMP_EMAIL_ID');

    



        if (isset($userdata) && $userdata != '') {

            if($email !=''){
            $otp = rand(100000,999999);
            //print_r($otp);exit;

            $updateOTPData = [

                'FORGET_OTP_MOBILE' => $otp

            ];
            $userWhere = [

                'LOGIN_ID'=>$userLogId

            ];
            $otpUpdate = $this->common_model->updateData(LOGIN, $updateOTPData, $userWhere);

            if ($otpUpdate != FALSE) {

               

                $emailEnc = encryptval($email);

                

                $name = $name;

                $messageText = ' We received a request to reset your ' . SITE_TITLE . ' password. You can enter the following  OTP code <br/> <div align="center" style="border: 1px solid green;

                 padding: 5px;margin: 5px auto;width:100px;text-align:center;background-color:#ccc;"> <b> ' . $otp . '</b></div>';

                

                $subject = SITE_TITLE . ' - OTP Verification';



                $data = array(

                    'view_file' => 'common/email/otp_user_verification_mobile',

                    'name' => $name,

                    'title' => SITE_TITLE,

                    'messageText' => $messageText,

                    'footerText' => '',

                    'loginURL' => '',

                );

                
               $template = $this->template->load_email_template($data);



               $sendmail = $this->emails->sendEmail($email, $subject, $template, $file = "");

                if ($sendmail) {

                    $message = array(

                        'otp' => $otp,

                        'status' => TRUE,

                       'emp_auto_id' => $userRefId,

                        'message' => 'Success',

                        'displaymessage' => 'Email sent',

                    );

                }else{
                    $message = array(

                        'otp' => $otp,

                        'status' => FALSE,

                       'emp_auto_id' => $userRefId,

                        'message' => 'Success',

                        'displaymessage' => 'Email Not sent',

                    );
                }

            } else {

                $message = array(
                    'otp' => '',

                    'status' => FALSE,
                    'emp_auto_id' => '',


                    'message' => 'No Data',

                    'displaymessage' => 'Email not sent',

                );

            }

            }else{

                $message = array(
                    'otp' => '',
                        'status' => FALSE,
                        'emp_auto_id' => '',

                        'message' => 'Contact Admin',

                        'displaymessage' => 'Please contact admin for reset password',

               );

            }



            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code

        } else {



            $message = array(

                'status' => FALSE,

                'message' => 'No Data'

            );



            $this->set_response($message, REST_Controller::HTTP_CREATED); // NOT_FOUND (404) being the HTTP response code

        }

    }

    

    public function otpVerifyForgetPass_post() {

        $this->load->model('Email_model', 'emails');

        $eotp = $this->post('eotp');

        $username = $this->post('username');

        $where = [

            'USERNAME' => $username,

            'FORGET_OTP_MOBILE' =>$eotp

        ];

        $option['where'] = $where;

        $option['return_type'] = 'row';

        $logindata = $this->common->getAlldata(LOGIN, ['*'], $option);

        $userType = postData($logindata,'USER_TYPE_ID');

        $userRefId = postData($logindata,'USER_REF_ID');

        $userLogId = postData($logindata,'LOGIN_ID');

        $userName = postData($logindata,'USERNAME');

            $where = [

                'EMP_AUTO_ID' => $userRefId

            ];

            $option['where'] = $where;

            $option['return_type'] = 'row';

            $userdata = $this->common->getAlldata(EMPL, ['*', 'FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID) as design_name', 'FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID) as depart_name'], $option);

             $name = postData($userdata, 'EMP_NAME');

             $email = postData($userdata, 'EMP_EMAIL_ID');


             $userData = [

                        'USER_ID' => $userLogId,

                        'USERNAME' => $userName,

                        'ID' => postData($userdata, 'EMP_AUTO_ID'),

                        'GEN_AUTO_ID' => postData($userdata, 'EMP_ID'),

                        'USER_TYPE_ID' => $userType,

                        'NAME' => postData($userdata, 'EMP_NAME'),

                        'DESIGNATIONID' => postData($userdata, 'EMP_DESIGNATION_ID'),

                        'DESIGNATION' => postData($userdata, 'design_name'),

                        'COMPANY' => '',

                        'ID_TYPE' => '',

                        'ID_NUMBER' => '',

                        'PHONE_NUMBER' => '',

                        'DEPTNAMEID' => postData($userdata, 'EMP_DEPT_ID'),

                        'DEPTNAME' => postData($userdata, 'depart_name'),

                        'LOGIN_STATUS' => postData($userdata, 'EMP_LOGIN_STATUS')

             ];

        if (isset($userdata) && $userdata != '') {

            $updateOTPData = [
                'FORGET_OTP_MOBILE' => ''
            ];

            $userWhere = [

                'LOGIN_ID'=>$userLogId

            ];

            $otpUpdate = $this->common_model->updateData(LOGIN, $updateOTPData, $userWhere);

            if ($otpUpdate != FALSE) {

                 $message = array(
                        'status' => TRUE,

                        'message' => 'Success',

                        'displaymessage' => 'OTP Verification success',

                        'UserData' => $userData,

                    );

            }else{

                 $message = array(

                        'status' => FALSE,

                        'message' => 'Failed',

                        'displaymessage' => 'OTP Verification Failed',

                        'UserData' =>(object) [],

                    );

            }
            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code

        } else {
            $message = array(

                'status' => FALSE,

                'message' => 'No Data',

                'displaymessage'=>'',

                'UserData' => (object)[],

            );

            $this->set_response($message, REST_Controller::HTTP_CREATED); 

        }

    }


    public function resetPassword_post() {

        $this->load->model('Email_model', 'emails');

       

        $username = $this->post('username');

        $password = $this->post('password');

        $conf_password = $this->post('conf_password');



        $where = [

            'USERNAME' => $username,
        ];



        

        $option['where'] = $where;

        $option['return_type'] = 'row';

        $logindata = $this->common->getAlldata(LOGIN, ['*'], $option);

        

        $userType = postData($logindata,'USER_TYPE_ID');

        $userRefId = postData($logindata,'USER_REF_ID');

        $userLogId = postData($logindata,'LOGIN_ID');

            $where = [

                'EMP_AUTO_ID' => $userRefId

            ];



            $option['where'] = $where;

            $option['return_type'] = 'row';

            $userdata = $this->common->getAlldata(EMPL, ['*'], $option);

             $name = postData($userdata, 'EMP_NAME');

             $email = postData($userdata, 'EMP_EMAIL_ID');

            
        if (isset($userdata) && $userdata != '') {

            

            $updateOTPData = [

                'ENCRYPT_PASSWORD'=> md5($password),

                'ORG_PWD'=> $password,

            ];

           

            $userWhere = [

                'LOGIN_ID'=>$userLogId

            ];

            



            $otpUpdate = $this->common_model->updateData(LOGIN, $updateOTPData, $userWhere);

            if ($otpUpdate != FALSE) {

                

                $userName = postData($logindata,'USERNAME');

                 $password = $password;

               

                    $messageText .= 'Login credential has been created for you to access WelSafe system (WelSafe\'s HSE application) :. Find

your below credentials : <br><br>';

                    

                    $messageText .= 'URL      : '.BASE_URL.' <br>';

                    $messageText .= 'UserName : ' . $userName . '  <br>';

                    $messageText .= 'Password : ' . $password . '  <br><br>';

                    

                    

                    $messageText .= 'This is system generated E-mail, do not reply to this email. <br>';

                    

                    $subject = 'WELSAFE - Login Details';

                    $data = array(

                        'view_file' => 'common/email/otp_verification_contractor',

                        'name' => $name,

                        'messageText' => $messageText,

                        'footerText' => ''

                    );

                   $template = $this->template->load_email_template($data);



                   $this->emails->sendEmail($email, $subject, $template, $file = "");

                   

                 $message = array(

                        'status' => TRUE,

                        'message' => 'Success',

                        'displaymessage' => 'Password reset successfully',

                    );

           

            }else{

                 $message = array(

                        'status' => FALSE,

                        'message' => 'Failed',

                        'displaymessage' => 'Password reset failed',

                    );

            }



            $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code

        } else {



            $message = array(

                'status' => FALSE,

                'message' => 'No Data'

            );



            $this->set_response($message, REST_Controller::HTTP_CREATED); // NOT_FOUND (404) being the HTTP response code

        }

    }

    

   

    

    



}

