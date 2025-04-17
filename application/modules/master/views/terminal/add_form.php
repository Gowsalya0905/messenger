<?php
$editId = postData($getDesdatas, 'TER_AUTO_ID', 0);
$formTitle = ($editId > 0 ) ? 'Edit Plant' : 'Add Plant';
?>
<style>
    #map {

        height:360px;
        width: 100%;
    }
    .keyContdetails{
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
                                    
                                    <a href="<?php echo BASE_URL('master/terminal/terminalInfo') ?>"><button type="button" class="btn btn-primary backBtns">Back</button></a>
                                </div>
                            </div>


                            <div class="card-body">
                                <?php
                                $getTerid = isset($getTerminaldatas->TER_AUTO_ID) && !empty($getTerminaldatas->TER_AUTO_ID) ? $getTerminaldatas->TER_AUTO_ID : '';
                                ?>
                                <?php echo form_open('master/terminal/saveTerminal/' . encryptval($getTerid), 'class="form-horizontal" id="company-profile" novalidate'); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-header with-border" >
                                            <h5 class="">  Plant Details </h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel-body col-md-12">
                                    
                                    <div class="row m-t-10 m-l-10">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Plant ID  <span class="error">*</span></label>
                                                <?php
                                                $AutoId = isset($getTerminaldatas->TERMINAL_ID) && !empty($getTerminaldatas->TERMINAL_ID) ? $getTerminaldatas->TERMINAL_ID : $getAutoId;
                                                $cMaildatas = [
                                                    'name' => 'ter[TERMINAL_ID]',
                                                    'class' => 'form-control',
                                                    'value' => $AutoId,
                                                    'autocomplete' => 'off',
                                                    'readonly' => TRUE
                                                ];
                                                echo form_input($cMaildatas);
                                                ?>
                                                <label class="error"><?php echo form_error('loc[TERMINAL_ID]') ?></label>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Plant Name  <span class="error">*</span></label>
                                                <?php
                                                $getTername = isset($getTerminaldatas->TERMINAL_NAME) && !empty($getTerminaldatas->TERMINAL_NAME) ? $getTerminaldatas->TERMINAL_NAME : set_value('ter[TERMINAL_NAME]');
                                                $cnamedata = array(
                                                    'name' => 'ter[TERMINAL_NAME]',
                                                    'id' => 'TERMINAL_NAME',
                                                    'placeholder' => 'Enter Plant Name',
                                                    'class' => 'form-control',
                                                    'value' => $getTername,
                                                    'required' => true,
                                                    'minlength' => 3,
                                                    // 'maxlength' => 25,
                                                    'autocomplete' => 'off'
                                                );

                                                echo form_input($cnamedata);
                                                ?>
                                                <label class="error"><?php echo form_error('ter[TERMINAL_NAME]') ?></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Plant Short Name  <span class="error">*</span></label>
                                                <?php
                                                $getshoTername = isset($getTerminaldatas->TERMINAL_SHORTNAME) && !empty($getTerminaldatas->TERMINAL_SHORTNAME) ? $getTerminaldatas->TERMINAL_SHORTNAME : set_value('ter[TERMINAL_SHORTNAME]');
                                                $cshortnamedata = array(
                                                    'name' => 'ter[TERMINAL_SHORTNAME]',
                                                    'id' => 'TERMINAL_SHORTNAME',
                                                    'placeholder' => 'Enter Plant Short Name',
                                                    'class' => 'form-control',
                                                    'value' => $getshoTername,
                                                    'required' => true,
                                                    'minlength' => 1,
                                                    'maxlength' => 5,
                                                    'autocomplete' => 'off'
                                                );

                                                echo form_input($cshortnamedata);
                                                ?>
                                                <label class="error"><?php echo form_error('ter[TERMINAL_SHORTNAME]') ?></label>
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <div class="row m-t-10 m-l-10">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Plant Email </label>
                                                <?php
                                                $gettermail = isset($getTerminaldatas->TERMINAL_EMAIL) && !empty($getTerminaldatas->TERMINAL_EMAIL) ? $getTerminaldatas->TERMINAL_EMAIL : set_value('ter[TERMINAL_EMAIL]');
                                                $cEmaildata = array(
                                                    'name' => 'ter[TERMINAL_EMAIL]',
                                                    'id' => 'TERMINAL_EMAIL',
                                                    'placeholder' => 'Enter Plant Email',
                                                    'class' => 'form-control',
                                                    'value' => $gettermail,
                                                    // 'required' => true,
                                                    'minlength' => 3,
                                                    // 'maxlength' => 25,
                                                    'autocomplete' => 'off'
                                                );

                                                echo form_input($cEmaildata);
                                                ?>
                                                <label class="error"><?php echo form_error('ter[TERMINAL_EMAIL]') ?></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Plant Phone Number</label>
                                                <?php
                                                $getTerphone = isset($getTerminaldatas->TERMINAL_PHONE) && !empty($getTerminaldatas->TERMINAL_PHONE) ? $getTerminaldatas->TERMINAL_PHONE : set_value('ter[TERMINAL_PHONE]');
                                                $cphonedata = array(
                                                    'name' => 'ter[TERMINAL_PHONE]',
                                                    'id' => 'TERMINAL_PHONE',
                                                    'placeholder' => 'Enter Plant Phone Number',
                                                    'class' => 'form-control',
                                                    'value' => $getTerphone,
                                                    // 'required' => true,
                                                    'minlength' => 3,
                                                    'maxlength' => 25,
                                                    'autocomplete' => 'off'
                                                );

                                                echo form_input($cphonedata);
                                                ?>
                                                <label class="error"><?php echo form_error('ter[TERMINAL_PHONE]') ?></label>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Plant Mobile Number </label>
                                                <?php
                                                $getTermobile = isset($getTerminaldatas->TERMINAL_MOBILE) && !empty($getTerminaldatas->TERMINAL_MOBILE) ? $getTerminaldatas->TERMINAL_MOBILE : set_value('ter[TERMINAL_MOBILE]');
                                                $cmobiledata = array(
                                                    'name' => 'ter[TERMINAL_MOBILE]',
                                                    'id' => 'TERMINAL_MOBILE',
                                                    'placeholder' => 'Enter Plant Mobile Number',
                                                    'class' => 'form-control',
                                                    'value' => $getTermobile,
                                                    'minlength' => 3,
                                                    'maxlength' => 25,
                                                    'autocomplete' => 'off'
                                                );

                                                echo form_input($cmobiledata);
                                                ?>
                                                <label class="error"><?php echo form_error('ter[TERMINAL_MOBILE]') ?></label>
                                            </div>
                                        </div>

                                         
                                    </div>
                                    <div class="row m-l-10">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Plant PIN Code  </label>
                                                <?php
                                                $getTerzip = isset($getTerminaldatas->TERMINAL_ZIPCODE) && !empty($getTerminaldatas->TERMINAL_ZIPCODE) ? $getTerminaldatas->TERMINAL_ZIPCODE : set_value('ter[TERMINAL_ZIPCODE]');
                                                $czipdata = array(
                                                    'name' => 'ter[TERMINAL_ZIPCODE]',
                                                    'id' => 'TERMINAL_ZIPCODE',
                                                    'placeholder' => 'Enter Plant PIN Code',
                                                    'class' => 'form-control',
                                                    'value' => $getTerzip,
                                                    
                                                    'minlength' => 3,
                                                    'maxlength' => 25,
                                                    'autocomplete' => 'off'
                                                );

                                                echo form_input($czipdata);
                                                ?>
                                                <label class="error"><?php echo form_error('ter[TERMINAL_ZIPCODE]') ?></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <!-- textarea -->
                                            <div class="form-group">
                                                <label>Plant Address </label>
                                                <?php
                                                $gettermiadd = isset($getTerminaldatas->TERMINAL_ADDRESS) && !empty($getTerminaldatas->TERMINAL_ADDRESS) ? $getTerminaldatas->TERMINAL_ADDRESS : set_value('ter[TERMINAL_ADDRESS]');
                                                $cAddr1data = array(
                                                    'name' => 'ter[TERMINAL_ADDRESS]',
                                                    'id' => 'TERMINAL_ADDRESS',
                                                    'placeholder' => 'Enter Plant Address',
                                                    'class' => 'form-control',
                                                    'value' => $gettermiadd,
                                                    'rows' => 4,
                                                    'cols' => 4,
                                                    'autocomplete' => 'off'
                                                );
                                                echo form_textarea($cAddr1data);
                                                ?>
                                                <span class="error"><?php echo form_error('ter[TERMINAL_ADDRESS]') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="AddmoreKys"></div>

                                <div class="form-group m-t-10" style="text-align: center;">
                                    <div class="col-sm-offset-2 col-sm-12"> 

                                        <?php
                                        $data = array('id' => 'submit', 'type' => 'submit', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> SUBMIT', 'class' => 'btn btn-primary');
                                        echo form_button($data);
                                        ?>
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
<!-- ./wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("validate_email", function (value, element) {

            if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
                return true;
            } else {
                return false;
            }
        }, "Please enter a valid Email.");

        $.validator.addMethod('alphanumeric', function (value) {
            return /^[A-Za-z0-9/,.  ]*$/.test(value);
        }, "Please Enter valid Alphanumeric characters with allowed special charters are /,.");

        $.validator.addMethod('accept_address', function (value) {
            return /^[a-zA-Z0-9\/\\n\s,.'-_*()+&^$#@!% ]{1,}$/.test(value);
        }, "Please Enter a valid address");
        $.validator.addMethod('phone', function (value) {
            return /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/.test(value);
        }, "Please Enter a valid phone number");
        $.validator.addMethod('mobile', function (value) {
            return /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/.test(value);
        }, "Please Enter a valid mobile number");
        
        $.validator.addMethod('zipcode', function (value) {
            return /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(value);
        }, "Please Enter a valid PIN Code");

        $.validator.addMethod('alphaspace', function (value) {
             return /^[A-Za-z/,. ]*$/.test(value);
        }, "Please Enter valid Alphabetic characters with allowed special charters are /,.");



        $("#company-profile").validate({
//            ignore: [],
//            success: function (error) {
//                error.removeClass("error");
//            },

            rules: {
                
                "ter[TERMINAL_NAME]": {
                    required: true,
                    alphanumeric: true,
                    minlength: 3,
                    // maxlength: 25,
                    programming_char:true
                },
                "ter[TERMINAL_SHORTNAME]": {
                    required: true,
                    alphanumeric: true,
                    minlength: 1,
                    maxlength: 5,
                    programming_char:true
                },
                // "ter[TERMINAL_EMAIL]": {
                //     required: true,
                //     validate_email: true
                // },
                // "ter[TERMINAL_PHONE]": {
                //     required: true,
                //     phone: true,
                //     maxlength: 16,
                //     programming_char:true
                // },
                // "ter[TERMINAL_MOBILE]": {
                //     required: true,
                //     mobile: true,
                //     programming_char:true,
                //     maxlength: 16
                // },
                // "ter[TERMINAL_ZIPCODE]": {
                //     required: true,
                //     zipcode: true,
                //     maxlength: 10,
                //     programming_char:true
                // },
                // "ter[TERMINAL_ADDRESS]": {
                //     required: true,
                //     accept_address: true,
                //     minlength: 2,
                //     maxlength: 255,
                //     programming_char:true
                // },
            },
            messages: {
                "ter[TERMINAL_NAME]": {
                    required: "Plant Name is required"
                },
                "ter[TERMINAL_SHORTNAME]": {
                    required: "Plant Short Name is required"
                },
                "ter[TERMINAL_EMAIL]": {
                    required: "Plant Email is required"
                },
                "ter[TERMINAL_PHONE]": {
                    required: "Plant Phone No. is required"
                },
                "ter[TERMINAL_MOBILE]": {
                    required: "Plant Mobile No. is required"
                },
                "ter[TERMINAL_ZIPCODE]": {
                    required: "Plant PIN Code is required"
                },
                "ter[TERMINAL_ADDRESS]": {
                    required: "Plant Address is required"
                },
            },
            errorPlacement: function (error, element) {

                if (element.attr('checkSelect') == 'select2') {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                    element.closest(".imgGroup").last().append(error);
                }
            }

        });
    });
</script>
