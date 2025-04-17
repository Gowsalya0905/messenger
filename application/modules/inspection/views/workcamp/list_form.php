<?php
$select_company = $select_area = $select_building  =  $select_dep = $select_proj = $select_status = $select_ins_cat  = $select_ins_type = $select_risk_id = $select_fac_id = $select_startdate = $select_enddate = $select_main_status = '';
if (isset($getdashdata['company_id']) && $getdashdata['company_id'] != '') {
    $select_company = $getdashdata['company_id'];
}

if (isset($getdashdata['area_id']) && $getdashdata['area_id'] != '') {
    $select_area = $getdashdata['area_id'];
}

if (isset($getdashdata['building_id']) && $getdashdata['building_id'] != '') {
    $select_building = $getdashdata['building_id'];
}


if (isset($getdashdata['department_id']) && $getdashdata['department_id'] != '') {
    $select_dep = $getdashdata['department_id'];
}


if (isset($getdashdata['project_id']) && $getdashdata['project_id'] != '') {
    $select_proj = $getdashdata['project_id'];
}

if (isset($getdashdata['ins_cat_id']) && $getdashdata['ins_cat_id'] != '') {
    $select_ins_cat = $getdashdata['ins_cat_id'];
}


if (isset($getdashdata['start_date']) && $getdashdata['start_date'] != '') {
    $select_startdate = $getdashdata['start_date'];
}

if (isset($getdashdata['end_date']) && $getdashdata['end_date'] != '') {
    $select_enddate = $getdashdata['end_date'];
}
if (isset($getdashdata['Status_val']) &&  $getdashdata['Status_val'] != '') {
    $select_status = $getdashdata['Status_val'];
}
if (isset($getdashdata['Status_main']) &&  $getdashdata['Status_main'] != '') {
    $select_main_status = $getdashdata['Status_main'];
}


