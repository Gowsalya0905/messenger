<?php
$emplouniqID = postData($getEmploydetails, 'EMP_AUTO_ID');
$emploID = postData($getEmploydetails, 'EMP_ID');
$emploName = postData($getEmploydetails, 'EMP_NAME');
$emploDesig = postData($getEmploydetails, 'DESIGNATION_NAME');
$emploDesigID = postData($getEmploydetails, 'EMP_DESIGNATION_ID');
$emploUsertyp = postData($getEmploydetails, 'USER_TYPE_NAME');
$emploUsertypID = postData($getEmploydetails, 'EMP_USERTYPE_ID');
$emploLogin = postData($getEmploydetails, 'EMP_LOGIN_STATUS');
$emploMail = postData($getEmploydetails, 'EMP_EMAIL_ID');
$emploPassword = postData($emplLogindet, 'ORG_PWD');
$emploLogineditid = postData($emplLogindet, 'LOGIN_ID');

//echo "<pre>";
//print_R($getEmploydetails);
//print_R($emplLogindet);
//exit;
$statReadonly = [];
if ($emploLogin == 'P') {
    //    $empLogStat = '<label class="btn btn-xs btn-warning">Pending</label>';
    $empLogStat = 'Pending';
    $statusColor = 'pendStatusstyl';
} elseif ($emploLogin == 'E') {
    //    $empLogStat = '<label class="btn btn-xs btn-success">Enable</label>';
    $empLogStat = 'Activated';
    $statusColor = 'enabStatusstyl';
    //     $statReadonly = ['readonly' => 'readonly'];
} else {
    //    $empLogStat = '<label class="btn btn-xs btn-danger">Disable</label>';
    $empLogStat = 'Deactivated';
    $statusColor = 'disabStatusstyl';
    //    $statReadonly = 'readonly = readonly';

}
?>

<style>
    .modal-header {
        background: #e6af0a;
        padding: 10px;
    }

    .modal-title {
        color: white;
    }

    .form-group {
        margin-bottom: 0rem !important;
    }

    .enabStatusstyl {
        background-color: #1ca71c !important;
        color: #fff !important;
        text-align: center;
    }

    .disabStatusstyl {
        background-color: #dc3545 !important;
        color: #fff !important;
        text-align: center;
    }

    .pendStatusstyl {
        background-color: #f19307fa !important;
        color: #fff !important;
        text-align: center;
    }
</style>
<div class="modal-header">
    <h3 class="modal-title m-l-10"> Activate/Deactivate Employee</h3>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<?php
if (!empty($emplouniqID)) {
    $editEmpid = encryptval($emplouniqID);
} else {
    $editEmpid = '';
}
echo form_open('master/employee/updateEmplstatus/' . $editEmpid, 'class="form-horizontal" id="resp-type" method="post" novalidate')
?>



