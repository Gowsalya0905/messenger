<?php

defined('BASEPATH') or exit('No direct script access allowed');


require LUMAPIPATH . 'libraries/REST_Controller.php';

class Login extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        // error_reporting(1);
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->common_model->api_log($this->post());

        $this->load->model('login/Login_model', 'login');
        $this->load->model('common/common_model', 'common');
        $this->load->helper("obs_helper");
    }

    public function index_post()
    {

        $username = $this->post('username');
        $password = $this->post('password');
        $token = $this->post('fcm');

        if (!empty($username) && !empty($password)) {
            $auth = $this->login->auth($username, $password);

            if ($auth && $auth != 'deactivated') {
                $session_data = [
                    'id' => $auth->LOGIN_ID,
                    'name' => $auth->USERNAME,
                    'role_id' => $auth->USER_TYPE_ID
                ];

                $empwhere = [
                    'EMP_AUTO_ID' => $auth->USER_REF_ID,
                    'EMP_STATUS' => 'Y'
                ];

                $option['where'] = $empwhere;
                $option['return_type'] = 'row';

                $empData = $this->common->getAlldata(EMPL . ' as emp', [
                    'emp.*',
                    'IF(emp.EMP_GENDER=1,"Male","Female") as gender',
                    'IF(emp.EMP_NATIONALITY=1,"SINGAPOREAN","Other") as nationality',
                    'FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID) as design_name',
                    'FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID) as depart_name'
                ], $option);

                if ($empData) {
                    $message = [
                        'status' => TRUE,
                        'message' => 'Login successful',
                        'LoginData' => $session_data,
                        'UserData' => $empData
                    ];

                    // Token registration if devicetoken is provided
                    if (!empty($token)) {
                        $user_id = $auth->LOGIN_ID;

                        $data = [
                            'APK_TOKEN' => $token,
                            'APK_USER_ID' => $user_id
                        ];

                        $optioninitial['where'] = [
                            'APK_USER_ID' => $user_id
                        ];

                        $optioninitial['return_type'] = 'row';
                        $user_token = $this->common_model->getAlldata(APK_TOKEN, ['*'], $optioninitial);

                        if (isset($user_token) && $user_token != '') {
                            $uwhere = [
                                'APK_USER_ID' => $user_id
                            ];

                            $register = $this->common_model->updateData(APK_TOKEN, $data, $uwhere);
                        } else {
                            $register = $this->common_model->updateData(APK_TOKEN, $data);
                        }

                        if (empty($register) || $register <= 0) {
                            $message['token_status'] = FALSE;
                            $message['token_message'] = 'Sorry! Token not been created';
                        } else {
                            $message['token_status'] = TRUE;
                            $message['token_message'] = 'Success! Token has been created';
                        }
                    }
                } else {
                    $message = [
                        'status' => FALSE,
                        'message' => 'Login failure. Login access not enabled',
                        'UserData' => (object)[]
                    ];
                }
            } elseif ($auth == 'deactivated') {
                $message = [
                    'status' => FALSE,
                    'message' => 'Account deactivated',
                    'UserData' => (object)[]
                ];
            } else {
                $message = [
                    'status' => FALSE,
                    'message' => 'Invalid user',
                    'UserData' => (object)[]
                ];
            }

            $this->set_response($message, REST_Controller::HTTP_CREATED);
        } else {
            $message = [
                'status' => FALSE,
                'message' => 'Username and password are required'
            ];

            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function register_token_post()
    {

        $user_id = $this->post('userid');
        $token = $this->post('devicetoken');

        if ($user_id != '' && $token != '') {

            $data = [
                'APK_TOKEN' => $token,
                'APK_USER_ID' => $user_id
            ];

            $where = [
                'APK_USER_ID' => $user_id
            ];

            $optioninitial['where'] = [
                'APK_USER_ID' => $user_id
            ];


            $optioninitial['return_type'] = 'row';
            $user_token = $this->common_model->getAlldata(APK_TOKEN, ['*'], $optioninitial);

            if (isset($user_token) && $user_token != '') {
                $uwhere = [
                    //'token' => $token,
                    'APK_USER_ID' => $user_id
                ];

                $register = $this->common_model->updateData(APK_TOKEN, $data, $uwhere);
            } else {

                $register = $this->common_model->updateData(APK_TOKEN, $data);
            }

            if (!empty($register) && $register > 0) {

                $message = array(
                    'status' => TRUE,
                    'message' => 'Success! Token has been created',
                );

                $this->set_response($message, REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
            } else {

                $message = array(
                    'status' => FALSE,
                    'message' => 'Sorry! Token not been created'
                );

                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    public function forget_pass_post()
    {

        $this->load->model('Email_model', 'emails');

        $username = $this->post('username');

        $where = [

            'USERNAME' => $username

        ];

        $option['where'] = $where;

        $option['return_type'] = 'row';

        $logindata = $this->common->getAlldata(LOGIN, ['*'], $option);



        $userType = postData($logindata, 'USER_TYPE_ID');

        $userRefId = postData($logindata, 'USER_REF_ID');

        $userLogId = postData($logindata, 'LOGIN_ID');



        $where = [

            'EMP_AUTO_ID' => $userRefId

        ];



        $option['where'] = $where;

        $option['return_type'] = 'row';

        $userdata = $this->common->getAlldata(EMPL, ['*'], $option);

        $name = postData($userdata, 'EMP_NAME');

        $email = postData($userdata, 'EMP_EMAIL_ID');





        if (isset($userdata) && $userdata != '') {

            if ($email != '') {
                $otp = rand(100000, 999999);
                //print_r($otp);exit;

                $updateOTPData = [

                    'FORGET_OTP_MOBILE' => $otp

                ];
                $userWhere = [

                    'LOGIN_ID' => $userLogId

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
                    } else {
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
            } else {

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

    public function forgetPassword_post()
    {

        $forgetUser = $this->post('username');

        $csrf_osh_name = $this->post('csrf_osh_name');

        $output = '';
        if ($forgetUser != '') {
            $n_rows = $this->common->Checkbasicinfo($forgetUser);
            $forgotEncrypValue = encryptval($forgetUser);

            if ($n_rows != 0) {

                $updateEncryptPass = $this->common->CheckforgotEncrypValue($forgetUser, $forgotEncrypValue);
                $subject = 'Forgot password Process';
                $toemail = isset($n_rows['emailId']) ? $n_rows['emailId'] : '';

                $message =  'This is Password Reset Mail.If you need Means Kindly click This url.'  . '<br>' . '<br>' . '<br>' . '<center>' .



                    '_______________________________________________________________________________________' . '<br>' . '<br>' .







                    BASE_URL() . 'login/resetPassword/' . $forgotEncrypValue . '<br>' . '<br>' .



                    '_______________________________________________________________________________________' . '</center>'



                    . '<br>' . 'Your Encrypt Password is : ' . $forgotEncrypValue;;







                $tempdata = array(

                    'view_file' => 'common/email/common_mail',

                    'messageText' => $message,

                    'footerText' => ''



                );

                $template = $this->template->load_email_template($tempdata);
                $sendmail =  $this->email_model->sendEmail($toemail, $subject, $template, $file = "");
                if ($sendmail) {

                    $message = array(

                        'status' => TRUE,

                        'message' => 'Success',

                        'displaymessage' => 'Kindly Check Your Mail!',

                    );
                    $this->set_response($message, REST_Controller::HTTP_CREATED);
                } else {
                    $message = array(

                        'status' => FALSE,

                        'displaymessage' => 'Email Not sent',

                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            } else {

                $message = array(

                    'status' => FALSE,

                    'displaymessage' => 'User Not Found',

                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {


            $message = array(

                'status' => FALSE,

                'displaymessage' => 'Username is Empty!',

            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function forgetPasswordProcess_post()
    {

        $encryptMsg = $this->post('encryptMsg');
        $forgetUser = decryptval($encryptMsg);

        $passwordDet = $this->post('newPassword');

        $confirmPass = $this->post('confirmPass');

        $CheckExpiredOrNot = $this->common->CheckExpiredOrNot($forgetUser, $encryptMsg);
        // echo "<pre>";
        // print_r($CheckExpiredOrNot);
        // exit;

        if (isset($CheckExpiredOrNot) && $CheckExpiredOrNot['ENCRYPT_OTP_PASSWORD'] != "") {

            if ($forgetUser != '') {


                $n_rows = $this->common->Checkbasicinfo($forgetUser);



                if ($n_rows != 0) {

                    if ($passwordDet != $confirmPass) {

                        $message = array(

                            'status' => FALSE,

                            'displaymessage' => 'Password is Mismatch!',

                        );
                        $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                    } else if ($forgetUser != '' &&  $passwordDet != '' && $confirmPass != '') {




                        $passwordUpdation = $this->common->UpdateNewPassword($forgetUser, $passwordDet);



                        $updateEncryptPassword = $this->common->CheckforgotEncrypValueEmpty($forgetUser, $encryptMsg);



                        //send mail process - conformation .



                        $subject = 'Password Successfully Changed';

                        $toemail = isset($n_rows['emailId']) ? $n_rows['emailId'] : '';



                        $message = 'Your password has been reset successfully. You can login with the below credentials:' . '<br>' .

                            ' Username:'    . $forgetUser . '<br>' .

                            'Password:'    . $passwordDet . '<br>';

                        $tempdata = array(

                            'view_file' => 'common/email/common_mail',

                            'messageText' => $message,

                            'footerText' => ''



                        );


                        $template = $this->template->load_email_template($tempdata);



                        $sendmail =  $this->email_model->sendEmail($toemail, $subject, $template, $file = "");

                        if ($sendmail) {

                            $message = array(

                                'status' => TRUE,

                                'message' => 'Success',

                                'displaymessage' => 'Password Successfully Changed!',

                            );
                            $this->set_response($message, REST_Controller::HTTP_CREATED);
                        } else {
                            $message = array(

                                'status' => FALSE,

                                'displaymessage' => 'Failed',

                            );
                            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                        }
                    }
                } else {

                    $message = array(

                        'status' => FALSE,

                        'displaymessage' => '>Sorry your Username does not exist !! Kindly check !',

                    );
                    $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            } else if ($forgetUser == '') {


                $message = array(

                    'status' => FALSE,

                    'displaymessage' => 'Kindly enter your Encrypt Password!',

                );
                $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {

            $message = array(

                'status' => FALSE,

                'displaymessage' => 'Your Encrypt Password is Expired!',

            );
            $this->set_response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function notification_post()
    {
        $postData = $this->post();

        // Fetch user_id from post data
        $user_id = $postData['user_id'];

        // Fetch pagination parameters
        $page = isset($postData['page']) ? (int)$postData['page'] : 1; // Default to page 1 if not provided
        $limit = 10; // Number of records per page
        $offset = ($page - 1) * $limit; // Calculate the offset

        // Query notifications with pagination
        $notific = $this->db->select('NOTIFICATION_DESC, NOTIFICATION_HREF, CREATED_ON, NOTIFICATION_MOBILE_LINK, MODULE_AUTO_ID')
            ->where('NOTIFICATION_STATUS', 'Y')
            ->where("FIND_IN_SET('$user_id', NOTIFICATION_EMPLOYEE_ID) > 0")
            ->order_by('NOTIFICATION_ID', 'DESC')
            ->limit($limit, $offset)
            ->get('notification')
            ->result();

        $notification = [];

        foreach ($notific as $notif) {
            $notification[] = [
                'notificationDesc' => $notif->NOTIFICATION_DESC,
                'lastactivity' => timeago($notif->CREATED_ON),
                'module_sub_type' => $notif->NOTIFICATION_MOBILE_LINK,
                'module_id' => $notif->MODULE_AUTO_ID,
            ];
        }

        // Get the total count of notifications for this user
        $total_count = $this->db->where('NOTIFICATION_STATUS', 'Y')
            ->where("FIND_IN_SET('$user_id', NOTIFICATION_EMPLOYEE_ID) > 0")
            ->count_all_results('notification');

        $notification_count = count($notification);
        if (!empty($notification)) {
            $message = [
                'status' => TRUE,
                'Notification' => $notification,
                'notification_count' => $notification_count,
                'total_count' => $total_count, // Total notifications
                'current_page' => $page, // Current page
                'total_pages' => ceil($total_count / $limit), // Total pages
            ];
            $this->set_response($message, REST_Controller::HTTP_OK);
        } else {
            $message = [
                'status' => FALSE,
                'message' => 'No Data',
            ];
            $this->set_response($message, REST_Controller::HTTP_OK);
        }
    }
}
