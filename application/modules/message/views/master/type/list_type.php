<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="" style="margin-top:10px;">
                <?php
                echo $this->session->flashdata('typeflash');
                $this->session->unset_userdata('typeflash'); ?>
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

                                        <button data-toggle="collapse" href="#search" name='Filter' role="button" aria-expanded="false" aria-controls="search" class="btn btn-sm btn-primary" style="margin-right: 10px;>
                                            <i class="fa fa-filter" data-bs-toggle="tooltip" title="Search"></i>
                                            Filter
                                        </button>
                                        <?php
                                        global $permission_master;

                                        $user_type = getCurrentUserGroupId();
                                        if (in_array($user_type, $permission_master['listadd'])) {
                                        ?>
                                            <a href="<?php echo BASE_URL('message/master/add_type') ?>" class="btn btn-sm btn-primary float-right resptarget">
                                                Add Message
                                            </a>
                                        <?php
                                        }

                                        ?>
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

                                    echo form_open('message/master/type_list', $formData);
                                    global $persons;
                                    // print_r($persons);
                                    // die;
                                    ?>
                                    <div class="row m-t-10">

                                        <?php
                                        $login_id = $this->session->userdata('userinfo')->LOGIN_ID;

                                        if ($login_id == 1) {
                                        ?>
                                            <div class="col-md-3 m-t-10">
                                                <label>Person Type</label><br>
                                                <div>
                                                    <?php
                                                    $cNamedatas_pl = [
                                                        'id' => 'per_type',
                                                        'class' => 'form-control select2',
                                                        'required' => true,
                                                        'autocomplete' => 'off',
                                                    ];
                                                    echo form_dropdown('per_type', $persons, '', $cNamedatas_pl);
                                                    ?>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                        <div class="col-md-3 m-t-10">
                                            <label>Message</label>
                                            <input type="text" class="form-control" name="message" placeholder="Enter value">
                                        </div>



                                        <div class="col-md-6" style="margin-top:20px;">
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
                                            <?php if ($this->session->userdata('userinfo')->LOGIN_ID == 1): ?>
                                                <th>Person Name</th>
                                            <?php endif; ?>
                                            <th>message</th>
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
                                    "<?php echo BASE_URL;  ?>message/master/type_exportpdf"

                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            action: function(e, dt, button, config) {

                                window.location.href =
                                    "<?php echo BASE_URL;  ?>message/master/type_exportexcel"

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
            $('#company').val('').trigger('change');
            $('#area_id').val('').trigger('change');
            $('#building_id').val('').trigger('change');
            $('#dept_id').val('').trigger('change');
            $('#project_id').val('').trigger('change');
            $('#emp_name').val('').trigger('change');
            $('#hse_cat').val('').trigger('change');
            $('#obs_type').val('').trigger('change');
            $('#risk_id').val('').trigger('change');
            $('#NotifyStatus').val('').trigger('change');
            $('#mainStatus').val('').trigger('change');
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

        $(document).on("click", ".deleteType", function(e) {
            e.preventDefault();
            var url = '<?php echo BASE_URL("message/master/delete_type"); ?>';
            var deletId = $(this).attr("delt-id");

            deleteDatas(url, deletId, csrfName, csrfToken)


        });




    });
</script>