<?php
$editRespid = postData($getCategories, 'id'); 
// print_r($editRespid);
// die;
?>
<div class="modal-header">
    <?php if ($editRespid) { ?>
        <h3 class="modal-title"> Edit Message</h3>
    <?php } else { ?>
        <h3 class="modal-title"> Add Message</h3>
    <?php } ?>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<?php


echo form_open('message/master/save_type/' . $editRespid, 'class="form-horizontal" id="type_form" novalidate')
?>


<style>
    .file-pop {
        top: 55% !important;
        margin-left: 23% !important;
    }

    .bootimgheight {
        margin-top: 0 !important;
    }

    p {
        margin-bottom: 0 !important;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.min.js"></script>
<!-- Modal body -->
<div class="modal-body">
    <?php $styleCSS = ($editRespid == '') ? '' : 'd-none' ?>

    <input type="hidden" name="main_mas[hiddenrespID]" value='<?php echo $editRespid; ?>'>


    <div class="form-group">
        <label for="loctyp">Message<span class="error"> * </span></label>
        <?php
        $editRespname = postData($getCategories, 'type');
        $respName = [
            'name' => 'main_mas[type]',
            'id' => 'type',
            'class' => 'form-control',
            'placeholder' => 'Enter type name',
            'value' => $editRespname,
        ];
        echo form_input($respName);
        ?>
        <span class="error type_err"><?php echo form_error('main_mas[type]') ?></span>

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
        $("#type_form").validate({
            rules: {
                "main_mas[type]": {
                    required: true,
                    maxlength: 100,
                    minlength: 2
                },

            },
            messages: {
                "main_mas[type]": {
                    required: "Type full name is required",
                    maxlength: "Maximum 100 characters allowed",
                    minlength: "Minimum 3 characters required"
                },

            },
            errorPlacement: function(error, element) {
                element.closest('.form-group').find('.type_err').html(error);
            },
            success: function(label) {
                label.closest('.form-group').find('.error').html('');
            }
        });

        // Submit handler
        $("#type_form").on('submit', function(e) {
            e.preventDefault();


            Swal.fire({
                title: "Are you sure?",
                text: "You want to send the message.",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if ($("#type_form").valid()) {
                        var formData = new FormData(this);
                        var url = "<?php echo BASE_URL('message/master/save_type') ?>";


                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: formData,
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            success: function(resp) {
                                if (resp.status === 'error') {
                                    $(".type_err").text(resp.company).show();
                                } else if (resp.status == true) {
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

                }
            });
        });
    });
</script>