<!-- Modal body -->
<div class="modal-body">

    <div class="row m-t-10 m-l-10">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Employee ID</label>
                <input type="hidden" name="stat[empId]" value="<?php echo $emplouniqID; ?>">
                <input type="hidden" name="stat[empMail]" value="<?php echo $emploMail; ?>">
                <?php
                $emCertnamedatas = [
                    'class' => 'form-control',
                    'value' => $emploID,
                    'readonly' => true,
                    'autocomplete' => 'off',
                ];
                echo form_input($emCertnamedatas);
                ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>Name </label>
                <input type="hidden" name="stat[empName]" value="<?php echo $emploName; ?>">
                <?php
                $emnamedatas = [
                    'class' => 'form-control',
                    'value' => $emploName,
                    'readonly' => true,
                    'autocomplete' => 'off',
                ];
                echo form_input($emnamedatas);
                ?>
            </div>
        </div>


    </div>
    <div class="row m-t-10 m-l-10">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Designation</label>
                <input type="hidden" name="stat[empDesig]" value="<?php echo $emploDesigID; ?>">
                <?php
                $emDesigdatas = [
                    'class' => 'form-control',
                    'value' => $emploDesig,
                    'readonly' => true,
                    'autocomplete' => 'off',
                ];
                echo form_input($emDesigdatas);
                ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>User Type </label>
                <input type="hidden" name="stat[empUsertypedit]" value="<?php echo $emploUsertypID; ?>">
                <?php

                $emUsertypdatas = [
                    'class' => 'form-control select2',
                    'autocomplete' => 'off',
                    'checkSelect' => 'select2',
                ];

                $emUsertypfinaldatas = $emUsertypdatas;
                echo form_dropdown('stat[empUsertyp]', $dropUsertyp, $emploUsertypID, $emUsertypfinaldatas);
                ?>
            </div>
        </div>


    </div>
    <div class="row m-t-10 m-l-10">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Status</label>
                <?php
                $emStatusdatas = [
                    'class' => 'form-control ' . $statusColor,
                    'value' => $empLogStat,
                    'readonly' => true,
                    'autocomplete' => 'off',
                ];
                echo form_input($emStatusdatas);
                ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label>User Name </label>
                <?php
                $emUsernamedatas = [
                    'name' => 'stat[empUsername]',
                    'class' => 'form-control',
                    'value' => $emploID,
                    'readonly' => true,
                    'autocomplete' => 'off',
                ];
                echo form_input($emUsernamedatas);
                ?>
                <input type="hidden" value="<?php echo $emploLogineditid; ?>" name="stat[hidLogid]">
            </div>
        </div>


    </div>
    <span class="appendPassword"></span>
    <?php // echo $emploLogin; 
    ?>
    <?php if (getParticsessdata('USER_TYPE_ID') == 1 && $emploLogin != 'E') { ?>

        <div class="row m-t-10 m-l-10">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Password</label>
                    <?php
                    $emPassworddatas = [
                        'name' => 'stat[password]',
                        'id' => 'password',
                        'class' => 'form-control',
                        'value' => $emploPassword,
                        'autocomplete' => 'off',
                        'placeholder' => 'Enter Password',

                    ];
                    $finPass = array_merge($emPassworddatas, $statReadonly);
                    echo form_input($finPass);
                    ?>
                    <span class="error" id="passErr"></span>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>Confirm Password </label>
                    <?php
                    $emCnfrmpassdatas = [
                        'name' => 'stat[cnfrm_password]',
                        'id' => 'cnfrm_password',
                        'class' => 'form-control',
                        'value' => $emploPassword,
                        'autocomplete' => 'off',
                        'placeholder' => 'Enter Confirm Password',
                    ];
                    $fincnfrmPass = array_merge($emCnfrmpassdatas, $statReadonly);
                    echo form_input($fincnfrmPass);
                    ?>
                    <span class="error" id="cnfrmErr"></span>
                </div>
            </div>


        </div>

    <?php } ?>

</div>

<!-- Modal footer -->
<div class="modal-footer justify-content-center">
    <input type="hidden" name="stat[empStatus]" value="">
    <?php
    if ($emploLogin == 'E') { ?>
        <button type="submit" id="submiPass" class="btnClickpassword btn btn-primary d-none"><i class="fa fa-user-circle"></i> Submit</button>
        <button type="button" id="changePasEmp" class="btnClicksubchange btn btn-primary "><i class="fa fa-user-circle"></i> Change Password</button>
        <button type="submit" id="disabEmp" class="btnClicksub btn btn-danger "><i class="fa fa-user-circle"></i> Deactivate Employee</button>

    <?php } else { ?>
        <button type="submit" id="enabEmp" class="btnClicksub btn btn-success "><i class="fa fa-user-circle"></i> Activate Employee</button>
    <?php } ?>
