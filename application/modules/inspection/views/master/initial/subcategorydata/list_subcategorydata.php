<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="" style="margin-top:10px;">
                <?php
                echo $this->session->flashdata('subcategorydataflash');
                $this->session->unset_userdata('subcategorydataflash'); ?>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card ">
                        <div class="card-header">   
                            <div class="row">
                                <div class="col-md-9">
                                    <h3 class="card-title"><?= $page_title; ?></h3>
                                </div>
                                <div class="col-md-3">
                                    <div class="float-right">
                                        <button data-toggle="collapse" href="#search" name='Filter' role="button" aria-expanded="false" aria-controls="search" class="btn btn-sm btn-primary" style="margin-right: 5px;">
                                            <i class="fa fa-filter" data-bs-toggle="tooltip" title="Search"></i>
                                            Filter
                                        </button>
                                        <?php
                                        global $permission_master;

                                        $user_type = getCurrentUserGroupId();
                                        if (in_array($user_type, $permission_master['listadd'])) {
                                        ?>
                                            <a href="<?php echo BASE_URL('inspection/master/add_initialsubcategory_data') ?>" class="btn btn-sm btn-primary float-right">
                                                Add Sub Category Data
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="search" class="collapse">

                                <?php
                                $formData = [
                                    'class' => 'form-horizontal',
                                    'id' => 'search_form',
                                    'novalidate' => 'novalidate'
                                ];

                                echo form_open('#', $formData);
                                ?>
                                <div class="row m-t-10">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Category <span class="error"> * </span></label>
                                            <?php
                                            $cat_id = '';
                                            $cNamedatas = [
                                                'id' => 'category',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2',
                                            ];
                                            echo form_dropdown('category', $dropCategories, $cat_id, $cNamedatas);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Sub Category <span class="error"> * </span></label>
                                            <select class="form-control area select2" name="subcategory" id="subcategory">
                                                <option value="">Select Sub Category</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-6 " style="margin-top:20px;">
                                        <label></label><br>
                                        <button class="btn btn-success" id="searchform" type="button" name="search">Search</button>
                                        <input type="reset" class="btn btn-danger" id="resetform" name="reset" value="Reset">
                                    </div>

                                </div>
                                <?php

                                echo form_close();
                                ?>
                                <br />
                            </div>
                            <table id="employeeid" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th>S.No</th> -->
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Sub Category Data</th>
                                        <th>Created on</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

</div>
<!----- popup starts----->
<div class="modal modal-info fade" id="respmodal" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>
<!----- popup ends----->



<script type="text/javascript">
    function nullcheck(value) {
        return value = value == null ? '' : value;
    }

    function qetquerystring() {

        var category = nullcheck($('#category').val());
        var subcategory = nullcheck($('#subcategory').val());

        return 'category=' + category + '&subcategory=' + subcategory
    }

    $(function() {

        var table = $('#employeeid').DataTable({
            autoWidth: false,
            responsive: true,
            dom: 'Bfrtip',
            processing: true,
            serverSide: true,
            searching: true,
            ordering: false,
            stateSave: true,
            ajax: {
                url: "<?php echo isset($ajaxurl) ? site_url($ajaxurl) : '' ?>",
                type: 'POST',
                "data": function(d) {
                    d.searchextra = $('#search_form').serializeArray();
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
                }
            },

            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            buttons: [{
                    extend: 'collection',
                    text: 'Export',
                    buttons: [{
                            extend: 'pdf',
                            text: 'Pdf',
                            action: function(e, dt, button, config) {

                                window.location.href =
                                    "<?php echo BASE_URL;  ?>inspection/master/initialsubcategorydata_exportpdf?" + qetquerystring()

                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            action: function(e, dt, button, config) {

                                window.location.href =
                                    "<?php echo BASE_URL;  ?>inspection/master/initialsubcategorydata_exportexcel?" + qetquerystring()

                            }
                        },
                    ]
                },
                'pageLength'
            ],

        });

        $(document).on('click', '#searchform', function() {
            table.draw();
        });

        $(document).on('click', '#resetform', function() {
            $('#category').val('').trigger('change');
            $('#subcategory').val('').trigger('change');
            table.draw();
        });

    });
</script>

<script>
    $(document).ready(function() {

        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfToken = '<?php echo $this->security->get_csrf_hash(); ?>';
        $(document).on('click', '.resptarget', function(e) {
            e.preventDefault();
            $('#respmodal').modal('show').find('.modal-content').load($(this).attr('href'), function() {
                $('.select2').select2({
                    dropdownParent: $('#respmodal')
                });
            });
        });

        $(document).on("click", ".deleteSubCategorydata", function(e) {
            e.preventDefault();
            var url = '<?php echo BASE_URL("inspection/master/delete_initialsubcategorydata"); ?>';
            var deletId = $(this).attr("delt-id");

            deleteDatas(url, deletId, csrfName, csrfToken)


        });


        $(document).on('change', '#category', function() {
            var cat_id = $(this).val();

            var url = "<?php echo BASE_URL('inspection/master/getinitialSubcategoryDetails') ?>";
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

                  
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred: ", status, error);
                }
            });
        });

        


    });
</script>