<?php

defined('EMPTP') or define('EMPTP', 'mas_employee_type');
defined('TER_MAN') or define('TER_MAN', 'terminal_management');
defined('TERM') or define('TERM', 'terminal_management');
defined('EMPLOYEE_MANAGEMENT') or define('EMPLOYEE_MANAGEMENT', 'employee_management');


defined('DES') or define('DES', 'designation');
defined('SPEC_LOC') or define('SPEC_LOC', 'specific_location');

defined('DEPT') or define('DEPT', '	department');
defined('SPE_LOC') or define('SPE_LOC', 'specific_location');

global $statusemp;
global $atarPermission_emp;
global $permission_master;
$statusemp = ['P' => 'Pending', 'E' => 'Activated'];

$atarPermission_emp = [
    'listadd' => [2, 7, 6, 8],
    'view_supadmin' => [2, 6, 8],
    'view_ad' => [7, 6, 8],
];


$permission_master = [
    'listadd' => [2, 7, 6, 8],
];
