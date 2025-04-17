<?php
$editId = postData($getProgramdatas, 'HMP_AUTO_ID', 0);
$formTitle = ($editId > 0) ? 'Edit Observation' : 'Add Observation';



$getobs_desc = isset($getProgramdatas->obs_desc) && !empty($getProgramdatas->obs_desc) ? $getProgramdatas->obs_desc : set_value('pro[obs_desc]');

$atarId = postData($getProgramdatas, 'obs_id', $this->input->post('pro[obs_id]'));
$editOwner_id = !empty($this->input->post('pro[obs_owner_id]')) ? postData($getProgramdatas, 'obs_owner_id', $this->input->post('pro[obs_owner_id]')) : '1';
$editowner_eng_id = !empty($this->input->post('pro[obs_owner_eng]')) ? postData($getProgramdatas, 'obs_owner_eng', $this->input->post('pro[obs_owner_eng]')) : '1';
$editobs_epc_id = !empty($this->input->post('pro[obs_epc_id]')) ? postData($getProgramdatas, 'obs_epc_id', $this->input->post('pro[obs_epc_id]')) : '1';
$editComp_id = postData($getProgramdatas, 'obs_comp_id', $this->input->post('pro[obs_comp_id]'));
$editArea_id = isset($getProgramdatas->obs_area_id) && !empty($getProgramdatas->obs_area_id) ? postData($getProgramdatas, 'obs_area_id', $this->input->post('pro[obs_area_id]')) : '';
$editBuilding_id = isset($getProgramdatas->obs_building_id) && !empty($getProgramdatas->obs_building_id) ? postData($getProgramdatas, 'obs_building_id', $this->input->post('pro[obs_building_id]')) : "";
$editDeptId  = postData($getProgramdatas, 'obs_dept_id', $this->input->post('pro[obs_dept_id]'));
$editProjId  = postData($getProgramdatas, 'obs_project_id', $this->input->post('pro[obs_project_id]'));
$editCatId = postData($getProgramdatas, 'obs_cat_id', $this->input->post('pro[obs_cat_id]'));
$editObsdate = postData($getProgramdatas, 'obs_date', $this->input->post('pro[obs_date]'));
$editObs_type_id = postData($getProgramdatas, 'obs_type_id', $this->input->post('pro[obs_type_id]'));
$editRiskId = postData($getProgramdatas, 'obs_risk_id', $this->input->post('pro[obs_risk_id]'));

$Reporter = isset($getProgramdatas->Reporter) && !empty($getProgramdatas->Reporter) ? $getProgramdatas->Reporter : $_SESSION['user_details']['NAME'];
$ReporterID = isset($getProgramdatas->obs_reporter_id) && !empty($getProgramdatas->obs_reporter_id) ? $getProgramdatas->obs_reporter_id : $_SESSION['userinfo']->LOGIN_ID;
$positionName = isset($getProgramdatas->desig) && !empty($getProgramdatas->desig) ? $getProgramdatas->desig : $_SESSION['user_details']['DESIGNATION'];
$positionID = isset($getProgramdatas->obs_reporter_desg_id) && !empty($getProgramdatas->obs_reporter_desg_id) ? $getProgramdatas->obs_reporter_desg_id : $_SESSION['user_details']['DESIGNATIONID'];
$roleID = isset($getProgramdatas->obs_reporter_type_id) && !empty($getProgramdatas->obs_reporter_type_id) ? $getProgramdatas->obs_reporter_type_id : $_SESSION['emp_details']->EMP_USERTYPE_ID;

$app_status = postData($getProgramdatas, 'obs_app_status', $this->input->post('pro[obs_app_status]'));


