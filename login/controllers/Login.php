<?php



defined('BASEPATH') or exit('No direct script access allowed');



class Login extends CI_Controller
{



    public function __construct()
    {



        parent::__construct();


        // error_reporting(0);

        $this->load->library(array('form_validation', 'user_agent'));

        $this->load->model('Login_model', 'login');

        $this->load->model('common/common_model', 'common');

        $this->load->helper(array('form'));

        ob_start();
    }

    public function policy_terms_api()
    {
        $data = array(
            'pageTitle' => 'Terms & Conditions',
            'view_file' => 'master/usertracking/terms_form',
            'site_title' => 'Terms & Conditions',
            'current_menu' => 'Terms & Conditions',
        );

        $this->template->load_login_template($data);
    }



    public function index()
    {

        $userdata = $this->session->userinfo;

        $login = (isset($userdata->LOGIN_ID) ? $userdata->LOGIN_ID : '');

        if (($login)) {


            redirect('dashboard/chart');
        } else {

            $page = '';

            if (isset($_GET) && !empty($_GET)) {

                $page =  $_GET['page'];
            }

            $data = array(

                'view_file' => 'login/login_form/add_login_form',

                'title' => 'Login Form',

                'current_menu' => 'Login Form',

                'page' =>  $page

            );
            // echo "jdjd";
            // exit;
            $this->template->load_login_template($data);
        }
    }



    public function LoginSubmit()
    {

        // echo '<pre>';print_r($_POST);exit;

        $PostData = $this->input->post();

        // echo $_SERVER['HTTP_REFERER'];exit;

        if (!empty($PostData['username']) && !empty($PostData['password'])) {

            $username = $PostData['username'];

            $pass = $PostData['password'];

            $auth = $this->login->auth($username, $pass);
            $remember = $PostData['remember'];



            if (($auth != FALSE) && ($auth != 'deactivated')) {

                $session_data = [

                    'id' => $auth->LOGIN_ID,

                    'name' => $auth->USERNAME,

                    'role_id' => $auth->USER_TYPE_ID,

                    'userinfo' => $auth

                ];

                $userTypeId = $auth->USER_TYPE_ID;


                $empwhere = [

                    'EMP_AUTO_ID' => $auth->USER_REF_ID

                ];

                $option['where'] = $empwhere;

                $option['return_type'] = 'row';
                // $option['group_by'] = 'emp.EMP_ZONE_IDS,emp.EMP_SUBZONE_IDS';

                $empData = $this->common->getAlldata(EMPL . ' as emp', ['emp.*', '
                FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID) as design_name,
                 FN_COMP_NAME(EMP_COMP_ID) as comp_name,
                FN_AREA_NAME(EMP_AREA_ID) as area_name,
                FN_BUILD_NAME(EMP_BUILDING_ID) as building_name,
                FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID) as dept_name,
                FN_ROLE_NAME(EMP_USERTYPE_ID) as role_name,
                
                '], $option);




                $session_data['emp_details'] = $empData;


                $userData = [

                    'NAME' => postData($empData, 'EMP_NAME'),
                    'ID' => postData($empData, 'EMP_ID'),
                    'DESIGNATIONID' => postData($empData, 'EMP_DESIGNATION_ID'),
                    'DESIGNATION' => postData($empData, 'design_name'),
                    'PHONE_NUMBER' => postData($empData, 'PHONE_NUMBER'),
                    'COMPANY_ID' => postData($empData, 'EMP_COMP_ID'),
                    'COMPANY' => postData($empData, 'comp_name'),
                    'AREA_ID' => postData($empData, 'EMP_AREA_ID'),
                    'AREA' => postData($empData, 'area_name'),
                    'BUILDING_ID' => postData($empData, 'EMP_BUILDING_ID'),
                    'BUILDING' => postData($empData, 'building_name'),
                    'DEPT_ID' => postData($empData, 'EMP_DEPT_ID'),
                    'DEPTNAME' => postData($empData, 'dept_name'),
                    'ROLENAME' => postData($empData, 'role_name'),

                ];


                $session_data['user_details'] = $userData;

                //              $session_data['role_permissions'] = getRolePermissions($check->role);

                $this->session->set_userdata($session_data);





                /* track table data */

                $ip = $this->input->ip_address();

                $session_id = session_id();

                if ($this->agent->is_browser()) {

                    $agent = $this->agent->browser() . ' ' . $this->agent->version();
                } elseif ($this->agent->is_robot()) {

                    $agent = $this->agent->robot();
                } elseif ($this->agent->is_mobile()) {

                    $agent = $this->agent->mobile();
                } else {

                    $agent = 'Unidentified User Agent';
                }

                $agentPlatform = $this->agent->platform();





                $trackData = [

                    'user_id' => $auth->LOGIN_ID,

                    'login_time' => date('Y-m-d h:i:s'),

                    'ip' => $ip,

                    'session_id' => $session_id,

                    'user_agent' => $agent,

                    'user_platform' => $agentPlatform

                ];

                //                $insertTrackData = $this->common->updateInfo(LOG_TRACK, $trackData);

                if (isset($remember)) {
                    setcookie('auser', $username, time() + (86400 * 30), "/");
                    setcookie('apass', $userpass, time() + (86400 * 30), "/");
                    setcookie('arem', $remember, time() + (86400 * 30), "/");
                }
                $session = $this->session->userdata['id'];



                if (isset($session) && $session != '') {

                    $this->session->set_flashdata('Messges', 'Login Successfully');

                    redirect('message/master/message');
                } else {

                    $this->session->set_flashdata('Messges', 'User session not found');
                    // print_r("hello");
                    // die;

                    redirect('login');
                }
            } else {

                $this->session->set_flashdata('Messges', 'Username and Password Not Matched!');

                redirect('login');
            }
        } else {

            $this->session->set_flashdata('Messges', 'Username and Password required!');

            redirect('login');

            //            exit;

        }
    }



