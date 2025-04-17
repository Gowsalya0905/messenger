<?php


defined('TR_FLOW_SEE') or define('TR_FLOW_SEE', 'training_main_flow');
defined('TR_VISIT_DETAILS') or define('TR_VISIT_DETAILS', 'tr_emp_visitor_details');

defined('TRN_TYPE_MAS') or define('TRN_TYPE_MAS', 'trng_type_master');
defined('TRN_CAT_MAS') or define('TRN_CAT_MAS', 'trng_category_master');
defined('TRN_QUES_MAS') or define('TRN_QUES_MAS', 'trng_question_master');
defined('OBS_IMG_SEE') or define('OBS_IMG_SEE', 'obs_image_upload');
defined('OBS_STAT_SEE') or define('OBS_STAT_SEE', 'obs_main_status');


defined('OBS_ESL_SEE') or define('OBS_ESL_SEE', 'obs_escalation_log');
defined('OBS_APP_ESL_SEE') or define('OBS_APP_ESL_SEE', 'obs_approval_escalation_log');

defined('MAS_INJ') or define('MAS_INJ', 'master_inj');
defined('MAS_HSE') or define('MAS_HSE', 'master_hse_category');
defined('SAFE_OBS') or define('SAFE_OBS', '3');
defined('HSSE_APPR') or define('HSSE_APPR', '9');

global $owner_list;
$owner_list = [
    '' => 'Select Owner',
    '1' => 'Owner Sana Taibah',
];

global $owner_engineer_list;
$owner_engineer_list = [
    '' => 'Select Owner Engineer Name',
    '1' => 'Owner Engineer OCA Global',
];

global $EPC_list;
$EPC_list = [
    '' => 'Select Owner Engineer Name',
    '1' => 'EPC Power China',
];

global $obs_type_list;
$obs_type_list = [
    '' => 'Select Observation Type',
    '1' => 'UnSafe Act',
    '2' => 'UnSafe Condition',
    '3' => 'Others(Positive/Good obs)',
];

global $risk_rating;
$risk_rating = [
    '' => 'Select Risk',
    '1' => 'Low',
    '2' => 'Medium',
    '3' => 'High',
];

global $risk_rating_batch;
$risk_rating_batch = [
    '' => ['label' => 'Select Risk', 'class' => 'secondary'],
    '1' => ['label' => 'Low', 'class' => 'success'],
    '2' => ['label' => 'Medium', 'class' => 'warning'],
    '3' => ['label' => 'High', 'class' => 'danger'],
];
global $status_drop;

$status_drop = [
    '' => 'Select Status',
    '0' => 'Drafted',
    '1' => 'Waiting for Supervisor Action',
    //'2' => 'Irrelevant',
    '3' => 'Observation Closed',
    '4' => 'CA Pending',
    '5' => 'EPC E&S Manager Approval',
    // '6' => 'Approved',
    '7' => 'EPC E&S Manager Rejected',
    '8' => 'Overdue',
    '9' => 'PC/OE - HSSE Manager Approval',
    '10' => 'PC/OE - HSSE Manager Rejected',
];
global $status_main;

$status_main = [
    '' => 'Select Main Status',
    '0' => 'Open',
    '1' => 'Closed',
 
];
global $trPermission;
$trPermission = [
    'view_supadmin' => [2],
    'view_ad' => [7],
    'view_assigner' => [5],


    'listadd' => [2, 7, 4],
    'view_filter' => [2, 6, 7, 8],

    'fix' => [3],
    'approve' => [6], //EPC E&S Manager
    'approve_final' => [8], //EPC E&S Manager

    //Log
    'view_approval_log' => [2, 6, 7, 8],
    'view_approve_final_log' =>  [2, 6, 7, 8],
    'view_action_log' =>  [2, 6, 7, 8],
];
global $persons;
$persons = [
    '' => 'Select person',
    '1' => 'admin',
    '2' => 'kowsalya',
    '3' => 'kannika',
    '4' => 'sathish',
    '6' => 'meena',
    '3' => 'mathi',
    '7' => 'prabu',
 
];