$currentDateTime = date('d-m-Y H:i:s');
$dateTimeatar = isset($getProgramdatas->obs_report_datetime) && !empty($getProgramdatas->obs_report_datetime) ? date('Y-m-d', strtotime($getProgramdatas->obs_report_datetime)) : $currentDateTime;

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
                                    <a href="<?php echo BASE_URL('atarusee/incident/incidentInfo') ?>"><button type="button" class="btn btn-primary backBtns">Back</button></a>
                                </div>
                            </div>


                            <div class="card-body">
                                <?php
                                $getProid = isset($getProgramdatas->obs_auto_id) && !empty($getProgramdatas->obs_auto_id) ? $getProgramdatas->obs_auto_id : '';
                                $risk_log_id = isset($getProgramdatas->risk_log_id) && !empty($getProgramdatas->risk_log_id) ? $getProgramdatas->risk_log_id : '';
                                ?>
                                <?php echo form_open_multipart('atarusee/incident/saveHmp/' . encryptval($getProid), 'class="form-horizontal" id="company-profile" novalidate'); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h5 class=""> HSE Observer Details </h5>
                                        </div>

                                    </div>
                                </div>
                                <input type="hidden" name="pro[risk_log_id]" value="<?php echo $risk_log_id; ?>">
                                <input type="hidden" name="pro[obs_app_status]" value="<?php echo $app_status; ?>">
                                <div class="panel-body col-md-12">

                                    <div class="row m-t-10 m-l-10 m-b-10">


                                        <div class="col-md-4">

                                            <label>HSE Observer</label>
                                            <?php

                                            $cMaildatas = [
                                                'name' => 'pro[obs_reporter]',
                                                'class' => 'form-control',
                                                'value' => $Reporter,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($cMaildatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[obs_reporter]') ?></label>

                                        </div>
                                        <div class="col-md-4" style="display:none;">

                                            <label>Reporter ID </label>
                                            <?php

                                            $crepdatas = [
                                                'name' => 'pro[obs_reporter_id]',
                                                'class' => 'form-control',
                                                'value' => $ReporterID,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($crepdatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[obs_reporter_id]') ?></label>

                                        </div>
                                        <div class="col-md-4" style="display:none;">

                                            <label>Auto generated ID </label>
                                            <?php

                                            $crepdatas = [
                                                'name' => 'pro[obs_main_id]',
                                                'class' => 'form-control',
                                                'value' => $atarId,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($crepdatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[obs_main_id]') ?></label>

                                        </div>
                                        <div class="col-md-4" style="display:none;">

                                            <label>Role ID </label>
                                            <?php

                                            $crepdatas = [
                                                'name' => 'pro[obs_reporter_role_id]',
                                                'class' => 'form-control',
                                                'value' => $roleID,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($crepdatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[obs_reporter_role_id]') ?></label>

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
                                            <label class="error"><?php echo form_error('pro[user_desgination_id]') ?></label>

                                        </div>
                                        <div class="col-md-4 m-b-10">

                                            <label>Reported Date and Time </label>
                                            <?php

                                            $cdatetimedatas = [
                                                'name' => 'pro[obs_report_datetime]',
                                                'class' => 'form-control',
                                                'value' => $dateTimeatar,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($cdatetimedatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[obs_report_datetime]') ?></label>

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
                                                    <input type="hidden" name="pro[obs_owner_id]" value="<?php echo $editOwner_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[obs_owner_id]', $owner_list, $editOwner_id, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[obs_owner_id]') ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> Owner Engineer Name<span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'obs_owner_eng',
                                                    'checkSelect' => 'select2',
                                                ];
                                                if (!empty($editowner_eng_id)) {
                                                    $cmpdata['disabled']  = TRUE;
                                                ?>
                                                    <input type="hidden" name="pro[obs_owner_eng]" value="<?php echo $editowner_eng_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[obs_owner_eng]', $owner_engineer_list, $editowner_eng_id, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[obs_owner_eng]') ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> EPC<span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'obs_epc_id',
                                                    'checkSelect' => 'select2',
                                                ];
                                                if (!empty($editobs_epc_id)) {
                                                    $cmpdata['disabled']  = TRUE;
                                                ?>
                                                    <input type="hidden" name="pro[obs_epc_id]" value="<?php echo $editobs_epc_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[obs_epc_id]', $EPC_list, $editobs_epc_id, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[obs_epc_id]') ?></span>
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
                                                    <input type="hidden" name="pro[obs_comp_id]" value="<?php echo $editComp_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[obs_comp_id]', $dropcompany, $editComp_id, $cmpdata);
                                                ?>

                                                <span class="error"><?php echo form_error('pro[obs_comp_id]') ?></span>
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> Area<span class="error"> * </span></label>
                                                <select class="form-control area select2" checkSelect="select2" name="pro[obs_area_id]" id="area">
                                                    <option value="">Select Area</option>
                                                </select>

                                                <?php if ($editArea_id) { ?>
                                                    <input type="hidden" name="pro[obs_area_id]" value="<?php echo $editArea_id; ?>">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Building/Block/Direction<span class="error"> * </span></label>
                                                <select class="form-control building select2 " checkSelect="select2" name="pro[obs_building_id]" id="building">
                                                    <option value="">Select Building/Block/Direction</option>
                                                </select>
                                                <?php if ($editBuilding_id) { ?>
                                                    <input type="hidden" name="pro[obs_building_id]" value="<?php echo $editBuilding_id; ?>">
                                                <?php } ?>
                                            </div>
                                        </div>



                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Department<span class="error"> * </span></label>
                                                <select class="form-control department select2 " checkSelect="select2" name="pro[obs_dept_id]" id="department">
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
                                                    <input type="hidden" name="pro[obs_project_id]" value="<?php echo $editProjId; ?>">
                                                <?php }
                                                echo form_dropdown('pro[obs_project_id]', $dropproject, $editProjId, $cmpdata);
                                                ?>

                                                <span class="error"><?php echo form_error('pro[obs_project_id]') ?></span>
                                            </div>
                                        </div>


                                    </div>


                                </div>


                                <div class="row m-t-10">
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h5 class=""> Observation Details </h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel-body col-md-12">
                                    <div class="row m-t-10 m-l-10">
                                        <div class="col-sm-4 m-t-10">

                                            <label>HSE Category<span class="error"> * </span></label>
                                            <?php
                                            $unitdataimp = [
                                                'class' => 'form-control select2',
                                                'id' => 'obs_cat_id',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('pro[obs_cat_id]', $hsecatDetails, $editCatId, $unitdataimp);
                                            ?>
                                            <span class="error"><?php echo form_error('pro[obs_cat_id]') ?></span>
                                        </div>
                                        <div class="col-sm-4 m-t-10">
                                            <div class="form-group">
                                                <label>Date of Observation <span class="error"> * </span></label>
                                                <?php
                                                $emMaildatas = [
                                                    'name' => 'pro[obs_date]',
                                                    'id' => 'obs_date',
                                                    'class' => 'form-control',
                                                    'value' => $editObsdate,
                                                    // 'required' => true,
                                                    'autocomplete' => 'off',
                                                    'placeholder' => 'Enter Observation Date',
                                                ];
                                                echo form_input($emMaildatas);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[obs_date]') ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 m-t-10">
                                            <div class="form-group">
                                                <label>Observation Type <span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'obs_type_id',
                                                    'checkSelect' => 'select2',
                                                ];
                                                echo form_dropdown('pro[obs_type_id]', $obs_type_list, $editObs_type_id, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[obs_type_id]') ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 m-t-10 risk_rating" style="display:none;">
                                            <div class="form-group">
                                                <label> Risk Rating<span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'obs_risk_id',
                                                    'checkSelect' => 'select2',
                                                ];
                                                echo form_dropdown('pro[obs_risk_id]', $risk_rating, $editRiskId, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[obs_risk_id]') ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 m-t-10">

                                            <label>Observation Description <span class="error"> * </span></label>
                                            <textarea class="form-control" style="width: 200px; height: 300px;"
                                                name="pro[obs_desc]" id="obs_desc" ckedit="ckeditor">
                                                <?php echo $getobs_desc; ?>
                                                    
                                                </textarea>

                                            <span class="error"><?php echo form_error('pro[obs_desc]') ?></span>

                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-10">
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h5 class=""> PHOTO DETAILS </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body col-md-12">
                                    <div class="row m-t-10 m-l-10">
                                        <div class="col-md-12">
                                            <span class="btn btn-xs btn-success  float-right addMorcerti1"><i class="fa fa-plus-square"> Add More</i></span>
                                        </div>
                                        <div class="col-md-12 ">
                                            <?php
                                            if (isset($getAllBfimage) && !empty($getAllBfimage)) {
                                                //  echo "<pre>";
                                                //   print_r($getUseeActdatas);exit;
                                            ?>
                                                <div class="othercertiDet1">
                                                    <label>Image Upload <span class="error"> </span></label>
                                                    <div class="row m-t-10 m-l-10 addMorecompet1">
                                                        <?php
                                                        foreach ($getAllBfimage as $othKey => $othVal) {
                                                            $useeRowval = $othKey + 1;
                                                            $useeID = postData($othVal, 'obs_att_id');
                                                            $useeimgpath = postData($othVal, 'obs_file_path');
                                                            $useeimgname = postData($othVal, 'obs_filename');

                                                            if ($useeimgname != '') {
                                                                $useeFilepath = $useeimgpath . $useeimgname;;
                                                            } else {
                                                                $useeFilepath = '';
                                                            }
                                                        ?>

                                                            <div class="col-sm-4 othercertiDet1Row" style="" certiRow-id1="<?php echo $useeRowval; ?>">
                                                                <div class="form-group ">
                                                                    <input type="hidden" name="useeafterfix[<?php echo $useeRowval; ?>][other_compCerti_ID2]" value="<?php echo $useeID; ?>">

                                                                    <div class="fileinput fileinput-new apprFileinput imgGroup" style="margin-top:30px;" data-provides="fileinput">
                                                                        <div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput">
                                                                            <?php
                                                                            if ($useeFilepath != '') {
                                                                            ?>
                                                                                <img src='<?php echo BASE_URL . $useeFilepath; ?>' alt='<?php echo $useeimgname; ?>' style="height: 100%;" />
                                                                            <?php } ?>
                                                                        </div>
                                                                        <p class="">( png, jpeg, jpg )</p>
                                                                        <div class="file-pop">
                                                                            <span class="text-green btn-file"><span class="photo fileinput-new" title="Add Image"><img class="imgupload" src='<?php echo BASE_URL("/assets/images/photo.png"); ?>' style=" width: 30%; " /></span>
                                                                                <span class="fileinput-exists" title="Add Image"></span>
                                                                                <input type="file" name="useeafterfix[]" class="atarfile" accept="image/png, image/jpeg"></span>
                                                                            <button type="button" name="re" class="btn btn-nothing text-maroon fileinput-exists" data-dismiss="fileinput" title="Remove Image"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="other_user_img[]" value="<?php echo $useeFilepath; ?>">
                                                                </div>
                                                            </div>



                                                        <?php } ?>
                                                    </div>

                                                <?php
                                            } else {
                                                ?>
                                                    <div>
                                                        <div class="row m-t-10 m-l-10 addMorecompet1">
                                                            <div class="col-sm-4 othercertiDet1Row" certiRow-id1="1">
                                                                <label>Image Upload <span class="error"> </span></label>
                                                                <div class="form-group ">

                                                                    <input type="hidden" name="useeafterfix[1][other_compCerti_ID2]" value="">



                                                                    <div class="fileinput fileinput-new apprFileinput imgGroup" style="" data-provides="fileinput">
                                                                        <div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput">


                                                                        </div>
                                                                        <p class="">( png, jpeg, jpg )</p>
                                                                        <div class="file-pop">
                                                                            <span class="text-green btn-file"><span class="photo fileinput-new" title="Add Image"><img class="imgupload" src='<?php echo BASE_URL("/assets/images/photo.png"); ?>' style=" width: 30%; " /></span>
                                                                                <span class="fileinput-exists" title="Add Image"></span>
                                                                                <input type="file" name="useeafterfix[]" class="atarfile" accept="image/png, image/jpeg"></span>
                                                                            <button type="button" name="re" class="btn btn-nothing text-maroon fileinput-exists" data-dismiss="fileinput" title="Remove Image"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <input type="hidden" name="other_user_img[]" value="<?php //echo $useeFilepath; 
                                                                                                                                ?>"> -->

                                                                    <input type="hidden" name="other_user_img[]" value="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group m-t-10" style="text-align: center;">
                                    <input type="hidden" name="action_type" id="action_type" value="1">
                                    <button type="submit" id="save" attr_sub="1" class="btn btn-primary">Submit</button>
                                    <?php if (!$app_status) { ?>
                                        <button type="button" id="drafted" attr_drft="0" class="btn btn-info">Save Draft</button>
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

<script>
    $(document).on('click', '.addMorcerti1', function() {
        alert("SDds");
        var baseUrl = '<?php echo BASE_URL; ?>'
        var lastUsee = $(".othercertiDet1Row").last().attr("certiRow-id1");


        var newUseerow = parseInt(lastUsee) + 1;
        var html = '<div class="col-sm-4 othercertiDet1Row" style="margin-top: 30px;" certiRow-id1="' + newUseerow + '" >\n\
   <span class="float-right removeCertinew">\n\
   <i class="fa fa-trash"></i>\n\
   </span>\n\
   <div class="form-group">\n\
   <div class="fileinput fileinput-new apprFileinput" style="" data-provides="fileinput">\n\
   <div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput">\n\
   </div>\n\
   <p class="">( png, jpeg, jpg )</p>\n\
   <div class="file-pop">\n\
   <span class="text-green btn-file">\n\
   <span class="photo fileinput-new" title="Add Image">\n\
   <img class="imgupload" src="' + baseUrl + '/assets/images/photo.png" style=" width: 30%; ">\n\
   </span>\n\
   <span class="fileinput-exists" title="Add Image">\n\
   </span>\n\
   <input type="file" name="useeafterfix[]" class="atarfile" accept="image/png, image/jpeg">\n\
   </span>\n\
   <button type="button" name="re" class="btn btn-nothing text-maroon fileinput-exists" data-dismiss="fileinput" title="Remove Image">\n\
   <i class="fa fa-times-circle-o" aria-hidden="true"></i>\n\
   </button>\n\
   </div>\n\
   </div>\n\
   <input type="hidden" name="other_user_img[]" value=""><input type="hidden" name="useeafterfix[' + newUseerow + '][other_compCerti_ID2]" value="">\n\
   </div>\n\
   </div>\n\
   ';
        $(".addMorecompet1").append(html);

    })
    $(document).on('click', '.removeCertinew', function() {
        $(this).closest('.col-sm-4').remove();
    })
</script>
<!-- ./wrapper -->
<script>
    $(document).ready(function() {
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
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#obs_desc').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript', 'ul', 'ol', 'color']],
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


        $.validator.addMethod("summernoteRequired", function(value, element) {
            var summernoteContent = $(element).summernote('code');
            return summernoteContent.trim() !== '';
        }, "This field is required.");


        $.validator.addMethod('alphanumeric', function(value) {
            return /^[A-Za-z0-9/,.  ]*$/.test(value);
        }, "Please Enter valid Alphanumeric characters with allowed special charters are /,.");


        $.validator.addMethod('accept_address', function(value) {
            return /^[a-zA-Z0-9\/\\n\s,.'-_*()+&^$#@!% ]{1,}$/.test(value);
        }, "Please Enter a valid address");

        function addImageValidation() {
            $.validator.addClassRules("atarfile", {
                imageFormat: true
            });

            $.validator.addMethod("imageFormat", function(value, element) {
                console.log(value);
                if (value) {
                    var extension = value.split('.').pop().toLowerCase();
                    return ['png', 'jpg', 'jpeg'].includes(extension);
                } else {
                    return true;
                }
            }, "Please upload an image in PNG, JPG, or JPEG format.");
        }



        function initializeValidation(isDraft) {
            // Destroy any existing validation
            $("#company-profile").validate().destroy();
            addImageValidation();
            // Reinitialize validation with appropriate settings
            $("#company-profile").validate({
                ignore: [],
                success: function(error) {
                    error.removeClass("error");
                    error.addClass("d-none");
                },
                rules: isDraft ? {
                    "pro[obs_comp_id]": {
                        required: true
                    },
                    "pro[obs_area_id]": {
                        required: true
                    },
                    "pro[obs_building_id]": {
                        required: true
                    },
                    "pro[obs_project_id]": {
                        required: true
                    },
                } : {
                    "pro[obs_owner_id]": {
                        required: true
                    },
                    "pro[obs_owner_eng]": {
                        required: true
                    },
                    "pro[obs_comp_id]": {
                        required: true
                    },
                    "pro[obs_area_id]": {
                        required: true
                    },
                    "pro[obs_building_id]": {
                        required: true
                    },
                    "pro[obs_dept_id]": {
                        required: true
                    },
                    "pro[obs_project_id]": {
                        required: true
                    },
                    "pro[obs_cat_id]": {
                        required: true
                    },
                    "pro[obs_date]": {
                        required: true
                    },
                    "pro[obs_type_id]": {
                        required: true
                    },
                    "pro[obs_risk_id]": {
                        required: function() {
                            return $('#obs_type_id').val() != 3;
                        },
                    },
                    "pro[obs_desc]": {
                        required: true,
                        summernoteRequired: true,
                    },
                },
                messages: isDraft ? {
                    "pro[obs_comp_id]": {
                        required: "Company is required for draft."
                    },
                    "pro[obs_area_id]": {
                        required: "Area is required for draft."
                    },
                    "pro[obs_building_id]": {
                        required: "Building/Block/Direction is required for draft."
                    },

                    "pro[obs_project_id]": {
                        required: "Project is required for draft."
                    },
                } : {
                    "pro[obs_owner_id]": {
                        required: "Owner is Required"
                    },
                    "pro[obs_owner_eng]": {
                        required: "Owner Engineer Name is Required"
                    },
                    "pro[obs_comp_id]": {
                        required: "Company is Required"
                    },
                    "pro[obs_area_id]": {
                        required: "Area is Required"
                    },
                    "pro[obs_building_id]": {
                        required: "Building/Block/Direction is Required"
                    },

                    "pro[obs_dept_id]": {
                        required: "Department is Required"
                    },
                    "pro[obs_project_id]": {
                        required: "Project is Required"
                    },
                    "pro[obs_cat_id]": {
                        required: "Category is Required"
                    },
                    "pro[obs_date]": {
                        required: "Observation Date is Required"
                    },
                    "pro[obs_type_id]": {
                        required: "Observation Type is Required"
                    },
                    "pro[obs_risk_id]": {
                        required: "Risk Rating is Required"
                    },
                    "pro[obs_desc]": {
                        required: "Observation Description is Required",
                        summernoteRequired: "Observation Description is Required",
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