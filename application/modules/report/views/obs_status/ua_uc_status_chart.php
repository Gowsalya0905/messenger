<?php
$chartWidth = 100;
?>

<style>
    .ua_pie_chart {
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
    // Get the counts from PHP
var uaCount = <?php echo json_encode($listUsee[0]->ua_count ?? "0"); ?>;
var ucCount = <?php echo json_encode($listUsee[0]->uc_count ?? "0"); ?>;


    
    // Calculate total count and percentages
    var totalCount = uaCount + ucCount;
    var uaPercentage = (totalCount > 0) ? (uaCount / totalCount) * 100 : 0;
    var ucPercentage = (totalCount > 0) ? (ucCount / totalCount) * 100 : 0;

    // ApexChart options
    var options = {
        series: [uaPercentage, ucPercentage],  // Percentages
        chart: {
            width: 400,
            type: 'pie',
            // toolbar: {
            //     show: true, // Enable the toolbar
            //     tools: {
            //         download: true, // Enable the download button
            //     },
            //     export: {
            //         png: true,  // Enable PNG export
            //     }
            // }
        },
        labels: [
            'UA ' + uaCount, // Display "UA" followed by the count
            'UC ' + ucCount  // Display "UC" followed by the count
        ],
        colors: ['#da4a4a', '#ADD8E6'],  // Brown and Light Blue colors
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
        }],
        tooltip: {
            y: {
                formatter: function(val, opt) {
                    // Get the count and percentage for each slice
                    return val.toFixed(0) + '%';
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#uauc_STATUS"), options);
    chart.render();
</script>

<div id="uauc_STATUS" class="ua_pie_chart"></div>
