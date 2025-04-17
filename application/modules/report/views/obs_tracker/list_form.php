<?php
$area_id = $building_id  =  $department_id = $project_id = $select_status = $select_cat = $select_startdate = $select_enddate = '';

?>
<style>
    /* Make table headers responsive */
    #employeeid th {
        white-space: nowrap;
        /* Prevent text from wrapping */
        overflow: hidden;
        /* Hide overflow content */
        text-overflow: ellipsis;
        /* Add ellipsis for overflowing text */
        max-width: 150px;
        /* Adjust the maximum width based on your requirements */
        min-width: 80px;
        /* Ensure headers have a minimum width */
    }

    /* Optional: For better appearance on small screens */
    #employeeid th {
        font-size: 14px;
        /* Reduce font size for smaller devices */
        padding: 8px;
        /* Adjust padding for smaller headers */
    }

    table {
        width: 100%;
        /* Let the table take full width */
        table-layout: auto;
        /* Enable automatic layout based on content */
    }

    div[style*="overflow-x: auto"] {
        max-width: 100%;
        /* Limit the container to the screen width */
        overflow-x: auto;
        /* Add horizontal scrolling */
    }

    #employeeid>thead>tr {
        color: #fff !important;
        font-size: 14px;
        background: #004EA3 !important;
    }
