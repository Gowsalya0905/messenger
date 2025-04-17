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
                                    <h3 class="card-title">Employee List</h3>
                                </div>
                                <div class="col-md-3">
                                    <div class="float-right">

                                        <button data-toggle="collapse" href="#search" name='Filter' role="button" aria-expanded="false" aria-controls="search" class="btn btn-sm btn-primary">
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
                                            <a href="<?php echo BASE_URL('master/employee/addEmployee') ?>" class="btn btn-sm btn-primary "> Add Employee</a>
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

                                        <label>Company Name</label>
                                        <?php
                                        $cmpdata = [
                                            'class' => 'form-control select2',
                                            'id' => 'company',
                                            'checkSelect' => 'select2',
                                        ];
                                        echo form_dropdown('company_id', $dropcompany, $cmp_name, $cmpdata);
                                        ?>
                                        <span class="error"><?php echo form_error('company_id') ?></span>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Area Name</label>
                                        <select class="form-control area_id select2 " checkSelect="select2" name="area_id" id="area_id">
                                            <option value="">Select Area</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Building/Block/Direction</label>
                                        <select class="form-control building_id select2 " checkSelect="select2" name="building_id" id="building_id">
                                            <option value="">Select Building/Block/Direction</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 m-t-10">
                                        <label>Department </label>
                                        <select class="form-control department select2 " checkSelect="select2" name="department_id" id="dept_id">
                                            <option value="">Select Department</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 m-t-10">
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
                                        <select class="form-control desig select2 " checkSelect="select2" name="desig_id" id="desig">
                                            <option value="">Select Department</option>
                                        </select>

                                    </div>


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

                                    <div class="col-md-6">
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
                                        <th> Company</th>
                                        <th> Area</th>
                                        <th> Building/Block/Direction</th>
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

    function qetquerystring() {

        var company_id = nullcheck($('#company').val());
        var area_id = nullcheck($('#area_id').val());
        var building_id = nullcheck($('#building_id').val());
        var dept_id = nullcheck($('#dept_id').val());
        var role = nullcheck($('#role').val());
        var desig = nullcheck($('#desig').val());
        var start_date = nullcheck($('#start_date').val());
        var end_date = nullcheck($('#end_date').val());
        var notifystatus = nullcheck($('#NotifyStatus').val());

        var searchvalue = $('.dataTables_filter input').val();

        return 'company_id=' + company_id + '&area_id=' + area_id + '&building_id=' + building_id + '&dept_id=' + dept_id + '&role=' + role + '&desig=' + desig + '&start_date=' + start_date + '&end_date=' + end_date + '&notifystatus=' + notifystatus + '&searchvalue=' + searchvalue
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

            $('#company').val('').trigger('change');
            $('#area_id').val('').trigger('change');
            $('#building_id').val('').trigger('change');
            $('#dept_id').val('').trigger('change');
            $('#role').val('').trigger('change');
            $('#desig').val('').trigger('change');
            table.draw();
        });


    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('change', '#role', function() {
            var eventRole = $(this).val();
            getDesignationDetailss(eventRole);

        });
        $(document).on('change', '#company', function() {
            // alert();
            var company = $(this).val();
            getAreaDetails(company);
        });
        $(document).on('change', '#area_id', function() {
            var area_id = $(this).val();
            getBuildingDetails(area_id);
            getDepartmentDetails(area_id);
        });



        function getAreaDetails(company) {

            var url = "<?php echo BASE_URL() . "Main/AreaDetails" ?>";
            var data = {
                company: company,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };

            $.ajax({
                type: 'post',
                url: url,
                data: data,
                cache: false,
                success: function(data) {
                    $('#area_id').html(data);
                    var area_id = '<?php echo $area_id; ?>';
                    if (area_id != '') {
                        $('#area_id').val(area_id);
                        $('#area_id option[value=' + area_id + ']').attr('selected', 'selected');
                    } else {
                        $('#building_id').val('');
                        $('#department_id').val('');
                    }

                }
            });
        }

        function getBuildingDetails(area) {

            var url = "<?php echo BASE_URL() . "Main/BuildingDetails" ?>";
            var data = {
                area: area,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };

            $.ajax({
                type: 'post',
                url: url,
                data: data,
                cache: false,
                success: function(data) {
                    $('#building_id').html(data);
                    var building_id = '<?php echo $building_id; ?>';
                    if (building_id != '') {
                        $('#building_id').val(building_id);
                        $('#building_id option[value=' + building_id + ']').attr('selected', 'selected');

                    } else {
                        $('#department_id').val('');
                    }

                }
            });
        }


        function getDepartmentDetails(area) {

            var url = "<?php echo BASE_URL() . "Main/DepartmentDetails" ?>";
            var data = {
                area: area,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };

            $.ajax({
                type: 'post',
                url: url,
                data: data,
                cache: false,
                success: function(data) {
                    $('#dept_id').html(data);
                    var department = '<?php echo $department_id; ?>';
                    if (department != '') {
                        $('#dept_id').val(department);
                        $('#dept_id option[value=' + department + ']').attr('selected', 'selected');
                    }

                }
            });
        }

    });

    function getDesignationDetailss(roleid) {

        var url = "<?php echo BASE_URL('common/getAjaxDesignation') ?>";
        var data = {
            roleid: roleid,
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
        $.ajax({
            dataType: 'json',
            data: 'ajax',
            method: 'post',
            data: data,
            url: url,
            success: function(resp) {
                var appendCheck = '<option value="">Select Designation</option>';
                if (resp.status) {
                    $.each(resp.chdatas, function(iKey, iVal) {
                        var moduleId = <?php echo ($emplDesig) ? $emplDesig : 0; ?>;
                        var selVal = '';
                        <?php if ($emplDesig != 0) { ?>
                            if (moduleId == iKey) {
                                var selVal = 'selected';
                            }
                        <?php } ?>
                        if (iKey != '') {
                            appendCheck += '<option value=' + iKey + ' ' + selVal + '>' + iVal + '</option>';
                        }
                    });
                }
                $("#desig").html(appendCheck);
            }
        });
    }
</script>