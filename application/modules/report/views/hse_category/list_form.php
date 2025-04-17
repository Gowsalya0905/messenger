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
                                    <h3 class="card-title">HSE-Category Report</h3>
                                </div>
                                <div class="col-md-3">
                                    <div class="float-right">

                                        <button data-toggle="collapse" href="#search" name='Filter' role="button" aria-expanded="false" aria-controls="search" class="btn btn-sm btn-primary">
                                            <i class="fa fa-filter" data-bs-toggle="tooltip" title="Search"></i>
                                            Filter
                                        </button>
                                        <button class="btn btn-sm btn-success" id="export_excel" type="export_excel" name="export_excel">Export Excel</button>
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
                                        <button class="btn btn-success" id="searchform" type="button" name="search">Search</button>
                                        <input type="reset" class="btn btn-danger" id="resetform" name="reset" value="Reset">
                                    </div>

                                </div>
                                <?php
                                echo form_close();
                                ?>
                                <br />

                            </div>

                            <div class="row">
                                <div class="card radius-10 border-top border-0 border-4 border-primary">
                                    <div class="card-body" id="">
                                        <div class="col-md-12" id="LoadHSECATEGORY">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card radius-10 border-top border-0 border-4 border-primary">
                                    <div class="card-header chart_heading">
                                        Total HSE observations
                                        <a class="fas fa-arrow-alt-circle-down chartdownload " style="display:none;" id="LoadHSECATCHARTDOWN_<?php echo $cat->hse_id; ?>"></a>

                                        <div class="card-body" id="">
                                            <div class="col-md-12" id="LoadHSEOBS">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display:none;">
                                <div class="card radius-10 border-top border-0 border-4 border-primary">
                                    <div class="card-header chart_heading">
                                        Total HSE observations by month
                                        <a class="fas fa-arrow-alt-circle-down chartdownload " style="display:none;" id="LoadHSECATCHARTDOWN_<?php echo $cat->hse_id; ?>"></a>
                                    </div>
                                    <div class="card-body" id="">
                                        <div class="col-md-12" id="LoadMONTHHSEOBS">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <?php foreach ($getAllhsecat as $key => $cat): ?>
                                    <div class="col-md-6">
                                        <div class="card radius-10 border-top border-0 border-4 border-primary">

                                            <div class="card-header chart_heading">
                                                <?php echo $cat->hse_cat; ?><a class="fas fa-arrow-alt-circle-down chartdownload " style="display:none;" id="LoadHSECATCHARTDOWN_<?php echo $cat->hse_id; ?>"></a>
                                            </div>
                                            <div class="card-body">
                                                <div id="LoadHSECATEGORYCHART_<?php echo $cat->hse_id; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
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
        load_hse_category_table();
        load_total_hse_category();
        load_monthly_hse_category_chart();
        <?php foreach ($getAllhsecat as $cat): ?>
            load_hse_category_chart(<?php echo $cat->hse_id; ?>);
        <?php endforeach; ?>

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

        function load_hse_category_chart(hse_id) {
            $('#LoadHSECATEGORYCHART_' + hse_id).html('Loading...');
            var url = "<?php echo BASE_URL() . 'report/hse_category_chart'; ?>";
            var data = {
                searchextra: $('#search_form').serializeArray(),
                hse_id: hse_id, // Pass the category ID dynamically
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                cache: false,
                success: function(response) {
                    $('#LoadHSECATEGORYCHART_' + hse_id).html(response);
                },
                error: function() {
                    $('#LoadHSECATEGORYCHART_' + hse_id).html('No Data Found!!!');
                }
            });
        }

        function load_total_hse_category() {
            $('#LoadHSEOBS').html('Loading...');
            var url = "<?php echo BASE_URL() . 'report/total_hse_category_chart'; ?>";
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
                    $('#LoadHSEOBS').html(response);
                },
                error: function() {
                    $('#LoadHSEOBS').html('No Data Found!!!');
                }
            });
        }

        function load_monthly_hse_category_chart() {
            $('#LoadMONTHHSEOBS').html('Loading...');
            var url = "<?php echo BASE_URL() . 'report/monthly_hse_category_chart'; ?>";
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
                    $('#LoadMONTHHSEOBS').html(response);
                },
                error: function() {
                    $('#LoadMONTHHSEOBS').html('No Data Found!!!');
                }
            });
        }


        $('#export_excel').click(function() {
            var queryString = qetquerystring();

            var downloadUrl = "<?php echo BASE_URL(); ?>report/hse_category_exportexcel?" + queryString;
            // window.open(downloadUrl, '_blank');
            window.location.href = downloadUrl;
        });



        $(document).on('click', '#searchform', function() {
            load_hse_category_table();
            load_total_hse_category();
            load_monthly_hse_category_chart();
            <?php foreach ($getAllhsecat as $cat): ?>
                load_hse_category_chart(<?php echo $cat->hse_id; ?>);
            <?php endforeach; ?>
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
            $('#obs_type').val('').trigger('change');
            $('#risk_id').val('').trigger('change');
            $('#NotifyStatus').val('').trigger('change');
            load_hse_category_table();
            load_total_hse_category();
            load_monthly_hse_category_chart();
            <?php foreach ($getAllhsecat as $cat): ?>
                load_hse_category_chart(<?php echo $cat->hse_id; ?>);
            <?php endforeach; ?>
        });


    });
</script>