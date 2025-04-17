<style type="text/css">
    #employeeid>thead>tr {
        color: #fff !important;
        font-size: 14px;
        background: #203669 !important;
    }
</style>
<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="" style="margin-top:10px;">
                <?php echo $this->session->flashdata('employee');
                $this->session->unset_userdata('employee');

                ?>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card ">
                        <div class="card-header">
                            <div class="col-10">
                                <h3 class="card-title">Employee Management</h3>
                            </div>
                            <div class="col-2 float-right">
                                <!-- <a href="<?php echo BASE_URL('master/employeeUpload') ?>" class="btn btn-sm btn-primary " ><i class="fa fa-upload"></i> Bulk Upload</a> -->
                               <button data-toggle="collapse" href="#search"  role="button" aria-expanded="false" aria-controls="search" class="btn btn-sm btn-primary">
    <i class="fa fa-filter" data-bs-toggle="tooltip" title="Search"></i>
    Filter
</button>
                                <a href="<?php echo BASE_URL('master/employee/addEmployee') ?>" class="btn btn-sm btn-primary "><i class="fa fa-plus-circle"></i> Add Employee</a>
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

                                <div class="col-md-3">
                                    <label>Employee ID</label><br>
                                    <div>

                                        <?php

                                        $cNamedatas_pl = [
                                            'id' => 'emp_id',
                                            'class' => 'form-control select2',
                                            'required' => true,
                                            'autocomplete' => 'off',
                                            'checkSelect' => 'select2'
                                        ];
                                        echo form_dropdown('emp_id', $getempid_drop, '', $cNamedatas_pl);
                                        ?>


                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>Employee Name</label><br>
                                    <div>

                                        <?php

                                        $cNamedatas_pl = [
                                            'id' => 'emp_name',
                                            'class' => 'form-control select2',
                                            'required' => true,
                                            'autocomplete' => 'off',
                                            'checkSelect' => 'select2'
                                        ];
                                        echo form_dropdown('emp_name', $getempname_drop, '', $cNamedatas_pl);
                                        ?>


                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>Plant Name</label><br>
                                    <div>

                                        <?php

                                        $cNamedatas_pl = [
                                            'id' => 'plant_name',
                                            'class' => 'form-control select2',
                                            'required' => true,
                                            'autocomplete' => 'off',
                                            'checkSelect' => 'select2'
                                        ];
                                        echo form_dropdown('plant_name', $getPlant, '', $cNamedatas_pl);
                                        ?>


                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label>Specific Location</label><br>
                                    <div>

                                        <?php

                                        $cNamedatas_loc = [
                                            'id' => 'loc',
                                            'class' => 'form-control select2',
                                            'required' => true,
                                            'autocomplete' => 'off',
                                            'checkSelect' => 'select2'
                                        ];
                                        echo form_dropdown('loc_name', $getlocation, '', $cNamedatas_loc);
                                        ?>


                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>Department</label><br>
                                    <div>

                                        <?php

                                        $cNamedatas_dept = [
                                            'id' => 'dept',
                                            'class' => 'form-control select2',
                                            'required' => true,
                                            'autocomplete' => 'off',
                                            'checkSelect' => 'select2'
                                        ];
                                        echo form_dropdown('dept', $getDeptnames, '', $cNamedatas_dept);
                                        ?>


                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>Role</label><br>
                                    <div>

                                        <?php
                                        $cNamedatas_role = [
                                            'id' => 'role',
                                            'class' => 'form-control select2',
                                            'required' => true,
                                            'autocomplete' => 'off',
                                            'checkSelect' => 'select2'
                                        ];
                                        echo form_dropdown('role', $getroles, '', $cNamedatas_role);
                                        ?>


                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label>Designation</label><br>
                                    <div>

                                        <?php

                                        $cNamedatas_desig = [
                                            'id' => 'desig',
                                            'class' => 'form-control select2',
                                            'required' => true,
                                            'autocomplete' => 'off',
                                            'checkSelect' => 'select2'
                                        ];
                                        echo form_dropdown('desig', $getdesig, '', $cNamedatas_desig);
                                        ?>


                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <label>Start Date </label><br>
                                    <div>

                                        <?php
                                        $data = array(
                                            'name' => 'start_date',
                                            'id' => 'start_date',
                                            'placeholder' => '',
                                            'class' => 'form-control datepicker',
                                            'autocomplete' => 'off',
                                            'value' => '',
                                        );

                                        echo form_input($data);
                                        ?>
                                        <?php echo form_error('start_date', '<div class="error">', '</div>'); ?>
                                        <span class="red"></span><br />


                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label>End Date </label><br>
                                    <div>
                                        <?php
                                        $data = array(
                                            'name' => 'end_date',
                                            'id' => 'end_date',
                                            'placeholder' => '',
                                            'class' => 'form-control datepicker',
                                            'autocomplete' => 'off',
                                            'value' => '',
                                        );

                                        echo form_input($data);
                                        ?>
                                        <?php echo form_error('end_date', '<div class="error">', '</div>'); ?>
                                        <span class="red"></span><br />


                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <label>Status </label><br>
                                    <div>
                                        <select class="form-control NotifyStatus select2" name="NotifyStatus" id="NotifyStatus">
                                            <option value="">Select - Status </option>
                                            <?php
                                            foreach ($statusemp as $ke => $val) {
                                            ?>
                                                <option value="<?php echo $ke ?>"><?php echo $val ?></option>
                                            <?php } ?>
                                        </select>


                                    </div>

                                </div>

                                <div class="col-md" style="margin-top:8px;">
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
                                        <th> Employee ID </th>
                                        <th> Employee Name </th>
                                        <th> Plant</th>
                                        <th> Specific Location</th>
                                        <th> Department</th>
                                        <th> Role</th>
                                        <th> Designation</th>
                                        <th> Created On</th>
                                        <th> Status</th>
                                        <th> Action</th>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>
