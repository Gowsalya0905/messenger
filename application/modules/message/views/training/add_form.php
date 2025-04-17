<?php
$editId = postData($getProgramdatas, 'HMP_AUTO_ID', 0);
$formTitle = ($editId > 0) ? 'Edit Training' : 'Add Training';



$getobs_desc = isset($getProgramdatas->tr_desc) && !empty($getProgramdatas->tr_desc) ? $getProgramdatas->tr_desc : set_value('pro[tr_desc]');

$atarId = postData($getProgramdatas, 'tr_id', $this->input->post('pro[tr_id]'));
$editOwner_id = !empty($this->input->post('pro[tr_owner_id]')) ? postData($getProgramdatas, 'tr_owner_id', $this->input->post('pro[tr_owner_id]')) : '1';
$editowner_eng_id = !empty($this->input->post('pro[tr_owner_eng]')) ? postData($getProgramdatas, 'tr_owner_eng', $this->input->post('pro[tr_owner_eng]')) : '1';
$edittr_epc_id = !empty($this->input->post('pro[tr_epc_id]')) ? postData($getProgramdatas, 'tr_epc_id', $this->input->post('pro[tr_epc_id]')) : '1';
$editComp_id = postData($getProgramdatas, 'tr_comp_id', $this->input->post('pro[tr_comp_id]'));
$editComp_id = postData($getProgramdatas, 'tr_comp_id', $this->input->post('pro[tr_comp_id]'));
// $post_tr_area_id = $this->input->post('pro[tr_area_id]');
// $editArea_id = !empty($post_tr_area_id) ? $post_tr_area_id : (!empty($getProgramdatas->tr_area_id) ? $getProgramdatas->tr_area_id : '');
// $post_tr_building_id = $this->input->post('pro[tr_building_id]');
// $editBuilding_id = !empty($post_tr_building_id) ? $post_tr_building_id : (!empty($getProgramdatas->tr_building_id) ? $getProgramdatas->tr_building_id : '');
// $editBuilding_id = !empty($post_tr_building_id) ? $post_tr_building_id : (!empty($getProgramdatas->tr_building_id) ? $getProgramdatas->tr_building_id : '');


$editArea_id = isset($getProgramdatas->tr_area_id) && !empty($getProgramdatas->tr_area_id) ? postData($getProgramdatas, 'tr_area_id', $this->input->post('pro[tr_area_id]')) : '';
$editBuilding_id = isset($getProgramdatas->tr_building_id) && !empty($getProgramdatas->tr_building_id) ? postData($getProgramdatas, 'tr_building_id', $this->input->post('pro[tr_building_id]')) : ""; 
$edittr_cat_id = isset($getProgramdatas->tr_cat_id) && !empty($getProgramdatas->tr_cat_id) ? postData($getProgramdatas, 'tr_cat_id', $this->input->post('pro[tr_cat_id]')) : ""; 

$editDeptId  = postData($getProgramdatas, 'tr_dept_id', $this->input->post('pro[tr_dept_id]'));
$editProjId  = postData($getProgramdatas, 'tr_project_id', $this->input->post('pro[tr_project_id]'));
// $editCatId = postData($getProgramdatas, 'tr_cat_id', $this->input->post('pro[tr_cat_id]'));
$edittrdate = postData($getProgramdatas, 'tr_date', $this->input->post('pro[tr_date]'));
$edittr_type_id = postData($getProgramdatas, 'tr_type_id', $this->input->post('pro[tr_type_id]'));
$editRiskId = postData($getProgramdatas, 'tr_risk_id', $this->input->post('pro[tr_risk_id]'));
$tr_venue_desc = postData($getProgramdatas, 'tr_venue_desc', $this->input->post('pro[tr_venue_desc]'));
$tr_topics = postData($getProgramdatas, 'tr_topics', $this->input->post('pro[tr_topics]'));
$tr_start_date = postData($getProgramdatas, 'tr_start_date', $this->input->post('pro[tr_start_date]'));
$tr_end_date = postData($getProgramdatas, 'tr_end_date', $this->input->post('pro[tr_end_date]'));
$tr_start_time = postData($getProgramdatas, 'tr_start_time', $this->input->post('pro[tr_start_time]'));
$tr_end_time = postData($getProgramdatas, 'tr_end_time', $this->input->post('pro[tr_end_time]'));
$tr_end_time = postData($getProgramdatas, 'tr_end_time', $this->input->post('pro[tr_end_time]'));
$tr_end_time = postData($getProgramdatas, 'tr_end_time', $this->input->post('pro[tr_end_time]'));

$tr_emp_cmp_id = postData($getProgramdatas, 'tr_emp_cmp_id', $this->input->post('pro[tr_emp_cmp_id]'));
$tr_emp_desig_id = postData($getProgramdatas, 'tr_emp_desig_id', $this->input->post('pro[tr_emp_desig_id]'));

if (!is_array($tr_emp_cmp_id)) {
    $tr_emp_cmp_id = explode(',', $tr_emp_cmp_id);
}
$tr_emp_ids = postData($getProgramdatas, 'tr_emp_ids', $this->input->post('pro[tr_emp_ids]'));


