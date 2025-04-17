<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//login
$route['login'] = 'login';
$route['loginSubmit'] = 'login/loginSubmit';
$route['logout'] = 'login/logout';

$route['dashboard'] = 'dashboard';




$route['register_company'] = 'Contractor/RegisterCompany';
$route['add_company'] = 'Contractor/RegisterCompany/addCompany';
$route['CompanyInsert'] = 'Contractor/RegisterCompany/CompanyInsert';
$route['getCompanyType'] = 'Contractor/RegisterCompany/getCompanyType';
$route['deleteCompany'] = 'Contractor/RegisterCompany/deleteCompany';





$route['contractor_list'] = 'Contractor/ContractorCompany';
$route['contractor_agreement'] = 'Contractor/ContractorAgreement';
$route['add_contractor'] = 'Contractor/ContractorCompany/addContractor';
$route['add_company/(:any)'] = 'Contractor/RegisterCompany/addCompany/$1';
$route['add_contractor/(:any)'] = 'Contractor/ContractorCompany/addContractor/$1';
$route['add_agreement'] = 'Contractor/ContractorAgreement/addContractorAgree';
$route['add_agreement/(:any)'] = 'Contractor/ContractorAgreement/addContractorAgree/$1';
$route['ContractorInsert'] = 'Contractor/ContractorCompany/ContractorInsert';
$route['ContractAgreementInsert'] = 'Contractor/ContractorAgreement/ContractAgreementInsert';


$route['Inspection_list'] = 'inspection/index';



/*
 * Database Connection
 */

//$route['RefreshList/(:any)'] = 'DataBase/RefreshList';

$route['add_db'] = 'DataBase/DataBase';
$route['insert_db'] = 'DataBase/InsertDB';
$route['ConnectDB'] = 'DataBase/ConnectDB';
$route['selectTable'] = 'DataBase/SelectMssqlTable';
$route['SelectTableRefresh'] = 'DataBase/SelectTableRefresh';
$route['ManualRefresh'] = 'DataBase/ManualRefresh';
$route['AutoRefresh'] = 'DataBase/AutoRefresh';
$route['DbSelect'] = 'DataBase/DbSelect';
$route['table'] = 'DataBase/table';
$route['select_table'] = 'DataBase/SelectTable';



$route['Refresh/(:any)'] = 'DataBase/RefreshList/$1';
//$route['ErrorListPopUp'] = 'DataBase/ErrorListPopUp';
$route['ErrorListPopUp/(:any)'] = 'DataBase/ErrorListPopUp/$1';
$route['getAllRefresh'] = 'DataBase/getAllRefresh';
$route['getData'] = 'DataBase/getData';
$route['auto_refresh_start'] = 'DataBase/AutoRefreshStart';
$route['getAutoRefresh'] = 'DataBase/getAutoRefresh';
$route['refresh_setting'] = 'DataBase/RefreshSetting';
$route['RefreshMailInsert'] = 'DataBase/RefreshMailInsert';
$route['sendEmail'] = 'DataBase/sendEmail';



//$route['add_company/(:any)'] = 'DataBase/RefreshList/$1';