<!----- popup ends----->






<script>
    $(document).ready(function() {

        $(".datepicker").datepicker({
            autoclose: true
        });

        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfToken = '<?php echo $this->security->get_csrf_hash(); ?>';

        $(document).on('click', '.emplStatus', function(e) {
            e.preventDefault();
            $('#respmodal').modal('show').find('.modal-content').load($(this).attr('href'));
        });

        $(document).on("click", ".deleteEmployee", function(e) {
            e.preventDefault();
            var url = '<?php echo BASE_URL("master/employee/deleteEmployee"); ?>';
            var deletId = $(this).attr("delt-id");

            deleteDatas(url, deletId, csrfName, csrfToken)


        });

    });
</script>


<script type="text/javascript">

    function qetquerystring(){

       var emp_id = $('#emp_id').val();
       var emp_name = $('#emp_name').val();
       var plant_name = $('#plant_name').val();
       var loc = $('#loc').val();
       var dept = $('#dept').val();
       var role = $('#role').val();
       var desig = $('#desig').val();
       var start_date = $('#start_date').val();
       var end_date = $('#end_date').val();
       var notifystatus = $('#NotifyStatus').val();

       var searchvalue = $('.dataTables_filter input').val();

       return 'emp_id='+emp_id+'&emp_name='+emp_name+'&plant_name='+plant_name+'&loc='+loc+'&dept='+dept+'&role='+role+'&desig='+desig+'&start_date='+start_date+'&end_date='+end_date+'&notifystatus='+notifystatus+'&searchvalue='+searchvalue
    }

    $(function() {

        / Datatable /
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

                            var searchvalue = $('.dataTables_filter input').val();
                            searchextra = $('#search_form').serializeArray();
                            // Redirect to another URL with the search value as a query parameter
                            window.location.href =
                                "<?php echo BASE_URL;  ?>master/employee/exportpdf?" + qetquerystring()
                                
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        action: function(e, dt, button, config) {
                            // Get the search value
                            var searchValue = $('.dataTables_filter input').val();
                            var inspectiontype = $("#inspectiontype").val();
                            var location = $("#location").val();
                            var status = $("#status").val();

                            // Redirect to another URL with the search value as a query parameter
                            window.location.href =
                            "<?php echo BASE_URL;  ?>master/employee/exportexcel?" + qetquerystring()
                              
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
            table.draw();
        });


    });
</script>