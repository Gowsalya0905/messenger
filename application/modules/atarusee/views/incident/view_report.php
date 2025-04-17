<?php
$obs_id = postData($editData, 'obs_id');
$Reporter = postData($editData, 'Reporter');
$desig = postData($editData, 'desig');
global $risk_rating, $obs_type_list, $owner_list, $owner_engineer_list, $EPC_list;
$owner_name = !empty(postData($editData, 'obs_owner_id')) ? $owner_list[postData($editData, 'obs_owner_id')] : '';
$owner_eng_name = !empty(postData($editData, 'obs_owner_eng')) ? $owner_engineer_list[postData($editData, 'obs_owner_eng')] : '';
$epc_name = !empty(postData($editData, 'obs_epc_id')) ? $EPC_list[postData($editData, 'obs_epc_id')] : '';
$comp_name = postData($editData, 'comp_name');
$area_name = postData($editData, 'area_name');
$building_name = postData($editData, 'building_name');
$dep_name = postData($editData, 'dep_name');
$proj_name = postData($editData, 'proj_name');
$hse_cat = postData($editData, 'hse_cat');
$editRiskId = postData($editData, 'obs_risk_id');

$risk_name = !empty($editRiskId) ? $risk_rating[$editRiskId] : '';
$obs_type_name = !empty(postData($editData, 'obs_type_id')) ? $obs_type_list[postData($editData, 'obs_type_id')] : '';
$obs_date = !empty(postData($editData, 'obs_date')) ? date('d-m-Y', strtotime(postData($editData, 'obs_date'))) : '';
$inj_name = postData($editData, 'inj_name');
$obs_desc = postData($editData, 'obs_desc');
$editComp_id = postData($editData, 'obs_comp_id');
$editobtype = postData($editData, 'obs_type_id');

$getProname = postData($editData, 'ATAR_PROJ_NAME');

$obs_report_datetime = date('d-m-Y H:i:s', strtotime(postData($editData, 'obs_report_datetime')));


$obs_supervisor_date = date('d-m-Y', strtotime(postData($editData, 'obs_supervisor_date', date('d-m-Y'))));

$obs_assigner_target_date_ACTUAL = date('d-m-Y', strtotime(postData($editData, 'obs_assigner_target_date')));
$obs_assigner_ca_submitted_date = !empty(postData($editData, 'obs_assigner_ca_submitted_date')) ? date('d-m-Y', strtotime(postData($editData, 'obs_assigner_ca_submitted_date'))) : '';

if ($obs_assigner_target_date_ACTUAL == '01-01-1970') {
    $obs_assigner_target_date_ACTUAL = "";
} else {
    $obs_assigner_target_date_ACTUAL = $obs_assigner_target_date_ACTUAL;
}

$obs_assigner_desc = postData($editData, 'obs_assigner_desc');

$obs_hsse_es_appr_rej_desc = postData($editData, 'obs_hsse_es_appr_rej_desc');
$obs_hsse_appr_rej_desc = postData($editData, 'obs_hsse_appr_rej_desc');

$Assignee = postData($editData, 'assignee');
$Supervisor = postData($editData, 'supervisor');
$Unithead = postData($editData, 'Unithead');
$obs_supervisor_desc_ASS = postData($editData, 'obs_supervisor_desc');



$obs_hsse_es_type_id = postData($editData, 'obs_hsse_es_type_id');
$obs_app_status = postData($editData, 'obs_app_status');


$obs_assigner_id = postData($editData, 'obs_assigner_id');
$obs_assigner_type_id = postData($editData, 'obs_assigner_type_id');
$obs_assigner_des_id = postData($editData, 'obs_assigner_des_id');

$obs_final_tar_date_time = postData($editData, 'obs_final_tar_date_time');
$obs_supervisor_date_FINAL_TARGET = date('Y-m-d', strtotime(postData($editData, 'obs_final_tar_date_time')));
$obs_assigner_desc = postData($editData, 'obs_assigner_desc');


$ca_so_name = $_SESSION['user_details']['NAME'];
$ca_fk_obs_so_login_id = $_SESSION['userinfo']->LOGIN_ID;
$ca_so_desg_name =  $_SESSION['user_details']['DESIGNATION'];
$ca_fk_obs_so_des_id = $_SESSION['user_details']['DESIGNATIONID'];
$ca_fk_obs_so_role_id = $_SESSION['emp_details']->EMP_USERTYPE_ID;
$ca_fk_obs_so_datetime = date("d-m-Y H:i:s");

$reason = "";

$userid = getCurrentUserid();
$user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
global $obsPermission;

?>

<style>
    .viewpage {
        /*        font-size: 14px;*/
    }

    .newhr {
        margin-top: 15px;
        margin-bottom: 15px;
        border-color: white;
    }

    .card-header-inner {
        color: #fff;
        background-color: #007bff;
        font-weight: bold;
        text-transform: uppercase;
    }

    .card-link {
        color: #fff;
    }

    .card-header a:hover {
        color: #fff;
    }

    .red {
        color: red;
    }

    .removeItem:not(:disabled):not(.disabled).active,
    .removeItem:not(:disabled):not(.disabled):active,
    .removeItem,
    .removeItem:hover {
        color: #fff;
        background-color: red;
        border-color: red;
    }

    .inner-header {
        background-color: #c8094e !important;
    }

    .uppertext {
        text-transform: uppercase;
    }

    .card-header-cc {
        color: #fff;
        background-color: green;
    }

    .docLabel {
        margin-left: 15px;
    }

    .tdlabel {
        width: 23%;
    }

    .title-head {
        text-transform: uppercase;
    }

    .title-colon {
        width: 1%;
    }

    .imagepopcombine:hover .popupgal {
        opacity: 1;
    }

    .popupgal {
        position: absolute;
        font-size: 3rem !important;
        color: #fff;
        opacity: 0;
        transition: .2s opacity ease-in-out;
        z-index: 5;
        background: #b7b7b77d;
        min-width: 82%;
        min-height: 90%;
        max-width: 90%;
        top: 0;
        text-align: center;
        border: 2px solid #007cb7;
    }

    .popupgal i {
        margin-top: 25%;
    }

    .imgupload {
        width: 100%;
        height: 100%;
    }

    .fileinput img {
        width: 100%;
        height: 100%;
    }

    .mini-txt {
        text-align: center;
    }
