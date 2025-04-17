<div class="content-wrapper">
    <style>
        .imgupload{
            width: 100%;
            height: 100%;
        }
        .fileinput img{
            width: 100%;
            height: 100%;
        }
        .file-pop {
            top: 25% !important;
            margin: 0px !important;
            display: inline-block !important;
            padding-left:20% !important;
            font-size: 20px;
        }
    </style>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">            
                <div class="col-md-12">                
                    <div class="card card-headprimary">
                        <div class="row headingStyl">
                            <div class="col-md-12">
                                <h4> Profile </h4>

                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row" style="margin:10px;">
                                <div class=" col-md-12">
                                    <?php
                                    echo $this->session->flashdata('profilemsg');

                                    $this->session->unset_userdata('profilemsg');

                                    ?>
                                </div>
                            </div>
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="av-profile" aria-selected="true">View Profile</a>
                                    <a class="nav-item nav-link " id="nav-change-password-tab" data-toggle="tab" href="#nav-change-password" role="tab" aria-controls="nav-change-password" aria-selected="false">Change Password</a>
                                    <a class="nav-item nav-link " id="nav-upload-photo-tab" data-toggle="tab" href="#nav-upload-photo" role="tab" aria-controls="nav-upload-photo" aria-selected="false">Upload Photo</a>

                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active " id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    <?php
                                    if ($userTypeId == 11) {
                                        $empData = [
                                            'getEmploydetails' => $profileInfo
                                        ];
                                        echo $this->load->view('cont_profile', $empData, TRUE);
                                    } else {
                                        $empData = [
                                            'getEmploydetails' => $profileInfo
                                        ];
                                        echo $this->load->view('emp_profile', $empData, TRUE);
                                    }
                                    ?>
                                </div>

                                <div class="tab-pane fade" id="nav-change-password" role="tabpanel" aria-labelledby="nav-change-password-tab">
                                    <?php echo form_open('dashboard/savepassword/', 'class="form-horizontal", id="change_password_form"'); ?>                   
                                    <div class="form-group">
                                        <label id="passcheck" class="col-sm-12 control-label">

                                        </label>
                                    </div>                                    


                                    <div class="form-group row">
                                        <label  class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-6">
                                            <?php
                                            $data = array(
                                                'type' => 'password',
                                                'name' => 'password',
                                                'id' => 'password',
                                                'placeholder' => '',
                                                'class' => 'form-control',
                                                'value' => set_value('password'),
                                            );

                                            echo form_input($data);
                                            ?> 
                                            <?php echo form_error('password', '<div class="error">', '</div>'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword" class="col-sm-2 col-form-label">Confirm Password</label>
                                        <div class="col-sm-6">
                                            <?php
                                            $data = array(
                                                'type' => 'password',
                                                'name' => 'confirm_password',
                                                'id' => 'confirm_password',
                                                'placeholder' => '',
                                                'class' => 'form-control',
                                                'value' => set_value('confirm_password'),
                                            );

                                            echo form_input($data);
                                            ?> 
                                            <?php echo form_error('confirm_password', '<div class="error">', '</div>'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <p id="captImg"> <?php
                                                echo $captchaCode;
                                                ?>
                                            </p>
                                            <p>click <a href="javascript:void(0);" class="refreshCaptcha">here</a> to refresh.</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <?php
                                            $data = array(
                                                'name' => 'captcha',
                                                'id' => 'captcha',
                                                'placeholder' => '',
                                                'class' => 'form-control',
                                                'value' => '',
                                            );

                                            echo form_input($data);
                                            ?> 
                                            <span class="error red"><?php echo $captcha_error; ?></span>
                                        </div> 

                                    </div>


                                    <div class="form-group mt-2 text-center">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <?php
                                            echo form_submit('passsubmit', 'Submit', 'class="btn btn-primary" id="passsubmit"');
                                            ?>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>

                                </div>

                                <div class="tab-pane fade" id="nav-upload-photo" role="tabpanel" aria-labelledby="nav-upload-photo-tab">
                                    <?php echo form_open_multipart('dashboard/savePhoto/', 'class="form-horizontal", id="change_photo_form"'); ?>
                                    <div class="form-group">
                                        <label id="photocheck" class="col-sm-12 control-label">
                                            <?php
                                            $profileImg = postData($profileInfo, 'PROFILE_IMG');
                                            ?>
                                        </label>
                                    </div>                                    


                                    <div class="form-group row">
                                        <label  class="col-sm-2 col-form-label">Photo</label>
                                        <div class="col-sm-6">
                                            <div class="form-group">

                                                <div class="fileinput fileinput-new apprFileinput" style="margin-top: -30px !important; " data-provides="fileinput" >
                                                    <div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput" >   
                                                        <?php
                                                        if ($profileImg != '') {
                                                            ?>
                                                            <img src='<?php echo BASE_URL . $profileImg ?>' class="imgupload" alt='profile' style="height: 100%;"/>     
                                                        <?php } ?>

                                                    </div>
                                                    <p class="">(png, jpeg, jpg )</p>
                                                    <div class="file-pop" >                                                    
                                                        <span class="text-green btn-file"><span class="photo fileinput-new" title="Add Image"><img class="imgupload" src='<?php echo BASE_URL("/assets/images/photo.png"); ?>'  style=" width: 30%; "/></span>
                                                            <span class="fileinput-exists" title="Add Image"></span>
                                                            <input type="file" name="profileImg" class='profileImgfile'  accept="image/*"></span>

                                                        <button type="button" name="re" class="btn btn-nothing text-maroon fileinput-exists"  data-dismiss="fileinput" title="Remove Image"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                                <?php echo form_hidden('profileImg_photo', $profileImg); ?>
                                                <br/>
                                                <span id="profileImg_error" class="red"></span>
                                                
                                            </div>

                                            <?php echo form_error('profileImg', '<div class="error">', '</div>'); ?>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group mt-2 text-center">
                                        <div class="col-sm-offset-2 col-sm-8">
                                            <?php
                                            echo form_submit('photosubmit', 'Submit', 'class="btn btn-primary" id="photosubmit"');
                                            ?>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
</div>
<script>
    $(document).ready(function () {

        $('.refreshCaptcha').on('click', function () {
            $.get('<?php echo base_url() . 'dashboard/captcharefresh'; ?>', function (data) {
                $('#captImg').html(data);
            });
        });

        var value = $("#password").val();

       $.validator.addMethod("checklower", function(value) {
  return /[[*|\"%:<>[\]{}`\\()';=!#@&$]/.test(value);
});
$.validator.addMethod("checkupper", function(value) {
  return /[A-Z]/.test(value);
});
$.validator.addMethod("checkdigit", function(value) {
  return /[0-9]/.test(value);
});
$.validator.addMethod("checkspl", function(value) {
  return /[a-z]/.test(value);
});

        $('#change_password_form').validate({
            rules: {

                "password": {
                    required: true,
                    maxlength: 25,
                   minlength: 8,
                    checklower:true,
                    checkupper:true,
                    checkdigit:true,
                    checkspl:true,

                },
                "captcha": {
                    required: true,
                    minlength: 5
                },
                "confirm_password": {
                    required: true,
                   maxlength: 25,
                   minlength: 8,
                    checklower:true,
                    checkupper:true,
                    checkdigit:true,
                     checkspl:true,
//                    equalTo: "#password"
                },
            },
            messages: {
                "password": {
                    required: "Please enter password",
                    checklower:"Please enter atleast one Special Characters",
                    checkupper:"Please enter atleast one Uppercase Alphabet",
                    checkdigit:"Please enter atleast one Numeric",
                    checkspl:"Please enter atleast one Lowercase Alphabet",

                },
                "confirm_password": {
                    required: "Please enter confirm password",
                    checklower:"Please enter atleast one Special Characters",
                    checkupper:"Please enter atleast one Uppercase Alphabet",
                    checkdigit:"Please enter atleast one Numeric",
                    checkspl:"Please enter atleast one Lowercase Alphabet",
                },
                "captcha": {
                    required: "Please enter captcha",
                },
            },
            success: function (label, element) {
                label.parent().removeClass('error');
                label.remove();
            },
            highlight: function (element) {
                $(element).parent('div').addClass('error');
            },
            unhighlight: function (element) {
                $(element).parent('div').removeClass('error');
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },
            submitHandler: function (form) {
                swal({
                    title: "Please wait..",
                    imageUrl: loadingImg,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                form.submit();
            }
        });
        
        $('#change_photo_form').validate({
            rules: {

                "profileImg": {
                    required: true,
                  
                },
              
            },
            messages: {
                "profileImg": {
                    required: "Please upload photo",
                },
                
            },
            success: function (label, element) {
                label.parent().removeClass('error');
                label.remove();
            },
            highlight: function (element) {
                $(element).parent('div').addClass('error');
            },
            unhighlight: function (element) {
                $(element).parent('div').removeClass('error');
            },
            errorPlacement: function (error, element) {
                console.log(element.attr('name'));
                 if (element.attr('name') == 'profileImg') {
                    error.appendTo('span#profileImg_error');
                }else{
                     error.insertAfter(element);
                }
               
                
            },
            submitHandler: function (form) {
                swal({
                    title: "Please wait..",
                    imageUrl: loadingImg,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                form.submit();
            }
        });
    })
</script>