<?php
$chartWidth = 100;
?>
<style>
    .hse_chart {
        width: 100%;
        height: 325px;
    }
</style>

<script type="text/javascript">
    var options = {
        series: [{
            name: '<?php echo $data['hse_cat']; ?>',
            data: [
                <?php echo implode(',', array_map(fn($m) => $m['total'], $data['month_wise'])); ?>
            ]
        }],
        chart: {
            height: 350,
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
            categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },

    };

    // Render the chart in the specific container
    var chart = new ApexCharts(
        document.querySelector("#LoadHSECATCHART_<?php echo $data['hse_id']; ?>"),
        options
    );
    chart.render();
</script>
<div id="LoadHSECATCHART_<?php echo $data['hse_id']; ?>" class="hse_chart"></div>