<?php
$chartWidth = 100;
?>
<style>
    .obs_pie_chart {
        width: 100%;
        height: 325px;
        /*margin-left:60px;*/
    }

    /* Custom CSS to position the toolbar in the top-right corner */
    .apexcharts-toolbar {
        position: absolute !important;
        top: 10px;
        right: 10px;
    }
    .exportSVG{
        display: none!important;
    }
    .exportCSV{
        display: none!important;
    }

</style>
<script type="text/javascript">
    // Assuming $listUsee has the required values
    var openCount = <?php echo !empty($listUsee) && isset($listUsee[0]->open_count) ? $listUsee[0]->open_count : 0; ?>;
var closedCount = <?php echo !empty($listUsee) && isset($listUsee[0]->closed_count) ? $listUsee[0]->closed_count : 0; ?>;
var totalCount = <?php echo !empty($listUsee) && isset($listUsee[0]->total_count) ? $listUsee[0]->total_count : 0; ?>;

    // Calculate the percentages and round to 2 decimal places
    var openPercentage = Math.round((openCount / totalCount) * 10000) / 100;
    var closedPercentage = Math.round((closedCount / totalCount) * 10000) / 100;

    var options = {
        series: [openPercentage, closedPercentage],
        chart: {
            width: 450,
            type: 'pie',
            // toolbar: {
            //     show: true, // Enable the toolbar
            //     tools: {
            //         download: true, // Enable the download button
            //     },
            //     export: {
            //         png: true,   // Enable PNG export
            //     }
            // }
        },
        labels: ['Open (' + openCount + ')', 'Closed (' + closedCount + ')'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 500
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        tooltip: {
            y: {
                formatter: function(val) {
                    // Round the value to 2 decimal places
                    return val.toFixed(0).replace('.', ',') + '%'; // 2 decimal places and comma separator
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#LoadOBS_STATUS"), options);
    chart.render();
</script>

<div id="LoadOBS_STATUS" class="obs_pie_chart"></div>