    public function logout()
    {

        $session_id = session_id();



        // $trackData = [

        //     'logout_time' => date('Y-m-d h:i:s')

        // ];

        // $this->db->where('session_id', $session_id);

        // $this->db->update(LOGTRACK, $trackData);

        $user_data = $this->session->userdata();

        //  $this->session->unset_userdata($user_data);

        $this->session->sess_destroy();



        redirect('login');
    }

    public function forgetPassword()
    {

        ini_set('display_errors', 1);



        $forgetUser = $this->input->post('forgetUser');

        $csrf_osh_name = $this->input->post('csrf_osh_name');



        $output = '';

        if ($forgetUser == '') {



            $output .= '<div class="alert alert-danger">

                    <button class="close" data-close="alert"> X </button>

                    <span>Please enter the valid Username</span>

                </div>';



            echo $output;
        } else {







            $n_rows = $this->common->Checkbasicinfo($forgetUser);

            $forgotEncrypValue = encryptval($forgetUser);



            if ($n_rows != 0) {



                $updateEncryptPass = $this->common->CheckforgotEncrypValue($forgetUser, $forgotEncrypValue);



                $output .= '<div class="alert alert-success msg-show">

                            <button class="close" data-close="alert"> X </button>

                           <span>Kindly Check Your Mail! </span>

                         </div>

                         <div class="form-group col-md-8"  style="display: inline-block;text-align: right;padding: 30px 0 0 0;width:100%;">

                         

                     </div>';

                echo $output;

                //        $otpArray = array ("0123" , "0234");



                //send mail process - conformation .



                $subject = 'Forgot password Process';

                $toemail = isset($n_rows['emailId']) ? $n_rows['emailId'] : '';

                //        foreach($otpArray as $otp){



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



                return  $this->email_model->sendEmail($toemail, $subject, $template, $file = "");
            } else {

                $output .= '<div class="alert alert-danger msg-show">

                            <button class="close" data-close="alert"> X </button>

                           <span>Sorry your Username does not exist !! Kindly check ! </span>

                         </div>

                         <div class="form-group col-md-8"  style="display: inline-block;text-align: right;padding: 30px 0 0 0;width:100%;">

                         

                     </div>';

                echo $output;
            }
        }
    }

    public function resetPassword($forgotEncrypValue = '')
    {

        $userdata = $this->session->userinfo;



        $login = $userdata->LOGIN_ID;

        if ($login != '') {

            redirect('message/master/message');
        } else {



            $data = array(

                'view_file' => 'login/login_form/forget_login_form',

                'title' => 'Login Form',

                'current_menu' => 'Login Form'

            );

            $this->template->load_login_template($data);
        }
    }