</style>
<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
            <div class="" style="margin-top:10px;">
                <?php echo $this->session->flashdata('incidentflash');
                $this->session->unset_userdata('incidentflash');
                ?>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card ">



                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    <h3 class="card-title">Observation Tracker</h3>
                                </div>
                                <div class="col-md-3">
                                    <div class="float-right">

                                        <button data-toggle="collapse" href="#search" name='Filter' role="button" aria-expanded="false" aria-controls="search" class="btn btn-sm btn-primary">
                                            <i class="fa fa-filter" data-bs-toggle="tooltip" title="Search"></i>
                                            Filter
                                        </button>
                                        <?php
                                        global $obsPermission;

                                        $user_type = getCurrentUserGroupId();
                                        ?>

                                        <?php
                                        // }

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

                                    <?php
                                    if (in_array($user_type, $obsPermission['view_filter'])) {

                                    ?>

                                        <div class="col-md-3">

                                            <label>Company Name</label>
                                            <?php
                                            $cmpdata = [
                                                'class' => 'form-control select2',
                                                'id' => 'company',
                                                'checkSelect' => 'select2',
                                            ];
                                            echo form_dropdown('company_id', $dropcompany, '', $cmpdata);
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
                                                <option value="">Select Building</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Department </label>
                                            <select class="form-control department select2 " checkSelect="select2" name="department_id" id="dept_id">
                                                <option value="">Select Department</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 m-t-10">

                                            <label>Project</label>
                                            <?php
                                            $cmpdata = [
                                                'class' => 'form-control select2',
                                                'id' => 'project_id',
                                                'checkSelect' => 'select2',
                                            ];
                                            echo form_dropdown('project_id', $dropproject, '', $cmpdata);
                                            ?>
                                            <span class="error"><?php echo form_error('project_id') ?></span>
                                        </div>

                                        <div class="col-md-3 m-t-10">
                                            <label>Responsible Person</label><br>
                                            <div>

                                                <?php

                                                $cNamedatas_pl = [
                                                    'id' => 'emp_name',
                                                    'class' => 'form-control select2',
                                                    'required' => true,
                                                    'autocomplete' => 'off',
                                                    'checkSelect' => 'select2'
                                                ];
                                                echo form_dropdown('emp_name', $getex_drop, '', $cNamedatas_pl);
                                                ?>


                                            </div>
                                        </div>
                                    <?php
                                    }

                                    ?>
                                    <div class="col-md-3 m-t-10">
                                        <label>HSE Category</label><br>
                                        <div>

                                            <?php

                                            $cNamedatas_pl = [
                                                'id' => 'hse_cat',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('hse_cat', $drophsecat, '', $cNamedatas_pl);
                                            ?>
                                        </div>
                                    </div>


                                    <div class="col-md-3 m-t-10">
                                        <label>Observation Type</label><br>
                                        <div>

                                            <?php

                                            $cNamedatas_pl = [
                                                'id' => 'obs_type',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('obs_type', $obs_type_list, '', $cNamedatas_pl);
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3 m-t-10">
                                        <label>Risk Rating</label><br>
                                        <div>
                                            <?php
                                            $cNamedatas_pl = [
                                                'id' => 'risk_id',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('risk_id', $risk_rating, '', $cNamedatas_pl);
                                            ?>
                                        </div>
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
                                                'value' => $select_startdate,
                                            );

                                            echo form_input($data);
                                            ?>


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
                                                'value' => $select_enddate,
                                            );

                                            echo form_input($data);
                                            ?>


                                        </div>
                                    </div>
                                    <div class="col-md-3 m-t-10 d-none">
                                        <label>Status </label><br>
                                        <div>

                                            <?php

                                            $cNamedatas_pl = [
                                                'id' => 'NotifyStatus',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('NotifyStatus', $status_drop_proj, $select_status, $cNamedatas_pl);
                                            ?>


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
                            <div style="overflow-x: auto;">
                                <table id="employeeid" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="color: #fff !important;font-size: 14px;background: #004EA3 !important;">
                                            <th> Report No </th>
                                            <th> Year </th>
                                            <th> Month </th>
                                            <th> Observation Date </th>
                                            <th> HSE Category </th>
                                            <th> Description of Observation </th>
                                            <th> Risk Level </th>
                                            <th> Rectification </th>
                                            <th> Type of Observation or Incident </th>
                                            <!-- <th> Injury Mechanism (FAC Only) </th> -->
                                            <th> Actionee </th>
                                            <th> Planned Closeout Date </th>
                                            <th> Actual Closeout Date </th>
                                            <th> Status</th>
                                            <th> Raised By PC / EPC/CCS Sub-Con</th>
                                            <th> Obs. Raised By</th>
                                            <th> Number of Days Pending</th>
                                            <th> Number of Days to Closure</th>
                                            <th> Delay (Days)</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.col -->
        </section>
    </div>
    <!-- /.row -->

    <!-- /.content -->
</div>






<script>
    $(document).ready(function() {

        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfToken = '<?php echo $this->security->get_csrf_hash(); ?>';
        $(document).on("click", ".deleteIncident", function(e) {
            e.preventDefault();
            var url = '<?php echo BASE_URL("atarusee/incident/deleteData"); ?>';
            var deletId = $(this).attr("delid");
            deleteDatas(url, deletId, csrfName, csrfToken)
        });

        $(document).on('change', '#company', function() {
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
                    var area = '<?php echo $area_id; ?>';
                    if (area != '') {
                        $('#area_id').val(area);
                        $('#area_id option[value=' + area + ']').attr('selected', 'selected');
                    } else {
                        $('#building_id').val('');
                        $('#dept_id').val('');
                        $('#project_id').val('');
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
                    var building = '<?php echo $building_id; ?>';
                    if (building != '') {
                        $('#building_id').val(building);
                        $('#building_id option[value=' + building + ']').attr('selected', 'selected');
                    } else {
                        $('#dept_id').val('');
                        $('#project_id').val('');
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
</script>
<script type="text/javascript">
    function nullcheck(value) {

        return value = value == null ? '' : value;
    }

    function qetquerystring() {

        const urlParams = new URLSearchParams(window.location.search);
        var company_id = nullcheck($('#company').val());
        var area_id = nullcheck($('#area_id').val());
        var building_id = nullcheck($('#building_id').val());
        var dept_id = nullcheck($('#dept_id').val());
        var project_id = nullcheck($('#project_id').val());
        var emp_name = nullcheck($('#emp_name').val());
        var hse_cat = nullcheck($('#hse_cat').val());
        var obs_type = nullcheck($('#obs_type').val());
        var risk_id = nullcheck($('#risk_id').val());
        var start_date = nullcheck($('#start_date').val());
        var end_date = nullcheck($('#end_date').val());
        var notifystatus = nullcheck($('#NotifyStatus').val());
        var searchvalue = $('.dataTables_filter input').val();


        return 'hse_cat=' + hse_cat + '&obs_type=' + obs_type + '&risk_id=' + risk_id + '&emp_name=' + emp_name + '&company_id=' + company_id + '&area_id=' + area_id + '&building_id=' + building_id + '&department_id=' + dept_id + '&project_id=' + project_id + '&start_date=' + start_date + '&end_date=' + end_date + '&NotifyStatus=' + notifystatus + '&searchvalue=' + searchvalue
    }


    $(function() {

        // / Datatable /
        var table = $('#employeeid').DataTable({
            scrollX: true, // Enable horizontal scrolling
            autoWidth: true,
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
                data: function(d) {
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
                text: 'Report',
                buttons: [{
                    extend: 'excel',
                    text: 'Download Excel',
                    action: function(e, dt, button, config) {

                        window.location.href =
                            "<?php echo BASE_URL;  ?>report/obstracker_exportexcel?" + qetquerystring()

                    }
                }, ]
            }, 'pageLength'],
            columnDefs: [{
                    targets: "_all",
                    width: "auto"
                } // Auto-adjust column widths
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
            table.draw();
        });


    });
</script>

<script type="text/javascript">


</script>