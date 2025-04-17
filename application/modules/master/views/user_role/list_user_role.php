<style>
    /*    .alert-success {
        color: #fff;
        background: #28a745;
        border-color: #23923d;
    }
    .alert-danger {
        color: #fff;
        background: #dc3545;
        border-color: #d32535;
    }*/
</style>
<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
            <div class="" style="margin-top:10px;">
                <?php
                echo $this->session->flashdata('clsubflash');
                $this->session->unset_userdata('clsubflash'); ?>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title"> User Role </h3>
                            <a href="<?php echo BASE_URL('master/terminal/add_user_role') ?>" class="btn btn-sm btn-primary float-right resptarget">
                                Add User Role
                            </a>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="employeeid" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>User Role ID</th>
                                        <th>User Role Full Name</th>
                                        <th>User Role Short Name </th>
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
                                // Get the search value

                                // var searchvalue = $('.dataTables_filter input').val();
                                // searchextra = $('#search_form').serializeArray();
                                // Redirect to another URL with the search value as a query parameter
                                window.location.href =
                                    "<?php echo BASE_URL;  ?>master/terminal/user_role_exportpdf"

                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            action: function(e, dt, button, config) {
                                // Get the search value
                                // var searchValue = $('.dataTables_filter input').val();
                                // var inspectiontype = $("#inspectiontype").val();
                                // var location = $("#location").val();
                                // var status = $("#status").val();

                                // Redirect to another URL with the search value as a query parameter
                                window.location.href =
                                    "<?php echo BASE_URL;  ?>master/terminal/user_role_exportexcel"

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

        $(document).on("click", ".deletesubtype", function(e) {
            e.preventDefault();
            var url = '<?php echo BASE_URL("master/terminal/delete_user_role"); ?>';
            var deletId = $(this).attr("delt-id");

            deleteDatas(url, deletId, csrfName, csrfToken)


        });

    });
</script>