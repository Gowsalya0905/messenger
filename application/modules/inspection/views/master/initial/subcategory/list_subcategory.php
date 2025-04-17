
<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="" style="margin-top:10px;">
                <?php
                echo $this->session->flashdata('subcategoryflash');
                $this->session->unset_userdata('subcategoryflash'); ?>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title"><?= $page_title; ?></h3>
                            <?php
                            global $permission_master;

                            $user_type = getCurrentUserGroupId();
                            if (in_array($user_type, $permission_master['listadd'])) {
                            ?>
                                <a href="<?php echo BASE_URL('inspection/master/add_initialsubcategory') ?>" class="btn btn-sm btn-primary float-right ">
                                    Add Sub Category
                                </a>
                            <?php
                            }

                            ?>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="employeeid" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th>S.No</th> -->
                                        <th>Category</th>
                                        <th>Sub Category</th>
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
    $(function() {

        // / Datatable /
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
                                    "<?php echo BASE_URL;  ?>inspection/master/initialsubcategory_exportpdf"

                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            action: function(e, dt, button, config) {
                             
                                window.location.href =
                                    "<?php echo BASE_URL;  ?>inspection/master/initialsubcategory_exportexcel"

                            }
                        },
                    ]
                },
                'pageLength'
            ],

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

        $(document).on("click", ".deleteSubCategory", function(e) {
            e.preventDefault();
            var url = '<?php echo BASE_URL("inspection/master/delete_initialsubcategory"); ?>';
            var deletId = $(this).attr("delt-id");

            deleteDatas(url, deletId, csrfName, csrfToken)


        });

    });
</script>