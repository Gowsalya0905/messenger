<?php
$area_id = $building_id  =  $department_id = $project_id = $select_status = $select_cat = $select_startdate = $select_enddate = '';

?>
<style>
    /* Make table headers responsive */
    #employeeid th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
        min-width: 80px;
    }

    #employeeid th {
        font-size: 14px;
        padding: 8px;
    }

    table {
        width: 100%;
        table-layout: auto;
    }

    div[style*="overflow-x: auto"] {
        max-width: 100%;
        overflow-x: auto;
    }

    #employeeid>thead>tr {
        color: #fff !important;
        font-size: 14px;
        background: #004EA3 !important;
    }

    .apexcharts-menu-item .exportSVG {
        display: none !important;
    }

    .apexcharts-menu-item .exportCSV {
        display: none !important;
    }

    .chart_heading {
        font-weight: bold;
        color: #203669;
        text-transform: uppercase;
    }

    .chartdownload {
        float: inline-end;
        font-size: 22px;
    }

    .datepicker-switch {
        color: white !important;
    }
</style>
<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
            <div class="mt-3">
                <?php
                echo $this->session->flashdata('incidentflash');
                $this->session->unset_userdata('incidentflash');
                ?>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- Card Header -->
                        <div class="card-header">
                            <h3 class="card-title">OBS Status</h3>
                            <div class="d-flex justify-content-end gap-2">
                                <button data-toggle="collapse" href="#search" class="btn btn-sm btn-primary"
                                    role="button" aria-expanded="false" aria-controls="search">
                                    <i class="fa fa-filter" data-bs-toggle="tooltip" title="Search"></i> Filter
                                </button>
                                <button class="btn btn-sm btn-success ml-2" id="export_excel" name="export_excel">Export
                                    Excel</button>
                                <?php
                                global $obsPermission;
                                $user_type = getCurrentUserGroupId();
                                ?>
                            </div>
                        </div>


                        <!-- Card Body -->
                        <div class="card-body">
                            <!-- Search Filters -->
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
                                            <select class="form-control area_id select2 " checkSelect="select2"
                                                name="area_id" id="area_id">
                                                <option value="">Select Area</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Building/Block/Direction</label>
                                            <select class="form-control building_id select2 " checkSelect="select2"
                                                name="building_id" id="building_id">
                                                <option value="">Select Building</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Department </label>
                                            <select class="form-control department select2 " checkSelect="select2"
                                                name="department_id" id="dept_id">
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
                                            <label>Responsible Person </label><br>
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
                                        <label>Start Month/Year </label><br>
                                        <div>
                                            <?php
                                            $data = array(
                                                'name' => 'start_date',
                                                'id' => 'start_date',
                                                'placeholder' => '',
                                                'class' => 'form-control datepicker-monthyear',
                                                'autocomplete' => 'off',
                                                'value' => $select_startdate,  // The format will be MM-YYYY
                                            );

                                            echo form_input($data);
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3 m-t-10">
                                        <label>End Month/Year </label><br>
                                        <div>
                                            <?php
                                            $data = array(
                                                'name' => 'end_date',
                                                'id' => 'end_date',
                                                'placeholder' => '',
                                                'class' => 'form-control datepicker-monthyear',
                                                'autocomplete' => 'off',
                                                'value' => $select_enddate,  // The format will be MM-YYYY
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
                                        <button class="btn btn-success" id="searchform" type="button"
                                            name="search">Search</button>
                                        <input type="reset" class="btn btn-danger" id="resetform" name="reset"
                                            value="Reset">
                                    </div>

                                </div>
                                <?php
                                echo form_close();
                                ?>
                                <br />

                            </div>

                            <!-- Cards -->

                            <div class="row">
                                <div class="card radius-10 border-top border-0 border-4 border-primary">
                                    <div class="card-body" id="">
                                        <div class="col-md-12" id="LoadHSECATEGORY">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card radius-10 border-primary">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">Observation Status</h5>
                                            <a class="fas fa-arrow-alt-circle-down chartdownload" style="display:none;"
                                                id="LoadHSECATCHARTDOWN"></a>
                                        </div>
                                        <div class="card-body">
                                            <div id="LoadOBSSTATUS"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card radius-10 border-primary">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">UA & UC Status</h5>
                                            <a class="fas fa-arrow-alt-circle-down chartdownload" style="display:none;"
                                                id="LoadHSECATCHARTDOWN"></a>
                                        </div>
                                        <div class="card-body">
                                            <div id="LoadUAUC_status"></div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="row mt-4">


                                <div class="col-md-12 mt-4">
                                    <div class="card radius-10 border-primary">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">Days Pending(Open)</h5>
                                            <a class="fas fa-arrow-alt-circle-down chartdownload" style="display:none;"
                                                id="LoadDaysPendingDownload"></a>
                                        </div>
                                        <div class="card-body">
                                            <div id="LoadDaysPending"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <div class="card radius-10 border-primary">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">Days to Closure(Closed)</h5>
                                            <a class="fas fa-arrow-alt-circle-down chartdownload" style="display:none;"
                                                id="LoadDaysCloseDownload"></a>
                                        </div>
                                        <div class="card-body">
                                            <div id="LoadDaysClose"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </section>

    </div>
    <!-- /.row -->

    <!-- /.content -->
</div>


<script>
    $(document).ready(function() {
        $(document).ready(function() {
            var startDatePicker = $(".datepicker-monthyear#start_date");
            var endDatePicker = $(".datepicker-monthyear#end_date");

            startDatePicker.datepicker({
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                format: 'mm-yyyy',
            }).on('changeDate', function(selected) {
                var selectedStartDate = new Date(selected.date.valueOf());
                endDatePicker.datepicker('setStartDate', selectedStartDate);
                endDatePicker.datepicker('setDate', selectedStartDate);

            });

            endDatePicker.datepicker({
                autoclose: true,
                startView: "months",
                minViewMode: "months",
                format: 'mm-yyyy',
            }).on('changeDate', function(selected) {
                var selectedEndDate = new Date(selected.date.valueOf());
                var selectedStartDate = startDatePicker.datepicker('getDate');
                if (selectedEndDate < selectedStartDate) {
                    alert("End date cannot be earlier than the start date.");
                    endDatePicker.val(''); // Clear the end date field
                }
            });
        });

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
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
        var fac_injury = nullcheck($('#fac_injury').val());
        var obs_type = nullcheck($('#obs_type').val());
        var risk_id = nullcheck($('#risk_id').val());
        var start_date = nullcheck($('#start_date').val());
        var end_date = nullcheck($('#end_date').val());
        var notifystatus = nullcheck($('#NotifyStatus').val());
        var searchvalue = $('.dataTables_filter input').val();


        return 'hse_cat=' + hse_cat + '&fac_injury=' + fac_injury + '&obs_type=' + obs_type + '&risk_id=' + risk_id +
            '&emp_name=' + emp_name + '&company_id=' + company_id + '&area_id=' + area_id + '&building_id=' + building_id +
            '&department_id=' + dept_id + '&project_id=' + project_id + '&start_date=' + start_date + '&end_date=' +
            end_date + '&NotifyStatus=' + notifystatus + '&searchvalue=' + searchvalue
    }


    $(function() {

        // / Datatable /
        load_hse_category_table();


        ua_uc_status();
        load_days_pending()
        load_days_close()
        load_total_obs_status()


        function load_hse_category_table() {
            $('#LoadHSECATEGORY').html('');
            var url = "<?php echo isset($ajaxurl) ? site_url($ajaxurl) : '' ?>";
            var data = {
                searchextra: $('#search_form').serializeArray(),
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                cache: false,
                success: function(response) {
                    $('#LoadHSECATEGORY').html(response);
                }
            });
        }




        function load_days_pending() {
            $('#LoadDaysPending').html('Loading...');
            var url = "<?php echo BASE_URL() . 'report/total_days_pending_chart'; ?>";
            var data = {
                searchextra: $('#search_form').serializeArray(),
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                cache: false,
                success: function(response) {
                    $('#LoadDaysPending').html(response);
                },
                error: function() {
                    $('#LoadDaysPending').html('No Data Found!!!');
                }
            });
        }

        function load_days_close() {
            $('#LoadDaysClose').html('Loading...');
            var url = "<?php echo BASE_URL() . 'report/total_days_close_chart'; ?>";
            var data = {
                searchextra: $('#search_form').serializeArray(),
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                cache: false,
                success: function(response) {
                    $('#LoadDaysClose').html(response);
                },
                error: function() {
                    $('#LoadDaysClose').html('No Data Found!!!');
                }
            });
        }

        function ua_uc_status() {
            $('#LoadUAUC_status').html('Loading...');
            var url = "<?php echo BASE_URL() . 'report/ua_uc_status'; ?>";
            var data = {
                searchextra: $('#search_form').serializeArray(),
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                cache: false,
                success: function(response) {
                    $('#LoadUAUC_status').html(response);
                },
                error: function() {
                    $('#LoadUAUC_status').html('No Data Found!!!');
                }
            });
        }

        function load_total_obs_status() {
            $('#LoadOBSSTATUS').html('Loading...');
            var url = "<?php echo BASE_URL() . 'report/total_obs_status_count'; ?>";
            var data = {
                searchextra: $('#search_form').serializeArray(),
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                cache: false,
                success: function(response) {
                    $('#LoadOBSSTATUS').html(response);
                },
                error: function() {
                    $('#LoadOBSSTATUS').html('No Data Found!!!');
                }
            });
        }



        $('#export_excel').click(function() {
            var queryString = qetquerystring();

            var downloadUrl = "<?php echo BASE_URL(); ?>report/obs_status_exportexcel?" + queryString;
            // window.open(downloadUrl, '_blank');
            window.location.href = downloadUrl;
        });



        $(document).on('click', '#searchform', function() {
            load_hse_category_table();
            ua_uc_status();
            load_days_pending()
            load_days_close()
            load_total_obs_status()

        });

        $(document).on('click', '#resetform', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#company').val('').trigger('change');
            $('#area_id').val('').trigger('change');
            $('#building_id').val('').trigger('change');
            $('#dept_id').val('').trigger('change');
            $('#project_id').val('').trigger('change');
            $('#emp_name').val('').trigger('change');
            $('#hse_cat').val('').trigger('change');
            $('#fac_injury').val('').trigger('change');
            $('#obs_type').val('').trigger('change');
            $('#risk_id').val('').trigger('change');
            $('#NotifyStatus').val('').trigger('change');
            load_hse_category_table();
            ua_uc_status();
            load_days_pending()
            load_days_close()
            load_total_obs_status()

        });


    });
</script>