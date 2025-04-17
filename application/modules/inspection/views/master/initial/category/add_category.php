<?php
$editRespid = postData($getCategories, 'id'); ?>
<div class="modal-header">
    <?php if ($editRespid) { ?>
        <h3 class="modal-title"> Edit Category</h3>
    <?php } else { ?>
        <h3 class="modal-title"> Add Category</h3>
    <?php } ?>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<?php


echo form_open_multipart('inspection/master/save_initialcategory/' . $editRespid, 'class="form-horizontal" id="category_form" novalidate')
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
        <label for="loctyp"> Category <span class="error"> * </span></label>
        <?php
        $editRespname = postData($getCategories, 'category');
        $respName = [
            'name' => 'main_mas[category]',
            'id' => 'category',
            'class' => 'form-control',
            'placeholder' => 'Enter Category name',
            'value' => $editRespname,
        ];
        echo form_input($respName);
        ?>
        <span class="error category_err"><?php echo form_error('main_mas[category]') ?></span>

    </div>

    <label style="margin-top: 10px;">Image Upload <span class="error"></span></label>
    <div class="form-group ">
        <div class="fileinput fileinput-new apprFileinput imgGroup" data-provides="fileinput">
            <div class="fileinput-preview thumbnail bootimgheight appbootimgheight" data-trigger="fileinput">
                <?php
                $useeimgname = postData($getCategories, 'category_image');
                $useeFilepath = (!empty($useeimgname)) ? $useeimgname : '';

                if (!empty($useeFilepath)) {
                ?>
                    <img src="<?php echo BASE_URL . $useeFilepath; ?>" alt="<?php echo !empty($useeimgname) ? $useeimgname : 'Uploaded Image'; ?>" style="height: 100%;" />
                <?php } else { ?>

                    <img src="" alt="Image Preview" style="height: 100%; display: none;" />
                <?php } ?>

            </div>
            <p>(png, jpeg, jpg)</p>
            <div class="file-pop">
                <span class="text-green btn-file">
                    <span class="photo fileinput-new" title="Add Image">
                        <img class="imgupload" src="<?php echo BASE_URL('/assets/images/photo.png'); ?>" style="width: 30%;" />
                    </span>
                    <input type="file" name="category_image" class="atarfile" accept="image/png, image/jpeg">
                </span>
                <button type="button" class="btn btn-nothing text-maroon fileinput-exists" data-dismiss="fileinput" title="Remove Image">
                    <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <input type="hidden" name="other_user_img" value="<?php echo $useeFilepath; ?>">
    </div>



</div>


<!-- Modal footer -->
<div class="modal-footer justify-content-center">
    <button type="submit" class="btn btn-primary "><i class=""></i> Submit</button>
</div>
<?php form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {

        $("input[type='file']").on('change', function(event) {
            var file = event.target.files[0];
            if (file) {
                if (file.type === 'image/png' || file.type === 'image/jpeg' || file.type === 'image/jpg') {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(".fileinput-preview img").attr("src", e.target.result).show();
                        $(".fileinput-preview").show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert("Please upload a valid image file (PNG, JPEG, JPG).");
                }
            }
        });


        $.validator.addMethod('alphanumeric', function(value) {
            return /^[A-Za-z0-9/,.()% ]*$/.test(value);
        }, "Please Enter valid Alphanumeric characters with allowed special charters are /,.()%");

        $.validator.addMethod('alphaspace', function(value) {
            return /^[A-Za-z ]*$/.test(value);
        }, "Please Enter only Alphabetic characters");

        // Initialize the form validation
        $("#category_form").validate({
            rules: {
                "main_mas[category]": {
                    required: true,
                    maxlength: 100,
                    minlength: 2
                },

            },
            messages: {
                "main_mas[category]": {
                    required: "Category full name is required",
                    maxlength: "Maximum 100 characters allowed",
                    minlength: "Minimum 3 characters required"
                },

            },
            errorPlacement: function(error, element) {
                element.closest('.form-group').find('.category_err').html(error);
            },
            success: function(label) {
                label.closest('.form-group').find('.error').html('');
            }
        });

        // Submit handler
        $("#category_form").on('submit', function(e) {
            e.preventDefault();

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
                    if ($("#category_form").valid()) {
                        var formData = new FormData(this);
                        var url = "<?php echo BASE_URL('inspection/master/save_initialcategory') ?>";


                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: formData,
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            success: function(resp) {
                                if (resp.status === 'error') {
                                    $(".category_err").text(resp.company).show();
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