<style>
    label.error {
        font-weight: 500 !important;
    }

    .with-border {
        background-color: #006eab;
        color: white;
        padding: 8px 0 1px 17px;
    }

    .emplDet {
        border: 1px solid #006eab;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.min.js"></script>
<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">


                        <!-- general form elements -->
                        <div class="card">
                            <div class="card-header">
                                <div class="col-md-12">
                                    <h3 class="card-title">ADD SUB CATEGORY DATA</h3>
                                    <a href="<?php echo BASE_URL('inspection/master/auditsubcategorydata') ?>"><button type="button" class="btn btn-sm btn-primary backBtns"> Back</button></a>
                                </div>
                            </div>

                            <div class="card-body">
                                <?php
                                // echo '<pre>';
                                // print_r($editData);
                                ?>
                                <?php echo form_open('inspection/Master/save_auditsubcategorydata', 'class="form-horizontal" id="company-profile" novalidate'); ?>

                                <div class="row m-l-10">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Category <span class="error"> * </span></label>
                                            <?php
                                            $cat_id = isset($editData[0]->fk_cat_id) && !empty($editData[0]->fk_cat_id) ? $editData[0]->fk_cat_id : set_value('main_mas[category_id]');
                                            $cNamedatas = [
                                                'id' => 'category_id',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2',
                                            ];
                                            echo form_dropdown('main_mas[category_id]', $dropCategories, $cat_id, $cNamedatas);
                                            ?>
                                        </div>
                                    </div>
                                    <input type="hidden" name="main_mas[category_id_edit]" value="<?php echo $cat_id; ?>">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Sub Category <span class="error"> * </span></label>
                                            <select class="form-control area select2" name="main_mas[subcategory_id]" id="subcategory">
                                                <option value="">Select Sub Category</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row float-right">
                                    <div class="col-md-12">
                                        <button type="button " class="btn btn-sm btn-warning addsubtyp"><i class="fa fa-plus-circle"> Add Sub Category Data</i></button>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="emplDet m-t-10">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box-header with-border">
                                                <h5> Sub Category Data </h5>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if (!empty($editData)) {
                                        foreach ($editData as $sbKey => $sbVal) {
                                            $inKey = $sbKey + 1;

                                    ?>
                                            <div class="row m-t-10 m-l-10 m-b-10 remRowid" id="remRowid_<?php echo $inKey; ?>" row-id="<?php echo $inKey; ?>">

                                                <div class="col-sm-11" style="margin-bottom: 10px;">
                                                    <div class="form-group">
                                                        <!--<label> Checklist Subtype <span class="error"> * </span></label>-->
                                                        <?php
                                                        $subcat = isset($sbVal->subcategorydata) && !empty($sbVal->subcategorydata) ? $sbVal->subcategorydata : '';
                                                        $subcatid = isset($sbVal->id) && !empty($sbVal->id) ? $sbVal->id : '';
                                                        $guidance = isset($sbVal->id) && !empty($sbVal->id) ? $sbVal->guidance : '';

                                                        $allSUbtypids[] = $subcatid;
                                                        $cMaildatas = [
                                                            'name' => 'main_mas[subcategorydata][]',
                                                            'id' => 'subtype_' . $inKey,
                                                            'class' => 'form-control',
                                                            'value' => $subcat,
                                                            'required' => true,
                                                            'autocomplete' => 'off',
                                                            'placeholder' => 'Enter Sub Category data',

                                                        ];
                                                        echo form_input($cMaildatas);
                                                        ?>
                                                        <span class="error"><?php echo form_error('main_mas[subcategorydata][]') ?></span>
                                                    </div>
                                                    <input type="hidden" name="main_mas[subcategorydata_id][]" value="<?php echo $subcatid; ?>">

                                                </div>
                                                <div class="col-md-1 trashRem" id="trash_<?php echo $inKey; ?>" trash-id="<?php echo $inKey; ?>">
                                                    <i class="fa fa-trash"></i>
                                                </div>
                                                <div class="col-sm-11">
                                                    <div class="form-group">
                                                        <textarea name="main_mas[guidance][]" id="guidance_<?php echo $inKey; ?>" required autocomplete="off" class="form-control summernote" ckedit="ckeditor"><?php echo $guidance; ?></textarea>
                                                        <span class="error"><?php echo form_error('main_mas[guidance][]') ?></span>
                                                    </div>
                                                </div>


                                            </div>
                                        <?php } ?>

                                    <?php  } else { ?>

                                        <div class="row m-t-10 m-l-10 m-b-10 remRowid" id="remRowid_1" row-id="1">

                                            <div class="col-sm-11" style="margin-bottom: 10px;">
                                                <div class="form-group">
                                                    <?php
                                                    $cMaildatas = [
                                                        'name' => 'main_mas[subcategorydata][]',
                                                        'id' => 'subtype_1',
                                                        'class' => 'form-control',
                                                        'value' => '',
                                                        'required' => true,
                                                        'autocomplete' => 'off',
                                                        'placeholder' => 'Enter Sub Category data',

                                                    ];
                                                    echo form_input($cMaildatas);
                                                    ?>
                                                    <span class="error"><?php echo form_error('main_mas[subcategorydata][]') ?></span>
                                                </div>
                                                <input type="hidden" name="main_mas[subcategorydata_id][]" value="">
                                            </div>

                                            <div class="col-md-1 trashRem" id="trash_1" trash-id="1">
                                                <i class="fa fa-trash"></i>
                                            </div>
                                            <div class="col-sm-11">
                                                <div class="form-group">
                                                    <textarea name="main_mas[guidance][]" id="guidance_1" required autocomplete="off" class="form-control" ckedit="ckeditor" placeholder="Enter Guidance"></textarea>
                                                    <span class="error"><?php echo form_error('main_mas[guidance][]') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="newSubtypes">
                                    </div>
                                </div>
                                <div class="form-group m-t-10" style="text-align: center;">
                                    <div class="">

                                        <?php
                                        $data = array('id' => 'submit', 'type' => 'submit', 'content' => ' SUBMIT', 'class' => 'btn btn-primary');
                                        echo form_button($data);
                                        ?>
                                    </div>
                                </div>
                                <input type="hidden" name="deleteIds" id="deleteIds">
                                <!-- input states -->
                                <?php echo form_close(); ?>

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
    $(document).ready(function() {

        $('.summernote').each(function() {
            $(this).summernote({
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['color', ['color']],
                    ['misc', ['undo', 'redo']],
                ],
                height: 100, // Set height
                placeholder: 'Enter Guidance',
            });
        });

        $('#guidance_1').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript', 'ul', 'ol', 'color']],
            ],
            height: 50,
            placeholder: 'Enter Guidance'

        });

        $.validator.addMethod('alphanumeric', function(value) {
            return /^[A-Za-z0-9/,.()%  ]*$/.test(value);
        }, "Please Enter valid Alphanumeric characters with allowed special charters are /,.()%");




        $(document).on("submit", "#company-profile", function(e) {
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
                    let isValid = true;
                    let deleteIdsString = JSON.stringify(deleteids);
                    $('input[name="deleteIds"]').remove();
                    $("<input>")
                        .attr({
                            type: "hidden",
                            name: "deleteIds",
                            value: deleteIdsString,
                        })
                        .appendTo("#company-profile");

                    $(".error-message").remove();

                    const selectedCategory = $('select[name="main_mas[category_id]"]').val();
                    if (!selectedCategory || selectedCategory.trim() === "") {

                        $('select[name="main_mas[category_id]"]')
                            .closest(".form-group")
                            .append('<span class="error-message" style="color: red;">Category is required.</span>');
                        isValid = false;
                    }

                    const selectedSubCategory = $('select[name="main_mas[subcategory_id]"]').val();
                    if (!selectedSubCategory || selectedSubCategory.trim() === "") {
                        $('select[name="main_mas[subcategory_id]"]')
                            .closest(".form-group")
                            .append('<span class="error-message" style="color: red;">SubCategory is required.</span>');
                        isValid = false;
                    }

                    // Validate subcategories
                    $(".remRowid").each(function() {
                        const $subcategoryInput = $(this).find('input[name="main_mas[subcategorydata][]"]');
                        const subcategoryDataValue = $subcategoryInput.val().trim();

                        if (!subcategoryDataValue) {
                            $subcategoryInput.after('<span class="error-message" style="color: red;">Subcategory Data is required.</span>');
                            isValid = false;
                        }
                    });

                    if (isValid) {
                        const formData = $(this).serialize();

                        $.ajax({
                            type: "POST",
                            url: $(this).attr("action"),
                            data: formData,
                            dataType: "json",
                            success: function(response) {
                                if (response.status === true) {
                                    window.location.href = "<?php echo BASE_URL('inspection/Master/auditsubcategorydata'); ?>";
                                } else {
                                    alert("Something went wrong, Try Again");
                                }
                            },
                            error: function() {
                                alert("An error occurred while submitting the form.");
                            },
                        });
                    }

                }
            });
        });

        $(".addsubtyp").on("click", function(e) {
            e.preventDefault();
            var html = '';
            var rowID = $('.row').last().attr('row-id');
            var finalRowid = parseInt(rowID) + 1;
            html = '<div class="row m-t-10 m-l-10 m-b-10 remRowid" id="remRowid_' + finalRowid + '" row-id="' + finalRowid + '">\n\
                            <div class="col-md-11" style="margin-bottom: 10px;" >\n\
                                            <div class="form-group">\n\
                                                <input type="text" name="main_mas[subcategorydata][]" value="" id="subtype_' + finalRowid + '" class="form-control" required="1" autocomplete="off" placeholder = "Enter Sub Category data">\n\
                                                <span class="error"></span>\n\
                                            </div>\n\
                                            <input type="hidden" name="main_mas[subcategorydata_id][]" value="" >\n\
                            </div>\n\
                            <div class="col-md-1 trashRem" id="trash_' + finalRowid + '" trash-id="' + finalRowid + '">\n\
                                <i class="fa fa-trash"></i>\n\
                            </div>\n\
                                            <div class="col-md-11"> \n\
                                                <div class="form-group"> \n\
                                                    <textarea name="main_mas[guidance][]" id="guidance_' + finalRowid + '" required autocomplete="off" class="form-control" ckedit="ckeditor" placeholder="Enter Guidance"></textarea>\n\
                                                    <span class="error"><?php echo form_error('main_mas[guidance][]') ?></span>\n\
                                                </div>\n\
                                            </div>\n\
                    </div>';
            $(".newSubtypes").append(html);

            $('#guidance_' + finalRowid).summernote({
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript', 'ul', 'ol', 'color']],
                ],
                height: 50,
                placeholder: 'Enter Guidance'

            });
        });

        var deleteids = [];
        $(document).on('click', ".trashRem", function() {
            var trashId = $(this).attr("trash-id");
            var count = $(".emplDet").find('.remRowid').length;

            var subcategorydataId = $("#remRowid_" + trashId).find('input[name="main_mas[subcategorydata_id][]"]').val();

            if (subcategorydataId) {
                deleteids.push(subcategorydataId);
            }

            if (count != 1) {
                $("#remRowid_" + trashId).remove();
            } else {
                swal('Sorry', 'Atleast one Sub Category Data is required', 'error');
            }
        });



        var category_id = <?php echo isset($editData[0]->fk_cat_id) ? $editData[0]->fk_cat_id : 'null'; ?>;
        if (category_id) {
            getsubcatdata(category_id);
        }


        $(document).on('change', '#category_id', function() {
            var cat_id = $(this).val();
            getsubcatdata(cat_id);
        });

        function getsubcatdata(cat_id) {

            var url = "<?php echo BASE_URL('inspection/Master/getauditSubcategoryDetails') ?>";
            var data = {
                id: cat_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            }
            $.ajax({
                dataType: 'html',
                data: 'ajax',
                method: 'post',
                data: data,
                url: url,
                success: function(response) {

                    $('#subcategory').empty();
                    $('#subcategory').html(response);

                    var sub = '<?php echo $editData[0]->fk_subcat_id; ?>';
                    if (sub) {
                        $('#subcategory').val(sub);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred: ", status, error);
                }
            });
        }
    });
</script>