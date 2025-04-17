<?php
$logo = '<img src="' . PDF_IMG_PATH . 'company_logo/Logo.png" style="width:200px;height:100px;">';
$logo1 = '<img src="' . PDF_IMG_PATH . 'company_logo/LOGO-LUMUT-PORTju.png" style="float:right;width:00px;height:35px;">';

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

<html>

<head>
    <style>
        * {
            font-family: calibri;
            color: #333;
            font-size: 11px;
            line-height: 15px;
        }

        @page {
            size: auto;
            margin-header: 0mm;
            margin-footer: 3mm;
            odd-header-name: html_myHeader1;
            even-header-name: html_myHeader2;
            odd-footer-name: html_myFooter1;
            even-footer-name: html_myFooter2;
        }

        @page noheader {
            odd-header-name: _blank;
            even-header-name: _blank;
            odd-footer-name: _blank;
            even-footer-name: _blank;
        }

        .full-width {
            width: 100%;
            font-size: 11px;
        }

        .bg-grey {
            background-color: #f1f1f1;
            border: 1px solid #333;
        }

        .padding-5 {
            padding: 5px;
        }

        .innertable td {
            padding: 5px 10px 5px;
        }

        .max-width {
            width: 95%;
            margin: 0 auto;
        }

        .bg-light-gr {
            background: #00bcd5;
            color: #fff;
        }

        .bg-light-green {
            background: #8cc34b;
            color: #fff;
        }

        .bg-dark-green {
            background: #009688;
            color: #fff;
        }

        .witness {
            margin-bottom: 20px;
        }

        .witness.even tr td .btn {
            background: #8cc34b !important;
            color: #fff;
        }

        .witness.odd tr td .btn {
            background: #009688 !important;
            color: #fff;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        .bg-light-blue-active {
            background-color: #357ca5 !important;
        }

        .pull-right {
            text-align: right;
        }

        .innertable {
            margin-bottom: 10px;
        }

        h3 {
            font-size: 0.9em;
            font-weight: bold;
        }

        .uppertext {
            text-transform: uppercase;
        }

        p {
            font-size: 11px;
        }

        p.desc {
            font-size: 1em;
            text-indent: 30px !important;
        }

        .table {
            border: 1px solid #bfbebe;
        }

        .inj_per tr:nth-child(odd) {
            background-color: rgba(63, 147, 187, 0.10) !important;
        }

        .inj_det tr:nth-child(odd) {
            background-color: rgba(59, 188, 212, 0.10) !important;
        }

        .witns tr:nth-child(odd) {
            background-color: rgba(241, 146, 52, 0.10) !important;
        }

        .summ tr:nth-child(odd) {
            background-color: rgb(228, 228, 228) !important;
        }

        .table td {
            padding: 6px;
        }

        .boxtitle {

            font-weight: bold;
            color: #333 !important;
        }

        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {

            text-align: justify;
        }

        .texthide {
            display: none;
        }

        body {
            color-adjust: exact;
            -webkit-print-color-adjust: exact;

        }

        .label_high {
            background-color: #ff0000 !important;

        }

        .label_low {
            background-color: #008f11 !important;
        }

        .label_medium {
            background-color: #f2ff00 !important;
        }

        .labels {

            padding: 10px;
            font-weight: bold;

            text-transform: uppercase;
        }


        .web {

            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            line-height: 25px
        }

        .web td {
            font-size: 11px;
            border: 1px solid #D9E9F4;
            padding: 5px 5px 5px 5px;
        }

        .web tr:nth-child(odd) {
            background-color: rgba(63, 147, 187, 0.10) !important;
        }

        .web th {
            font-size: 12px;
            text-align: left;
            padding: 3px 0px 3px 10px;
            color: #ffffff;
            background-color: #316191;
            border: 1px solid #316191;
        }

        .web tr.alt td {
            color: #000000;
            background-color: #316191;
        }



        .panelheading {
            font-size: 11px;
        }

        .panel-body {
            padding: 15px;
        }

        .col-md-3-p {
            width: 22% !important;
        }

        .col-md-12 {
            width: 100% !important;
        }

        .box-header {

            display: block;
            padding: 10px;
            position: relative;
        }

        .box-header.with-border {
            border-radius: 0px !important;
        }

        .box-header.with-border {
            border-bottom: 1px solid #007cb7 !important;
        }

        .col-print {
            padding: 20px !important;
        }

        .row {
            margin-right: -10px;
            margin-left: -10px;
        }

        .col-md-6-p {
            width: 42% !important;
        }

        .web td p.fjust {
            text-align: justify !important;
        }

        .web td h5.fright {

            text-align: right !important;
        }

        .txt-uppercase {
            text-transform: uppercase;

        }

        .col-md-3-pdf {
            margin: 5px;
            float: left;
            width: 45% !important;
            border: 2px solid #000;
            padding: 5px;
        }

        .tab-border table,
        .tab-border table th,
        .tab-border table td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .tab-border table th,
        .tab-border table td {
            padding: 15px;
        }

        .fsize {
            font-size: 11px;
        }

        .doc-border table,
        .doc-border table th,
        .doc-border table td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
        }

        .doc-border table th,
        .doc-border table td {
            padding: 5px;
        }

        .tcenter {
            text-align: center;
        }
    </style>