?>
<div class="wrapper">

    <div class="content-wrapper">

        <section class="content">
            <div class="" style="margin-top:10px;">
                <?php echo $this->session->flashdata('inspectionflash');
                $this->session->unset_userdata('inspectionflash');
                ?>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card ">

                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    <h3 class="card-title"><?php echo $pageTitle; ?></h3>
                                </div>
                                <div class="col-md-3">
                                    <div class="float-right">

                                        <button data-toggle="collapse" href="#search" name='Filter' role="button" aria-expanded="false" aria-controls="search" class="btn btn-sm btn-primary">
                                            <i class="fa fa-filter" data-bs-toggle="tooltip" title="Search"></i>
                                            Filter
                                        </button>
                                        <?php
                                        global $insPermission;

                                        $user_type = getCurrentUserGroupId();
                                        ?>
                                        <a href="<?php echo BASE_URL('inspection/workcamp/add_inspection') ?>" class="btn btn-sm btn-primary "><i class="fa fa-plus-circle"></i> Add Inspection</a>
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
                                    if (in_array($user_type, $insPermission['view_filter'])) {

                                    ?>

                                        <div class="col-md-3">

                                            <label>Company Name</label>
                                            <?php
                                            $cmpdata = [
                                                'class' => 'form-control select2',
                                                'id' => 'company',
                                                'checkSelect' => 'select2',
                                            ];
                                            echo form_dropdown('company_id', $dropcompany, $select_company, $cmpdata);
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
                                            echo form_dropdown('project_id', $dropproject, $select_proj, $cmpdata);
                                            ?>
                                            <span class="error"><?php echo form_error('project_id') ?></span>
                                        </div>

                                        <div class="col-md-3 m-t-10 d-none">
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
                                        <label>Category</label><br>
                                        <div>

                                            <?php

                                            $cNamedatas_pl = [
                                                'id' => 'cat_id',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('ins_cat_id', $drophsecat, $select_ins_cat, $cNamedatas_pl);
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3 m-t-10 d-none">
                                        <label>Observation Type</label><br>
                                        <div>

                                            <?php

                                            $cNamedatas_pl = [
                                                'id' => 'ins_type',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('ins_type', $ins_type_list, $select_ins_type, $cNamedatas_pl);
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3 m-t-10 d-none">
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
                                            echo form_dropdown('risk_id', $risk_rating, $select_risk_id, $cNamedatas_pl);
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
                                    <div class="col-md-3 m-t-10">
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

                                    <div class="col-md-3 m-t-10 d-none">
                                        <label>Main Status </label><br>
                                        <div>

                                            <?php

                                            $cNamedatas_pl = [
                                                'id' => 'mainStatus',
                                                'class' => 'form-control select2',
                                                'required' => true,
                                                'autocomplete' => 'off',
                                                'checkSelect' => 'select2'
                                            ];
                                            echo form_dropdown('mainStatus', $status_main, $select_main_status, $cNamedatas_pl);
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
                            <table id="employeeid" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th> Inspection ID </th>
                                        <th> Company </th>
                                        <th> Area </th>
                                        <th> Building/Block/Direction </th>
                                        <th> Department </th>
                                        <th> Project </th>
                                        <th> Category </th>
                                        <th> Reported Date & Time </th>
                                        <th> Status </th>
                                        <th> Action</th>
                                    </tr>
                                </thead>

                            </table>
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
            var url = '<?php echo BASE_URL("inspection/workcamp/delete_inspection"); ?>';
            var deletId = $(this).attr("delid");
            deleteDatas(url, deletId, csrfName, csrfToken)
        });

        var company = '<?php echo $select_company; ?>';
        var area_id = '<?php echo $select_area; ?>';
        var building_id = '<?php echo $select_building; ?>';
        var department_id = '<?php echo $select_dep; ?>';
        if (company != '') {
            getAreaDetails(company);
        }
        if (area_id != '') {
            getBuildingDetails(area_id);
            getDepartmentDetails(area_id);
        }

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
                    var area = '<?php echo $select_area; ?>';
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
                    var building = '<?php echo $select_building; ?>';
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
                    var department = '<?php echo $select_dep; ?>';
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
        const Month = nullcheck(urlParams.get('Month'));
        var company_id = nullcheck($('#company').val());
        var area_id = nullcheck($('#area_id').val());
        var building_id = nullcheck($('#building_id').val());
        var dept_id = nullcheck($('#dept_id').val());
        var project_id = nullcheck($('#project_id').val());
        var emp_name = nullcheck($('#emp_name').val());
        var cat_id = nullcheck($('#cat_id').val());
        var start_date = nullcheck($('#start_date').val());
        var end_date = nullcheck($('#end_date').val());
        var notifystatus = nullcheck($('#NotifyStatus').val());
        var mainStatus = nullcheck($('#mainStatus').val());
        var searchvalue = $('.dataTables_filter input').val();


        return 'cat_id=' + cat_id + '&emp_name=' + emp_name + '&company_id=' + company_id + '&area_id=' + area_id + '&building_id=' + building_id + '&department_id=' + dept_id + '&project_id=' + project_id + '&start_date=' + start_date + '&end_date=' + end_date + '&Month=' + Month + '&NotifyStatus=' + notifystatus + '&mainStatus=' + mainStatus + '&searchvalue=' + searchvalue
    }

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
                                    "<?php echo BASE_URL;  ?>inspection/workcamp/Workcamp_exportpdf?" + qetquerystring()

                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            action: function(e, dt, button, config) {

                                window.location.href =
                                    "<?php echo BASE_URL;  ?>inspection/workcamp/Workcamp_exportexcel?" + qetquerystring()

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
            $('#cat_id').val('').trigger('change');
            $('#obs_type').val('').trigger('change');
            $('#risk_id').val('').trigger('change');
            $('#NotifyStatus').val('').trigger('change');
            $('#mainStatus').val('').trigger('change');
            table.draw();
        });


    });
</script>