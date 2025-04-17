<style type="text/css">
 
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
                            <div class="row">
                            <div class="col-md-9">
                                <h3 class="card-title">Employee Integration Error Log List</h3>
                            </div>
                            <div class="col-md-3">
                                <div class="float-right">

                                <button data-toggle="collapse" href="#search" name='Filter' role="button" aria-expanded="false" aria-controls="search" class="btn btn-sm btn-primary d-none">
                                    <i class="fa fa-filter" data-bs-toggle="tooltip" title="Search"></i>
                                    Filter
                                </button>
                                <?php
                                global $atarPermission_emp;
                                // echo "<pre>";
                                // print_r($atarPermission_legal);
                                // exit;
                                $user_type = getCurrentUserGroupId();



                                if (in_array($user_type, $atarPermission_emp['listadd'])) {

                                ?>
                                <!-- <a href="<?php //echo BASE_URL('master/employee/addEmployee') ?>" class="btn btn-sm btn-primary "> Add Employee</a> -->
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
                                 <div class="col-md-3">

                                            <label>Plant </label>
                                            <?php
                                            // $editLoc = $editTerminalId;
                                            $options = $getPlant;
                                            $extra = 'class="form-control select2" id="eventLocation" checkSelect = "select2"';

                                            echo form_dropdown('plant_name', $options, '', $extra);
                                            ?>



                                        </div>

                               <div class="col-md-3 ">
                                            <label>Location </label>
                                            <select class="form-control specificLocation select2 " checkSelect="select2" name="loc_name" id="specificLocation">
                                                <option value="">Select Location</option>
                                            </select>

                                        </div>
                                        <div class="col-md-3 ">
                                            <label>Department </label>
                                            <select class="form-control specificDepartment select2" checkSelect="select2" name="dept" id="specificDepartment">
                                                <option value="">Select Department</option>
                                            </select>

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

                                <div class="col-md-3 m-t-10">
                                    <label>Designation </label>
                                    <select class="form-control specificDesignation select2" checkSelect="select2" name="desig" id="specificDesignation">
                                        <option value="">Select Designation</option>
                                    </select>

                                </div>


                                <!-- <div class="col-md-3 m-t-10">
                                    <label>Designation</label><br>
                                    <div>

                                        <?php

                                        $cNamedatas_desig = [
                                            // 'id' => 'desig',
                                            // 'class' => 'form-control select2',
                                            // 'required' => true,
                                            // 'autocomplete' => 'off',
                                            // 'checkSelect' => 'select2'
                                        ];
                                        //echo form_dropdown('desig', $getdesig, '', $cNamedatas_desig);
                                        ?>


                                    </div>
                                </div> -->


                                <div class="col-md-3 m-t-10">
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

                                <div class="col-md-3 m-t-10">
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


                                <div class="col-md-3 m-t-10">
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

                               <div class="col-md-6" >
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
    function nullcheck(value) {

        return value = value == null ? '' : value;
    }

    function qetquerystring(){

       // var emp_id = $('#emp_id').val();
       // var emp_name = $('#emp_name').val();
      var plant_name = nullcheck($('#eventLocation').val());
        var loc = nullcheck($('#specificLocation').val());
        var dept = nullcheck($('#specificDepartment').val());
       var role = nullcheck($('#role').val());
       var desig = nullcheck($('#desig').val());
        var start_date = nullcheck($('#start_date').val());
        var end_date = nullcheck($('#end_date').val());
        var notifystatus = nullcheck($('#NotifyStatus').val());

       var searchvalue = $('.dataTables_filter input').val();

       return 'plant_name='+plant_name+'&loc='+loc+'&dept='+dept+'&role='+role+'&desig='+desig+'&start_date='+start_date+'&end_date='+end_date+'&notifystatus='+notifystatus+'&searchvalue='+searchvalue
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

            $('#eventLocation').val('').trigger('change');
            $('#specificLocation').val('').trigger('change');
            $('#specificDepartment').val('').trigger('change');
            table.draw();
        });


    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('change', '#eventLocation', function() {
            // alert();
            var eventLocation = $(this).val();
            getSpecificLocatDetailss(eventLocation);
            getSpecificDeptDetailss(eventLocation);
        });
        $(document).on('change', '#role', function() {
            // alert();
            var eventRole = $(this).val();
            getDesignationDetailss(eventRole);
            
        });
    });
    // var specificDepartment = '<?php //echo $editDeptId; 
                                    ?>';
    // if(specificDepartment !=''){
    //       getDeptHeadDetailss(specificDepartment);
    //       getExecDetailss(specificDepartment);
    //   }

    // $(document).on('change','#specificDepartment', function(){
    //     var specificDepartment = $(this).val();
    //     getDeptHeadDetailss(specificDepartment);
    //       getExecDetailss(specificDepartment);
    // });


    function getSpecificLocatDetailss(eventLocation) {

        var url = "<?php echo BASE_URL() . "Main/SpecificLocatDetails" ?>";
        var data = {
            eventLocation: eventLocation,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            cache: false,
            success: function(data) {
                $('#specificLocation').html(data);
                // var specificLocation = '<?php // echo $speclocid; 
                                            ?>';
                //      if (specificLocation != '') {
                $('#specificLocation').val(specificLocation);
                // $('#specificLocation option[value=' + specificLocation + ']').attr('selected', 'selected');

                // } 

            }
        });
    }

    function getSpecificDeptDetailss(eventLocation) {

        var url = "<?php echo BASE_URL() . "Main/SpecificDeptDetails" ?>";
        var data = {
            eventLocation: eventLocation,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            cache: false,
            success: function(data) {
                $('#specificDepartment').html(data);
                // var specificDepartment = '<?php //echo $editDeptId; 
                                                ?>';
                //      if (specificDepartment != '') {
                $('#specificDepartment').val(specificDepartment);
                //  $('#specificDepartment option[value=' + specificDepartment + ']').attr('selected', 'selected');
                // } 

            }
        });
    }

     function getDesignationDetailss(eventRole) {

        var url = "<?php echo BASE_URL() . "Main/SpecificDesignationDetails" ?>";
        var data = {
            eventRole: eventRole,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        };

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            cache: false,
            success: function(data) {
                $('#specificDesignation').html(data);
                // var specificDepartment = '<?php //echo $editDeptId; 
                                                ?>';
                //      if (specificDepartment != '') {
                $('#specificDesignation').val(specificDesignation);
                //  $('#specificDepartment option[value=' + specificDepartment + ']').attr('selected', 'selected');
                // } 

            }
        });
    }
</script>