<?php
$chartWidth = 100;
?>
<style>
    .hse_chart {
        width: 100%;
        height: 425px;
    }
</style>

<script type="text/javascript">
    // Prepare data from PHP
    var categories = <?php echo json_encode(array_map(function ($item) {
                            return $item->hse_cat;
                        }, $data)); ?>;
    var data = <?php echo json_encode(array_map(function ($item) {
                    return $item->total;
                }, $data)); ?>;

    // Chart options
    var options = {
        series: [{
            name: 'TOTAL',
            data: data
        }],
        chart: {
            height: 450,
            type: 'bar',
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                columnWidth: '45%',
                distributed: true,
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '13px',
                fontWeight: 900,
                colors: ['#000']
            },
        },
        legend: {
            show: true
        },
        xaxis: {
            categories: categories,
            labels: {
                style: {
                    fontSize: '9px'
                }
            }
        },
    };

    // Render the chart in the specific container
    var chart = new ApexCharts(document.querySelector("#LoadTOTHSECATCHART"), options);
    chart.render();
</script>

<div id="LoadTOTHSECATCHART" class="hse_chart"></div>