</head>

<body style="font-family:'calibri';">
    <htmlpageheader name="myHeader1" style="display:none">
        <table border="0" style="width:100%;border:0;background-color: #FFF;padding-top:10px;padding-bottom:10px;">
            <tr style="">
                <td border="0" style="width:20%;float:left;text-align:left;"><?php echo $logo; ?></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 24px;font-weight: bold">Observation Report</td>


            </tr>

        </table>
        <table border="0" style="width:100%;border:0;border-top: 4px solid #000;">
            <tr>
                <td border="0" style="width:30%;"></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 12px;font-weight: bold"><?php echo $obs_id; ?></td>
            </tr>
        </table>

    </htmlpageheader>
    <htmlpageheader name="myHeader2" style="display:none">
        <table border="0" style="width:100%;border:0;background-color: #FFF;padding-top:10px;padding-bottom:10px;">
            <tr style="">
                <td border="0" style="width:20%;float:left;text-align:left;"><?php echo $logo; ?></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 24px;font-weight: bold">Observation Report</td>


            </tr>

        </table>
        <table border="0" style="width:100%;border:0;border-top: 4px solid #000;">
            <tr>
                <td border="0" style="width:30%;"></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 12px;font-weight: bold"><?php echo $obs_id; ?></td>
            </tr>
        </table>

    </htmlpageheader>

    <htmlpagefooter name="myFooter1" style="display:none">
        <table width="100%" style="width:100%;border:0;background-color: #FFF;border-top: 4px solid #000;padding-top:10px;padding-bottom:10px;">
            <tr>
                <td width="33%">
                    <span style="font-style: italic;">{DATE d-m-Y}</span>
                </td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">

                </td>
                <td width="33%" style="text-align: right;">
                    {PAGENO}/{nbpg}
                </td>
            </tr>
        </table>
    </htmlpagefooter>

    <htmlpagefooter name="myFooter2" style="display:none">
        <table width="100%" style="width:100%;border:0;background-color: #FFF;border-top: 4px solid #000;padding-top:10px;padding-bottom:10px;">
            <tr>
                <td width="33%">My document</td>
                <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right;">{PAGENO}/{nbpg}</td>
            </tr>
        </table>
    </htmlpagefooter>


    <table style="width:100%;padding-bottom: 10px;margin-top: 10px">
        <tr>
            <td style="width:100%;background-color: #004EA3  !important;color:#fff;font-weight:bold;display: inline-block;
                padding: 5px 5px 5px;">
                Observation Details
            </td>

        </tr>
    </table>

    <table style="width:100%;" class="table summ full-width innertable">
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

            <td class="tdlabel"><b>EPC </b></td>
            <td class="title-colon">:</td>
            <td><?php echo ($epc_name != '') ? $epc_name : '-'; ?></td>
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
            <td><?php echo ($obs_date != '') ? $obs_date : '-'; ?></td>


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
    <div style="clear:both"></div>
    <?php
    if ($getuincidentimage != FALSE) { ?>
        <table style="width:100%;" class="table summ full-width innertable">

            <tr>
                <td class="tdlabel"><b>Uploaded Images</b></td>
                <td>:</td>
                <td>
                    <div class="row">
                        <?php
                        foreach ($getuincidentimage as $akey => $attachmnt) {

                            $filepath = postData($attachmnt, 'obs_file_path');
                            $filename = postData($attachmnt, 'obs_filename');
                            $fileFullpath = BASE_URL . $filepath . $filename; ?>

                            <!-- Image Preview -->
                            <div class="col-md-3">
                                <img src="<?php echo $fileFullpath; ?>" alt="Uploaded Image" style="width: 100%; max-width: 200px; height: auto; border: 1px solid #ccc; margin-bottom: 10px;">
                            </div>
                        <?php
                        } ?>
                    </div>
                </td>
            </tr>

        </table>
    <?php } ?>

    <div style="clear:both"></div>
    <?php if ($obs_app_status >= 2) { ?>
        <?php if ((isset($getObsReassign) && !empty($getObsReassign)) || (isset($getObsAssign) && !empty($getObsAssign))) { { ?>

                <table style="width:100%;padding-bottom: 10px;margin-top: 10px;">
                    <tr>
                        <td style="width:100%;background-color: #004EA3;color:#FFF;font-weight:bold;display: inline-block;
                padding: 5px 5px 5px;">
                            Assign / Re-Assign Log Details
                        </td>

                    </tr>
                </table>

                <?php
                if (isset($getObsAssign) && !empty($getObsAssign)) { ?>
                    <table style="width:100%;padding-bottom: 10px;margin-top: 10px;">
                        <tr>
                            <td style="width:100%;background-color: #c8094e;color:#FFF;font-weight:bold;display: inline-block;
                padding: 5px 5px 5px;">
                                Assign Log Details
                            </td>
                        </tr>
                    </table>
                    <table style="width:100%;" class="table summ full-width innertable">
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
                            <td><?php echo ($getObsAssign->fk_obs_supervisor_desc != '') ? $getObsAssign->fk_obs_supervisor_desc : '-'; ?></td>
                        </tr>


                    </table>
                <?php
                }
                if (isset($getObsReassign) && !empty($getObsReassign)) {
                ?>
                    <table style="width:100%;padding-bottom: 10px;margin-top: 10px;">
                        <tr>
                            <td style="width:100%;background-color: #004EA3;color:#FFF;font-weight:bold;display: inline-block;
                padding: 5px 5px 5px;">
                                Re-Assign Log Details
                            </td>

                        </tr>
                    </table>

                    <?php
                    $i = 1;
                    foreach ($getObsReassign as $insDKeyRe1 => $InsDataRe1) {
                        $bfrKeyReval = $insDKeyRe1 - 1;
                    ?>
                        <table style="width:100%;" class="table summ full-width innertable">
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
                                <td><?php echo ($InsDataRe1->fk_obs_supervisor_desc != '') ? $InsDataRe1->fk_obs_supervisor_desc : '-'; ?></td>
                            </tr>

                        </table>
                <?php }
                } ?>

        <?php    }
        } ?>

    <?php } ?>

    <?php if (isset($getObsActionTaken) && !empty($getObsActionTaken) && in_array($user_type, $obsPermission['view_action_log'])) {   ?>
        <table style="width:100%;padding-bottom: 10px;margin-top: 10px;">
            <tr>
                <td style="width:100%;background-color: #004EA3;color:#FFF;font-weight:bold;display: inline-block;
                padding: 5px 5px 5px;">
                    Action Taken Details
                </td>

            </tr>
        </table>
        <?php foreach ($getObsActionTaken as $appr => $action) { ?>
            <table style="width:100%;" class="table summ full-width innertable">
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
                    <td><?php echo ($action->fk_obs_ca_desc != '') ? $action->fk_obs_ca_desc : '-'; ?></td>
                </tr>

                <?php if ($getuafterallimage != FALSE) { ?>
                    <tr>
                        <td class="tdlabel"><b>Uploaded Images</b></td>
                        <td>:</td>
                        <td>
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

            </table>
<?php }
            }
        } ?>

