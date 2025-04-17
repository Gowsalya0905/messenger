<style>
  .countclass {
    /* font-size: 30px; */
  }

  .curson_pointer {
    cursor: pointer;
  }

  .maindash-widgets-icons {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ededed;
    font-size: 26px;
    border-radius: 10px;
    font-size: 40px;
  }

  .dashboardmetricicon {
    font-size: 30px;
  }

  .dashboard_metric_name {
    font-weight: bold;
    color: #203669;
    text-transform: uppercase;
  }

  .maindash-widgets-icons {
    background-image: linear-gradient(to bottom right, red, yellow);
    color: white;
  }

  .card-dashbaord .card-body {
    flex: 1 1 auto;
    padding: 0.5rem 0.5rem;
  }

  .chartcard .card-body {
    padding: 0 !important;
  }

  .card-dashbaord {
    box-shadow: 0 2px 6px 0 #6c757d, 0 2px 6px 0 #6c757d !important;
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

  .responsive-iframe {
    width: 100%;
    height: 500px;
    /* Adjust the initial height as needed */
  }

  @media (max-width: 768px) {
    .responsive-iframe {
      height: 100px;
      /* Adjust height for smaller screens */
    }
  }
</style>
<style>
  #calendar {
    max-width: 100%;
    margin: 0 auto;
    width: 100%;
    height: 500px;
    /* min-height:0px; */
  }

  button.fc-today-button.fc-button.fc-button-primary,
  button.fc-dayGridMonth-button.fc-button.fc-button-primary.fc-button-active,
  button.fc-timeGridWeek-button.fc-button.fc-button-primary,
  button.fc-timeGridDay-button.fc-button.fc-button-primary,
  button.fc-listWeek-button.fc-button.fc-button-primary,
  button.fc-dayGridMonth-button.fc-button.fc-button-primary {
    text-transform: capitalize !important;
  }

  @media only screen and (max-width: 600px) {
    #calendar {
      max-width: 100%;
      margin: 0 auto;
      width: 100%;
      height: auto !important;
      max-height: 100% !important;
      min-height: 100% !important;
    }

    .fc .fc-button-group {
      display: inline-grid !important;
    }

  }
</style>
<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>fullcalendar/lib/main.min.css">
<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>fullcalendar-daygrid/main.min.css">
<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>fullcalendar-timegrid/main.min.css">
<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>fullcalendar-bootstrap/main.min.css">
<script src="<?php echo LAYOUT_PLUG_PATH; ?>fullcalendar/lib/main.min.js"></script>

<?php
global $dashPermission;
$user_type = $_SESSION["emp_details"]->EMP_USERTYPE_ID;
?>

