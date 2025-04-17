<?php

$tr_uni_id = postData($editData, 'tr_uni_id');
$tr_auto_id = postData($editData, 'tr_auto_id');
$Reporter = postData($editData, 'Reporter');
$desig = postData($editData, 'desig');
global $risk_rating, $tr_type_list, $owner_list, $owner_engineer_list, $EPC_list;
$owner_name = !empty(postData($editData, 'tr_owner_id')) ? $owner_list[postData($editData, 'tr_owner_id')] : '';
$owner_eng_name = !empty(postData($editData, 'tr_owner_eng')) ? $owner_engineer_list[postData($editData, 'tr_owner_eng')] : '';
$epc_name = !empty(postData($editData, 'tr_epc_id')) ? $EPC_list[postData($editData, 'tr_epc_id')] : '';
$comp_name = postData($editData, 'comp_name');
$area_name = postData($editData, 'area_name');
$building_name = postData($editData, 'building_name');
$dep_name = postData($editData, 'dep_name');
$proj_name = postData($editData, 'proj_name');
$trainAutoID = postData($editData, 'tr_ev_auto_id');

$editRiskId = postData($editData, 'tr_risk_id');

$risk_name = !empty($editRiskId) ? $risk_rating[$editRiskId] : '';

$tr_date = !empty(postData($editData, 'tr_date')) ? date('d-m-Y', strtotime(postData($editData, 'tr_date'))) : '';
$inj_name = postData($editData, 'inj_name');
$tr_desc = postData($editData, 'tr_desc');
$editComp_id = postData($editData, 'tr_comp_id');
$editobtype = postData($editData, 'tr_type_id');
$tr_type_name = postData($editData, 'tr_type_name');
$tr_cat_name = postData($editData, 'tr_cat_name');
$tr_start_date = postData($editData, 'tr_start_date');
$tr_end_date = postData($editData, 'tr_end_date');
$tr_start_time = postData($editData, 'tr_start_time');
$tr_end_time = postData($editData, 'tr_end_time');

$getProname = postData($editData, 'ATAR_PROJ_NAME');

$tr_report_datetime = date('d-m-Y H:i:s', strtotime(postData($editData, 'tr_report_datetime')));


$tr_supervisor_date = date('d-m-Y', strtotime(postData($editData, 'tr_supervisor_date', date('d-m-Y'))));

$tr_assigner_target_date_ACTUAL = date('d-m-Y', strtotime(postData($editData, 'tr_assigner_target_date')));
$tr_assigner_ca_submitted_date = !empty(postData($editData, 'tr_assigner_ca_submitted_date')) ? date('d-m-Y', strtotime(postData($editData, 'tr_assigner_ca_submitted_date'))) : '';

if ($tr_assigner_target_date_ACTUAL == '01-01-1970') {
    $tr_assigner_target_date_ACTUAL = "";
} else {
    $tr_assigner_target_date_ACTUAL = $tr_assigner_target_date_ACTUAL;
}

$tr_assigner_desc = postData($editData, 'tr_assigner_desc');

$tr_hsse_es_appr_rej_desc = postData($editData, 'tr_hsse_es_appr_rej_desc');
$tr_hsse_appr_rej_desc = postData($editData, 'tr_hsse_appr_rej_desc');

$Assignee = postData($editData, 'assignee');
$Supervisor = postData($editData, 'supervisor');
$Unithead = postData($editData, 'Unithead');
$tr_venue_desc = postData($editData, 'tr_venue_desc');



$tr_app_status = postData($editData, 'tr_app_status');


$tr_final_tar_date_time = postData($editData, 'tr_final_tar_date_time');

$tr_assigner_desc = postData($editData, 'tr_assigner_desc');


$ca_so_name = $_SESSION['user_details']['NAME'];
$ca_fk_tr_so_login_id = $_SESSION['userinfo']->LOGIN_ID;
$ca_so_desg_name =  $_SESSION['user_details']['DESIGNATION'];
$ca_fk_tr_so_des_id = $_SESSION['user_details']['DESIGNATIONID'];
$ca_fk_tr_so_role_id = $_SESSION['emp_details']->EMP_USERTYPE_ID;
$ca_fk_tr_so_datetime = date("d-m-Y H:i:s");

