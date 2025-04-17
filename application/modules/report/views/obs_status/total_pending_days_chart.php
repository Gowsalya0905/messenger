<?php
$chartWidth = 100;
?>
<style>
    .total_pending_days_chart {
        width: 100%;
        height: 325px;
    }
</style>
<?php
if (!empty($listUsee) && isset($listUsee[0])) {
    $dataCounts = [
        '0 - 3 days' => $listUsee[0]->open_3_days_count ?? 0,
        '4 to 7 days' => $listUsee[0]->open_4to7_days_count ?? 0,
        '7 to 14 days' => $listUsee[0]->open_7to14_days_count ?? 0,
        '14 to 30 days' => $listUsee[0]->open_14to30_days_count ?? 0,
        '> 30 days' => $listUsee[0]->open_more_than_30_days_count ?? 0,
    ];
} else {
    $dataCounts = [
        '0 - 3 days' => 0,
        '4 to 7 days' => 0,
        '7 to 14 days' => 0,
        '14 to 30 days' => 0,
        '> 30 days' => 0,
    ];
}
// Pass data to JavaScript
$categories = json_encode(array_keys($dataCounts));
$dataValues = json_encode(array_values($dataCounts));
?>

<script type="text/javascript">
    // Get dynamic data from PHP
    var categories = <?php echo $categories; ?>; // ['0 - 3 days', '4 to 7 days', '7 to 14 days', '14 to 30 days', '> 30 days']
    var dataValues = <?php echo $dataValues; ?>; // [value1, value2, value3, value4, value5]

    var options = {
        series: [{
            data: dataValues
        }],
        chart: {
            height: 350,
            type: 'bar',
                toolbar: {
                show: false, // Hide the toolbar (and the download button)
            },
            events: {
                click: function(chart, w, e) {
                    // Click event logic if needed
                }
            }
        },
        colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0'], // Example colors
        plotOptions: {
            bar: {
                columnWidth: '45%',
                distributed: true,
                dataLabels: {
                    position: 'top' // Display data labels on top of the bars
                }
            }
        },
        dataLabels: {
            enabled: true, // Enable data labels
            formatter: function(val) {
                return val; // Customize this if needed (e.g., add a suffix like '%' or 'days')
            },
            style: {
                fontSize: '12px',
                colors: ['#000'] // Color of the data labels
            }
        },
        legend: {
            show: false
        },
        xaxis: {
            categories: categories, // Dynamic categories
            labels: {
                style: {
                    colors: ['#161819', '#161819', '#161819', '#161819', '#161819'], // Example label colors
                    fontSize: '12px'
                }
            }
        },
        tooltip: {
            enabled: false // Disable tooltips if you don't want hover effects
        }
    };

    var chart = new ApexCharts(document.querySelector("#total_pending_days"), options);
    chart.render();
</script>





<div id="total_pending_days" class="total_pending_days_chart"></div>