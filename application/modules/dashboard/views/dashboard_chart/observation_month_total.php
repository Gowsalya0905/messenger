<style>
  #obs_single_bar {
    width: 100%;
    height: 353px;
  }

  #obs_single_bar .highcharts-color-0 {
    fill: #ffc107 !important;
    stroke: #ffc107 !important;
  }

  #obs_single_bar .highcharts-color-1 {
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
<div id="obs_single_bar"></div>
<script>
  var options = {
    legend: {
      position: 'bottom',
      show: true,
    },
    colors: ['<?php echo $dashboard_blue; ?>'],
    series: [{
      name: 'Total Observation',
      data: [
        <?php
        echo isset($obs_single_bar['total'])
          ? implode(", ", $obs_single_bar['total'])
          : '0';
        ?>
      ]
    }, ],
    chart: {
      type: 'bar',
      height: 315,
      stacked: true,
      toolbar: {
        show: false,
      },
      zoom: {
        enabled: true
      },
      events: {
        dataPointSelection: function(event, chartContext, config) {
          var selectedMonth = chartContext.w.globals.labels[config.dataPointIndex];

          var start_date = '<?php echo $getdashdata['Start_Date']; ?>';
          var end_date = '<?php echo $getdashdata['End_Date']; ?>';
          var company_id = <?php echo isset($getdashdata['Company']) ? json_encode($getdashdata['Company']) : 'null'; ?>;
          var area_id = <?php echo isset($getdashdata['Area']) ? json_encode($getdashdata['Area']) : 'null'; ?>;
          var building_id = <?php echo isset($getdashdata['Building']) ? json_encode($getdashdata['Building']) : 'null'; ?>;
          var project_id = <?php echo isset($getdashdata['Project']) ? json_encode($getdashdata['Project']) : 'null'; ?>;
          var department_id = <?php echo isset($getdashdata['Department']) ? json_encode($getdashdata['Department']) : 'null'; ?>;
          
          var url = '<?php echo BASE_URL . 'atarusee/incident/incidentInfo'; ?>';
          url += '?Month=' + encodeURIComponent(selectedMonth) + '&start_date=' + encodeURIComponent(start_date) + '&end_date=' + encodeURIComponent(end_date) +
            '&company_id=' + encodeURIComponent(company_id) +
            '&area_id=' + encodeURIComponent(area_id) +
            '&building_id=' + encodeURIComponent(building_id) +
            '&department_id=' + encodeURIComponent(department_id) +
            '&project_id=' + encodeURIComponent(project_id);
          window.location.href = url;
        }
      }
    },
    responsive: [{
      breakpoint: 480,
      options: {
        legend: {
          position: 'bottom',
          offsetX: -10,
          offsetY: 0
        }
      }
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
          },
          style: {
            colors: ['#fff'],
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
      type: 'month',
      categories: [
        <?php
        echo isset($obs_single_bar['label'])
          ? "'" . implode("', '", $obs_single_bar['label']) . "'"
          : '';
        ?>
      ],
    },
    fill: {
      opacity: 1
    }
  };

  var obs_single_barchart = new ApexCharts($("#obs_single_bar")[0], options);
  obs_single_barchart.render();
  $(document).ready(function() {
    $("#obsreportmonth").off("click").on("click", function() {
      obs_single_barchart.dataURI().then(({
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
          var headerText = 'OBSERVATION REPORT - BASED ON MONTHS';
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
          var end_date = '<?php echo $getdashdata['End_Date']; ?>';
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
            if (end_date) {
              yPos += 30;
              var subHeaderTextData = 'End Date: ' + end_date;
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
            link.download = 'Observation_report_months.png'; // Change the file name here
            link.click();
          });
        };
        image.src = imgURI;
      });
    });
  });
</script>