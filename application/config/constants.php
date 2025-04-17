<?php

defined('BASEPATH') or exit('No direct script access allowed');



/*

|--------------------------------------------------------------------------

| Display Debug backtrace

|--------------------------------------------------------------------------

|

| If set to TRUE, a backtrace will be displayed along with php errors. If

| error_reporting is disabled, the backtrace will not display, regardless

| of this setting

|

*/

defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);



/*

|--------------------------------------------------------------------------

| File and Directory Modes

|--------------------------------------------------------------------------

|

| These prefs are used when checking and setting modes when working

| with the file system.  The defaults are fine on servers with proper

| security, but you may wish (or even need) to change the values in

| certain environments (Apache running a separate process for each

| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should

| always be used to set the mode correctly.

|

*/

defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);

defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);

defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);

defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);



/*

|--------------------------------------------------------------------------

| File Stream Modes

|--------------------------------------------------------------------------

|

| These modes are used when working with fopen()/popen()

|

*/

defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');

defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');

defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care

defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care

defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');

defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');

defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');

defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');



/*

|--------------------------------------------------------------------------

| Exit Status Codes

|--------------------------------------------------------------------------

|

| Used to indicate the conditions under which the script is exit()ing.

| While there is no universal standard for error codes, there are some

| broad conventions.  Three such conventions are mentioned below, for

| those who wish to make use of them.  The CodeIgniter defaults were

| chosen for the least overlap with these conventions, while still

| leaving room for others to be defined in future versions and user

| applications.

|

| The three main conventions used for determining exit status codes

| are as follows:

|

|    Standard C/C++ Library (stdlibc):

|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html

|       (This link also contains other GNU-specific conventions)

|    BSD sysexits.h:

|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits

|    Bash scripting:

|       http://tldp.org/LDP/abs/html/exitcodes.html

|

*/



//$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

//$http_host = $_SERVER['HTTP_HOST']; 

//if($http_host == 'tsqctc.neoehs.com'){

//	if(preg_match("/http:\/\//",$protocol)){

//		$protocol = 'https://';

//	}

//}

//$baseurl = $protocol.$_SERVER['HTTP_HOST'];

//$baseurl .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

//

//

//defined('HTTPHOST')        OR define('HTTPHOST', $protocol);

//defined('HTTP_HOST')            OR define('HTTP_HOST', $http_host);

//defined('SITE_TITLE')         OR define('SITE_TITLE', 'NITHYA');





/*Database Connectivity*/







$aHttpSever = [
    'localhost',
    '192.168.0.250',
    '103.88.129.32',
    'ardhas.tk',
    'neoembassy.tk',
    '52.66.184.170',
    '104.211.139.218'

];

$aHttpsServer = [

    'dev.welspun.neoehs.com'

];



$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'http://' : 'http://';

$http_host = $_SERVER['HTTP_HOST'];



if (in_array($http_host, $aHttpsServer)) {

    if (preg_match("/http:\/\//", $protocol)) {

        $protocol = 'http://';
    }
}

$protocol = WEB_SERVER_PROTOCOL;

$baseurl = $protocol . $_SERVER['HTTP_HOST'];

$baseurl .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

/*

 * Database Connectivity

 */

$aHost = array_merge($aHttpSever, $aHttpsServer);






defined('TEST_MODE')       or define('TEST_MODE', FALSE);
defined('TEST_EMAILID')       or define('TEST_EMAILID', 'nithyashree@ardhas.com');
defined('BASE_URL')            or define('BASE_URL', $baseurl);
defined('DEFAULT_LANG')   or define('DEFAULT_LANG', 'english');
defined('SITE_TITLE')         or define('SITE_TITLE', 'Message System');

defined('LAYOUT_PLUG_PATH')           or define('LAYOUT_PLUG_PATH', BASE_URL . 'assets/plugins/');
defined('LAYOUT_PLUG_PATH_CSS')           or define('LAYOUT_PLUG_PATH_CSS', BASE_URL . 'assets/dist/css/');
defined('LAYOUT_PLUG_PATH_JS')           or define('LAYOUT_PLUG_PATH_JS', BASE_URL . 'assets/dist/js/');
defined('LAYOUT_PLUG_PATH_IMG')           or define('LAYOUT_PLUG_PATH_IMG', BASE_URL . 'assets/dist/img/');


//own js