<?php if (isset($getObsApproval) && !empty($getObsApproval) && in_array($user_type, $obsPermission['view_approval_log'])) {  ?>
    <table style="width:100%;padding-bottom: 10px;margin-top: 10px;">
        <tr>
            <td style="width:100%;background-color: #004EA3;color:#FFF;font-weight:bold;display: inline-block;
                padding: 5px 5px 5px;">
                EPC E&S Manager Approval Details
            </td>

        </tr>
    </table>
    <?php foreach ($getObsApproval as $appr => $approval) { ?>
        <table style="width:100%;" class="table summ full-width innertable">
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
                <td><?php echo date('d-m-Y H:i:s', strtotime($approval->approver_report_dt)) != '' ? date('d-m-Y H:i:s', strtotime($approval->approver_report_dt)) : '-'; ?></td>
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
                    <td><?php echo ($approval->fk_approver_rej_desc != '') ? $approval->fk_approver_rej_desc : '-'; ?></td>
                <?php } else if ($approval->approval_type_id == 1) { ?>
                    <td class="tdlabel"><b>Approved Reason</b></td>
                    <td>:</td>
                    <td><?php echo ($approval->fk_approver_app_desc != '') ? $approval->fk_approver_app_desc : '-'; ?></td>
                <?php } ?>
            </tr>

        </table>
<?php }
} ?>

<?php if (isset($getObsApprovalFinal) && !empty($getObsApprovalFinal) && in_array($user_type, $obsPermission['view_approve_final_log'])) {   ?>
    <table style="width:100%;padding-bottom: 10px;margin-top: 10px;">
        <tr>
            <td style="width:100%;background-color: #004EA3;color:#FFF;font-weight:bold;display: inline-block;
                padding: 5px 5px 5px;">
                PC/OE - HSSE Manager Approval Details
            </td>

        </tr>
    </table>
    <?php foreach ($getObsApprovalFinal as $appr => $approval) { ?>
        <table style="width:100%;" class="table summ full-width innertable">
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
                <td><?php echo date('d-m-Y H:i:s', strtotime($approval->approver_report_dt)) != '' ? date('d-m-Y H:i:s', strtotime($approval->approver_report_dt)) : '-'; ?></td>
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
                    <td><?php echo ($approval->fk_approver_rej_desc != '') ? $approval->fk_approver_rej_desc : '-'; ?></td>
                <?php } else if ($approval->approval_type_id == 3) { ?>
                    <td class="tdlabel"><b>Approved Reason</b></td>
                    <td>:</td>
                    <td><?php echo ($approval->fk_approver_app_desc != '') ? $approval->fk_approver_app_desc : '-'; ?></td>
                <?php } ?>
            </tr>

        </table>
<?php }
} ?>



</body>

</html>