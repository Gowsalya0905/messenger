<?php
$editRespid = postData($getSubtypedatas, 'USER_TYPE_ID'); ?>
<div class="modal-header">
    <?php if ($editRespid) { ?>
        <h3 class="modal-title"> Edit User Role</h3>
    <?php } else { ?>
        <h3 class="modal-title"> Add User Role</h3>
    <?php } ?>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<?php


echo form_open('main_master/save_user_role/' . $editRespid, 'class="form-horizontal" id="resp-type" novalidate')
?>



<!-- Modal body -->
<div class="modal-body">
    <?php $styleCSS = ($editRespid == '') ? '' : 'd-none' ?>

    <input type="hidden" name="main_mas[hiddenrespID]" value='<?php echo $editRespid; ?>'>


    <div class="form-group">
        <label for="loctyp"> User Role Full name <span class="error"> * </span></label>
        <?php
        $editRespname = postData($getSubtypedatas, 'USER_TYPE_NAME');
        $respName = [
            'name' => 'main_mas[USER_TYPE_NAME]',
            'id' => 'USER_TYPE_NAME',
            'class' => 'form-control',
            'placeholder' => 'Enter User Role Full name',
            'value' => $editRespname,
        ];
        echo form_input($respName);
        ?>
        <span class="error loctyp_err"><?php echo form_error('main_mas[USER_TYPE_NAME]') ?></span>

    </div>

    <div class="form-group">
        <label for="loctyp">User Role short name <span class="error"> * </span></label>
        <?php
        $editRespname = postData($getSubtypedatas, 'USER_TYPE_CODE');
        $respName = [
            'name' => 'main_mas[USER_TYPE_CODE]',
            'id' => 'USER_TYPE_CODE',
            'class' => 'form-control',
            'placeholder' => 'Enter User Role Short name',
            'value' => $editRespname,
        ];
        echo form_input($respName);
        ?>
        <span class="error loctyp_err"><?php echo form_error('main_mas[USER_TYPE_CODE]') ?></span>

    </div>

</div>

<!-- Modal footer -->
<div class="modal-footer justify-content-center">
    <button type="submit" class="btn btn-primary "><i class=""></i> Submit</button>
</div>
<?php form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $.validator.addMethod('alphanumeric', function(value) {
            return /^[A-Za-z0-9/,.()% ]*$/.test(value);
        }, "Please Enter valid Alphanumeric characters with allowed special charters are /,.()%");

        $.validator.addMethod('alphaspace', function(value) {
            return /^[A-Za-z ]*$/.test(value);
        }, "Please Enter only Alphabetic characters");

        // Initialize the form validation
        $("#resp-type").validate({
            rules: {
                "main_mas[USER_TYPE_NAME]": {
                    required: true,
                    maxlength: 100,
                    minlength: 3
                },
                "main_mas[USER_TYPE_CODE]": {
                    required: true,
                    maxlength: 50,
                    minlength: 3
                }
            },
            messages: {
                "main_mas[USER_TYPE_NAME]": {
                    required: "User Role full name is required",
                    maxlength: "Maximum 100 characters allowed",
                    minlength: "Minimum 3 characters required"
                },
                "main_mas[USER_TYPE_CODE]": {
                    required: "User Role short name is required",
                    maxlength: "Maximum 50 characters allowed",
                    minlength: "Minimum 3 characters required"
                }
            },
            errorPlacement: function(error, element) {
                element.closest('.form-group').find('.loctyp_err').html(error);
            },
            success: function(label) {
                label.closest('.form-group').find('.error').html('');
            }
        });

        // Submit handler
        $("#resp-type").on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            if ($("#resp-type").valid()) {
                // If form is valid, proceed with AJAX submission
                var formDatas = $(this).serialize();

                var url = "<?php echo BASE_URL('master/terminal/save_user_role') ?>";

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formDatas,
                    dataType: 'json',
                    success: function(resp) {
                        // console.log(resp.status);
                        if (resp.status === 'error') {
                            $(".loctyp_err").text(resp.user_role).show();
                        } else if (resp.status == true) {
                            // Reload or redirect on success
                            window.location.reload();
                        } else {
                            alert("Unexpected error occurred.");
                        }
                    },
                    error: function() {
                        alert("An error occurred while processing the request.");
                    }
                });
            }
        });
    });
</script>