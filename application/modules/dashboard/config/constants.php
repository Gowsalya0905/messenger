<?php


global $dashboard_blue;
$dashboard_blue = '#4144ae';

global $dashboard_orange;
$dashboard_orange = '#ff8406';
global $location_status_color_code;


$location_status_color_code = [
    '#ffc107',   // Amber
    '#3d9970',   // Medium Sea Green
    '#ff5733',   // Red Orange
    '#006994',   // Dark Blue
    '#F8AF00',   // Golden Yellow
    '#3498DB',   // Sky Blue
    '#0E47A1',   // Deep Blue
    '#1564C0',   // Azure Blue
    '#1976D3',   // Bright Blue
    '#1D89E4',   // Light Blue
    '#2196F3',   // Dodger Blue
    '#8e44ad',   // Purple
    '#2c3e50',   // Dark Slate Gray
    '#e74c3c',   // Red
    '#27ae60',   // Green
    '#f39c12',   // Orange
    '#2980b9',   // Blue
    '#8e44ad',   // Purple
    '#16a085',   // Turquoise
    '#f1c40f',   // Yellow
    '#d35400',   // Pumpkin
    '#c0392b',   // Firebrick
    '#2ecc71',   // Emerald Green
    '#7f8c8d',   // Gray
    '#34495e',   // Blue Gray
    '#1abc9c',   // Light Green
];

global $atar_color_code;
$atar_color_code = [
    '#F8AF00', // Pending

    '#28B463', // Approved & Closed
    '#72C89E', // CA Pending
    '#006994', // Waiting For Approval
    '#d40000', // Rejected
    '#ff4500', // Overdue
    '#3498DB', // FA
    '#CD5C5C', // FR

];


global $risk_color_code;
$risk_color_code = [
    '#F8AF00', // low
    '#CD5C5C', // medium
    '#d40000', // high


];






global $dashPermission;
global $status_val;
$dashPermission = [
    'view_supadmin' => [2],
    'view_ad' => [7],
    'view_assigner' => [5],
    'view_filter' => [2, 6, 7, 8],
    'fix' => [3],
    'approve' => [6], //EPC E&S Manager
    'approve_final' => [8], //EPC E&S Manager


];

$status_val = [
    'obs_closed' => '3',
];

defined('OBS_MAIN_SEE') or define('OBS_MAIN_SEE', 'obs_main_flow');

global $obstype_color_status_bar;
$obstype_color_status_bar =
    [
        '#FA8072', // Unsafe Act
        '#F8AF00', // Unsafe condition
        '#228b22', // Positive
    ];