</style>
<div class="wrapper">


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div id="accordion" class="permitAccordion">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">


                            <!-- general form elements -->
                            <div class="card">

                                <div class="card-header">

                                    <h4 class='card-title'>Observation Management </h4>
                                    <a href="javascript:history.back()"><button type="button" class="btn btn-primary backBtns"> Back</button></a>

                                </div>
                                <div class="card-body">
                                    <?php
                                    $getstatusid = isset($editData->obs_auto_id) && !empty($editData->obs_auto_id) ? $editData->obs_auto_id : '';

                                    //$groupid = getCurrentUserGroupId(); 
                                    // if((($groupid == 6 || $groupid == 7) || is_admin()) && $obs_app_status == 1 ){
                                    $url = '';
                                    if ((is_admin() || $obs_assigner_id == $userid || $obsPermission['view_ad']) && ($obs_app_status == 1 || $obs_app_status == 8)) {
                                        $url = 'atarusee/incident/saveStatus/';
                                    } else if ($obs_app_status == 4 || $obs_app_status == 7) {
                                        $url = 'atarusee/incident/saveCADetails/';
                                    } else if ($obs_app_status == 3) {
                                        $url = 'atarusee/incident/saveCatDetails/';
                                    } else if ($obs_app_status == 5 || $obs_app_status == 10) {
                                        $url = 'atarusee/incident/saveApproveDetails/';
                                    } else if ($obs_app_status == 9) {
                                        $url = 'atarusee/incident/saveFinalApproveDetails/';
                                    }

                                    ?>
                                    <?php echo form_open_multipart($url . $getstatusid, 'class="form-horizontal" id="company-profile" novalidate'); ?>

                                    <div class="card ">
                                        <div class="card-header card-header-inner">
                                            Basic Details<span class="float-right"></span>
                                        </div>

                                        <div class="card-body">
                                            <div class="row viewpage">
                                                <div class="col-12 table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <tr>
                                                            <td class="tdlabel"><b>Observation ID</b></td>
                                                            <td>:</td>
                                                            <td><?php echo ($obs_id != '') ? $obs_id : '-'; ?></td>
                                                            <td class="tdlabel"><b>Reporter Name</b></td>
                                                            <td>:</td>
                                                            <td><?php echo ($Reporter != '') ? $Reporter : '-'; ?></td>

                                                        </tr>

                                                        <tr>
                                                            <td class="tdlabel"><b>Reporter's Designation</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($desig != '') ? $desig : '-'; ?></td>

                                                            <td class="tdlabel"><b>Reported Date and Time</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($obs_report_datetime != '') ? $obs_report_datetime : '-'; ?></td>



                                                        </tr>
                                                        <tr>

                                                            <td class="tdlabel"><b>Owner </b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($owner_name != '') ? $owner_name : '-'; ?></td>

                                                            <td class="tdlabel"><b>Owner Engineer Name</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($owner_eng_name != '') ? $owner_eng_name  : '-'; ?></td>

                                                        </tr>
                                                        <tr>
                                                            <td class="tdlabel"><b>EPC</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($epc_name != '') ? $epc_name  : '-'; ?></td>
                                                            <td class="tdlabel"><b>Company </b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($comp_name != '') ? $comp_name : '-'; ?></td>



                                                        </tr>
                                                        <tr>
                                                            <td class="tdlabel"><b>Area</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($area_name != '') ? $area_name  : '-'; ?></td>
                                                            <td class="tdlabel"><b>Building/Block/Direction </b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($building_name != '') ? $building_name : '-'; ?></td>



                                                        </tr>



                                                        <tr>
                                                            <td class="tdlabel"><b>Department</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($dep_name != '') ? $dep_name : '-'; ?></td>


                                                            <td class="tdlabel"><b>Project</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($proj_name != '') ? $proj_name : '-'; ?></td>

                                                        </tr>

                                                        <tr>
                                                            <td class="tdlabel"><b> HSE Category</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($hse_cat != '') ? $hse_cat : '-'; ?></td>
                                                            <td class="tdlabel"><b>Date of Observation</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo !empty($obs_date) ? $obs_date : '-'; ?></td>


                                                        </tr>
                                                        <tr>
                                                            <td class="tdlabel"><b>Observation Type</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($obs_type_name != '') ? $obs_type_name : '-'; ?></td>
                                                            <td class="tdlabel"><b>Risk Rating</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($risk_name != '') ? $risk_name : '-'; ?></td>

                                                        </tr>


                                                        <tr>
                                                            <td class="tdlabel"><b>Observation Description</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td colspan="4"><?php echo ($obs_desc != '') ? $obs_desc : '-'; ?></td>
                                                        </tr>

                                                    </table>
                                                    <?php
                                                    if ($getuincidentimage != FALSE) { ?>
                                                        <label>&nbsp;&nbsp;&nbsp;Uploaded Images :</label>
                                                        <div class="row col-md-12">

                                                            <?php

                                                            foreach ($getuincidentimage as $akey => $attachmnt) {
                                                                $filepath = postData($attachmnt, 'obs_file_path');
                                                                $filename = postData($attachmnt, 'obs_filename');
                                                                $fileext = postData($attachmnt, 'obs_file_ext');
                                                                $fileFullpath = BASE_URL . $filepath . $filename;
                                                                $displayLabel = ($filepath != '') ? '<img src="' . $fileFullpath . '"/><div class="popupgal"><a data-fancybox="gallery" href="' . $fileFullpath . '"><i class="fa fa-search" aria-hidden="true"></i></a></div>' : $filename;
                                                            ?>
                                                                <div class="col-md-3 imagepopcombine" id='imagepopcombine_<?php echo $akey; ?>'>
                                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                        <div class="fileinput-preview img-thumbnail" style="width: 200px; height: 150px;"><?php echo $displayLabel; ?></div>
                                                                    </div>
                                                                </div>

                                                            <?php
                                                            }
                                                            ?>

                                                        </div>
                                                    <?php } ?>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($obs_app_status >= 2) { ?>
                                        <?php
                                        if ((isset($getObsReassign) && !empty($getObsReassign)) || (isset($getObsAssign) && !empty($getObsAssign))) {
                                        ?>
                                            <div class="card ">
                                                <div class="card-header card-header-inner">
                                                    Assign / Re-Assign Log Details<span class="float-right"></span>
                                                </div>
                                                <?php
                                                if (isset($getObsAssign) && !empty($getObsAssign)) {
                                                ?>
                                                    <div class="card-body">
                                                        <div class="card-header card-header-inner inner-header">
                                                            Assign Details<span class="float-right"></span>
                                                        </div>
                                                        <div class="row viewpage">
                                                            <div class="col-12 table-responsive ">

                                                                <table class="table table-striped approval" style="border: 1px solid #949699;">
                                                                    <tr>
                                                                        <td class="tdlabel"><b>Assigned By</b></td>
                                                                        <td>:</td>
                                                                        <td><?php echo ($getObsAssign->repman_reassign_name != '') ? $getObsAssign->repman_reassign_name : '-'; ?></td>
                                                                        <td class="tdlabel"><b>Assigned Person's Role</b></td>
                                                                        <td>:</td>
                                                                        <td><?php echo ($getObsAssign->repman_role_reassign != '') ? $getObsAssign->repman_role_reassign : '-'; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="tdlabel"><b>Assigned Person's Designation</b></td>
                                                                        <td class="title-colon">:</td>
                                                                        <td><?php echo ($getObsAssign->repman_desg_reassign != '') ? $getObsAssign->repman_desg_reassign : '-'; ?></td>
                                                                        <td class="tdlabel"><b>Assigned Date & Time</b></td>
                                                                        <td>:</td>
                                                                        <td><?php echo date('d-m-Y H:i:s', strtotime($getObsAssign->log_created_date)) != '' ? date('d-m-Y H:i:s', strtotime($getObsAssign->log_created_date)) : '-'; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="tdlabel"><b>Assigned To</b></td>
                                                                        <td>:</td>
                                                                        <td><?php echo ($getObsAssign->aiemp_name_reassign != '') ? $getObsAssign->aiemp_name_reassign : '-'; ?></td>
                                                                        <td class="tdlabel"><b>Target Date & Time for CA</b></td>
                                                                        <td>:</td>
                                                                        <td><?php echo date('d-m-Y H:i:s', strtotime($getObsAssign->fk_obs_new_target_datetime)) != '' ? date('d-m-Y H:i:s', strtotime($getObsAssign->fk_obs_new_target_datetime)) : '-'; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="tdlabel"><b>Reason for Assign</b></td>
                                                                        <td>:</td>
                                                                        <td colspan="4"><?php echo ($getObsAssign->fk_obs_supervisor_desc != '') ? $getObsAssign->fk_obs_supervisor_desc : '-'; ?></td>
                                                                    </tr>

                                                                </table>


                                                            </div>


                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php
                                                if (isset($getObsReassign) && !empty($getObsReassign)) {
                                                ?>
                                                    <div class="card-body">
                                                        <div class="card-header card-header-inner inner-header">
                                                            Re-Assign Log Details<span class="float-right"></span>
                                                        </div>
                                                        <div class="row viewpage">
                                                            <div class="col-12 table-responsive ">
                                                                <?php
                                                                $i = 1;

                                                                foreach ($getObsReassign as $insDKeyRe1 => $InsDataRe1) {
                                                                    $bfrKeyReval = $insDKeyRe1 - 1;
                                                                ?>
                                                                    <table class="table table-striped approval" style="border: 1px solid #949699;">
                                                                        <tr>
                                                                            <td class="tdlabel"><b>Re-assigned By</b></td>
                                                                            <td>:</td>
                                                                            <td><?php echo ($InsDataRe1->repman_reassign_name != '') ? $InsDataRe1->repman_reassign_name : '-'; ?></td>
                                                                            <td class="tdlabel"><b>Re-assigned Person's Role</b></td>
                                                                            <td>:</td>
                                                                            <td><?php echo ($InsDataRe1->repman_role_reassign != '') ? $InsDataRe1->repman_role_reassign : '-'; ?></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="tdlabel"><b>Re-assigned Person's Designation</b></td>
                                                                            <td class="title-colon">:</td>
                                                                            <td><?php echo ($InsDataRe1->repman_desg_reassign != '') ? $InsDataRe1->repman_desg_reassign : '-'; ?></td>
                                                                            <td class="tdlabel"><b>Re-assigned Date & Time</b></td>
                                                                            <td>:</td>
                                                                            <td><?php echo date('d-m-Y H:i:s', strtotime($InsDataRe1->log_created_date)) != '' ? date('d-m-Y H:i:s', strtotime($InsDataRe1->log_created_date)) : '-'; ?></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="tdlabel"><b>Re-assigned To</b></td>
                                                                            <td>:</td>
                                                                            <td><?php echo ($InsDataRe1->aiemp_name_reassign != '') ? $InsDataRe1->aiemp_name_reassign : '-'; ?></td>
                                                                            <td class="tdlabel"><b>New Target Date & Time for CA</b></td>
                                                                            <td>:</td>
                                                                            <td><?php echo date('d-m-Y H:i:s', strtotime($InsDataRe1->fk_obs_new_target_datetime)) != '' ? date('d-m-Y H:i:s', strtotime($InsDataRe1->fk_obs_new_target_datetime)) : '-'; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="tdlabel"><b>Reason for Re-assign</b></td>
                                                                            <td>:</td>
                                                                            <td colspan="4"><?php echo ($InsDataRe1->fk_obs_supervisor_desc != '') ? $InsDataRe1->fk_obs_supervisor_desc : '-'; ?></td>
                                                                        </tr>

                                                                    </table>
                                                                <?php } ?>

                                                            </div>


                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>

                                        <?php
                                        if (isset($getObsApproval) && !empty($getObsApproval) && in_array($user_type, $obsPermission['view_approval_log'])) { ?>
                                            <div class="card ">
                                                <div class="card-body">
                                                    <div class="card-header card-header-inner ">
                                                        EPC E&S Manager Approval Log Details<span class="float-right"></span>
                                                    </div>
                                                    <div class="row viewpage">
                                                        <div class="col-12 table-responsive ">
                                                            <?php
                                                            foreach ($getObsApproval as $appr => $approval) {

                                                            ?>
                                                                <table class="table table-striped approval" style="border: 1px solid #949699;">
                                                                    <tr>
                                                                        <td class="tdlabel"><b>Status</b></td>
                                                                        <td>:</td>
                                                                        <td>
                                                                            <?php if ($approval->approval_type_id == 1): ?>
                                                                                <span class="badge bg-success">Approved</span>
                                                                            <?php else: ?>
                                                                                <span class="badge bg-danger">Rejected</span>
                                                                            <?php endif; ?>

                                                                        </td>
                                                                        <td class="tdlabel"><b>Approved/Rejected Date & Time</b></td>
                                                                        <td>:</td>
                                                                        <td colspan="4"><?php echo date('d-m-Y H:i:s', strtotime($approval->approver_report_dt)) != '' ? date('d-m-Y H:i:s', strtotime($approval->approver_report_dt)) : '-'; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="tdlabel"><b>Approved/Rejected By</b></td>
                                                                        <td>:</td>
                                                                        <td><?php echo ($approval->approver_name != '') ? $approval->approver_name : '-'; ?></td>

                                                                        <td class="tdlabel"><b>CA Completed By</b></td>
                                                                        <td class="title-colon">:</td>
                                                                        <td><?php echo ($approval->assigner_name != '') ? $approval->assigner_name : '-'; ?></td>

                                                                    </tr>

                                                                    <tr>
                                                                        <?php if ($approval->approval_type_id == 2) { ?>
                                                                            <td class="tdlabel"><b>Rejected for Reason</b></td>
                                                                            <td>:</td>
                                                                            <td colspan="4"><?php echo ($approval->fk_approver_rej_desc != '') ? $approval->fk_approver_rej_desc : '-'; ?></td>
                                                                        <?php } else if ($approval->approval_type_id == 1) { ?>
                                                                            <td class="tdlabel"><b>Approved Reason</b></td>
                                                                            <td>:</td>
                                                                            <td colspan="4"><?php echo ($approval->fk_approver_app_desc != '') ? $approval->fk_approver_app_desc : '-'; ?></td>
                                                                        <?php } ?>
                                                                    </tr>

                                                                <?php } ?>
                                                                </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if (isset($getObsApprovalFinal) && !empty($getObsApprovalFinal) && in_array($user_type, $obsPermission['view_approve_final_log'])) { ?>
                                            <div class="card ">
                                                <div class="card-body">
                                                    <div class="card-header card-header-inner ">
                                                        PC/OE - HSSE Manager Approval Log Details<span class="float-right"></span>
                                                    </div>
                                                    <div class="row viewpage">
                                                        <div class="col-12 table-responsive ">
                                                            <?php
                                                            foreach ($getObsApprovalFinal as $appr => $approval) {

                                                            ?>
                                                                <table class="table table-striped approval" style="border: 1px solid #949699;">
                                                                    <tr>
                                                                        <td class="tdlabel"><b>Status</b></td>
                                                                        <td>:</td>
                                                                        <td>
                                                                            <?php if ($approval->approval_type_id == 3): ?>
                                                                                <span class="badge bg-success">Approved</span>
                                                                            <?php else: ?>
                                                                                <span class="badge bg-danger">Rejected</span>
                                                                            <?php endif; ?>

                                                                        </td>
                                                                        <td class="tdlabel"><b>Approved/Rejected Date & Time</b></td>
                                                                        <td>:</td>
                                                                        <td colspan="4"><?php echo date('d-m-Y H:i:s', strtotime($approval->approver_report_dt)) != '' ? date('d-m-Y H:i:s', strtotime($approval->approver_report_dt)) : '-'; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="tdlabel"><b>Approved/Rejected By</b></td>
                                                                        <td>:</td>
                                                                        <td><?php echo ($approval->approver_name != '') ? $approval->approver_name : '-'; ?></td>

                                                                        <td class="tdlabel"><b>CA Completed By</b></td>
                                                                        <td class="title-colon">:</td>
                                                                        <td><?php echo ($approval->assigner_name != '') ? $approval->assigner_name : '-'; ?></td>

                                                                    </tr>

                                                                    <tr>
                                                                        <?php if ($approval->approval_type_id == 4) { ?>
                                                                            <td class="tdlabel"><b>Rejected for Reason</b></td>
                                                                            <td>:</td>
                                                                            <td colspan="4"><?php echo ($approval->fk_approver_rej_desc != '') ? $approval->fk_approver_rej_desc : '-'; ?></td>
                                                                        <?php } else if ($approval->approval_type_id == 3) { ?>
                                                                            <td class="tdlabel"><b>Approved Reason</b></td>
                                                                            <td>:</td>
                                                                            <td colspan="4"><?php echo ($approval->fk_approver_app_desc != '') ? $approval->fk_approver_app_desc : '-'; ?></td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                </table>
                                                            <?php } ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if (isset($getObsActionTaken) && !empty($getObsActionTaken) && in_array($user_type, $obsPermission['view_action_log'])) { ?>
                                            <div class="card ">
                                                <div class="card-body">
                                                    <div class="card-header card-header-inner ">
                                                        Action Taken Log Details<span class="float-right"></span>
                                                    </div>
                                                    <div class="row viewpage">
                                                        <div class="col-12 table-responsive ">
                                                            <?php
                                                            foreach ($getObsActionTaken as $appr => $action) {

                                                            ?>
                                                                <table class="table table-striped approval" style="border: 1px solid #949699;">
                                                                    <tr>
                                                                        <td class="tdlabel"><b>Assignee Name</b></td>
                                                                        <td>:</td>
                                                                        <td>
                                                                            <?php echo isset($action->assigner_name) ? $action->assigner_name : ''; ?>
                                                                        </td>
                                                                        <td class="tdlabel"><b>Assignee Role</b></td>
                                                                        <td>:</td>
                                                                        <td><?php echo ($action->assigner_role != '') ? $action->assigner_role : '-'; ?></td>

                                                                    </tr>

                                                                    <tr>
                                                                        <td class="tdlabel"><b>Assignee Designation</b></td>
                                                                        <td>:</td>
                                                                        <td><?php echo ($action->assigner_name_desig != '') ? $action->assigner_name_desig : '-'; ?></td>


                                                                        <td class="tdlabel"><b>Submitted Date</b></td>
                                                                        <td class="title-colon">:</td>
                                                                        <td><?php echo date('d-m-Y H:i:s', strtotime($action->fk_obs_ca_datetime)) != '' ? date('d-m-Y H:i:s', strtotime($action->fk_obs_ca_datetime)) : '-'; ?></td>

                                                                    </tr>


                                                                    <tr>
                                                                        <td class="tdlabel"><b>Description entered by Assignee</b></td>
                                                                        <td>:</td>
                                                                        <td colspan="4"><?php echo ($action->fk_obs_ca_desc != '') ? $action->fk_obs_ca_desc : '-'; ?></td>
                                                                    </tr>

                                                                    <?php if ($getuafterallimage != FALSE) { ?>
                                                                        <tr>
                                                                            <td class="tdlabel"><b>Uploaded Images</b></td>
                                                                            <td>:</td>
                                                                            <td colspan="4">
                                                                                <div class="row">

                                                                                    <?php

                                                                                    foreach ($getuafterallimage as $akey => $attachmnt) {
                                                                                        $uploadedIds = explode(',', $action->obs_att_ids);

                                                                                        if (in_array($attachmnt->obs_att_id, $uploadedIds)) {
                                                                                            $filepath = postData($attachmnt, 'obs_file_path');
                                                                                            $filename = postData($attachmnt, 'obs_filename');
                                                                                            $fileFullpath = BASE_URL . $filepath . $filename; ?>

                                                                                            <!-- Image Preview -->
                                                                                            <div class="col-md-3">
                                                                                                <img src="<?php echo $fileFullpath; ?>" alt="Uploaded Image" style="width: 100%; max-width: 200px; height: auto; border: 1px solid #ccc; margin-bottom: 10px;">
                                                                                            </div>
                                                                                    <?php }
                                                                                    } ?>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </table>
                                                            <?php } ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>


                                    <?php } ?>
                                    <?php

                                    // status detail start
                                    if ((((in_array($user_type, $obsPermission['view_ad'])) || (in_array($user_type, $obsPermission['view_supadmin']))  || (in_array($user_type, $obsPermission['view_assigner']))) && ($obs_app_status == 1 || $obs_app_status == 8))) {
                                    ?>
                                        <div class="card">
                                            <div class="card-header card-header-inner">
                                                Status Details<span class="float-right"></span>
                                            </div>

                                            <div class="card-body">
                                                <div class="row viewpage">
                                                    <div class="col-md-4" style="display:none;">
                                                        <label>Status <span class="error"> * </span></label>
                                                        <?php
                                                        $disabled = "";
                                                        $obs_app_status_data = "";
                                                        if ($obs_app_status != 1) {

                                                            if ($obs_app_status == 8) {
                                                                $obs_app_status_data = 4;
                                                            } else {
                                                                $obs_app_status_data = $obs_app_status_data;
                                                            }
                                                            $disabled = ($obs_app_status_data != 1) ? 'disabled' : '';
                                                        }
                                                        if ($editobtyp == 3) {
                                                            $obs_app_status_data = 3;
                                                        } else {
                                                            $obs_app_status_data = 4;
                                                        }
                                                        ?>
                                                        <select name="usee[obs_app_statusS]" id="obs_app_statusS" class="form-control" <?php echo $disabled; ?>>
                                                            <option value="">Select Status</option>
                                                            <option value="4" <?php if ($obs_app_status_data == '4') echo 'selected'; ?>>Accept</option>
                                                            <option value="3" <?php if ($obs_app_status_data == '3') echo 'selected'; ?>>Close</option>
                                                            <option value="2" <?php if ($obs_app_status_data == '2') echo 'selected'; ?>>Irrelevant</option>
                                                        </select>
                                                        <?php
                                                        echo form_hidden('usee[obs_app_status]', $obs_app_status);
                                                        ?>
                                                        <label class="error"><?php echo form_error('usee[obs_app_statusS]') ?></label>

                                                        <input type="hidden" id="obs_app_statusS" name="usee[obs_app_statusS" value="<?php echo  $obs_app_status_data; ?>" />
                                                    </div>

                                                    <div class="col-md-4 m-t-10 risk_rating">
                                                        <div class="form-group">
                                                            <label> Risk Rating<span class="error"> * </span></label>
                                                            <?php
                                                            $cmpdata = [
                                                                'class' => 'form-control select2',
                                                                'id' => 'obs_risk_id',
                                                                'checkSelect' => 'select2',
                                                            ];
                                                            echo form_dropdown('usee[obs_risk_id]', $risk_rating, $editRiskId, $cmpdata);
                                                            ?>
                                                            <span class="error"><?php echo form_error('usee[obs_risk_id]') ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 m-b-10 status_post">
                                                        <label>Assignee <span class="error"> * </span></label>
                                                        <select class="form-control specificdh select2" checkSelect="select2" name="usee[obs_assigner_id]" id="obs_assigner_id">
                                                            <option value="">Select Assignee</option>
                                                        </select>

                                                    </div>

                                                    <div class="col-md-4 status_post">

                                                        <label>Target Date <span class="error"> * </span></label><br>
                                                        <div>

                                                            <?php

                                                            $data = array(
                                                                'name' => 'usee[obs_assigner_target_date]',
                                                                'id' => 'obs_assigner_target_date',
                                                                'placeholder' => '',
                                                                'class' => 'form-control datepicker',
                                                                'autocomplete' => 'off',
                                                                'disabled' => true
                                                            );

                                                            echo form_input($data);
                                                            ?>

                                                        </div>
                                                        <input type="hidden" id="obs_assigner_target_date_hidden" name="usee[obs_assigner_target_date]" value="" />
                                                    </div>

                                                    <div class="col-sm-12 m-b-10">

                                                        <label>Remarks <span class="error"> * </span></label>
                                                        <?php
                                                        // $deschsse = isset($obs_supervisor_desc_ASS) && !empty($obs_supervisor_desc_ASS) ? $obs_supervisor_desc_ASS : "";
                                                        $cdeschs = array(
                                                            'name' => 'usee[assigner_desc]',
                                                            'id' => 'assigner_desc',
                                                            'placeholder' => 'Enter Remarks',
                                                            'class' => 'form-control',
                                                            'value' => "",
                                                            'rows' => 4,
                                                            'cols' => 4,
                                                            'autocomplete' => 'off'
                                                        );
                                                        echo form_textarea($cdeschs);
                                                        ?>


                                                    </div>



                                                </div>
                                            </div>
                                        </div>

                                    <?php }  ?>

                                    <?php if ((in_array($user_type, $obsPermission['fix']) && $obs_assigner_id == $userid && ($obs_app_status == 4 || $obs_app_status == 7)) ||
                                        (is_admin() && ($obs_app_status == 4 || $obs_app_status == 7)) ||
                                        (in_array($user_type, $obsPermission['view_ad']) && ($obs_app_status == 4 || $obs_app_status == 7))

                                    ) { ?>

                                        <div class="card ">
                                            <div class="card-header card-header-inner">
                                                Action Taken<span class="float-right"></span>
                                            </div>

                                            <div class="card-body">
                                                <div class="row viewpage">
                                                    <div class="col-sm-12">
                                                        <!-- textarea -->
                                                        <div class="form-group">
                                                            <label>Description<span class="error">*</span></label>
                                                            <?php
                                                            $desc = isset($obs_assigner_desc) && !empty($obs_assigner_desc) ? $obs_assigner_desc : "";
                                                            $cdesc = array(
                                                                'name' => 'useeafterfix[obs_assigner_desc]',
                                                                'id' => 'obs_assigner_desc',
                                                                'placeholder' => 'Enter Description',
                                                                'class' => 'form-control',
                                                                // 'value' => $desc,
                                                                'value' => '',
                                                                'rows' => 4,
                                                                'cols' => 4,
                                                                'autocomplete' => 'off'
                                                            );
                                                            echo form_textarea($cdesc);
                                                            ?>
                                                            <span class="error"><?php echo form_error('useeafterfix[obs_assigner_desc]') ?></span>
                                                            <input type="hidden" name="useeafterfix[obs_assigner_id]" value="<?php echo $obs_assigner_id; ?>">
                                                            <input type="hidden" name="useeafterfix[obs_assigner_type_id]" value="<?php echo $obs_assigner_type_id; ?>" />
                                                            <input type="hidden" name="useeafterfix[obs_assigner_des_id]" value="<?php echo $obs_assigner_des_id; ?>" />

                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">

                                                        <span class="btn btn-xs btn-success  float-right addMorcerti"><i class="fa fa-plus-square"> Add More</i></span>
                                                    </div>
                                                    <div class="col-md-12 ">


                                                        <div class="othercertiDet" certiRow-id="1">


                                                            <div class="row m-t-10 m-l-10 addMorecompet">

                                                                <div class="col-sm-4">
                                                                    <label>Image Upload </label>
                                                                    <div class="form-group">

                                                                        <div class="fileinput fileinput-new apprFileinput imgGroup" style="margin-top: -30px !important; " data-provides="fileinput">
                                                                            <div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput">
                                                                                <?php
                                                                                $useeFilepath = '';
                                                                                if ($useeFilepath != '') {
                                                                                ?>
                                                                                    <img src='<?php echo BASE_URL . $useeFilepath ?>' alt='<?php echo $useeimgname; ?>' style="height: 100%;" />
                                                                                <?php } ?>
                                                                            </div>
                                                                            <p class="mini-txt">(png, jpeg, jpg )</p>
                                                                            <div class="file-pop">
                                                                                <span class="text-green btn-file"><span class="photo fileinput-new" title="Add Image"><img class="imgupload" src='<?php echo BASE_URL("/assets/images/photo.png"); ?>' style=" width: 30%; " /></span>
                                                                                    <span class="fileinput-exists" title="Add Image"></span>
                                                                                    <input type="file" name="useeafterfix[]" class="atarfile" accept="image/*"></span>

                                                                                <button type="button" name="re" class="btn btn-nothing text-maroon fileinput-exists" data-dismiss="fileinput" title="Remove Image"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="other_user_img[]" value="<?php echo $useeFilepath; ?>">

                                                                    </div>
                                                                </div>


                                                            </div>

                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- approval 1 start -->
                                    <?php if ((in_array($user_type, $obsPermission['approve']) && ($obs_app_status == 5 || $obs_app_status == 10)) ||
                                        (is_admin() && ($obs_app_status == 5 || $obs_app_status == 10)) ||

                                        (in_array($user_type, $obsPermission['view_ad']) && ($obs_app_status == 5 || $obs_app_status == 10))

                                    ) { ?>

                                        <div class="card ">
                                            <div class="card-header card-header-inner">
                                                EPC E&S Manager Approve Details<span class="float-right"></span>
                                            </div>
                                            <div class="card-body">
                                                <div class="row viewpage">
                                                    <div class="col-md-4">
                                                        <label>Name </label>
                                                        <?php
                                                        $csorepbydatas = [
                                                            'name' => 'useeapprove[so_name]',
                                                            'class' => 'form-control',
                                                            'value' => $ca_so_name,
                                                            'autocomplete' => 'off',
                                                            'readonly' => TRUE
                                                        ];
                                                        echo form_input($csorepbydatas);
                                                        ?>

                                                    </div>
                                                    <input type="hidden" name="useeapprove[fk_obs_so_login_id]" value="<?php echo $ca_fk_obs_so_login_id; ?>">

                                                    <div class="col-md-4">

                                                        <label>Designation </label>
                                                        <?php
                                                        $csopositiondata = array(
                                                            'name' => 'useeapprove[so_desg_name]',
                                                            'id' => 'obs_by_des',
                                                            'placeholder' => 'Enter Designation',
                                                            'class' => 'form-control',
                                                            'value' => $ca_so_desg_name,
                                                            'readonly' => TRUE,
                                                            'autocomplete' => 'off'
                                                        );

                                                        echo form_input($csopositiondata);
                                                        ?>

                                                    </div>
                                                    <input type="hidden" name="useeapprove[fk_obs_so_des_id]" value="<?php echo $ca_fk_obs_so_des_id; ?>">
                                                    <input type="hidden" name="useeapprove[fk_obs_so_role_id]" value="<?php echo $ca_fk_obs_so_role_id; ?>">
                                                    <div class="col-md-4 m-b-10">
                                                        <label> Date and Time </label>
                                                        <?php
                                                        $csodatetimedatas = [
                                                            'name' => 'useeapprove[fk_obs_so_datetime]',
                                                            'class' => 'form-control',
                                                            'value' => $ca_fk_obs_so_datetime,
                                                            'autocomplete' => 'off',
                                                            'readonly' => TRUE
                                                        ];
                                                        echo form_input($csodatetimedatas);
                                                        ?>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>Reason<span class="error">*</span></label>
                                                            <?php

                                                            $cdesc = array(
                                                                'name' => 'useeapprove[obs_hsse_es_type_id]',
                                                                'id' => 'obs_hsse_es_type_id',
                                                                'placeholder' => 'Enter Reason',
                                                                'class' => 'form-control',
                                                                // 'value' => $reason,
                                                                'value' => '',
                                                                'rows' => 4,
                                                                'cols' => 4,
                                                                'autocomplete' => 'off'
                                                            );
                                                            echo form_textarea($cdesc);
                                                            ?>
                                                            <span class="error"><?php echo form_error('useeapprove[obs_hsse_es_type_id]') ?></span>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="useeapprove[aisi_login_id]" value="<?php echo $obs_assigner_id; ?>" />
                                                    <input type="hidden" name="useeapprove[aisi_role_id]" value="<?php echo $obs_assigner_type_id; ?>" />
                                                    <input type="hidden" name="useeapprove[aisi_report_dt]" value="<?php echo date('Y-m-d H:i:s'); ?>" />
                                                    <input type="hidden" name="useeapprove[aisi_desc]" value="<?php echo $obs_assigner_desc; ?>" />
                                                    <input type="hidden" name="useeapprove[aisi_target_date]" value="<?php echo $obs_supervisor_date_FINAL_TARGET; ?>" />

                                                </div>
                                            </div>


                                            <div class="form-group m-t-10" style="text-align: center;">
                                                <div class="col-sm-offset-2 col-sm-12">

                                                    <input type="submit" class="btn btn-success " name="useeapprove[approve]" value="Approve" />
                                                    <input type="submit" class="btn btn-danger " name="useeapprove[reject]" value="Reject" />

                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>
                                    <!-- approval 1 end -->

                                    <!-- approval 2 start -->
                                    <?php if ((in_array($user_type, $obsPermission['approve_final']) && $obs_app_status == 9) ||
                                        (is_admin() && $obs_app_status == 9) ||

                                        (in_array($user_type, $obsPermission['view_ad']) && $obs_app_status == 9)

                                    ) { ?>

                                        <div class="card ">
                                            <div class="card-header card-header-inner">
                                                PC/OE - HSSE Manager Approve Details<span class="float-right"></span>
                                            </div>
                                            <div class="card-body">
                                                <div class="row viewpage">
                                                    <div class="col-md-4">
                                                        <label>Name </label>
                                                        <?php
                                                        $csorepbydatas = [
                                                            'name' => 'useeapprove[so_name]',
                                                            'class' => 'form-control',
                                                            'value' => $ca_so_name,
                                                            'autocomplete' => 'off',
                                                            'readonly' => TRUE
                                                        ];
                                                        echo form_input($csorepbydatas);
                                                        ?>

                                                    </div>
                                                    <input type="hidden" name="useeapprove[fk_obs_so_login_id]" value="<?php echo $ca_fk_obs_so_login_id; ?>">

                                                    <div class="col-md-4">

                                                        <label>Designation </label>
                                                        <?php
                                                        $csopositiondata = array(
                                                            'name' => 'useeapprove[so_desg_name]',
                                                            'id' => 'obs_by_des',
                                                            'placeholder' => 'Enter Designation',
                                                            'class' => 'form-control',
                                                            'value' => $ca_so_desg_name,
                                                            'readonly' => TRUE,
                                                            'autocomplete' => 'off'
                                                        );

                                                        echo form_input($csopositiondata);
                                                        ?>

                                                    </div>
                                                    <input type="hidden" name="useeapprove[fk_obs_so_des_id]" value="<?php echo $ca_fk_obs_so_des_id; ?>">
                                                    <input type="hidden" name="useeapprove[fk_obs_so_role_id]" value="<?php echo $ca_fk_obs_so_role_id; ?>">
                                                    <div class="col-md-4 m-b-10">
                                                        <label> Date and Time </label>
                                                        <?php
                                                        $csodatetimedatas = [
                                                            'name' => 'useeapprove[fk_obs_so_datetime]',
                                                            'class' => 'form-control',
                                                            'value' => $ca_fk_obs_so_datetime,
                                                            'autocomplete' => 'off',
                                                            'readonly' => TRUE
                                                        ];
                                                        echo form_input($csodatetimedatas);
                                                        ?>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>Reason<span class="error">*</span></label>
                                                            <?php

                                                            $cdesc = array(
                                                                'name' => 'useeapprove[obs_hsse_type_id]',
                                                                'id' => 'obs_hsse_type_id',
                                                                'placeholder' => 'Enter Reason',
                                                                'class' => 'form-control',
                                                                // 'value' => $reason,
                                                                'value' => '',
                                                                'rows' => 4,
                                                                'cols' => 4,
                                                                'autocomplete' => 'off'
                                                            );
                                                            echo form_textarea($cdesc);
                                                            ?>
                                                            <span class="error"><?php echo form_error('useeapprove[obs_hsse_type_id]') ?></span>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="useeapprove[aisi_login_id]" value="<?php echo $obs_assigner_id; ?>" />
                                                    <input type="hidden" name="useeapprove[aisi_role_id]" value="<?php echo $obs_assigner_type_id; ?>" />
                                                    <input type="hidden" name="useeapprove[aisi_report_dt]" value="<?php echo date('Y-m-d H:i:s'); ?>" />
                                                    <input type="hidden" name="useeapprove[aisi_desc]" value="<?php echo $obs_assigner_desc; ?>" />

                                                    <input type="hidden" name="useeapprove[aisi_target_date]" value="<?php echo $obs_supervisor_date_FINAL_TARGET; ?>" />

                                                </div>
                                            </div>


                                            <div class="form-group m-t-10" style="text-align: center;">
                                                <div class="col-sm-offset-2 col-sm-12">

                                                    <input type="submit" class="btn btn-success " name="useeapprove[approve]" value="Approve" />
                                                    <?php
                                                    if ($editobtype != SAFE_OBS) { ?>
                                                        <input type="submit" class="btn btn-danger " name="useeapprove[reject]" value="Reject" />
                                                    <?php } ?>

                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>
                                    <!-- approval 2 end -->

                                    <?php if (

                                        (((in_array($user_type, $obsPermission['view_assigner']))) && ($obs_app_status == 1 || $obs_app_status == 8))

                                        || ((in_array($user_type, $obsPermission['approve']) && $obs_app_status == 4))
                                        || ((in_array($user_type, $obsPermission['fix']) && $obs_assigner_id == $userid && ($obs_app_status == 4 || $obs_app_status == 7)) ||

                                            (is_admin() && ($obs_app_status == 4 || $obs_app_status == 7 || $obs_app_status == 1))

                                            || (in_array($user_type, $obsPermission['view_ad']) && ($obs_app_status == 4 || $obs_app_status == 7 || $obs_app_status == 1 || $obs_app_status == 8)))

                                    ) { ?>



                                        <div class="form-group m-t-10" style="text-align: center;">
                                            <div class="col-sm-offset-2 col-sm-12">

                                                <?php
                                                $data = array('id' => 'submit', 'type' => 'submit', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> SUBMIT', 'class' => 'btn btn-primary');
                                                echo form_button($data);
                                                ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php echo form_close(); ?>

                                </div>
                            </div>
                            <!-- /.card -->

                        </div>
                        <!--/.col (left) -->

                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
<script type="text/javascript">
    function dateData() {

        $(".datepicker").datepicker({
            autoclose: true,
            startDate: new Date(),
            format: 'dd-mm-yyyy'
        }).on('changeDate', function(selected) {

            var minDate = new Date(selected.date.valueOf());

        });

    }
    $(document).ready(function() {

        dateData();
        setTargetDate();

        function setTargetDate() {
            var riskRating = $('#obs_risk_id').val(); // Get selected risk rating value
            var targetDate = new Date(); // Get current date

            // Adjust target date based on risk rating
            if (riskRating == 1) {
                // Low - Add 10 days
                targetDate.setDate(targetDate.getDate() + 10);
            } else if (riskRating == 2) {
                // Medium - Add 5 days
                targetDate.setDate(targetDate.getDate() + 5);
            } else if (riskRating == 3) {
                // High - Add 48 hours
                targetDate.setHours(targetDate.getHours() + 48);
            } else {
                // Reset target date if no valid risk rating
                $('#obs_assigner_target_date').val('');
                return;
            }
            // Format the target date to 'dd-mm-yyyy'
            var formattedDate = ('0' + targetDate.getDate()).slice(-2) + '-' +
                ('0' + (targetDate.getMonth() + 1)).slice(-2) + '-' +
                targetDate.getFullYear();
            $('#obs_assigner_target_date').val(formattedDate);
            $('#obs_assigner_target_date').datepicker('update', formattedDate);
            $('#obs_assigner_target_date_hidden').val(formattedDate);
        }

        $('#obs_risk_id').on('change', function() {
            setTargetDate();
        });
        $.validator.addMethod('accept_address', function(value) {
            return /^[a-zA-Z0-9\/\\n\s,.'-_*()+&^$#@!% ]{1,}$/.test(value);
        }, "Please Enter a valid data");

        $.validator.addMethod('filesize', function(value, element, param) {

            return this.optional(element) || (Math.round(element.files[0].size / (1024 * 1024)) <= 5)
        }, 'File size must be less than 5MB');

        $.validator.addClassRules("atarfile", {
            accept: "png|jpe?g|gif",
            required: false,
            filesize: true
        });

        $("#company-profile").validate({
            ignore: [],
            success: function(error) {
                error.removeClass("error");
                error.addClass("d-none");
            },

            rules: {

                "usee[obs_app_statusS]": {
                    required: false
                },
                "usee[obs_app_statusS]": {
                    required: false
                },
                "usee[obs_risk_id]": {
                    required: true,
                },
                "usee[obs_assigner_id]": {
                    required: true,
                },
                "usee[obs_assigner_target_date]": {
                    required: true,
                },
                "useeafterfix[obs_assigner_desc]": {
                    required: true,
                    accept_address: true
                    //    programming_char:true
                },
                "useeapprove[obs_hsse_es_type_id]": {
                    required: true,
                    accept_address: true
                },
                "usee[assigner_desc]": {
                    required: true,
                },

            },
            messages: {
                "usee[obs_app_statusS]": {
                    required: "Status is required"
                },
                "usee[obs_risk_id]": {
                    required: "Risk Rating is required",
                },
                "usee[obs_assigner_id]": {
                    required: "Assignee is required"
                },
                "useeafterfix[obs_assigner_desc]": {
                    required: "Description is required"
                },
                "useeapprove[obs_hsse_es_type_id]": {
                    required: "Reason is required"
                },
                "usee[assigner_desc]": {
                    required: "Remarks is required"
                },
                "usee[obs_assigner_target_date]": {
                    required: "Target Date is required"
                },


            },
            errorPlacement: function(error, element) {

                if (element.attr('checkSelect') == 'select2') {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                    element.closest(".imgGroup").last().append(error);
                }
            },
            submitHandler: function(form) {
                swal({
                    title: "Please wait..",
                    imageUrl: loadingImg,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                form.submit();
            }

        });
    });
</script>
<script>
    var eventComp = '<?php echo $editComp_id; ?>';
    if (eventComp != '') {
        getAssigneeDetails(eventComp);
    }

    function getAssigneeDetails(eventComp) {

        var url = "<?php echo BASE_URL() . "Main/CompanyEmployeeDetails" ?>";
        var data = {
            company_id: eventComp,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            cache: false,
            success: function(data) {
                $('#obs_assigner_id').html(data);
                var specificdh = '<?php echo $obs_assigner_id; ?>';
                if (specificdh != '') {
                    $('#obs_assigner_id').val(specificdh);
                    $('#obs_assigner_id option[value=' + specificdh + ']').attr('selected', 'selected');
                }

            }
        });
    }


    $(document).on('click', '.addMorcerti', function() {

        var baseUrl = '<?php echo BASE_URL; ?>'
        var lastUsee = $(this).parents().eq(2).next().find(".othercertiDet").last().attr("certiRow-id");

        var newUseerow = parseInt(lastUsee) + 1;
        //var html = '<div class="col-sm-4" style="margin-top:30px;">\n\
        var html = '<div class="col-sm-4" style="">\n\
  <span class="float-right removeCertinew">\n\
<i class="fa fa-trash"></i>\n\
</span>\n\
<div class="form-group">\n\
<div class="fileinput fileinput-new apprFileinput imgGroup" style="" data-provides="fileinput">\n\
<div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput">\n\
</div>\n\
<p class="mini-txt">(png, jpeg, jpg )</p>\n\
<div class="file-pop">\n\
<span class="text-green btn-file">\n\
<span class="photo fileinput-new" title="Add Image">\n\
<img class="imgupload" src="' + baseUrl + '/assets/images/photo.png" style=" width: 30%; ">\n\
</span>\n\
<span class="fileinput-exists" title="Add Image">\n\
</span>\n\
<input type="file" name="useeafterfix[]" class="atarfile" accept="image/*">\n\
</span>\n\
<button type="button" name="re" class="btn btn-nothing text-maroon fileinput-exists" data-dismiss="fileinput" title="Remove Image">\n\
<i class="fa fa-times-circle-o" aria-hidden="true"></i>\n\
</button>\n\
</div>\n\
</div>\n\
<input type="hidden" name="other_user_img[]" value="">\n\
</div>\n\
</div>\n\
';
        $(".addMorecompet").append(html);

    })
    $(document).on('click', '.removeCertinew', function() {
        $(this).closest('.col-sm-4').remove();
    })
</script>