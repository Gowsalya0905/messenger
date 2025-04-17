<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
            <div class="" style="margin-top:10px;">
                <?php echo $this->session->flashdata('inccatflash');
                $this->session->unset_userdata('inccatflash');
                ?>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $pageTitle; ?></h3>
                            <a href="<?php echo BASE_URL('master/terminal/addDesc') ?>" class="btn btn-sm btn-primary float-right designation"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Designation Name</a>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="employeeid" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Designation ID</th>
                                        <th>User Role</th>
                                        <th>Designation Name</th>

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
<div class="modal modal-info fade" id="rolemodal" role="basic" aria-hidden="true">
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
                                    "<?php echo BASE_URL;  ?>master/terminal/designation_exportpdf"

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
                                    "<?php echo BASE_URL;  ?>master/terminal/designation_exportexcel"

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
        $(document).on('click', '.designation', function(e) {
            e.preventDefault();
            $('#rolemodal').modal('show').find('.modal-content').load($(this).attr('href'));
        });

        $(document).on("click", ".deleteDes", function(e) {
            e.preventDefault();
            var url = '<?php echo BASE_URL("master/terminal/deleteDesignation"); ?>';
            var deletId = $(this).attr("delt-id");

            deleteDatas(url, deletId, csrfName, csrfToken)


        });

    });
</script>