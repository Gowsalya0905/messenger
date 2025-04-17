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
                                    <h3 class="card-title">ADD SUB CATEGORY</h3>
                                    <a href="<?php echo BASE_URL('inspection/master/auditsubcategory') ?>"><button type="button" class="btn btn-sm btn-primary backBtns"> Back</button></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php
                                $cat_id = isset($editData[0]->fk_cat_id) && !empty($editData[0]->fk_cat_id) ? $editData[0]->fk_cat_id : '';
                                ?>
                                <?php echo form_open('inspection/master/save_auditsubcategory/' . encryptval($cat_id), 'class="form-horizontal" id="company-profile" novalidate'); ?>

                                <div class="row m-l-10">
                                    <div class="col-sm-4">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Category <span class="error"> * </span></label>
                                            <?php
                                            $cat_id = isset($editData[0]->fk_cat_id) && !empty($editData[0]->fk_cat_id) ? $editData[0]->fk_cat_id : set_value('insp[checklist_type]');
                                            $cNamedatas = [
                                                'id' => 'category_id',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('main_mas[fk_cat_id]', $dropCategories, $cat_id, $cNamedatas);
                                            ?>
                                            <span class="error"><?php echo form_error('main_mas[fk_cat_id]') ?></span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="main_mas[category_id_edit]" value="<?php echo $cat_id; ?>">
                                </div>
                                <div class="row float-right">
                                    <div class="col-md-12">
                                        <button type="button " class="btn btn-sm btn-warning addsubcat"><i class="fa fa-plus-circle"></i> Add Sub Category</button>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="emplDet m-t-10">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box-header with-border">
                                                <h5 class=""> Sub Category </h5>
                                            </div>

                                        </div>
                                    </div>

                                    <?php
                                    if (!empty($editData)) {
                                        foreach ($editData as $sbKey => $sbVal) {
                                            $inKey = $sbKey + 1;

                                    ?>
                                            <div class="row m-t-10 m-l-10 m-b-10 remRowid" id="remRowid_<?php echo $inKey; ?>" row-id="<?php echo $inKey; ?>">

                                                <div class="col-sm-11">
                                                    <div class="form-group">
                                                        <?php
                                                        $getsubtypeval = isset($sbVal->subcategory) && !empty($sbVal->subcategory) ? $sbVal->subcategory : '';
                                                        $getsubtypeid = isset($sbVal->id) && !empty($sbVal->id) ? $sbVal->id : '';

                                                        $cMaildatas = [
                                                            'name' => 'main_mas[subcategory][]',
                                                            'id' => 'subtype_' . $inKey,
                                                            'class' => 'form-control inspe_subtyp',
                                                            'value' => $getsubtypeval,
                                                            'required' => true,
                                                            'autocomplete' => 'off',

                                                        ];
                                                        echo form_input($cMaildatas);
                                                        ?>

                                                    </div>
                                                    <input type="hidden" name="main_mas[subcategory_id][]" value="<?php echo $getsubtypeid; ?>">
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
                                                        'name' => 'main_mas[subcategory][]',
                                                        'id' => 'subtype_1',
                                                        'class' => 'form-control inspe_subtyp',
                                                        'value' => '',
                                                        'required' => true,
                                                        'autocomplete' => 'off',

                                                    ];
                                                    echo form_input($cMaildatas);
                                                    ?>
                                                    <span class="error"><?php echo form_error('main_mas[subcategory][]') ?></span>
                                                </div>
                                                <input type="hidden" name="main_mas[subcategory_id][]" value="">
                                            </div>
                                            <div class="col-md-1 trashRem" id="trash_1" trash-id="1">
                                                <i class="fa fa-trash"></i>
                                            </div>


                                        </div>
                                    <?php } ?>
                                    <div class="newSubtypes">
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
                    let deleteIdsString = JSON.stringify(deletedSubcategoryIds);
                    $('input[name="deleteIds"]').remove();
                    $("<input>")
                        .attr({
                            type: "hidden",
                            name: "deleteIds",
                            value: deleteIdsString,
                        })
                        .appendTo("#company-profile");

                    $(".error-message").remove();

                    const selectedCategory = $('select[name="main_mas[fk_cat_id]"]').val();
                    if (!selectedCategory || selectedCategory.trim() === "") {
                        $('select[name="main_mas[fk_cat_id]"]')
                            .closest(".form-group")
                            .append('<span class="error-message" style="color: red;">Category name is required.</span>');
                        isValid = false;
                    }

                    // Validate subcategories
                    $(".remRowid").each(function() {
                        const $subcategoryInput = $(this).find('input[name="main_mas[subcategory][]"]');
                        const subcategoryValue = $subcategoryInput.val().trim();

                        if (!subcategoryValue) {
                            $subcategoryInput.after('<span class="error-message" style="color: red;">Subcategory name is required.</span>');
                            isValid = false;
                        }
                    });

                    // Submit form via AJAX if valid
                    if (isValid) {
                        const formData = $(this).serialize();

                        $.ajax({
                            type: "POST",
                            url: $(this).attr("action"),
                            data: formData,
                            dataType: "json",
                            success: function(response) {
                                if (response.status === true) {
                                    window.location.href = "<?php echo BASE_URL('inspection/master/auditsubcategory'); ?>";
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

        $(".addsubcat").on("click", function(e) {
            e.preventDefault();
            var html = '';
            var rowID = $('.row').last().attr('row-id');
            var finalRowid = parseInt(rowID) + 1;
            html = '<div class="row m-t-10 m-l-10 m-b-10 remRowid" id="remRowid_' + finalRowid + '" row-id="' + finalRowid + '">\n\
                        <div class="col-sm-11">\n\
                                        <div class="form-group">\n\
                                            <input type="text" name="main_mas[subcategory][]" value="" id="subtype_' + finalRowid + '" class="form-control inspe_subtyp" required autocomplete="off" >\n\
                                            <span class="error"></span>\n\
                                        </div>\n\
                                        <input type="hidden" name="main_mas[subcategory_id][]" value=""> \n\
                                    </div>\n\
                <div class="col-md-1 trashRem" id="trash_' + finalRowid + '" trash-id="' + finalRowid + '">\n\
                <i class="fa fa-trash"></i>\n\
                </div>\n\
                                    </div>';
            $(".newSubtypes").append(html)
        });

        let deletedSubcategoryIds = [];

        $(document).on('click', ".trashRem", function() {
            var trashId = $(this).attr("trash-id");
            var count = $(".emplDet").find('.remRowid').length;
            if (count != 1) {
                var subcategoryId = $("#remRowid_" + trashId).find('input[name="main_mas[subcategory_id][]"]').val();

                if (subcategoryId && subcategoryId.trim() !== "") {
                    deletedSubcategoryIds.push(subcategoryId);
                }
                $("#remRowid_" + trashId).remove();
            } else {
                swal('Sorry', 'Atleast one Sub category is required', 'error');
            }
        });


    });
</script>