$reason = "";

$userid = getCurrentUserid();
$user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
global $trPermission;

$practical_reporter_session = postData($sessDataemp, 'EMP_NAME');
$practical_reporter_session_id =  $_SESSION['userinfo']->LOGIN_ID;


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

                                    <h4 class='card-title'>View Attendance </h4>
                                    <a href="javascript:history.back()"><button type="button" class="btn btn-primary backBtns"> Back</button></a>

                                </div>
                                <div class="card-body">
                                    <?php
                                    $getstatusid = isset($editData->tr_auto_id) && !empty($editData->tr_auto_id) ? $editData->tr_auto_id : '';
                                     ?>
                                        <?php echo form_open_multipart('training/training/saveAttendance/' . encryptval($getstatusid), 'class="form-horizontal company" id="company" novalidate'); ?>

                                    <div class="card ">
                                        <div class="card-header card-header-inner">
                                            Basic Details<span class="float-right"></span>
                                        </div>

                                        <div class="card-body">
                                            <div class="row viewpage">
                                                <div class="col-12 table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <tr>
                                                            <td class="tdlabel"><b>Training ID</b></td>
                                                            <td>:</td>
                                                            <td><?php echo ($tr_uni_id != '') ? $tr_uni_id : '-'; ?></td>
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
                                                            <td><?php echo ($tr_report_datetime != '') ? $tr_report_datetime : '-'; ?></td>



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
                                                          
                                                            <td class="tdlabel"><b>Venue Description</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td ><?php echo ($tr_venue_desc != '') ? $tr_venue_desc : '-'; ?></td>
                                                          
                                                            <td class="tdlabel"><b>Start Date</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td ><?php echo ($tr_start_date != '') ? $tr_start_date : '-'; ?></td>


                                                        </tr>

                                                        <tr>
                                                          
                                                            <td class="tdlabel"><b>End Date</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td ><?php echo ($tr_end_date != '') ? $tr_end_date : '-'; ?></td>
                                                          
                                                            <td class="tdlabel"><b>Start Time</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td ><?php echo ($tr_start_time != '') ? $tr_start_time : '-'; ?></td>


                                                        </tr>
                                                        <tr>
                                                             
                                                        <td class="tdlabel"><b>End Time</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td ><?php echo ($tr_end_time != '') ? $tr_end_time : '-'; ?></td>
                                                            <td class="tdlabel"><b>Training Type</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($tr_type_name != '') ? $tr_type_name : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                          
                                                        <td class="tdlabel"><b>Training Category</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td><?php echo ($tr_cat_name != '') ? $tr_cat_name : '-'; ?></td>
                                                 
                                                        </tr>

                                                    </table>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card ">
                                        <div class="card-header card-header-inner">
                                            Upload Attendence Method<span class="float-right"></span>
                                        </div>

                                        <div class="card-body">
                                            <div class="row viewpage">
                                                <div class="col-12 table-responsive">
                                                          <!-- Radio Buttons -->
                                                          <div class="form-group">
                                                                <label>
                                                                    <input type="radio" name="inspec[attendance_method]" value="1" 
                                                                        <?php echo (isset($editData->img_attendance_submit_method) && $editData->img_attendance_submit_method == 1) ? 'checked' : ''; ?>> By System
                                                                </label>
                                                                <label>
                                                                    <input type="radio" name="inspec[attendance_method]" value="2" 
                                                                        <?php echo (isset($editData->img_attendance_submit_method) && $editData->img_attendance_submit_method == 2) ? 'checked' : ''; ?>> By Manual
                                                                </label>
                                                            </div>
                                     
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="card ">
                                        <div class="card-header card-header-inner">
                                        <input type="hidden" name="inspec[trainer_login_id]" value="<?php echo $practical_reporter_session_id; ?>">
                                      
                                        <input type="hidden" name="inspec[training_auto_id]" value="<?php echo $trainAutoID; ?>">
                                        <input type="hidden" name="inspec[training_main_auto_id]" value="<?php echo $tr_auto_id; ?>">
                                            Attendance Details<span class="float-right"></span>
                                        </div>

                                        <div class="card-body">
                                            <div class="row viewpage">
                                                <div class="col-12 table-responsive">
                                                          <!-- Radio Buttons -->
                                                          <div id="by_system_section">
                                                                <?php if (isset($getEmployeedatas) && !empty($getEmployeedatas)) { ?>
                                                                    <div class="panel-body col-md-12">
                                                                        <div class="row m-t-10 m-l-10">
                                                                            <div class="row emplDetoth table-responsive" style="margin:10px;">
                                                                                <div class="col-sm-12">
                                                                                    <table id="" class="table table-bordered table-striped">
                                                                                        <thead style="background: linear-gradient(45deg, #5a5a59, #5a5a59); color: #ffffff;">
                                                                                            <tr>
                                                                                                <th> S.No </th>
                                                                                                <th> Employee / Visitor </th>
                                                                                                <th> Employee / Visitor Name </th>
                                                                                                <th> Company Name </th>
                                                                                                <th> Designation </th>
                                                                                                <th> Attendance (Present / Absent) </th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            foreach ($getEmployeedatas as $othKey => $othVal) {
                                                                                                $certiRowval = $othKey + 1;
                                                                                                $othCertiID = postData($othVal, 'fk_tr_emp_id');
                                                                                                $othtrainingID = postData($othVal, 'tr_ev_auto_id');
                                                                                                $empType = postData($othVal, 'emp_type');

                                                                                                // Employee Details
                                                                                                $empID = postData($othVal, 'EMP_ID');
                                                                                                $empName = postData($othVal, 'EMP_NAME');
                                                                                                $empDesig = postData($othVal, 'design_name');
                                                                                                $empCompany = postData($othVal, 'FN_COMP_NAME(Attendemp.EMP_COMP_ID)');

                                                                                                // Visitor Details
                                                                                                $visitorName = postData($othVal, 'tr_visitor_name');
                                                                                                $visitorDesig = postData($othVal, 'tr_visitor_desig');
                                                                                                $visitorCompany = postData($othVal, 'tr_visitor_cmp_name');

                                                                                                // Attendance Status
                                                                                                $attendanceID = postData($othVal, 'tr_attendence_status');
                                                                                                $aACCESS = ($attendanceID == 0) ? 0 : (($attendanceID == 1) ? 1 : 0);
                                                                                            ?>
                                                                                                <tr certiRow-id="<?php echo $certiRowval; ?>">
                                                                                                    <td>
                                                                                                        <?php echo $certiRowval; ?>
                                                                                                        <input type="hidden" name="nom[<?php echo $certiRowval; ?>][hse_training_emp_auto_id]" value="<?php echo $othCertiID; ?>">
                                                                                                        <input type="hidden" name="nom[<?php echo $certiRowval; ?>][training_auto_id]" value="<?php echo $othtrainingID; ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <?php echo ($empType == 1) ? 'Employee' : 'Visitor'; ?>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <?php echo ($empType == 1) ? $empName : $visitorName; ?>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <?php echo ($empType == 1) ? $empCompany : $visitorCompany; ?>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <?php echo ($empType == 1) ? $empDesig : $visitorDesig; ?>
                                                                                                    </td>
                                                                                                    <td style="width:15%">
                                                                                                        <?php
                                                                                                        $ACCESSDetails = [
                                                                                                            'id' => 'attendance_' . $certiRowval,
                                                                                                            'class' => 'form-control select2',
                                                                                                            'checkSelect' => 'select2',
                                                                                                            'required' => 'true'
                                                                                                        ];
                                                                                                        $ACCESSdata = [
                                                                                                            '' => 'Select Attendance',
                                                                                                            '0' => 'Absent',
                                                                                                            '1' => 'Present',
                                                                                                        ];
                                                                                                        echo form_dropdown('nom[' . $certiRowval . '][attendance]', $ACCESSdata, $aACCESS, $ACCESSDetails);
                                                                                                        ?>
                                                                                                        <span class="error" id="assetDetail_error"></span>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            <?php } ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>    
                                                            
                                                                        <!-- File Upload Section (Hidden by default) -->
                                                                        <div id="by_upload_section" style="display: none;">
                                                                    <div class="form-group">
                                                                        <div class="fileinput fileinput-new apprFileinput imgGroup" data-provides="fileinput">
                                                                            <!-- Image Preview Container -->
                                                                            <div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput">
                                                                                <?php if (!empty($editData->tr_file_path)): ?>
                                                                                    <!-- If an image exists, display it -->
                                                                                    <img src="<?php echo BASE_URL($editData->tr_file_path); ?>" alt="Uploaded Image" style="max-width: 100%; max-height: 150px;" />
                                                                                <?php else: ?>
                                                                                    <!-- If no image exists, show the default placeholder -->
                                                                                    <img class="imgupload" src="<?php echo BASE_URL('/assets/images/photo.png'); ?>" style="width: 30%;" />
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <!-- File Format Hint -->
                                                                            <p class="">( png, jpeg, jpg )</p>
                                                                            <!-- File Input and Actions -->
                                                                            <div class="file-pop" style="margin-left: 10% !important;">
                                                                                <span class="text-green btn-file">
                                                                                    <span class="photo fileinput-new" title="Add Image">
                                                                                        <img class="imgupload" src="<?php echo BASE_URL('/assets/images/photo.png'); ?>" style="width: 30%;" />
                                                                                    </span>
                                                                                    <span class="fileinput-exists" title="Change Image"></span>
                                                                                    <input type="file" name="attendance_file" class="atarfile" id="attendance_file" accept="image/png, image/jpeg">
                                                                                </span>
                                                                                <button type="button" name="re" class="btn btn-nothing text-maroon fileinput-exists" data-dismiss="fileinput" title="Remove Image">
                                                                                    <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                       </div>
                                                  </div>
                                             </div>
                                         </div> 
                                          <div class="form-group m-t-10" style="text-align: center;">
                                                <div class="col-sm-offset-2 col-sm-12"> 

                                                            <?php
                                                            $data = array('id' => 'submitnn', 'type' => 'button', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> SUBMIT', 'class' => 'submitNom btn btn-primary');
                                                            echo form_button($data);
                                                            ?>
                                                </div>
                                            </div>
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
$(document).ready(function () {

    function toggleAttendanceMethod() {
        var selectedValue = $('input[name="inspec[attendance_method]"]:checked').val();
        
        if (selectedValue == '1') {
            $('#by_system_section').show();
            $('#by_upload_section').hide();
        } else if (selectedValue == '2') { 
            $('#by_system_section').hide();
            $('#by_upload_section').show();
        }
    }

    // Attach event listener
    $('input[name="inspec[attendance_method]"]').change(function() {
        toggleAttendanceMethod();
    });

    // Trigger on page load after everything is set
    toggleAttendanceMethod();
$(document).on('click','.submitNom',function(){
swal({
title: "Are you sure ",
text: "<b>You want to submit the Attendance Report</b>",
type: "warning",
showCancelButton: true,
confirmButtonClass: "btn-success",
closeButtonClass: "btn-danger",
confirmButtonText: "Yes",
cancelButtonText: "No",
closeOnConfirm: true,
closeOnCancel: true,
html: true,
},
function (isConfirm) {
if (isConfirm) {
$(".company").validate({
ignore: [],
success: function (error) {
error.removeClass("error");
error.addClass("d-none");


                
},

errorPlacement: function (error, element) {

if (element.hasClass('select2')) {
error.insertAfter(element.next('span'));
}
else if (element.attr('name') == 'nom[1][attendance]') {
error.appendTo('span#assetDetail_error');
}
else {
error.insertAfter(element);
}

}

});

}
if($('.company').valid()){
$('.company').submit();
}

});

});



});
</script>