<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">


      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">

              <h3 class="card-title">Dashboard Metrics
              </h3>
              <span>
                <button data-toggle="collapse" style="float: inline-end;" href="#search" name="Filter" role="button" id="show_filter" aria-expanded="true" aria-controls="search" class="btn btn-sm btn-primary" fdprocessedid="cqh7b">
                  <i class="fa fa-filter" data-bs-toggle="tooltip" title="Search"></i>
                  Filter
                </button>
              </span>
            </div>
            <div class="card-body">


              <!-- Filter -->
              <div id="filter_form" style="display:none;">
                <form action="#" name="filterForm" id="filter_form" class="row g-3" autocomplete="off" method="get" accept-charset="utf-8" novalidate="novalidate">

                  <?php if (in_array($user_type, $dashPermission["view_filter"])) { ?>

                    <div class="col-lg-3">
                      <label>Company </label>
                      <?php
                      $options = $getCompany;
                      $extra =
                        'class="form-control select2" id="company" checkSelect = "select2"';

                      echo form_dropdown("company", $options, "", $extra);
                      ?>
                    </div>
                    <div class="col-lg-3 ">
                      <label>Area </label>
                      <select class="form-control area select2 " checkSelect="select2" name="area" id="area">
                        <option value="">Select Area</option>
                      </select>
                    </div>
                    <div class="col-lg-3 ">
                      <label>Building/Block/Direction </label>
                      <select class="form-control area select2 " checkSelect="select2" name="building" id="building">
                        <option value="">Select Building/Block/Direction</option>
                      </select>
                    </div>
                    <div class="col-lg-3 ">
                      <label>Department </label>
                      <select class="form-control area select2 " checkSelect="select2" name="department" id="department">
                        <option value="">Select Department</option>
                      </select>
                    </div>
                    <div class="col-lg-3 m-t-10">
                      <label>Project </label>
                      <?php
                      $optionsp = $dropproject;
                      $extrap = 'class="form-control select2" id="project" checkSelect = "select2"';
                      echo form_dropdown("project", $optionsp, "", $extrap);
                      ?>
                    </div>


                  <?php } ?>
                  <div class="col-lg-3 m-t-10">
                    <label>Start Date </label><br>
                    <div>
                      <?php
                      $data = [
                        "name" => "start_date",
                        "id" => "start_date",
                        "placeholder" => "",
                        "class" =>
                        "form-control datepicker",
                        "autocomplete" => "off",
                        "value" => "",
                      ];

                      echo form_input($data);
                      ?>


                    </div>
                  </div>
                  <div class="col-lg-3 m-t-10">
                    <label>End Date </label><br>
                    <div>
                      <?php
                      $data = [
                        "name" => "end_date",
                        "id" => "end_date",
                        "placeholder" => "",
                        "class" => "form-control datepicker",
                        "autocomplete" => "off",
                        "value" => "",
                      ];

                      echo form_input($data);
                      ?>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <label></label><br>
                    <button class="btn btn-success" onclick="filterDashboard();" id="searchform" type="button" name="search">Search</button>
                    <a href='<?php echo BASE_URL .
                                "dashboard/chart"; ?>' class="btn btn-danger saveBtn" data-bs-toggle="tooltip" title="Clear">Clear</a>
                  </div>
                  <hr>
              </div>



              </form>


            </div>

            <!--filter-->

            <div class="row row-cols-1 row-cols-lg-3">
              <div class="col curson_pointer" onclick="redirectToObslist()">
                <div class="card card-dashbaord radius-10">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="flex-grow-1">
                        <p class="dashboard_metric_name mb-0" style="font-weight: bold;">Observation Management</p>
                        <h4 class="font-weight-bold countclass " id="obs_count">0</h4>
                        <p class="text-success mb-0 font-13">Closure Percentage <span class="text-success font-13" id="obs_closure">0.00</span>%</p>
                      </div>
                      <div class="maindash-widgets-icons text-white" style="color:white !important;background-color:#FFEDBF;"><i class='dashboardmetricicon fa fa-search'></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>



      <div class="row">
        <div class="col-lg-8">
          <div class="card chartcard">
            <div class="card-header chart_heading">
              Observation Report - Based on Months<a class="fas fa-arrow-alt-circle-down chartdownload" id="obsreportmonth"></a>
            </div>
            <div class="card-body">
              <div id="LoadObservation"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card chartcard">
            <div class="card-header chart_heading">
              Observation Report - Based on Status<a class="fas fa-arrow-alt-circle-down chartdownload" id="obsstatusreport"></a>
            </div>
            <div class="card-body">
              <div id="LoadObservationStatusPie"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card chartcard">
            <div class="card-header chart_heading">
              Observation Category Report - Based on months<a class="fas fa-arrow-alt-circle-down chartdownload" id="obscategoryreport"></a>
            </div>
            <div class="card-body">
              <div id="LoadObservationStatusBar"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <div class="card chartcard">
            <div class="card-header chart_heading">
              Observation Report - Based on Risk Rating<a class="fas fa-arrow-alt-circle-down chartdownload" id="obsriskreport"></a>
            </div>
            <div class="card-body">
              <div id="LoadObservationRiskPie"></div>
            </div>
          </div>

        </div>
        <div class="col-lg-6">
          <div class="card chartcard">
            <div class="card-header chart_heading">
              Observation Report - Based on Type<a class="fas fa-arrow-alt-circle-down chartdownload" id="obsreportdonut"></a>
            </div>
            <div class="card-body">
              <div id="LoadObservationStatusDonut"></div>
            </div>
          </div>
        </div>

      </div>



    </div>

  </section>

