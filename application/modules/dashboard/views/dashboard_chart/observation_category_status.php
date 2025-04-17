<style>
  #obs_impact_bar {
    width: 100%;
    height: 350px;
  }

  #obs_impact_bar .highcharts-color-0 {
    fill: #ffc107 !important;
    stroke: #ffc107 !important;
  }

  #obs_impact_bar .highcharts-color-1 {
    fill: #3d9970 !important;
    stroke: #3d9970 !important;
  }

  .highcharts-visually-hidden {
    display: none;
  }

  .apexcharts-title-text {
    font-weight: bold;
    color: #203669;
    text-transform: uppercase;
  }
</style>

<div id="obs_impact_bar"></div>

<script>
  // Define the color code and data from PHP
  var obs_status_barData = <?php echo json_encode($obs_status_bar); ?>;
  var monthDetails = <?php echo json_encode($monthDetails); ?>;
  var locations = <?php echo json_encode($obsactdet); ?>;
  var reversedimpactdet = {};
  for (var key in locations) {
    reversedimpactdet[locations[key]] = key;
  }
  var seriesData = obs_status_barData.map(function(item) {
    return {
      name: item.name,
      data: item.data
    };
  });
  var options = {
    legend: {
      position: 'bottom',
      show: true,
    },
    series: seriesData,
    chart: {
      type: 'bar',
      height: 350,
      stacked: true,
      toolbar: {
        show: false
      },
      zoom: {
        enabled: true
      },
      events: {
        dataPointSelection: function(event, chartContext, config) {
          var selectedMonth = chartContext.w.globals.labels[config.dataPointIndex];
          var impact = chartContext.w.globals.seriesNames[config.seriesIndex];
          console.log(reversedimpactdet);

          var Status = Status_val = '';
          var impactKey = reversedimpactdet[impact];

          var start_date = '<?php echo $getdashdata['Start_Date']; ?>';
          var end_date = '<?php echo $getdashdata['End_Date']; ?>';
          var company_id = <?php echo isset($getdashdata['Company']) ? json_encode($getdashdata['Company']) : 'null'; ?>;
          var area_id = <?php echo isset($getdashdata['Area']) ? json_encode($getdashdata['Area']) : 'null'; ?>;
          var building_id = <?php echo isset($getdashdata['Building']) ? json_encode($getdashdata['Building']) : 'null'; ?>;
          var project_id = <?php echo isset($getdashdata['Project']) ? json_encode($getdashdata['Project']) : 'null'; ?>;
          var department_id = <?php echo isset($getdashdata['Department']) ? json_encode($getdashdata['Department']) : 'null'; ?>;



          var url = '<?php echo BASE_URL . 'atarusee/incident/incidentInfo'; ?>';
          url += '?Month=' + encodeURIComponent(selectedMonth) +
            '&hse_cat=' + encodeURIComponent(impactKey) +
            '&start_date=' + encodeURIComponent(start_date) +
            '&end_date=' + encodeURIComponent(end_date) +
            '&company_id=' + encodeURIComponent(company_id) +
            '&area_id=' + encodeURIComponent(area_id) +
            '&building_id=' + encodeURIComponent(building_id) +
            '&department_id=' + encodeURIComponent(department_id) +
            '&project_id=' + encodeURIComponent(project_id);
          window.location.href = url;
        }
      },
    },
    responsive: [{
      breakpoint: 480,
    }],
    plotOptions: {
      bar: {
        horizontal: false,
        borderRadius: 10,
        borderRadiusApplication: 'end', // 'around', 'end'
        borderRadiusWhenStacked: 'last', // 'all', 'last'
        dataLabels: {
          total: {
            enabled: true,
            style: {
              fontSize: '13px',
              fontWeight: 900
            }
          }
        }
      },
    },
    yaxis: {
      title: {
        text: 'Observation Counts',
      },
    },
    xaxis: {
      categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    },
    fill: {
      opacity: 1
    }
  };

  var obs_impact_barchart = new ApexCharts($("#obs_impact_bar")[0], options);
  obs_impact_barchart.render();
  $(document).ready(function() {
    $("#obscategoryreport").off("click").on("click", function() {
      obs_impact_barchart.dataURI().then(({
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
          var headerText = 'OBSERVATION CATEGORY REPORT - BASED ON MONTHS';
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
            link.download = 'Observation_category_report.png'; // Change the file name here
            link.click();
          });
        };
        image.src = imgURI;
      });
    });
  });
</script>