$tr_emp_cmp_id_string = implode(',', $tr_emp_cmp_id);
// Check if $tr_emp_ids is an array
if (!is_array($tr_emp_ids)) {
  
    $tr_emp_ids = explode(',', $tr_emp_ids);
}

// Now you can safely implode it into a comma-separated string
$tr_emp_ids_string = implode(',', $tr_emp_ids);



$Reporter = isset($getProgramdatas->Reporter) && !empty($getProgramdatas->Reporter) ? $getProgramdatas->Reporter : $_SESSION['user_details']['NAME'];
$ReporterID = isset($getProgramdatas->tr_reporter_id) && !empty($getProgramdatas->tr_reporter_id) ? $getProgramdatas->tr_reporter_id : $_SESSION['userinfo']->LOGIN_ID;
$positionName = isset($getProgramdatas->desig) && !empty($getProgramdatas->desig) ? $getProgramdatas->desig : $_SESSION['user_details']['DESIGNATION'];
$positionID = isset($getProgramdatas->tr_reporter_desg_id) && !empty($getProgramdatas->tr_reporter_desg_id) ? $getProgramdatas->tr_reporter_desg_id : $_SESSION['user_details']['DESIGNATIONID'];
$roleID = isset($getProgramdatas->tr_reporter_type_id) && !empty($getProgramdatas->tr_reporter_type_id) ? $getProgramdatas->tr_reporter_type_id : $_SESSION['emp_details']->EMP_USERTYPE_ID;

$app_status = postData($getProgramdatas, 'tr_app_status', $this->input->post('pro[tr_app_status]'));

$tr_conducted_by = postData($getProgramdatas, 'tr_conducted_by', $this->input->post('pro[tr_conducted_by]'));


$currentDateTime = date('d-m-Y H:i:s');
$dateTimeatar = isset($getProgramdatas->tr_report_datetime) && !empty($getProgramdatas->tr_report_datetime) ? date('Y-m-d', strtotime($getProgramdatas->tr_report_datetime)) : $currentDateTime;

?>
<style>
#map {

    height: 360px;
    width: 100%;
}

