<!-- Styles -->
<style>
  #obs_type {
    width: 100%;
    min-height: 325px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .apexcharts-legend {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
  }

  .apexcharts-legend-series {
    margin: 5px;
    padding: 5px;
    display: flex;
    align-items: center;
  }

  .apexcharts-legend-marker {
    width: 10px;
    height: 10px;
    margin-right: 5px;
  }
</style>

<!-- HTML -->
<div id="obs_type">
  <div class="card-header" style="display:none;">
    <h3 class="card-title">Inspection - Based on Status</h3>
  </div>
  <div id="chart-containerpie" style="height: 100%;"></div>
</div>
<!-- Chart code -->
<script>
  var insp_statusData = <?php echo json_encode($insp_status); ?>;
  var chartContainer = document.getElementById("chart-containerpie");
  var categories = <?php echo json_encode(array_column($insp_status, 'status')); ?>;
  var value = <?php echo json_encode(array_column($insp_status, 'value')); ?>;
  var insp_color_status_bar = <?php echo json_encode($insp_color_status_bar); ?>;
  if (Array.isArray(insp_statusData) && insp_statusData.length === 0) {
    chartContainer.innerHTML = "<div style='text-align: center; display: grid; align-items: center; height: 100%;'>No Data Found</div>";

  } else {
    var options = {
      legend: {
        position: 'bottom',
        show: true,
      },
      colors: insp_color_status_bar,
      series: value,
      chart: {
        type: 'donut',
        height: 350,
        toolbar: {
          show: false,
        },
        events: {
          dataPointSelection: function(event, chartContext, config) {

            var name = chartContext.w.globals.labels[config.dataPointIndex];
            //    var name = chartContext.w.globals.seriesNames[config.seriesIndex];
            var selectedMonth = '';
            var Status = obs_type = '';
            if (name == 'Unsafe Act') {
              Status = '<?php echo md5(1); ?>';
              obs_type = '1';
            } else if (name == 'Unsafe Condition') {
              Status = '<?php echo md5(2); ?>';
              obs_type = '2';
            } else if (name == 'Others(Positive/Good obs)') {
              Status = '<?php echo md5(3); ?>';
              obs_type = '3';
            }

            var start_date = '<?php echo $getdashdata['Start_Date']; ?>';
            var end_date = '<?php echo $getdashdata['End_Date']; ?>';
            var company_id = <?php echo isset($getdashdata['Company']) ? json_encode($getdashdata['Company']) : 'null'; ?>;
            var area_id = <?php echo isset($getdashdata['Area']) ? json_encode($getdashdata['Area']) : 'null'; ?>;
            var building_id = <?php echo isset($getdashdata['Building']) ? json_encode($getdashdata['Building']) : 'null'; ?>;
            var project_id = <?php echo isset($getdashdata['Project']) ? json_encode($getdashdata['Project']) : 'null'; ?>;
            var department_id = <?php echo isset($getdashdata['Department']) ? json_encode($getdashdata['Department']) : 'null'; ?>;


            var url = '<?php echo BASE_URL . 'atarusee/incident/incidentInfo'; ?>';
            url += '?Month=' + encodeURIComponent(selectedMonth) +
              '&obs_type=' + obs_type +
              '&start_date=' + encodeURIComponent(start_date) +
              '&end_date=' + encodeURIComponent(end_date) +
              '&company_id=' + encodeURIComponent(company_id) +
              '&area_id=' + encodeURIComponent(area_id) +
              '&building_id=' + encodeURIComponent(building_id) +
              '&department_id=' + encodeURIComponent(department_id) +
              '&project_id=' + encodeURIComponent(project_id);
            window.location.href = url;
          }
        }
      },
      labels: categories,
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
    };
    var insp_statuschart = new ApexCharts($("#obs_type")[0], options);
    insp_statuschart.render();
  }
  $(document).ready(function() {
    $("#obsreportdonut").off("click").on("click", function() {
      if (Array.isArray(insp_statusData) && insp_statusData.length === 0) {
        swal("No Data Found", "", "info");

      } else {
        insp_statuschart.dataURI().then(({
          imgURI,
          svgURI
        }) => {
          // Create a new image with the custom header
          var newCanvas = document.createElement('canvas');
          var ctx = newCanvas.getContext('2d');
          var image = new Image();

          image.onload = function() {
            newCanvas.width = image.width;
            newCanvas.height = image.height + 350; // Extra space for header

            // Add the custom header
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, newCanvas.width, 350);
            ctx.fillStyle = '#203669';
            ctx.font = '20px Arial';
            var headerText = 'Observation Report - Based on Types';
            ctx.fillText(headerText, 10, 30);
            ctx.fillStyle = 'black';
            var company_id = <?php echo isset($getdashdata['Company']) ? json_encode($getdashdata['Company']) : 'null'; ?>;
            var area_id = <?php echo isset($getdashdata['Area']) ? json_encode($getdashdata['Area']) : 'null'; ?>;
            var building_id = <?php echo isset($getdashdata['Building']) ? json_encode($getdashdata['Building']) : 'null'; ?>;
            var department_id = <?php echo isset($getdashdata['Department']) ? json_encode($getdashdata['Department']) : 'null'; ?>;
            var project_id = <?php echo isset($getdashdata['Project']) ? json_encode($getdashdata['Project']) : 'null'; ?>;

            var CompanyName = <?php echo isset($getdashdata['CompanyName']) ? json_encode($getdashdata['CompanyName']) : 'null'; ?>;
            var AreaName = <?php echo isset($getdashdata['AreaName']) ? json_encode($getdashdata['AreaName']) : 'null'; ?>;
            var BuildingName = <?php echo isset($getdashdata['BuildingName']) ? json_encode($getdashdata['BuildingName']) : 'null'; ?>;
            var DepartmentName = <?php echo isset($getdashdata['DepartmentName']) ? json_encode($getdashdata['DepartmentName']) : 'null'; ?>;
            var ProjectName = <?php echo isset($getdashdata['ProjectName']) ? json_encode($getdashdata['ProjectName']) : 'null'; ?>;

            var startDate = '<?php echo $getdashdata['Start_Date']; ?>';
            var End_Date = '<?php echo $getdashdata['End_Date']; ?>';
            var yPos = 60;
            if (company_id || area_id || building_id || department_id || project_id || startDate || end_date) {
              var subHeaderText = 'Filtered By:';
              ctx.fillText(subHeaderText, 10, yPos);

              if (company_id) {
                var subHeaderTextData = 'Company: ' + CompanyName;
                yPos += 30;
                ctx.fillText(subHeaderTextData, 10, yPos);
                yPos += 5;
              }
              if (area_id) {
                var subHeaderTextData = 'Area: ' + AreaName;
                yPos += 30;
                ctx.fillText(subHeaderTextData, 10, yPos);
                yPos += 5;
              }
              if (building_id) {
                var subHeaderTextData = 'Building: ' + BuildingName;
                yPos += 30;
                ctx.fillText(subHeaderTextData, 10, yPos);
                yPos += 5;
              }
              if (department_id) {
                var subHeaderTextData = 'Department: ' + DepartmentName;
                yPos += 30;
                ctx.fillText(subHeaderTextData, 10, yPos);
                yPos += 5;
              }
              if (project_id) {
                var subHeaderTextData = 'Project: ' + ProjectName;
                yPos += 30;
                ctx.fillText(subHeaderTextData, 10, yPos);
                yPos += 5;
              }

              if (startDate) {
                var subHeaderTextData = 'Start Date: ' + startDate;
                yPos += 30;
                ctx.fillText(subHeaderTextData, 10, yPos);
                yPos += 5;
              }
              if (End_Date) {
                yPos += 30;
                var subHeaderTextData = 'End Date: ' + End_Date;
                ctx.fillText(subHeaderTextData, 10, yPos);
                yPos += 5;
              }
            }

            // Draw the chart image onto the new canvas
            ctx.drawImage(image, 0, yPos);

            // Export the new canvas as an image
            newCanvas.toBlob(function(blob) {
              var link = document.createElement('a');
              link.href = URL.createObjectURL(blob);
              link.download = 'Observation_type_report.png'; // Change the file name here
              link.click();
            });
          };
          image.src = imgURI;
        });
      }
    });

  });
</script>