</div>

<script>
  function redirectToObslist(Status) {
    <?php if (in_array($user_type, $dashPermission["view_filter"])) { ?>
      Company = $("#company").val();
      Area = $("#area").val();
      Building = $("#building").val();
      Department = $("#department").val();
      Project = $("#project").val();
    <?php } else { ?>
      Company = '';
      Area = '';
      Building = '';
      Department = '';
      Project = '';
    <?php } ?>
    Start_Date = $("#start_date").val();
    End_Date = $("#end_date").val();
    var url = '<?php echo BASE_URL . "atarusee/incident/incidentInfo"; ?>';
    url += '?company_id=' + encodeURIComponent(Company) + '&area_id=' + encodeURIComponent(Area) + '&start_date=' + encodeURIComponent(Start_Date) + '&end_date=' + encodeURIComponent(End_Date) + '&building_id=' + encodeURIComponent(Building) + '&project_id=' + encodeURIComponent(Project) + '&Department=' + encodeURIComponent(Department);
    window.location.href = url;
  }





  function filterDashboard() {
    <?php if (in_array($user_type, $dashPermission["view_filter"])) { ?>
      Company = $("#company").val();
      Area = $("#area").val();
      Building = $("#building").val();
      Department = $("#department").val();
      Project = $("#project").val();
    <?php } else { ?>
      Company = '';
      Area = '';
      Building = '';
      Department = '';
      Project = '';
    <?php } ?>
    Start_Date = $("#start_date").val();
    End_Date = $("#end_date").val();

    getalldashboardmetrics(Company, Area, Building, Department, Project, Start_Date, End_Date);

    //Observation
    loadobservation(Company, Area, Building, Department, Project, Start_Date, End_Date);
    loadobservationstatusBar(Company, Area, Building, Department, Project, Start_Date, End_Date);
    loadobservationstatusPie(Company, Area, Building, Department, Project, Start_Date, End_Date);
    loadObservationRiskPie(Company, Area, Building, Department, Project, Start_Date, End_Date);
    loadObservationStatusDonut(Company, Area, Building, Department, Project, Start_Date, End_Date);


  }

  function getalldashboardmetrics(Company = '', Area = '', Building = '', Department = '', Project = '', Start_Date = '', End_Date = '') {
    var url = "<?php echo BASE_URL() . "dashboard/chart/getalldashboardmetrics"; ?>";
    var data = {
      Company: Company,
      Area: Area,
      Building: Building,
      Department: Department,
      Project: Project,
      Start_Date: Start_Date,
      End_Date: End_Date,
    };

    $('#obs_count').html('');
    $('#obs_closure').html('');

    $.ajax({
      type: 'get',
      url: url,
      data: data,
      cache: false,
      success: function(dataAjx) {
        var Data = JSON.parse(dataAjx);
        $('#obs_count').html(Data.observation.obs_count);
        $('#obs_closure').html(Data.observation.closure_percentage);

      }
    });
  }


  //Observation
  function loadobservation() {
    var url = "<?php echo BASE_URL() . "dashboard/chart/observationtotalmonthwise"; ?>";
    var data = {
      Company: Company,
      Area: Area,
      Building: Building,
      Department: Department,
      Project: Project,
      Start_Date: Start_Date,
      End_Date: End_Date,
    };
    $('#LoadObservation').html('');
    $.ajax({
      type: 'get',
      url: url,
      data: data,
      cache: false,
      success: function(dataAjx) {

        $('#LoadObservation').html(dataAjx);
      }
    });
  }

  function loadobservationstatusBar() {
    var url = "<?php echo BASE_URL() . "dashboard/chart/observationstatusCategorywise"; ?>";
    var data = {
      Company: Company,
      Area: Area,
      Building: Building,
      Department: Department,
      Project: Project,
      Start_Date: Start_Date,
      End_Date: End_Date,
    };
    $('#LoadObservationStatusBar').html('');
    $.ajax({
      type: 'get',
      url: url,
      data: data,
      cache: false,
      success: function(dataAjx) {
        $('#LoadObservationStatusBar').html(dataAjx);
      }
    });
  }


  function loadobservationstatusPie() {
    var url = "<?php echo BASE_URL() . "dashboard/chart/observationstatustotal"; ?>";
    var data = {
      Company: Company,
      Area: Area,
      Building: Building,
      Department: Department,
      Project: Project,
      Start_Date: Start_Date,
      End_Date: End_Date,
    };
    $('#LoadObservationStatusPie').html('');
    $.ajax({
      type: 'get',
      url: url,
      data: data,
      cache: false,
      success: function(dataAjx) {

        $('#LoadObservationStatusPie').html(dataAjx);
      }
    });
  }

  function loadObservationRiskPie() {
    var url = "<?php echo BASE_URL() . "dashboard/chart/observationrisktotal"; ?>";
    var data = {
      Company: Company,
      Area: Area,
      Building: Building,
      Department: Department,
      Project: Project,
      Start_Date: Start_Date,
      End_Date: End_Date,
    };
    $('#LoadObservationRiskPie').html('');
    $.ajax({
      type: 'get',
      url: url,
      data: data,
      cache: false,
      success: function(dataAjx) {

        $('#LoadObservationRiskPie').html(dataAjx);
      }
    });
  }

  function loadObservationStatusDonut() {

    var url = "<?php echo BASE_URL() . "dashboard/chart/observationtypetotal" ?>";
    var data = {
      Company: Company,
      Area: Area,
      Building: Building,
      Department: Department,
      Project: Project,
      Start_Date: Start_Date,
      End_Date: End_Date,
    };
    $('#LoadObservationStatusDonut').html('');
    $.ajax({
      type: 'get',
      url: url,
      data: data,
      cache: false,
      success: function(dataAjx) {

        $('#LoadObservationStatusDonut').html(dataAjx);
      }
    });
  }




  $(document).ready(function() {

    $('#resetform').on('click', function(e) {
      e.preventDefault(); // Prevent the default form reset behavior
      $('#filter_form')[0].reset();
      $('.select2').val(null).trigger('change');
    });
    $('#show_filter').on('click', function() {
      $('#filter_form').toggle();
    });


    $(document).on('change', '#company', function() {
      var company = $(this).val();
      getAreaDetails(company);
    });

    $(document).on('change', '#area', function() {
      var area_id = $(this).val();
      getBuildingDetails(area_id);
      getDepartmentDetails(area_id);
    });

    function getAreaDetails(company) {
      var url = "<?php echo BASE_URL() . "Main/AreaDetails"; ?>";
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
          $('#area').html(data);
          // $('#area').val(area);  
        }
      });
    }

    function getBuildingDetails(area) {
      var url = "<?php echo BASE_URL() . "Main/BuildingDetails"; ?>";
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
          $('#building').html(data);
          // $('#building').val(area);  
        }
      });
    }

    function getDepartmentDetails(area) {
      var url = "<?php echo BASE_URL() . "Main/DepartmentDetails"; ?>";
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
          $('#department').html(data);
          // $('#department').val(Department);  
        }
      });
    }


    filterDashboard();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      beforeSend: function() {
        // Show the loader before sending the request
        $('#loader').show();
      },
      complete: function() {
        // Hide the loader after the request completes
        $('#loader').hide();
      }
    });

  });
</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>bootstrap-datepicker/datepicker.min.css">