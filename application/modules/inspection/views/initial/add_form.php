<?php
$getProgramdatas = isset($getProgramdatas[0]) ? $getProgramdatas[0] : null;
$editId = postData($getProgramdatas, 'ins_auto_id', 0);


if ($isatar) {
    $formTitle = 'Edit Initial Atar Inspection';
    $back = 'inspection/initial/initial_atar_list';
} else {
    $formTitle = ($editId > 0) ? 'Edit Inspection' : 'Add Inspection';
    $back = 'inspection/initial/inspection_list';
}

// echo '<pre>';
// print_r($getItemdatas);
// exit;

$getins_desc = isset($getProgramdatas->ins_desc) && !empty($getProgramdatas->ins_desc) ? $getProgramdatas->ins_desc : set_value('pro[ins_desc]');

$edit_id = postData($getProgramdatas, 'ins_auto_id', $this->input->post('pro[ins_auto_id]'));

$InsId = postData($getProgramdatas, 'ins_id', $this->input->post('pro[ins_id]'));
$editOwner_id = !empty($this->input->post('pro[ins_owner_id]')) ? postData($getProgramdatas, 'ins_owner_id', $this->input->post('pro[ins_owner_id]')) : '1';
$editowner_eng_id = !empty($this->input->post('pro[ins_owner_eng]')) ? postData($getProgramdatas, 'ins_owner_eng', $this->input->post('pro[ins_owner_eng]')) : '1';
$editins_epc_id = !empty($this->input->post('pro[ins_epc_id]')) ? postData($getProgramdatas, 'ins_epc_id', $this->input->post('pro[ins_epc_id]')) : '1';
$editComp_id = postData($getProgramdatas, 'ins_comp_id', $this->input->post('pro[ins_comp_id]'));
$editArea_id = isset($getProgramdatas->ins_area_id) && !empty($getProgramdatas->ins_area_id) ? postData($getProgramdatas, 'ins_area_id', $this->input->post('pro[ins_area_id]')) : '';
$editBuilding_id = isset($getProgramdatas->ins_building_id) && !empty($getProgramdatas->ins_building_id) ? postData($getProgramdatas, 'ins_building_id', $this->input->post('pro[ins_building_id]')) : "";
$editDeptId  = postData($getProgramdatas, 'ins_dept_id', $this->input->post('pro[ins_dept_id]'));
$editProjId  = postData($getProgramdatas, 'ins_project_id', $this->input->post('pro[ins_project_id]'));
$editCatId = postData($getProgramdatas, 'ins_cat_id', $this->input->post('pro[ins_cat_id]'));
$editObsdate = postData($getProgramdatas, 'ins_date', $this->input->post('pro[ins_date]'));
$editins_type_id = postData($getProgramdatas, 'ins_type_id', $this->input->post('pro[ins_type_id]'));
$editRiskId = postData($getProgramdatas, 'ins_risk_id', $this->input->post('pro[ins_risk_id]'));

$Reporter = isset($getProgramdatas->Reporter) && !empty($getProgramdatas->Reporter) ? $getProgramdatas->Reporter : $_SESSION['user_details']['NAME'];
$ReporterID = isset($getProgramdatas->ins_reporter_id) && !empty($getProgramdatas->ins_reporter_id) ? $getProgramdatas->ins_reporter_id : $_SESSION['userinfo']->LOGIN_ID;
$positionName = isset($getProgramdatas->desig) && !empty($getProgramdatas->desig) ? $getProgramdatas->desig : $_SESSION['user_details']['DESIGNATION'];
$positionID = isset($getProgramdatas->ins_reporter_desg_id) && !empty($getProgramdatas->ins_reporter_desg_id) ? $getProgramdatas->ins_reporter_desg_id : $_SESSION['user_details']['DESIGNATIONID'];
$roleID = isset($getProgramdatas->ins_reporter_type_id) && !empty($getProgramdatas->ins_reporter_type_id) ? $getProgramdatas->ins_reporter_type_id : $_SESSION['emp_details']->EMP_USERTYPE_ID;