.keyContdetails {
    border: 2px solid #1e6dab;
}
</style>
<div class="wrapper">


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


        <!-- Main content -->
        <section class=" content">
            <div class="container-fluid">

                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">


                        <!-- general form elements -->
                        <div class="card">
                            <div class="card-header">
                                <div class="col-md-12">
                                    <h3 class="card-title"><?php echo $formTitle; ?></h3>
                                    <a href="<?php echo BASE_URL('training/training/trainingInfo') ?>"><button
                                            type="button" class="btn btn-primary backBtns">Back</button></a>
                                </div>
                            </div>


                            <div class="card-body">
                                <?php
                                $getProid = isset($getProgramdatas->tr_auto_id) && !empty($getProgramdatas->tr_auto_id) ? $getProgramdatas->tr_auto_id : '';                             
                                ?>
                                <?php echo form_open_multipart('training/training/saveHmp/' . encryptval($getProid), 'class="form-horizontal" id="company-profile" novalidate'); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h5 class=""> HSE Observer Details </h5>
                                        </div>

                                    </div>
                                </div>

                                <input type="hidden" name="pro[tr_app_status]" value="<?php echo $app_status; ?>">
                                <div class="panel-body col-md-12">

                                    <div class="row m-t-10 m-l-10 m-b-10">


                                        <div class="col-md-4">

                                            <label>HSE Observer</label>
                                            <?php

                                            $cMaildatas = [
                                                'name' => 'pro[tr_reporter]',
                                                'class' => 'form-control',
                                                'value' => $Reporter,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($cMaildatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[tr_reporter]') ?></label>

                                        </div>
                                        <div class="col-md-4" style="display:none;">

                                            <label>Reporter ID </label>
                                            <?php

                                            $crepdatas = [
                                                'name' => 'pro[tr_reporter_id]',
                                                'class' => 'form-control',
                                                'value' => $ReporterID,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($crepdatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[tr_reporter_id]') ?></label>

                                        </div>
                                        <div class="col-md-4" style="display:none;">

                                            <label>Auto generated ID </label>
                                            <?php

                                            $crepdatas = [
                                                'name' => 'pro[tr_main_id]',
                                                'class' => 'form-control',
                                                'value' => $atarId,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($crepdatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[tr_main_id]') ?></label>

                                        </div>
                                        <div class="col-md-4" style="display:none;">

                                            <label>Role ID </label>
                                            <?php

                                            $crepdatas = [
                                                'name' => 'pro[tr_reporter_role_id]',
                                                'class' => 'form-control',
                                                'value' => $roleID,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($crepdatas);
                                            ?>
                                            <label
                                                class="error"><?php echo form_error('pro[tr_reporter_role_id]') ?></label>

                                        </div>

                                        <div class="col-md-4">

                                            <label>Designation</label>
                                            <?php

                                            $cpositiondata = array(
                                                'name' => 'pro[DES_NAME]',
                                                'id' => 'DES_NAME',
                                                'placeholder' => 'Enter Designation',
                                                'class' => 'form-control',
                                                'value' => $positionName,
                                                'readonly' => TRUE,
                                                'autocomplete' => 'off'
                                            );

                                            echo form_input($cpositiondata);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[DES_NAME]') ?></label>

                                        </div>
                                        <div class="col-md-4" style="display:none;">

                                            <label>Position ID </label>
                                            <?php

                                            $positioniddatas = [
                                                'name' => 'pro[user_desgination_id]',
                                                'class' => 'form-control',
                                                'value' => $positionID,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($positioniddatas);
                                            ?>
                                            <label
                                                class="error"><?php echo form_error('pro[user_desgination_id]') ?></label>

                                        </div>
                                        <div class="col-md-4 m-b-10">

                                            <label>Reported Date and Time </label>
                                            <?php

                                            $cdatetimedatas = [
                                                'name' => 'pro[tr_report_datetime]',
                                                'class' => 'form-control',
                                                'value' => $dateTimeatar,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($cdatetimedatas);
                                            ?>
                                            <label
                                                class="error"><?php echo form_error('pro[tr_report_datetime]') ?></label>

                                        </div>


                                    </div>

                                </div>



                                <div class="row m-t-10">
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h5 class=""> Operating Management Details</h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel-body col-md-12">

                                    <div class="row m-t-10 m-l-10 m-b-10">

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Owner <span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'owner',
                                                    'checkSelect' => 'select2',
                                                ];
                                                if (!empty($editOwner_id)) {
                                                    $cmpdata['disabled']  = TRUE;
                                                ?>
                                                <input type="hidden" name="pro[tr_owner_id]"
                                                    value="<?php echo $editOwner_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[tr_owner_id]', $owner_list, $editOwner_id, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[tr_owner_id]') ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> Owner Engineer Name<span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'tr_owner_eng',
                                                    'checkSelect' => 'select2',
                                                ];
                                                if (!empty($editowner_eng_id)) {
                                                    $cmpdata['disabled']  = TRUE;
                                                ?>
                                                <input type="hidden" name="pro[tr_owner_eng]"
                                                    value="<?php echo $editowner_eng_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[tr_owner_eng]', $owner_engineer_list, $editowner_eng_id, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[tr_owner_eng]') ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> EPC<span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'tr_epc_id',
                                                    'checkSelect' => 'select2',
                                                ];
                                                if (!empty($edittr_epc_id)) {
                                                    $cmpdata['disabled']  = TRUE;
                                                ?>
                                                <input type="hidden" name="pro[tr_epc_id]"
                                                    value="<?php echo $edittr_epc_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[tr_epc_id]', $EPC_list, $edittr_epc_id, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[tr_epc_id]') ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Company<span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'company',
                                                    'checkSelect' => 'select2',
                                                ];
                                                if (!empty($editComp_id)) {
                                                    $cmpdata['disabled']  = TRUE;
                                                ?>
                                                <input type="hidden" name="pro[tr_comp_id]"
                                                    value="<?php echo $editComp_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[tr_comp_id]', $dropcompany, $editComp_id, $cmpdata);
                                                ?>

                                                <span class="error"><?php echo form_error('pro[tr_comp_id]') ?></span>
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> Area<span class="error"> * </span></label>
                                                <select class="form-control area select2" checkSelect="select2" name="pro[tr_area_id]" id="area">
                                                    <option value="">Select Area</option>
                                                </select>

                                                <?php if ($editArea_id) { ?>
                                                <input type="hidden" name="pro[tr_area_id]"
                                                    value="<?php echo $editArea_id; ?>">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Building/Block/Direction<span class="error"> * </span></label>
                                                <select class="form-control building select2 " checkSelect="select2"
                                                    name="pro[tr_building_id]" id="building">
                                                    <option value="">Select Building/Block/Direction</option>
                                                </select>
                                                <?php if ($editBuilding_id) { ?>
                                                <input type="hidden" name="pro[tr_building_id]"
                                                    value="<?php echo $editBuilding_id; ?>">
                                                <?php } ?>
                                            </div>
                                        </div>



                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Department<span class="error"> * </span></label>
                                                <select class="form-control department select2 " checkSelect="select2"
                                                    name="pro[tr_dept_id]" id="department">
                                                    <option value="">Select Department</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Project<span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'project',
                                                    'checkSelect' => 'select2',
                                                ];
                                                if (!empty($editProjId)) {
                                                    $cmpdata['disabled']  = TRUE;
                                                ?>
                                                <input type="hidden" name="pro[tr_project_id]"
                                                    value="<?php echo $editProjId; ?>">
                                                <?php }
                                                echo form_dropdown('pro[tr_project_id]', $dropproject, $editProjId, $cmpdata);
                                                ?>

                                                <span
                                                    class="error"><?php echo form_error('pro[tr_project_id]') ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Venue Description<span class="error"> * </span></label>
                                                <?php
                                            $venuedata = [
                                                'name'  => 'pro[tr_venue_desc]',  // Ensure name attribute is set
                                                'id'    => 'tr_venue_desc',
                                                'class' => 'form-control',
                                                'value' => isset($tr_venue_desc) ? $tr_venue_desc : '', // Ensure value is set properly
                                            ];                                            
                                            echo form_input($venuedata);
                                            ?>

                                                <span
                                                    class="error"><?php echo form_error('pro[tr_venue_desc]'); ?></span>
                                            </div>
                                        </div>

                                    </div>


                                </div>


                                <div class="row m-t-10">
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h5 class="">Training Details </h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel-body col-md-12">
                                    <div class="row m-t-10 m-l-10">
                                        <div class="col-sm-4 m-t-10">

                                            <label>Training Type<span class="error"> * </span></label>
                                            <?php
                                            $unitdataimp = [
                                                'class' => 'form-control select2',
                                                'id' => 'tr_type_id',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('pro[tr_type_id]', $dropmastype, $edittr_type_id, $unitdataimp);
                                            ?>
                                            <span class="error"><?php echo form_error('pro[tr_type_id]') ?></span>
                                        </div>
                                        <div class="col-sm-4 m-t-10">

                                            <label>Training Type Category<span class="error"> * </span></label>
                                          

                                            <select class="form-control area select2" checkSelect="select2" name="pro[tr_cat_id]" id="tr_cat_id">
                                                    <option value="">Select Area</option>
                                                </select>

                                                <?php if ($edittr_cat_id) { ?>
                                                <input type="hidden" name="pro[tr_cat_id]"
                                                    value="<?php echo $edittr_cat_id; ?>">
                                                <?php } ?>
                                        </div>
                                        <div class="col-sm-4 m-t-10">
                                            <div class="form-group">
                                                <label>Training Conducted By <span class="error"> * </span></label>
                                                <?php
                                                $emMaildatas = [
                                                    'name' => 'pro[tr_conducted_by]',
                                                    'id' => 'tr_conducted_by',
                                                    'class' => 'form-control',
                                                    'value' => $tr_conducted_by,                                               
                                                    'autocomplete' => 'off',
                                                    'placeholder' => 'Enter Conductor Name',
                                                ];
                                                echo form_input($emMaildatas);
                                                ?>
                                                <span
                                                    class="error"><?php echo form_error('pro[tr_conducted_by]') ?></span>
                                            </div>
                                        </div>


                                        <div class="col-sm-12 m-t-10 m-b-10">

                                            <label>Training Topics <span class="error"> * </span></label>
                                            <textarea class="form-control" name="pro[tr_topics]" id="tr_topics">
                                                <?php echo $tr_topics; ?>                                                    
                                                </textarea>
                                            <span class="error"><?php echo form_error('pro[tr_topics]') ?></span>
                                        </div>
                                        <div class="col-md-4 has-float-label m-t-20">
                                            <label for="start" class="control-label labOwnsel2">Start Date <span
                                                    class="error">*</span></label>
                                            <?php
                                            $data = array(
                                                'name' => 'pro[sdate]',
                                                'id' => 'start_date',
                                                'class' => 'form-control inpOwn',
                                                'required' => true,
                                                'value' => (isset($getProgramdatas->tr_start_date)) ? date('d-m-Y', strtotime($getProgramdatas->tr_start_date)) : '',
                                            );

                                            echo form_input($data);
                                            ?>
                                        </div>

                                        <div class="col-md-4 has-float-label m-t-20">
                                            <label for="end" class="control-label labOwnsel2">End Date <span
                                                    class="error">*</span></label>
                                            <?php
                                            $data = array(
                                                'name' => 'pro[edate]',
                                                'id' => 'end_date',
                                                'class' => 'form-control todate inpOwn',
                                                'required' => true,
                                                'value' => (isset($getProgramdatas->tr_end_date)) ? date('d-m-Y', strtotime($getProgramdatas->tr_end_date)) : '',
                                            );

                                            echo form_input($data);
                                            ?>
                                        </div>

                                        <div class="col-sm-4 has-float-label m-t-20">
                                            <div class="form-group">
                                                <label for="end" class="control-label labOwnsel2">Start Time <span
                                                        class="error"> * </span> </label>
                                                <input type="text" name="pro[theorystarttime]"
                                                    value="<?php echo isset($getProgramdatas->tr_start_time) ? $getProgramdatas->tr_start_time: ''; ?>"
                                                    id="theorystarttime" class="form-control timepicker " required="1"
                                                    autocomplete="off">
                                                <span class="error start_time2Error"></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 has-float-label m-t-20">
                                            <div class="form-group">
                                                <label for="end" class="control-label labOwnsel2">End Time <span
                                                        class="error"> * </span> </label>
                                                <input type="text" name="pro[practicalstarttime]"
                                                    value="<?php echo isset($getProgramdatas->tr_end_time) ? $getProgramdatas->tr_end_time: ''; ?>"
                                                    id="practicalstarttime" class="form-control timepicker "
                                                    required="1" autocomplete="off">
                                                <span class="error end_time2Error"></span>
                                            </div>
                                        </div>

                                    </div><br>

                                </div>


                                <div class="row m-t-10">
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h5 class="">Employee Details </h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel-body col-md-12">
                                    <div class="row m-t-10 m-l-10">
                                        <div class="col-sm-4 m-t-10 m-b-10">

                                            <label>Company<span class="error"> * </span></label>
                                            <?php
                                            $unitdataimp = [
                                                'class' => 'form-control select2 inpOwn',
                                                'id' => 'tr_emp_company_id',
                                                'multiple' => '',
                                            ];
                                            echo form_dropdown('pro[tr_emp_company_id][]', $dropcompany, $tr_emp_cmp_id, $unitdataimp);
                                            ?>
                                            <span
                                                class="error"><?php echo form_error('pro[tr_emp_company_id]') ?></span>
                                        </div>
                                        <div class="col-sm-4 m-t-10 m-b-10">

                                            <label>Designation<span class="error"> * </span></label>
                                            <?php
                                            $unitdataimp = [
                                                'class' => 'form-control select2',
                                                'id' => 'tr_emp_design_id',
                                                'multiple' => '',
                                            ];
                                            echo form_dropdown('pro[tr_emp_design_id][]', $dropdesig, $tr_emp_desig_id, $unitdataimp);
                                            ?>
                                            <span class="error"><?php echo form_error('pro[tr_emp_design_id][]') ?></span>
                                        </div>
                                        <div class="col-sm-4 m-t-10 m-b-10">
                                            <label>Employees<span class="error"> * </span></label>
                                            <select class="form-control area select2" checkSelect="select2"
                                                name="pro[tr_emp_ids][]" id="tr_emp_ids" multiple>

                                            </select>
                                            <span class="error"><?php echo form_error('pro[tr_emp_ids]') ?></span>
                                        </div>

                                    </div>
                                </div>


                                <div class="row m-t-10">
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h5 class="">Visitors Details</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
    <div class="col-md-12">
        <div class="panel-body">
            <div class="col-md-12 d-flex justify-content-end">
                <span class="btn btn-xs btn-success addMorcerti">
                    <i class="fa fa-plus-square"></i> Add More
                </span>
            </div>
            <?php if (!empty($visitdata)) {  ?> 
                 <div class="visitorWrapper">
                <?php 
                foreach ($visitdata as $index => $visitor) { ?>
                <div class="row m-t-10 m-l-10 visitorDetailsRow">
                    <div class="col-sm-4 m-t-10">
                        <div class="form-group">
                            <label>Visitor Name <span class="error"> * </span></label>
                            <input type="text"
                                name="pro[<?php echo $index; ?>][tr_vistor_name]"
                                class="form-control" placeholder="Enter Visitor Name"
                                value="<?php echo htmlspecialchars($visitor->tr_visitor_name); ?>">
                        </div>
                    </div>
                    <div class="col-sm-4 m-t-10">
                        <div class="form-group">
                            <label>Visitor Designation <span class="error"> *
                            </span></label>
                            <input type="text"
                                name="pro[<?php echo $index; ?>][tr_vistor_deign]"
                                class="form-control" placeholder="Enter Designation Name"
                                value="<?php echo htmlspecialchars($visitor->tr_visitor_desig); ?>">
                        </div>
                    </div>
                    <div class="col-sm-4 m-t-10">
                        <div class="form-group">
                            <label>Visitor Company <span class="error"> *
                            </span></label>
                            <input type="text"
                                name="pro[<?php echo $index; ?>][tr_vistor_comp_name]"
                                class="form-control" placeholder="Enter Company Name"
                                value="<?php echo htmlspecialchars($visitor->tr_visitor_cmp_name); ?>">
                        </div>
                    </div>
                    <div class="col-sm-4 m-t-10">
                        <div class="form-group">
                            <label>Iqama Number <span class="error"> * </span></label>
                            <input type="text"
                                name="pro[<?php echo $index; ?>][tr_visitor_iqama_num]"
                                class="form-control" placeholder="Enter Iqama Number"
                                value="<?php echo htmlspecialchars($visitor->tr_visitor_iq_number); ?>">
                        </div>
                    </div>
                    <div class="col-sm-4 m-t-10">
                        <div class="form-group">
                            <label>Contact Number <span class="error"> * </span></label>
                            <input type="text"
                                name="pro[<?php echo $index; ?>][tr_visitor_contact_num]"
                                class="form-control" placeholder="Enter Contact Number"
                                value="<?php echo htmlspecialchars($visitor->tr_visitor_contact_number); ?>">
                        </div>
                    </div>
                    <div class="col-sm-4 m-t-10">
                        <div class="form-group">
                            <label>Email ID <span class="error"> * </span></label>
                            <input type="email"
                                name="pro[<?php echo $index; ?>][tr_visitor_email]"
                                class="form-control" placeholder="Enter Email"
                                value="<?php echo htmlspecialchars($visitor->tr_visitor_email_id); ?>">
                        </div>
                    </div>
                    <input type="hidden" name="pro[<?php echo $index; ?>][tr_ev_auto_id]" value="<?php echo htmlspecialchars($visitor->tr_ev_auto_id); ?>">
                </div>
                <?php } ?>
                  </div>
           <?php } else { ?>
                <div class="visitorWrapper">
                    <div class="row m-t-10 m-l-10 visitorDetailsRow">
                        <div class="col-sm-4 m-t-10">
                            <div class="form-group">
                                <label>Visitor Name <span class="error"> * </span></label>
                                <input type="text" name="pro[0][tr_vistor_name]"
                                    class="form-control" placeholder="Enter Visitor Name">
                            </div>
                        </div>
                        <div class="col-sm-4 m-t-10">
                            <div class="form-group">
                                <label>Visitor Designation <span class="error"> *
                                </span></label>
                                <input type="text" name="pro[0][tr_vistor_deign]"
                                    class="form-control" placeholder="Enter Designation Name">
                            </div>
                        </div>
                        <div class="col-sm-4 m-t-10">
                            <div class="form-group">
                                <label>Visitor Company <span class="error"> *
                                </span></label>
                                <input type="text" name="pro[0][tr_vistor_comp_name]"
                                    class="form-control" placeholder="Enter Company Name">
                            </div>
                        </div>
                        <div class="col-sm-4 m-t-10">
                            <div class="form-group">
                                <label>Iqama Number <span class="error"> * </span></label>
                                <input type="text" name="pro[0][tr_visitor_iqama_num]"
                                    class="form-control" placeholder="Enter Iqama Number">
                            </div>
                        </div>
                        <div class="col-sm-4 m-t-10">
                            <div class="form-group">
                                <label>Contact Number <span class="error"> * </span></label>
                                <input type="text" name="pro[0][tr_visitor_contact_num]"
                                    class="form-control" placeholder="Enter Contact Number">
                            </div>
                        </div>
                        <div class="col-sm-4 m-t-10">
                            <div class="form-group">
                                <label>Email ID <span class="error"> * </span></label>
                                <input type="email" name="pro[0][tr_visitor_email]"
                                    class="form-control" placeholder="Enter Email">
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>






                            <div class="form-group m-t-10" style="text-align: center;">
                                <input type="hidden" name="action_type" id="action_type" value="1">
                                <button type="submit" id="save" attr_sub="1" class="btn btn-primary">Submit</button>
                                <?php if (!$app_status) { ?>
                                <button type="button" id="drafted" attr_drft="0" class="btn btn-info">Save
                                    Draft</button>
                                <?php } ?>
                            </div>
                        </div>


                        <!-- input states -->
                        <?php echo form_close(); ?>
                    </div>
                </div>

                <!-- /.card -->

            </div>
            <!--/.col (left) -->

    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

</div>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<script>
var newRowId = $(".visitorDetailsRow").length;

$(document).on('click', '.addMorcerti', function() {

    var html = '<div class="row visitorDetailsRow" style="margin-top: 10px;">' +
        // Add More button
        '<div class="col-md-12">' +
        '<div class="d-flex justify-content-end align-items-center">' +
        '<span class="btn btn-xs btn-success addMorcerti"><i class="fa fa-plus-square"></i> Add More</span>' +
        '<button type="button" class="btn btn-xs btn-danger removeVisitor"><i class="fa fa-trash"></i></button>' +
        '</div>' +
        '</div>' +
        // Form fields
        '<div class="col-sm-4">' +
        '<div class="form-group">' +
        '<label>Visitor Name <span class="error"> * </span></label>' +
        '<input type="text" name="pro[' + newRowId +
        '][tr_vistor_name]" class="form-control" placeholder="Enter Visitor Name" value="" autocomplete="off">' +
        '</div>' +
        '</div>' +
        '<div class="col-sm-4">' +
        '<div class="form-group">' +
        '<label>Visitor Designation <span class="error"> * </span></label>' +
        '<input type="text" name="pro[' + newRowId +
        '][tr_vistor_deign]" class="form-control" placeholder="Enter Designation Name" value="" autocomplete="off">' +
        '</div>' +
        '</div>' +
        '<div class="col-sm-4">' +
        '<div class="form-group">' +
        '<label>Visitor Company <span class="error"> * </span></label>' +
        '<input type="text" name="pro[' + newRowId +
        '][tr_vistor_comp_name]" class="form-control" placeholder="Enter Company Name" value="" autocomplete="off">' +
        '</div>' +
        '</div>' +
        '<div class="col-sm-4">' +
        '<div class="form-group">' +
        '<label>Iqama Number <span class="error"> * </span></label>' +
        '<input type="text" name="pro[' + newRowId +
        '][tr_visitor_iqama_num]" class="form-control" placeholder="Enter Iqama Number" value="" autocomplete="off">' +
        '</div>' +
        '</div>' +
        '<div class="col-sm-4">' +
        '<div class="form-group">' +
        '<label>Contact Number <span class="error"> * </span></label>' +
        '<input type="text" name="pro[' + newRowId +
        '][tr_visitor_contact_num]" class="form-control" placeholder="Enter Contact Number" value="" autocomplete="off">' +
        '</div>' +
        '</div>' +
        '<div class="col-sm-4">' +
        '<div class="form-group">' +
        '<label>Email ID <span class="error"> * </span></label>' +
        '<input type="email" name="pro[' + newRowId +
        '][tr_visitor_email]" class="form-control" placeholder="Enter Email" value="" autocomplete="off">' +
        '</div>' +
        '</div>' +
        '</div>';
    newRowId += 1;
    $(".visitorWrapper").append(html);

});

$(document).on('click', '.removeVisitor', function() {
    $(this).closest('.visitorDetailsRow').remove();
});
</script>
<!-- ./wrapper -->
<script>
$(document).ready(function() {


    $("#start_date").datepicker({
        todayBtn: 1,
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate: 'today',
    }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#end_date').datepicker('setStartDate', minDate);
        $('#end_date').datepicker('setDate', minDate); // <--THIS IS THE LINE ADDED
    });

    $('#end_date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
        })
        .on('changeDate', function(selected) {
            var maxDate = new Date(selected.date.valueOf());
            $("#start_date").datepicker('setEndDate', maxDate);
        });


    $('#theorystarttime').timepicker({
        timeFormat: 'H:mm',
        interval: 30,
        maxTime: '10:00pm',
        minTime: '08:00am',
        dynamic: false,
        dropdown: true,
        scrollbar: true,
    });

    $('#practicalstarttime').timepicker({
        timeFormat: 'H:mm',
        interval: 30,
        maxTime: '10:00pm',
        minTime: '08:00am',
        dynamic: false,
        dropdown: true,
        scrollbar: true,
    });


    function getAreaDetails(company) {

        var url = "<?php echo BASE_URL() . "Main/AreaDetails" ?>";
        var data = {
            company: company,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            cache: false,
            success: function(data) {
                $('#area').html(data);
                var area = '<?php echo $editArea_id; ?>';
                if (area != '') {
                    $('#area').val(area);
                    $('#area option[value=' + area + ']').attr('selected', 'selected');
                    $('#area').prop('disabled', true);
                } else {
                    $('#building').val('');
                    $('#department').val('');
                    $('#project').val('');
                }

            }
        });
    }

    function getBuildingDetails(area) {

        var url = "<?php echo BASE_URL() . "Main/BuildingDetails" ?>";
        var data = {
            area: area,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            cache: false,
            success: function(data) {
                $('#building').html(data);
                var building = '<?php echo $editBuilding_id; ?>';
                if (building != '') {
                    $('#building').val(building);
                    $('#building option[value=' + building + ']').attr('selected', 'selected');
                    $('#building').prop('disabled', true);

                } else {
                    $('#department').val('');
                    $('#project').val('');
                }

            }
        });
    }

    function getDepartmentDetails(area) {

        var url = "<?php echo BASE_URL() . "Main/DepartmentDetails" ?>";
        var data = {
            area: area,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            cache: false,
            success: function(data) {
                $('#department').html(data);
                var department = '<?php echo $editDeptId; ?>';
                if (department != '') {
                    $('#department').val(department);
                    $('#department option[value=' + department + ']').attr('selected', 'selected');
                }

            }
        });
    }


    var company = '<?php echo $editComp_id; ?>';
    var area_id = '<?php echo $editArea_id; ?>';
    var building_id = '<?php echo $editBuilding_id; ?>';
    var department_id = '<?php echo $editDeptId; ?>';
    var tr_emp_cmp_id = '<?php echo $tr_emp_cmp_id_string ?>';



    // if (tr_emp_cmp_id != '') {
    //     var company = $(this).val();
    //     var designation = $('#tr_emp_design_id').val(); 
    //     getEmployeeDetails(company, designation);

    // }
    var company1 = $('#tr_emp_company_id').val();
    var designation1 = $('#tr_emp_design_id').val();

    if (company1 != '' && designation1 != '') {
        getEmployeeDetails(company1, designation1);
    }

    if (company != '') {
        getAreaDetails(company);
    }
    if (area_id != '') {
        getBuildingDetails(area_id);
        getDepartmentDetails(area_id);
    }


    $(document).on('change', '#company', function() {
        var company = $(this).val();
        getAreaDetails(company);
    });
    $(document).on('change', '#area', function() {
        var area_id = $(this).val();
        getBuildingDetails(area_id);
        getDepartmentDetails(area_id);
    });


    function toggleFields() {
        var obsType = $('#obs_type_id').val();
        if (obsType == 1 || obsType == 2) {
            $('.risk_rating').show();
        } else {
            $('.risk_rating').hide();
            $('#obs_risk_id').val(null).trigger('change');
        }
    }

    toggleFields();

    $('#obs_type_id').on('change', function() {
        toggleFields();
    });




    $(document).on('change', '#tr_emp_company_id', function() {
        var company = $(this).val();
        var designation = $('#tr_emp_design_id').val();

        getEmployeeDetails(company, designation);
    });

    $(document).on('change', '#tr_emp_design_id', function() {
        var company = $('#tr_emp_company_id').val();
        var designation = $(this).val();

        getEmployeeDetails(company, designation);
    });


    function getEmployeeDetails(company, designation) {
        var url = "<?php echo BASE_URL() . 'Main/EmployeeDetails'; ?>";
        var data = {
            company: company, // Array if multiple companies are selected
            designation: designation, // Array if multiple designations are selected
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            cache: false,
            success: function(response) {
                $('#tr_emp_ids').html(response);
                $('#tr_emp_ids').select2();
                var selectedEmpIds = '<?php echo $tr_emp_ids_string ?>';

                if (selectedEmpIds) {

                    var selectedEmpIdsArray = selectedEmpIds.split(',');
                    $('#tr_emp_ids').val(selectedEmpIdsArray).trigger('change');
                }
            }
        });
    }


    $(document).on('change', '#tr_type_id', function() {
        var type = $(this).val();
        getCategoryDetails(type);
    });



    function getCategoryDetails(type) {

        var url = "<?php echo BASE_URL() . "Main/trainingCategoryDetails" ?>";
        var data = {
            type: type,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            cache: false,
            success: function(data) {
                $('#tr_cat_id').html(data);
                var edittr_cat_id = '<?php echo $edittr_cat_id; ?>';
                if (edittr_cat_id != '') {
                    $('#tr_cat_id').val(edittr_cat_id);
                    $('#tr_cat_id option[value=' + edittr_cat_id + ']').attr('selected', 'selected');
                }

            }
        });
        }

});
</script>
<script type="text/javascript">
$(document).ready(function() {

    $('#obs_desc').summernote({
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear', 'strikethrough', 'superscript',
                'subscript', 'ul', 'ol', 'color'
            ]],
        ],

    });

    const today = new Date();
    const pastTwoDays = new Date();
    pastTwoDays.setDate(today.getDate() - 2);

    // Initialize the datepicker
    $("#obs_date").datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate: pastTwoDays, // Set the start date to two days ago
        endDate: today, // Set the end date to today
    });


    $.validator.addMethod('alphanumeric', function(value) {
        return /^[A-Za-z0-9/,.  ]*$/.test(value);
    }, "Please Enter valid Alphanumeric characters with allowed special charters are /,.");


    $.validator.addMethod('accept_address', function(value) {
        return /^[a-zA-Z0-9\/\\n\s,.'-_*()+&^$#@!% ]{1,}$/.test(value);
    }, "Please Enter a valid address");




    function initializeValidation(isDraft) {
        // Destroy any existing validation
        $("#company-profile").validate().destroy();
  
        // Reinitialize validation with appropriate settings
        $("#company-profile").validate({
            ignore: [],
            success: function(error) {
                error.removeClass("error");
                error.addClass("d-none");
            },
            rules: isDraft ? {
                "pro[tr_comp_id]": {
                    required: true
                },
                "pro[tr_area_id]": {
                    required: true
                },
                "pro[tr_building_id]": {
                    required: true
                },
                "pro[tr_project_id]": {
                    required: true
                },
            } : {
                "pro[tr_owner_id]": {
                    required: true
                },
                "pro[tr_owner_eng]": {
                    required: true
                },
                "pro[tr_comp_id]": {
                    required: true
                },
                "pro[tr_area_id]": {
                    required: true
                },
                "pro[tr_building_id]": {
                    required: true
                },
                "pro[tr_dept_id]": {
                    required: true
                },
                "pro[tr_project_id]": {
                    required: true
                },
                "pro[tr_cat_id]": {
                    required: true
                },
                "pro[tr_date]": {
                    required: true
                },
                "pro[tr_type_id]": {
                    required: true
                },
               
                "pro[tr_desc]": {
                    required: true,
                    summernoteRequired: true,
                },
            },
            messages: isDraft ? {
                "pro[tr_comp_id]": {
                    required: "Company is required for draft."
                },
                "pro[tr_area_id]": {
                    required: "Area is required for draft."
                },
                "pro[tr_building_id]": {
                    required: "Building/Block/Direction is required for draft."
                },

                "pro[tr_project_id]": {
                    required: "Project is required for draft."
                },
            } : {
                "pro[tr_owner_id]": {
                    required: "Owner is Required"
                },
                "pro[tr_owner_eng]": {
                    required: "Owner Engineer Name is Required"
                },
                "pro[tr_comp_id]": {
                    required: "Company is Required"
                },
                "pro[tr_area_id]": {
                    required: "Area is Required"
                },
                "pro[tr_building_id]": {
                    required: "Building/Block/Direction is Required"
                },

                "pro[tr_dept_id]": {
                    required: "Department is Required"
                },
                "pro[tr_project_id]": {
                    required: "Project is Required"
                },
                "pro[tr_cat_id]": {
                    required: "Category is Required"
                },
                "pro[tr_date]": {
                    required: "trervation Date is Required"
                },
                "pro[tr_type_id]": {
                    required: "trervation Type is Required"
                },
                "pro[tr_risk_id]": {
                    required: "Risk Rating is Required"
                },
                "pro[tr_desc]": {
                    required: "Training Description is Required",
                    summernoteRequired: "Training Description is Required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.hasClass("atarfile")) {
                    element.closest(".imgGroup").append(error);
                } else if (element.attr('checkSelect') === 'select2') {
                    error.insertAfter(element.next('.select2-container'));
                } else if (element.attr('ckedit') === 'ckeditor') {
                    error.insertAfter(element.next('.note-editor'));
                } else {
                    error.insertAfter(element);
                    element.closest(".imgGroup").last().append(error);
                }
            },
            submitHandler: function(form, event) {
                swal({
                    title: "Please wait..",
                    imageUrl: loadingImg,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                form.submit();
            },
        });
    }

    $("#save").on("click", function() {
        $("#action_type").val(1);
        initializeValidation(false); // Use "submitted" validation rules
        $("#company-profile").submit();
    });

    $("#drafted").on("click", function() {
        $("#action_type").val(0);
        initializeValidation(true); // Use "draft" validation rules
        $("#company-profile").submit();
    });

});
</script>