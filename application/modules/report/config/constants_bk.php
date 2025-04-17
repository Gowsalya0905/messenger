<?php


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

global $owner_list;
$owner_list = [
    '' => 'Select Owner',
    '1' => 'Sana Taibah',
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
global $obsPermission;
$obsPermission = [
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