</div>
<?php form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {

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

        $(".select2").select2();
        $("#resp-type").validate({
            ignore: [],

            rules: {
                "stat[password]": {
                    required: true,
                    // alphanumeric: true,
                    // maxlength: 25,
                    // minlength: 8,
                    //  checklower:true,
                    //  checkupper:true,
                    //  checkdigit:true,
                    //   checkspl:true,
                },
                "stat[cnfrm_password]": {
                    required: true,
                    // alphanumeric: true,
                    //  maxlength: 25,
                    // minlength: 8,
                    //  checklower:true,
                    //  checkupper:true,
                    //  checkdigit:true,
                    //   checkspl:true,
                },
                "stat[empUsertyp]": {
                    required: true,

                },

            },
            messages: {
                "stat[password]": {
                    required: "Password is required",
                    checklower: "Please enter atleast one Special Characters",
                    checkupper: "Please enter atleast one Uppercase Alphabet",
                    checkdigit: "Please enter atleast one Numeric",
                    checkspl: "Please enter atleast one Lowercase Alphabet",
                },
                "stat[cnfrm_password]": {
                    required: "Confirm Password is required",
                    checklower: "Please enter atleast one Special Characters",
                    checkupper: "Please enter atleast one Uppercase Alphabet",
                    checkdigit: "Please enter atleast one Numeric",
                    checkspl: "Please enter atleast one Lowercase Alphabet",
                },
                "stat[empUsertyp]": {
                    required: "User Type is required"
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
            submitHandler: function(form, e) {
                var submTyp = $(".btnClicksub").attr('id');

                if (submTyp == 'enabEmp') {
                    $("input[name='stat[empStatus]']").val("E");
                } else if (submTyp == 'disabEmp') {
                    $("input[name='stat[empStatus]']").val("D");
                } else {
                    $("input[name='stat[empStatus]']").val("E");

                }
                e.preventDefault();
                if (submTyp == 'enabEmp') {
                    swal({
                            title: "Are you sure?",
                            text: "<b> You want to Activate the Employee</b>",
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
                        function(isConfirm) {
                            if (isConfirm) {
                                loader();
                                form.submit();
                            }
                        });
                } else if (submTyp == 'disabEmp') {
                    swal({
                            title: "Are you sure?",
                            text: "<b> You want to Deactivate the Employee</b>",
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
                        function(isConfirm) {
                            if (isConfirm) {
                                loader();
                                form.submit();
                            }
                        });
                } else {
                    swal({
                            title: "Are you sure?",
                            text: "<b> You want to submit</b>",
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
                        function(isConfirm) {
                            if (isConfirm) {
                                loader();
                                form.submit();
                            }
                        });
                }
            }

        });

        $(document).on('keyup', '#cnfrm_password', function() {
            var passVal = $("#password").val();
            var cnfpassVal = $(this).val();
            $("#cnfrmErr").text('');
            $("#passErr").text('');
            if (cnfpassVal != passVal) {
                $("#cnfrmErr").text("Confirm Password must be the same as Password")
            }
        })
        $(document).on('keyup', '#password', function() {
            var cnfpassVal = $("#cnfrm_password").val();
            var passVal = $(this).val();
            $("#passErr").text('');
            $("#cnfrmErr").text('');
            if ((cnfpassVal != passVal) && cnfpassVal != '') {
                $("#passErr").text("Password must be the same as Confirm Password")
            }
        })

        $(document).on('click', '#changePasEmp', function() {
            var passW = '<?php echo $emploPassword; ?>'

            var appHtml = '<div class="row m-t-10 m-l-10">\n\
                                <div class="col-sm-6">\n\
                                    <div class="form-group">\n\
                                        <label>Password</label>\n\
                                        <input type="text" name="stat[password]" value="' + passW + '" id="password" class="form-control" autocomplete="off" placeholder="Enter Password">\n\
                                         <span class="error" id="passErr"></span>\n\
                                    </div>\n\
                                </div>\n\
                              <div class="col-sm-6">\n\
                                    <div class="form-group">\n\
                                        <label>Confirm Password </label>\n\
                                        <input type="text" name="stat[cnfrm_password]" value="' + passW + '" id="cnfrm_password" class="form-control" autocomplete="off" placeholder="Enter Confirm Password">\n\
                                        <span class="error" id="cnfrmErr"></span>\n\
                                    </div>\n\
                                </div>\n\
                        </div>\n\
        ';
            $(".appendPassword").html(appHtml);
            $(".btnClicksub").remove();
            //                                            $("#disabEmp").removeClass('d-none');
            $(".btnClicksubchange").addClass('d-none');
            $(".btnClickpassword").removeClass('d-none');
        })


    });
</script>