defined('PDF_IMG_PATH')            or define('PDF_IMG_PATH', FCPATH . 'assets/images/modules/');
defined('IMG_PATH')            or define('IMG_PATH', BASE_URL . 'assets/images/modules/');
defined('GEN_IMG_PATH')            or define('GEN_IMG_PATH', BASE_URL . 'assets/images/');
defined('CSS_PATH')            or define('CSS_PATH', BASE_URL . 'assets/css/');
defined('JS_PATH')             or define('JS_PATH', BASE_URL . 'assets/js/');


defined('ICON_PATH')         or define('ICON_PATH', BASE_URL . 'assets/icons/');
defined('UPLOAD')         or define('UPLOAD', BASE_URL . 'assets/upload/');

////////////table names start

defined('MAS_COMP') or define('MAS_COMP', 'master_company');
defined('MAS_AREA') or define('MAS_AREA', 'master_area');
defined('MAS_BUILDING') or define('MAS_BUILDING', '	master_building');
defined('MAS_DEPT') or define('MAS_DEPT', 'master_department');
defined('MAS_PROJ') or define('MAS_PROJ', 'master_project');
/** OBSERVATION */
defined('OBS_FLOW_SEE') or define('OBS_FLOW_SEE', 'obs_main_flow');
defined('OBS_MAIN_SEE') or define('OBS_MAIN_SEE', 'obs_main_flow');
defined('OBS_ACT_LOG') or define('OBS_ACT_LOG', 'obs_action_taken_log');
defined('OBS_RISK_LOG') or define('OBS_RISK_LOG', 'obs_risk_log');

defined('OBS_IMG_SEE') or define('OBS_IMG_SEE', 'obs_image_upload');
defined('OBS_STAT_SEE') or define('OBS_STAT_SEE', 'obs_main_status');

defined('OBS_ESL_SEE') or define('OBS_ESL_SEE', 'obs_escalation_log');
defined('OBS_APP_ESL_SEE') or define('OBS_APP_ESL_SEE', 'obs_approval_escalation_log');

defined('MAS_INJ') or define('MAS_INJ', 'master_inj');
defined('MAS_HSE') or define('MAS_HSE', 'master_hse_category');
defined('SAFE_OBS') or define('SAFE_OBS', '3');
defined('HSSE_APPR') or define('HSSE_APPR', '9');
/** OBSERVATION */


defined('UTYPE_DESIG') or define('UTYPE_DESIG', 'user_type_designation');
defined('UTYPE') or define('UTYPE', 'user_type');

defined('LOGIN') or define('LOGIN', 'login_details');
defined('USER_LOG')         or define('USER_LOG', 'login_details');

defined('COMP_PROF')         or define('COMP_PROF', 'company_profile');

defined('NOTI')            or define('NOTI', 'notification');
defined('NOTI_LOG')        or define('NOTI_LOG', 'notification_log');



defined('GENDER') or define('GENDER', 'gender_master');
defined('NATION') or define('NATION', 'employee_nationality');
defined('EMPL') or define('EMPL', 'employee_management');

defined('COMP_CERTI') or define('COMP_CERTI', 'competency_certificate');
defined('CONT_DET') or define('CONT_DET', 'contractor_details');




defined('DESIG') or define('DESIG', 'designation');
defined('DES') or define('DES', 'designation');


defined('DASH_SLIDER') or define('DASH_SLIDER', 'dashboard_image_upload');

defined('PER_MS') or define('PER_MS', 'permission_master');
defined('LOADING_IMG') or define('LOADING_IMG', BASE_URL . 'assets/images/loader.gif');


require_once(APPPATH . 'modules/master/config/constants.php');
require_once(APPPATH . 'modules/dashboard/config/constants.php');
////////////table names end


// Training

defined('TR_FLOW_SEE') or define('TR_FLOW_SEE', 'training_main_flow');
defined('TR_VISIT_DETAILS') or define('TR_VISIT_DETAILS', 'tr_emp_visitor_details');

defined('TRN_TYPE_MAS') or define('TRN_TYPE_MAS', 'trng_type_master');
defined('TRN_CAT_MAS') or define('TRN_CAT_MAS', 'trng_category_master');
defined('TRN_QUES_MAS') or define('TRN_QUES_MAS', 'trng_question_master');


/* REST API */
defined('APIURL') or define('APIURL', 'lumut-api');
defined('LUMAPIPATH') or define('LUMAPIPATH', APPPATH . 'modules/' . APIURL . '/');
defined('APK_TOKEN') or define('APK_TOKEN', 'apk_token_details');




///////////////////////mobile api






defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

///////cron


$cronTo_Supervisor_Escalation_Times = 3;
$cronAdmin = ['7'];
$superadmin = ['2'];
$superplant = ['9'];

$dropsuperexec = ['4'];
$dropsuperdept = ['8'];

// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
