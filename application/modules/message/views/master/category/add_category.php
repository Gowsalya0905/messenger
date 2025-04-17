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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-md-12">
                                    <h3 class="card-title">ADD CATEGORY</h3>
                                    <a href="<?php echo BASE_URL('training/master/category') ?>"><button type="button" class="btn btn-sm btn-primary backBtns"> Back</button></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php
                                $type_id = isset($editData[0]->fk_type_id) && !empty($editData[0]->fk_type_id) ? $editData[0]->fk_type_id : '';
                                print_r($type_id);
                                ?>
                                <?php echo form_open('training/master/save_category/' . encryptval($type_id), 'class="form-horizontal" id="company-profile" novalidate'); ?>

                                <div class="row m-l-10">
                                    <div class="col-sm-4">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Type <span class="error"> * </span></label>
                                            <?php
                                            $type_id = isset($editData[0]->fk_type_id) && !empty($editData[0]->fk_type_id) ? $editData[0]->fk_type_id : set_value('insp[checklist_type]');
                                            $tNamedatas = [
                                                'id' => 'type_id',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('main_mas[fk_type_id]', $dropCategories, $type_id, $tNamedatas);
                                            ?>
                                            <span class="error"><?php echo form_error('main_mas[fk_type_id]') ?></span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="main_mas[type_id_edit]" value="<?php echo $type_id; ?>">

                                </div>
                                <div class="row float-right">
                                    <div class="col-md-12">
                                        <button type="button " class="btn btn-sm btn-warning addcat"><i class="fa fa-plus-circle"></i> Add Category</button>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="emplDet m-t-10">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box-header with-border">
                                                <h5 class=""> Category </h5>
                                            </div>

                                        </div>
                                    </div>

                                    <?php
                                    if (!empty($editData)) {
                                        foreach ($editData as $caKey => $caVal) {
                                            $inKey = $caKey + 1;

                                    ?>
                                            <div class="row m-t-10 m-l-10 m-b-10 remRowid" id="remRowid_<?php echo $inKey; ?>" row-id="<?php echo $inKey; ?>">

                                                <div class="col-sm-11">
                                                    <div class="form-group">
                                                        <?php
                                                        $getcattypeval = isset($caVal->category) && !empty($caVal->category) ? $caVal->category : '';
                                                        $getcattypeid = isset($caVal->id) && !empty($caVal->id) ? $caVal->id : '';

                                                        $cMaildatas = [
                                                            'name' => 'main_mas[category][]',
                                                            'id' => 'cattype_' . $inKey,
                                                            'class' => 'form-control trng_cattyp',
                                                            'value' => $getcattypeval,
                                                            'required' => true,
                                                            'autocomplete' => 'off',

                                                        ];
                                                        echo form_input($cMaildatas);
                                                        ?>

                                                    </div>
                                                    <input type="hidden" name="main_mas[category_id][]" value="<?php echo $getcattypeid; ?>">
                                                </div>
                                                <div class="col-md-1 trashRem" id="trash_<?php echo $inKey; ?>" trash-id="<?php echo $inKey; ?>">
                                                    <i class="fa fa-trash"></i>
                                                </div>


                                            </div>
                                        <?php }
                                    } else { ?>

                                        <div class="row m-t-10 m-l-10 m-b-10 remRowid" id="remRowid_1" row-id="1">

                                            <div class="col-sm-11">
                                                <div class="form-group">
                                                    <!--<label> Checklist Subtype <span class="error"> * </span></label>-->
                                                    <?php
                                                    $cMaildatas = [
                                                        'name' => 'main_mas[category][]',
                                                        'id' => 'cattype_1',
                                                        'class' => 'form-control trng_cattyp',
                                                        'value' => '',
                                                        'required' => true,
                                                        'autocomplete' => 'off',

                                                    ];
                                                    echo form_input($cMaildatas);
                                                    ?>
                                                    <span class="error"><?php echo form_error('main_mas[category][]') ?></span>
                                                </div>
                                                <input type="hidden" name="main_mas[category_id][]" value="">
                                            </div>
                                            <div class="col-md-1 trashRem" id="trash_1" trash-id="1">
                                                <i class="fa fa-trash"></i>
                                            </div>


                                        </div>
                                    <?php } ?>
                                    <div class="newcattypes">
                                    </div>
                                </div>
                                <div class="form-group m-t-10" style="text-align: center;">
                                    <div class="">

                                        <?php
                                        $data = array('id' => 'submit', 'type' => 'submit', 'content' => 'SUBMIT', 'class' => 'btn btn-primary');
                                        echo form_button($data);
                                        ?>
                                    </div>
                                </div>
                                <input type="hidden" name="main_mas[deleteIds]" value="">
                                <!-- input states -->
                                <?php echo form_close(); ?>

                            </div>

                            <!-- /.card -->

                        </div>
                        <!--/.col (left) -->

                    </div>
                    <!-- /.row -->
                </div>
        </section>
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function() {

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
                    let deleteIdsString = JSON.stringify(deletedcategoryIds);
                    $('input[name="deleteIds"]').remove();
                    $("<input>")
                        .attr({
                            type: "hidden",
                            name: "deleteIds",
                            value: deleteIdsString,
                        })
                        .appendTo("#company-profile");

                    $(".error-message").remove();

                    const selectedType = $('#type_id').val();

                    if (!selectedType || selectedType.trim() === "") {
                        $('select[name="main_mas[fk_type_id]"]')
                            .closest(".form-group")
                            .append('<span class="error-message" style="color: red;"> Type is required.</span>');
                        isValid = false;
                    }

                    // Validate categories
                    $(".remRowid").each(function() {
                        const $categoryInput = $(this).find('input[name="main_mas[category][]"]');
                        const categoryValue = $categoryInput.val().trim();


                        if (!categoryValue) {
                            $categoryInput.after('<span class="error-message" style="color: red;">category name is required.</span>');
                            isValid = false;
                        }
                    });

                    // Submit form via AJAX if valid
                    if (isValid) {
                        const formData = $(this).serialize();
                        var url = $(this).attr("action");
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: formData,
                            dataType: "json",
                            success: function(response) {

                                if (response.status === true) {
                                    window.location.href = "<?php echo BASE_URL('training/master/category'); ?>";
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

        $(".addcat").on("click", function(e) {
            e.preventDefault();
            var html = '';
            var rowID = $('.row').last().attr('row-id');
            var finalRowid = parseInt(rowID) + 1;
            html = '<div class="row m-t-10 m-l-10 m-b-10 remRowid" id="remRowid_' + finalRowid + '" row-id="' + finalRowid + '">\n\
                        <div class="col-sm-11">\n\
                                        <div class="form-group">\n\
                                            <input type="text" name="main_mas[category][]" value="" id="cattype_' + finalRowid + '" class="form-control trng_cattyp" required autocomplete="off" >\n\
                                            <span class="error"></span>\n\
                                        </div>\n\
                                        <input type="hidden" name="main_mas[category_id][]" value=""> \n\
                                    </div>\n\
                <div class="col-md-1 trashRem" id="trash_' + finalRowid + '" trash-id="' + finalRowid + '">\n\
                <i class="fa fa-trash"></i>\n\
                </div>\n\
                                    </div>';
            $(".newcattypes").append(html)
        });

        let deletedcategoryIds = [];

        $(document).on('click', ".trashRem", function() {
            var trashId = $(this).attr("trash-id");
            var count = $(".emplDet").find('.remRowid').length;
            if (count != 1) {
                var categoryId = $("#remRowid_" + trashId).find('input[name="main_mas[category_id][]"]').val();

                if (categoryId && categoryId.trim() !== "") {
                    deletedcategoryIds.push(categoryId);
                }
                $("#remRowid_" + trashId).remove();
            } else {
                swal('Sorry', 'Atleast one category is required', 'error');
            }
        });


    });
</script>