$app_status = postData($getProgramdatas, 'ins_app_status', $this->input->post('pro[ins_app_status]'));


$currentDateTime = date('d-m-Y H:i:s');
$dateTimeatar = isset($getProgramdatas->ins_report_datetime) && !empty($getProgramdatas->ins_report_datetime) ? date('Y-m-d', strtotime($getProgramdatas->ins_report_datetime)) : $currentDateTime;

?>
<style>
    #map {

        height: 360px;
        width: 100%;
    }

    .keyContdetails {
        border: 2px solid #1e6dab;
    }

    .fit-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.min.js"></script>
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
                                    <a href="<?php echo BASE_URL($back) ?>"><button type="button" class="btn btn-primary backBtns">Back</button></a>
                                </div>
                            </div>


                            <div class="card-body">
                                <?php
                                $getProid = isset($getProgramdatas->ins_auto_id) && !empty($getProgramdatas->ins_auto_id) ? $getProgramdatas->ins_auto_id : '';
                                ?>
                                <?php echo form_open_multipart('inspection/initial/save_inspection/' . encryptval($getProid),  'class="form-horizontal" id="company-profile" novalidate'); ?>
                                <input type="hidden" name="initial_ins_id" id="initial_ins_id" value="<?php echo !empty($edit_id) ? $edit_id : '' ?>">
                                <!-- <input type="file" name="image"> -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h5 class=""> Inspection Observer Details </h5>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="pro[ins_app_status]" value="<?php echo $app_status; ?>">
                                <input type="hidden" name="isatar" value="<?php echo $isatar; ?>">

                                <div class="panel-body col-md-12">

                                    <div class="row m-t-10 m-l-10 m-b-10">


                                        <div class="col-md-4">

                                            <label>Inspection Observer</label>
                                            <?php

                                            $cMaildatas = [
                                                'name' => 'pro[ins_reporter]',
                                                'class' => 'form-control',
                                                'value' => $Reporter,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($cMaildatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[ins_reporter]') ?></label>

                                        </div>
                                        <div class="col-md-4" style="display:none;">

                                            <label>Reporter ID </label>
                                            <?php

                                            $crepdatas = [
                                                'name' => 'pro[ins_reporter_id]',
                                                'class' => 'form-control',
                                                'value' => $ReporterID,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($crepdatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[ins_reporter_id]') ?></label>

                                        </div>
                                        <div class="col-md-4" style="display:none;">

                                            <label>Auto generated ID </label>
                                            <?php

                                            $crepdatas = [
                                                'name' => 'pro[ins_main_id]',
                                                'class' => 'form-control',
                                                'value' => $InsId,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($crepdatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[ins_main_id]') ?></label>

                                        </div>
                                        <div class="col-md-4" style="display:none;">

                                            <label>Role ID </label>
                                            <?php

                                            $crepdatas = [
                                                'name' => 'pro[ins_reporter_role_id]',
                                                'class' => 'form-control',
                                                'value' => $roleID,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($crepdatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[ins_reporter_role_id]') ?></label>

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
                                                'name' => 'pro[ins_report_datetime]',
                                                'class' => 'form-control',
                                                'value' => $dateTimeatar,
                                                'autocomplete' => 'off',
                                                'readonly' => TRUE
                                            ];
                                            echo form_input($cdatetimedatas);
                                            ?>
                                            <label class="error"><?php echo form_error('pro[ins_report_datetime]') ?></label>

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
                                                    <input type="hidden" name="pro[ins_owner_id]" value="<?php echo $editOwner_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[ins_owner_id]', $owner_list, $editOwner_id, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[ins_owner_id]') ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> Owner Engineer Name<span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'ins_owner_eng',
                                                    'checkSelect' => 'select2',
                                                ];
                                                if (!empty($editowner_eng_id)) {
                                                    $cmpdata['disabled']  = TRUE;
                                                ?>
                                                    <input type="hidden" name="pro[ins_owner_eng]" value="<?php echo $editowner_eng_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[ins_owner_eng]', $owner_engineer_list, $editowner_eng_id, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[ins_owner_eng]') ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> EPC<span class="error"> * </span></label>
                                                <?php
                                                $cmpdata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'ins_epc_id',
                                                    'checkSelect' => 'select2',
                                                ];
                                                if (!empty($editins_epc_id)) {
                                                    $cmpdata['disabled']  = TRUE;
                                                ?>
                                                    <input type="hidden" name="pro[ins_epc_id]" value="<?php echo $editins_epc_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[ins_epc_id]', $EPC_list, $editins_epc_id, $cmpdata);
                                                ?>
                                                <span class="error"><?php echo form_error('pro[ins_epc_id]') ?></span>
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
                                                    <input type="hidden" name="pro[ins_comp_id]" value="<?php echo $editComp_id; ?>">
                                                <?php }
                                                echo form_dropdown('pro[ins_comp_id]', $dropcompany, $editComp_id, $cmpdata);
                                                ?>

                                                <span class="error"><?php echo form_error('pro[ins_comp_id]') ?></span>
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> Area<span class="error"> * </span></label>
                                                <select class="form-control area select2" checkSelect="select2" name="pro[ins_area_id]" id="area">
                                                    <option value="">Select Area</option>
                                                </select>

                                                <?php if ($editArea_id) { ?>
                                                    <input type="hidden" name="pro[ins_area_id]" value="<?php echo $editArea_id; ?>">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Building/Block/Direction<span class="error"> * </span></label>
                                                <select class="form-control building select2 " checkSelect="select2" name="pro[ins_building_id]" id="building">
                                                    <option value="">Select Building/Block/Direction</option>
                                                </select>
                                                <?php if ($editBuilding_id) { ?>
                                                    <input type="hidden" name="pro[ins_building_id]" value="<?php echo $editBuilding_id; ?>">
                                                <?php } ?>
                                            </div>
                                        </div>



                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Department<span class="error"> * </span></label>
                                                <select class="form-control department select2 " checkSelect="select2" name="pro[ins_dept_id]" id="department">
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
                                                    <input type="hidden" name="pro[ins_project_id]" value="<?php echo $editProjId; ?>">
                                                <?php }
                                                echo form_dropdown('pro[ins_project_id]', $dropproject, $editProjId, $cmpdata);
                                                ?>

                                                <span class="error"><?php echo form_error('pro[ins_project_id]') ?></span>
                                            </div>
                                        </div>


                                    </div>


                                </div>


                                <div class="row m-t-10">
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h5 class=""> Inspection Details </h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel-body col-md-12">
                                    <div class="row m-t-10 m-l-10">
                                        <div class="col-sm-4 m-t-10">
                                            <label>Category <span class="error"> * </span></label>
                                            <?php
                                            $unitdataimp = [
                                                'class' => 'form-control select2',
                                                'id' => 'ins_cat_id',
                                                'checkSelect' => 'select2'
                                            ];

                                            if (!empty($editId)) {
                                                $unitdataimp['disabled']  = TRUE;
                                            ?>
                                                <input type="hidden" name="pro[ins_cat_id]" value="<?php echo $editCatId; ?>">
                                            <?php }
                                            echo form_dropdown('pro[ins_cat_id]', $hsecatDetails, $editCatId, $unitdataimp);
                                            ?>
                                            <span class="error"><?php echo form_error('pro[ins_cat_id]') ?></span>
                                        </div>

                                        <div class="col-sm-4 m-t-10">
                                            <label>Date of Inspection <span class="error"> * </span></label>
                                            <?php
                                            $emMaildatas = [
                                                'name' => 'pro[ins_date]',
                                                'id' => 'ins_date',
                                                'class' => 'form-control',
                                                'value' => $editObsdate,
                                                'autocomplete' => 'off',
                                                'placeholder' => 'Enter Inspection Date',
                                            ];
                                            echo form_input($emMaildatas);
                                            ?>
                                            <span class="error"><?php echo form_error('pro[ins_date]') ?></span>
                                        </div>
                                        <div class="col-sm-4 m-t-10 ">
                                            <div class="row m-t-10" id="image_div" style="display: none;  ">
                                                <img src="" alt="Catgeory Image" style="width: 6cm; height: 4cm;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4"></div>

                                    <div id="modalContainer"></div>

                                    <div class="row m-t-10" id="subcategory_div" style="display: none">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Sl. No.</th>
                                                    <th>Items to be checked</th>
                                                    <th class="text-success">G</th>
                                                    <th class="text-danger">B</th>
                                                    <th>Evidence</th>
                                                    <th>Comments / Notes</th>
                                                    <th>Send to Atar</th>
                                                </tr>
                                            </thead>
                                            <tbody id="subcategory_table">

                                            </tbody>
                                        </table>
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
                            <?php echo form_close(); ?>
                        </div>
                    </div>

                </div>


            </div>
    </div>
    </section>
</div>

</div>


<script>
    $(document).ready(function() {
        function getAreaDetails(company) {
            // alert('hello');
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
            var obsType = $('#ins_type_id').val();
            if (obsType == 1 || obsType == 2) {
                $('.risk_rating').show();
            } else {
                $('.risk_rating').hide();
                $('#ins_risk_id').val(null).trigger('change');
            }
        }

        toggleFields();

        $('#ins_type_id').on('change', function() {
            toggleFields();
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#ins_desc').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript', 'ul', 'ol', 'color']],
            ],

        });

        const today = new Date();
        const pastTwoDays = new Date();
        pastTwoDays.setDate(today.getDate() - 2);

        // Initialize the datepicker
        $("#ins_date").datepicker({
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
                    "pro[ins_comp_id]": {
                        required: true
                    },
                    "pro[ins_area_id]": {
                        required: true
                    },
                    "pro[ins_building_id]": {
                        required: true
                    },
                    "pro[ins_project_id]": {
                        required: true
                    },
                    "pro[ins_cat_id]": {
                        required: true
                    },
                } : {
                    "pro[ins_owner_id]": {
                        required: true
                    },
                    "pro[ins_owner_eng]": {
                        required: true
                    },
                    "pro[ins_comp_id]": {
                        required: true
                    },
                    "pro[ins_area_id]": {
                        required: true
                    },
                    "pro[ins_building_id]": {
                        required: true
                    },
                    "pro[ins_dept_id]": {
                        required: true
                    },
                    "pro[ins_project_id]": {
                        required: true
                    },
                    "pro[ins_cat_id]": {
                        required: true
                    },
                    "pro[ins_date]": {
                        required: true
                    },
                    "pro[ins_type_id]": {
                        required: true
                    },
                    "pro[ins_risk_id]": {
                        required: function() {
                            return $('#ins_type_id').val() != 3;
                        },
                    },
                    "pro[ins_desc]": {
                        required: true,
                        summernoteRequired: true,
                    },

                },
                messages: isDraft ? {
                    "pro[ins_comp_id]": {
                        required: "Company is required for draft."
                    },
                    "pro[ins_area_id]": {
                        required: "Area is required for draft."
                    },
                    "pro[ins_building_id]": {
                        required: "Building/Block/Direction is required for draft."
                    },

                    "pro[ins_project_id]": {
                        required: "Project is required for draft."
                    },
                    "pro[ins_cat_id]": {
                        required: "Category is Required for draft"
                    },
                } : {
                    "pro[ins_owner_id]": {
                        required: "Owner is Required"
                    },
                    "pro[ins_owner_eng]": {
                        required: "Owner Engineer Name is Required"
                    },
                    "pro[ins_comp_id]": {
                        required: "Company is Required"
                    },
                    "pro[ins_area_id]": {
                        required: "Area is Required"
                    },
                    "pro[ins_building_id]": {
                        required: "Building/Block/Direction is Required"
                    },

                    "pro[ins_dept_id]": {
                        required: "Department is Required"
                    },
                    "pro[ins_project_id]": {
                        required: "Project is Required"
                    },
                    "pro[ins_cat_id]": {
                        required: "Category is Required"
                    },
                    "pro[ins_date]": {
                        required: "Inspection Date is Required"
                    },
                    "pro[ins_type_id]": {
                        required: "Observation Type is Required"
                    },
                    "pro[ins_risk_id]": {
                        required: "Risk Rating is Required"
                    },
                    "pro[ins_desc]": {
                        required: "Observation Description is Required",
                        summernoteRequired: "Observation Description is Required",
                    },
                    "pro[item[notes]]": {
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

        $("#save").on("click", function(e) {
            e.preventDefault();
            let isValid = true;
            Swal.fire({
                title: "Are you sure?",
                text: "You want to submit the form.",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {


                    $('#subcategory_table tr').each(function() {
                        let dropdown = $(this).find('select[name^="item[outcome]"]');
                        let notesInput = $(this).find('textarea[name^="item[notes]"]');
                        let isChecked = $(this).find('input[name^="item[is_checked]"]').prop('checked');
                        let errorMessage = notesInput.siblings('.error-message');

                        if (isChecked) {
                            if (notesInput.val().trim() === '') {
                                isValid = false;
                                errorMessage.show();
                                event.preventDefault();
                            } else {
                                errorMessage.hide();
                            }
                        } else {
                            if (dropdown.val() === '2' || dropdown.val() === '1') {
                                if (notesInput.val().trim() === '') {
                                    isValid = false;
                                    errorMessage.show();
                                    event.preventDefault();
                                } else {
                                    errorMessage.hide();
                                }
                            } else {
                                errorMessage.hide();
                            }
                        }


                    });
                    $("#action_type").val(1);
                    initializeValidation(false);
                    if ($("#company-profile").valid() && isValid) {
                        $("#company-profile").submit();
                    }
                }
            });

        });



        $("#drafted").on("click", function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You want to save as draft.",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#action_type").val(0);
                    initializeValidation(true);
                    $("#company-profile").submit();
                }
            });
        });

    });


    $(document).ready(function() {

        var categoryId = $('#ins_cat_id').val();

        var isEditForm = categoryId !== "";


        $('#ins_cat_id').change(function() {
            categoryId = $(this).val();
            fetchCategoryDetails(categoryId);
        });


        if (isEditForm) {
            fetchCategoryDetails(categoryId);
        }


        function generateDropdown(slNo, editStatus) {
            const dropinitialstatus = <?php echo json_encode($dropinitialstatus); ?>;

            let dropdown = `<select id="dropdown_${slNo}" name="item[outcome][${slNo}]" class="form-control select2 initial_status">`;

            for (const [value, label] of Object.entries(dropinitialstatus)) {
                const selected = value == editStatus ? 'selected' : '';
                dropdown += `<option value="${value}" ${selected}>${label}</option>`;
            }

            dropdown += '</select>';
            return dropdown;
        }



        function fetchCategoryDetails(categoryId) {
            var insId = $('#initial_ins_id').val();

            $('#subcategory_div').hide();

            $('#image_div').hide();

            $('#subcategory_table').empty();
            $('#modalContainer').empty();

            let itemData = <?php echo json_encode($getItemdatas); ?>;
            let isatar = <?php echo json_encode($isatar); ?>;

            let item_id = '';
            let subcatdata_id = '';

            if (isatar && itemData.length > 0) {
                item_id = itemData[0].insp_item_auto_id;
                subcatdata_id = itemData[0].fk_item_subcatdata_id;
            }

            $.ajax({
                url: "<?php echo BASE_URL() . 'inspection/initial/fetchCategoryDetails'; ?>",
                type: "POST",
                data: {
                    category_id: categoryId,
                    ins_id: insId,
                    item_id: item_id,
                    subcatdata_id: subcatdata_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: "json",
                success: function(response) {
                    var subcategoryTable = $('#subcategory_table');
                    var modalContainer = $('#modalContainer');
                    subcategoryTable.empty();
                    var slNo = 1;
                    $('#subcategory_div').show();

                    if (response.subcategoriesdata) {

                        var evidence = response.evidence_images;
                        $.each(response.subcategoriesdata, function(subcatId, subcategory) {

                            if (!subcategory.data || subcategory.data.length === 0) {
                                return;
                            }

                            subcategoryTable.append(
                                '<tr><td colspan="5"><strong>' +
                                subcategory.subcategory +
                                '</strong></td></tr>'
                            );



                            let itemData = <?php echo json_encode($getItemdatas); ?>;
                            $.each(subcategory.data, function(key, data) {
                                let checkedYes = '';
                                let checkedNo = '';
                                let itemActionReq = '';
                                let ins_item_id = '';
                                let is_checked = '';
                                let unique_id = '';


                                $.each(itemData, function(index, item) {

                                    if (item.fk_item_subcatdata_id == data.id) {
                                        if (item.insp_item_condition == '0') {
                                            checkedYes = 'checked';
                                        } else if (item.insp_item_condition == '1') {
                                            checkedNo = 'checked';
                                        }
                                        itemActionReq = item.insp_desc || '';
                                        ins_item_id = item.insp_item_auto_id || '';
                                        unique_id = item.insp_atar_uni_id || '';
                                        is_checked = (parseInt(item.send_to_atar) === 1) ? 'checked' : '';
                                    }
                                });

                                subcategoryTable.append(
                                    '<tr>' +
                                    '<input type="hidden" name="item[ins_item_id][' + slNo + ']" value="' + ins_item_id + '"> ' +
                                    '<input type="hidden" name="item[ins_item_uni_id][' + slNo + ']" value="' + unique_id + '"> ' +
                                    '<input type="hidden" name="item[ins_sub_id][' + slNo + ']" value="' + data.fk_subcat_id + '"> ' +
                                    '<td>' +
                                    slNo +
                                    '</td>' +
                                    '<td>' +
                                    data.subcategorydata +
                                    '</td>' +
                                    '<input type="hidden" name="item[sub_cat_data_id][' + slNo + ']" value="' + data.id + '">' +
                                    '<td><input type="radio" name="item[selection][' + slNo + ']" value="0"  ' + checkedYes + ' checked /></td>' +
                                    '<td><input type="radio" name="item[selection][' + slNo + ']" value="1" ' + checkedNo + ' /></td>' +
                                    '<td class="text-center">' +
                                    '<i class="fas fa-upload upload-icon" data-toggle="modal" data-target="#uploadModal_' + data.id + '" data-id="' + data.id + '" style="cursor: pointer; font-size: 16px; color:green;" title="Upload"></i>' +
                                    '</td>' +
                                    '<td><textarea class="form-control notes-field" name="item[notes][' + slNo + ']" placeholder="Enter comments" >' + itemActionReq + '</textarea>' +
                                    '<div class="error-message" style="color: red; display: none;"> Comments / Notes.</div>' +
                                    '<td class="text-center" ><input type="checkbox" class="custom-checkbox sendAtar" name="item[is_checked][' + slNo + ']" value="1" ' + is_checked + ' >' +
                                    '</td>' +
                                    '</tr>' +
                                    slNo++
                                );

                                $('#dropdown_' + slNo).select2();
                                slNo++


                                let modalContent = `
                                    <div class="modal fade" id="uploadModal_${data.id}" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="max-width: 60%;">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="uploadModalLabel">Upload Evidence</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <input type="hidden" name="image_subcatdata_id[]" value="${data.id}">
                                                        <button type="button" id="add-more" data-id="${data.id}" class="btn btn-primary btn-sm" style="position: absolute; right: 10px; top: 10px;">Add More</button>
                                                        <div class="img-container row" style="margin-top: 20px;">`;

                                if (evidence[data.id] && evidence[data.id].length > 0) {
                                    $.each(evidence[data.id], function(index, imageData) {
                                        const imagePath = imageData.image_path;
                                        const imageId = imageData.id;
                                        modalContent += `
                                        <div class="col-md-4 image-upload">
                                            <div class="fileinput fileinput-new apprFileinput imgGroup" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput">
                                                    <img src="<?php echo BASE_URL(); ?>/${imagePath}" alt="Evidence Image" class="img-thumbnail fit-image">
                                                </div>
                                                <p>(png, jpeg, jpg)</p>
                                                <div class="file-pop">
                                                    <span class="text-green btn-file">
                                                        <span class="photo fileinput-new" title="Add Image">
                                                            <img class="imgupload" src="<?php echo BASE_URL('/assets/images/photo.png'); ?>" style="width: 25%; margin-top: 10px; margin-left: 30px" />
                                                        </span>
                                                        <input type="file" name="images[${data.id}][]" class="atarfile" accept="image/png, image/jpeg" id="imageUpload">
                                                        <input type="hidden" name="existing_image[${data.id}][]" value="${imagePath}">
                                                        <input type="hidden" name="image_id[${data.id}][]" class"image_id" value="${imageId}">
                                                    </span>
                                                </div>
                                                 <button type="button" class="position-absolute delete-btn" style="top: 5px; right: 5px; background: none; border: none; padding: 0; cursor: pointer; outline: none;">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </div>`;
                                    });
                                } else {
                                    modalContent += `
                                    <div class="col-md-4 image-upload">
                                        <div class="fileinput fileinput-new apprFileinput imgGroup" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput"></div>
                                            <p>(png, jpeg, jpg)</p>
                                            <div class="file-pop">
                                                <span class="text-green btn-file">
                                                    <span class="photo fileinput-new" title="Add Image">
                                                        <img class="imgupload" src="<?php echo BASE_URL('/assets/images/photo.png'); ?>" style="width: 25%; margin-top: 10px; margin-left: 30px" />
                                                    </span>
                                                    <input type="file" name="images[${data.id}][]" class="atarfile" accept="image/png, image/jpeg" id="imageUpload">
                                                    <input type="hidden" name="existing_image[${data.id}][]" value="">
                                                    <input type="hidden" name="image_id[${data.id}][]" class"image_id" value="">
                                                </span>
                                            </div>
                                        </div>
                                    </div>`;
                                }

                                modalContent += `
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                                modalContainer.append(modalContent);


                            });

                        });
                        if (subcategoryTable.is(':empty')) {
                            subcategoryTable.append(
                                '<tr>' +
                                '<td colspan = "5" style="text-align: center;">' +
                                "No Data Found" +
                                '</td>' +
                                '</tr>'
                            );
                        }
                    } else {
                        subcategoryTable.append(
                            '<tr>' +
                            '<td colspan = "5" style="text-align: center;">' +
                            "No Data Found" +
                            '</td>' +
                            '</tr>'
                        );
                    }
                    if (response.image_path) {
                        $('#image_div img').attr('src', '<?php echo BASE_URL(); ?>' + response.image_path);
                        $('#image_div').show();
                    } else {
                        $('#image_div img').attr('src', '');
                        $('#image_div').hide();
                    }
                },
                error: function() {
                    alert('Failed to fetch data. Please try again.');
                }
            });
        }

        $(document).on('click', '#add-more', function() {
            let dataId = $(this).data('id');
            let newImageUpload = `
                <div class="col-md-4 image-upload">
                    <div class="fileinput fileinput-new apprFileinput imgGroup" data-provides="fileinput">
                        <div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput"></div>
                        <p>(png, jpeg, jpg)</p>
                        <div class="file-pop">
                            <span class="text-green btn-file">
                                <span class="photo fileinput-new" title="Add Image">
                                    <img class="imgupload" src="<?php echo BASE_URL('/assets/images/photo.png'); ?>" style="width: 25%; margin-top: 10px; margin-left: 30px; " />
                                </span>
                                <input type="file" name="images[${dataId}][]" class="atarfile" accept="image/png, image/jpeg" id="imageUpload">
                                <input type="hidden" name="existing_image[${dataId}][]" value="">
                                <input type="hidden" name="image_id[${dataId}][]" class"image_id" value="">
                            </span>
                        </div>
                        <button type="button" class="position-absolute delete-btn" style="top: 5px; right: 5px; background: none; border: none; padding: 0; cursor: pointer; outline: none;">
                            <i class="fas fa-trash text-danger"></i>
                        </button>
                    </div>
                </div>
                `;
            $(this).closest('.modal-body').find('.img-container').append(newImageUpload);
        });

        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfToken = '<?php echo $this->security->get_csrf_hash(); ?>';


        $(document).on('click', '.delete-btn', function() {
            var url = '<?php echo BASE_URL("inspection/initial/delete_evidence"); ?>';
            var deletId = $(this).closest('.image-upload').find('input[name^="image_id"]').val();
            var deleteButton = $(this);
            swal({
                    title: "Are you sure",
                    text: "You want to delete this? Once deleted, it cannot be recovered.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    closeButtonClass: "btn-danger",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {

                        if (deletId) {
                            var data = {
                                delid: deletId,
                                'csrf_osh_name': csrfToken
                            }
                            $.ajax({
                                url: url,
                                type: "post",
                                data: data,
                                dataType: 'JSON',
                                cache: false,
                                success: function(resp) {

                                    if (resp.status) {
                                        swal({
                                                title: "Success",
                                                text: resp.msgs,
                                                type: "success",
                                                confirmButtonClass: "btn-primary",
                                                confirmButtonText: "OK",
                                                closeOnConfirm: false,

                                            },
                                            function(isConfirm) {
                                                if (isConfirm) {
                                                    swal.close();
                                                    deleteButton.closest('.image-upload').remove();
                                                }
                                            }
                                        )
                                        //                                swal('Success', resp.msgs, 'success');

                                    } else {
                                        swal({
                                                title: "Error",
                                                text: resp.msgs,
                                                type: "error",
                                                confirmButtonClass: "btn-primary",
                                                confirmButtonText: "OK",
                                                closeOnConfirm: false,

                                            },
                                            function(isConfirm) {
                                                if (isConfirm) {
                                                    swal.close();
                                                    // location.reload();
                                                }
                                            }
                                        )

                                    }
                                }
                            });


                        } else {
                            // swal.close();
                            swal({
                                    title: "Success",
                                    text: "Evidence has been deleted successfully",
                                    type: "success",
                                    confirmButtonClass: "btn-primary",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false,
                                },
                                function(isConfirm) {
                                    if (isConfirm) {
                                        swal.close();
                                    }
                                }
                            )
                            deleteButton.closest('.image-upload').remove();

                        }



                    } else {
                        swal('Sorry', 'There occured some error', 'warning');
                        swal.close();
                    }
                });



        });


        $(document).on("click", ".sendAtar", function(e) {
            e.preventDefault();
            var $this = $(this);
            var currentState = $this.prop('checked');
            currentState = (currentState) ? false : true;

            if (currentState) {
                var condition = 'unsend';
            } else {
                var condition = 'send';
            }
            swal({
                    title: "Are you sure ?",
                    text: "<b>Once Inspection submitted, will be " + condition + " to ATAR</b>",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    closeButtonClass: "btn-danger",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: false,
                    html: true,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        swal.close();
                        (currentState) ? $this.prop('checked', false): $this.prop('checked', true);
                    } else {
                        swal.close();
                        (currentState) ? $this.prop('checked', true): $this.prop('checked', false);
                    }
                });
        });
    });
</script>