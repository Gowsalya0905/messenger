<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $tablePrefix;
defined('JKP6_PLN') or define('JKP6_PLN', $tablePrefix . '_jkkp6_initial_form');
defined('JKP6_AFTPER') or define('JKP6_AFTPER', $tablePrefix . '_jkkp6_affct_persons');
defined('JKP6_UPLOADS') or define('JKP6_UPLOADS', $tablePrefix . '_jkkp6_final_uploads');
defined('ACDMEET') or define('ACDMEET', $tablePrefix . '_accdmeeting');
defined('USRMSTR') or define('USRMSTR', $tablePrefix . '_user_master');
defined('INITIALCLASS') or define('INITIALCLASS', $tablePrefix . '_industrial_classification');

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