    public function forgetPasswordProcess()
    {



        $encryptMsg = $this->input->post('encryptMsg');

        $forgetUser = decryptval($encryptMsg);



        $passwordDet = $this->input->post('passwordDet');

        // echo 'M<pre>';print_r($passwordDet);

        $confirmPass = $this->input->post('confirmPass');

        //echo 'A<pre>';print_r($confirmPass);exit;

        // $pass = $confirmPass;

        $CheckExpiredOrNot = $this->common->CheckExpiredOrNot($forgetUser, $encryptMsg);



        if ($CheckExpiredOrNot['ENCRYPT_OTP_PASSWORD'] != "") {

            if ($forgetUser != '') {



                $n_rows = $this->common->Checkbasicinfo($forgetUser);

                //     echo '<pre>';print_r($n_rows);exit;



                if ($n_rows != 0) {



                    $output .= '<div class="col-sm-12">

                             <input type="password" name="passwordDet" id="passwordDet" class="form-control passwordDet" value="" placeholder="Please Enter New Password" maxlength="25" minlength="8">

                          </div>';

                    $output .= '<div class="col-sm-12">

                             <input type="password" name="confirmPass" id="confirmPass" class="form-control confirmPass" value="" placeholder="Please Confirm Password" maxlength="40" minlength="8">

                          </div>';

                    echo $output;
                } else {

                    $output .= '<div class="alert alert-danger msg-show">

                            <button class="close" data-close="alert"> X </button>

                           <span>Sorry your Username does not exist !! Kindly check ! </span>

                         </div>

                         <div class="form-group col-md-8"  style="display: inline-block;text-align: right;padding: 30px 0 0 0;width:100%;">

                         

                     </div>';

                    echo $output;
                }
            }



            $output = '';

            if ($passwordDet != $confirmPass) {

                $output .= '<div class="alert alert-danger msg-show">

                            <button class="close" data-close="alert"> X </button>

                           <span> Password Mismatch !</span>

                         </div>

                         <div class="form-group col-md-8"  style="display: inline-block;text-align: right;padding: 30px 0 0 0;width:100%;">

                         

                     </div>';

                echo $output;
            } else if ($forgetUser != '' &&  $passwordDet != '' && $confirmPass != '') {



                $uppercase = preg_match('@[A-Z]@', $passwordDet);

                $lowercase = preg_match('@[a-z]@', $passwordDet);

                $number    = preg_match('@[0-9]@', $passwordDet);

                $spll    = preg_match('@[^\da-zA-Z]@', $passwordDet);





                // if(!$uppercase || !$spll || !$number || !$lowercase || strlen($passwordDet) < 8) {

                //  $output.='<div class="alert alert-danger msg-show">

                //                             <button class="close" data-close="alert"> X </button>

                //                            <span>Please Enter Atlease One Uppercase, Lowercase, Number, Special Character & Minimum Length 8!</span>

                //                          </div>

                //                          <div class="form-group col-md-8"  style="display: inline-block;text-align: right;padding: 30px 0 0 0;width:100%;">



                //                      </div>';

                //                     echo $output; 

                // }else {





                $passwordUpdation = $this->common->UpdateNewPassword($forgetUser, $passwordDet);



                $updateEncryptPassword = $this->common->CheckforgotEncrypValueEmpty($forgetUser, $encryptMsg);





                $output .= '<div class="alert alert-success msg-show">

                            <button class="close" data-close="alert"> X </button>

                           <span>Successfully Updated Password!</span>

                         </div>

                         <div class="form-group col-md-8"  style="display: inline-block;text-align: right;padding: 30px 0 0 0;width:100%;">

                         

                          </div>';

                echo $output;



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



                return  $this->email_model->sendEmail($toemail, $subject, $template, $file = "");

                // }               



            } else if ($forgetUser == '') {



                $output .= '<div class="alert alert-danger">

                    <button class="close" data-close="alert"> X </button>

                    <span>Kindly enter your Encrypt Password!</span>

                </div>';



                echo $output;
            }
        } else {

            $output .= '<div class="alert alert-danger msg-show">

                            <button class="close" data-close="alert"> X </button>

                           <span> Your Encrypt Password is Expired!</span>

                         </div>

                         <div class="form-group col-md-8"  style="display: inline-block;text-align: right;padding: 30px 0 0 0;width:100%;">

                         

                     </div>';

            echo $output;
        }
    }
}
