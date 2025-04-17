<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS</title>
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 12px;
            color:black;
        }

        th,
        td {
            border: 1px solid black;
            text-align: center;
            padding: 5px;
        }

        th {
            background-color: #003049;
            /* Dark blue for headers */
            /* color: white; */
        }

        .category-header {
            background-color: #fcbf49;
            /* Orange background for category section */
            font-weight: bold;
        }

        .bold {
            font-weight: bold;
        }

        .monthly-totals {
            background-color: #0077b6;
            /* Blue for monthly totals */
            color: white;
        }

        .total-observations {
            font-weight: bold;
        }

        /* Wrapper for scrollable table */
        .table-wrapper {
            overflow-x: auto;
        }

        .pinkrow {
            background-color: #E6B8B7;
        }

        .liteorange {
            background-color: #FABF8F;
        }

        .total_blue {
            background-color: #8DB4E2
        }

        .red {
            background-color: #C00000;
            color: white;
        }
        th.no-border {
    border: none;
    background-color: transparent;
}
        .row-label{
            background-color: #d4d6d9!important;
        }
        .header-total{
            background-color: #d3e3fd! !important;
        }
        .header-open{
            background-color: #E6B8B7;
        }
        .header-closed{
            background-color:rgb(37, 203, 109); 
        }
    </style>
</head>

<body>
    <div class="table-wrapper">
     <h4 class="bold">Observation Overall Count</h4>
    <table  class="mt-3">
    <thead>
        <tr>
         <th class="no-border"></th>
            <th class="header-open">Open</th>    
            <th class="header-closed">Closed</th>
            <th class="row-label">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="row-label bold">Number</td>
            <td><?= $overallData['open_count']; ?></td>         
            <td><?= $overallData['closed_count']; ?></td>
            <td class="bold row-label"><?= $overallData['total_count']; ?></td>
        </tr>
        <tr>
            <td class="row-label bold">Percentage</td>
            <td><?= $overallData['open_percentage'] . '%'; ?></td>       
            <td><?= $overallData['closed_percentage'] . '%'; ?></td>
            <td class="bold row-label"><?= $overallData['total_percentage']; ?></td>
        </tr>
    </tbody>
</table>
<br>
<br>
<h4 class="bold">Observation UA/UC Count</h4>
<table  class="mt-3">
    <thead>
        <tr>
         <th class="no-border"></th>
            <th class="header-open bold">UA</th>    
            <th class="header-closed bold">UC</th>
            <th class="row-label bold">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="row-label bold">Number</td>
            <td><?= $overallData['ua_count']; ?></td>         
            <td><?= $overallData['uc_count']; ?></td>
            <td class="bold row-label"><?= $overallData['ua_count'] + $overallData['uc_count']; ?></td>
        </tr>
        <tr>
            <td class="row-label bold">Percentage</td>
            <td><?= $overallData['ua_percentage'] . '%'; ?></td>       
            <td><?= $overallData['uc_percentage'] . '%'; ?></td>
            <td class="bold row-label"><?=$overallData['ua_percentage'] + $overallData['uc_percentage'] . '%'; ?></td>
        </tr>
    </tbody>
</table>
<br>
<br>
<h4 class="bold">Number of days pending(Open)</h4>
<table  class="mt-3">
    <thead>
        <tr>
            <th rowspan="2" class="no-border"></th>
            <th colspan="6" class="row-label">Number of days pending</th>           
        </tr>
        <tr>
            <th class="row-label bold">0 - 3 days</th>
            <th class="row-label bold">4 - 7 days</th>
            <th class="row-label bold">7 - 14 days</th>
            <th class="row-label bold">14 - 30 days</th>
            <th class="row-label bold">&gt; 30 days</th>
            <th class="row-label bold">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="row-label bold">Number</td>
            <td><?php echo isset($overallData['open_3_days_count']) ? $overallData['open_3_days_count'] : 0; ?></td>
            <td><?php echo isset($overallData['open_4to7_days_count']) ? $overallData['open_4to7_days_count'] : 0; ?></td>
            <td><?php echo isset($overallData['open_7to14_days_count']) ? $overallData['open_7to14_days_count'] : 0; ?></td>
            <td><?php echo isset($overallData['open_14to30_days_count']) ? $overallData['open_14to30_days_count'] : 0; ?></td>
            <td><?php echo isset($overallData['open_more_than_30_days_count']) ? $overallData['open_more_than_30_days_count'] : 0; ?></td>
            <td><?php echo isset($overallData['num_pending_days_count']) ? $overallData['num_pending_days_count'] : 0; ?></td>
        </tr>
        <tr>
            <td class="row-label bold">Percentage</td>
            <td><?php echo isset($overallData['open_3_days_percentage']) ? $overallData['open_3_days_percentage'] . "%" : "0.00%"; ?></td>
            <td><?php echo isset($overallData['open_4to7_days_percentage']) ? $overallData['open_4to7_days_percentage'] . "%" : "0.00%"; ?></td>
            <td><?php echo isset($overallData['open_7to14_days_percentage']) ? $overallData['open_7to14_days_percentage'] . "%" : "0.00%"; ?></td>
            <td><?php echo isset($overallData['open_14to30_days_percentage']) ? $overallData['open_14to30_days_percentage'] . "%" : "0.00%"; ?></td>
            <td><?php echo isset($overallData['open_more_than_30_days_percentage']) ? $overallData['open_more_than_30_days_percentage'] . "%" : "0.00%"; ?></td>
            <td><?= isset($overallData['pending_days_percentage']) ? $overallData['pending_days_percentage'] : "0.00%"; ?></td>
        </tr>
    </tbody>
</table>
<br>
<br>
<h4 class="bold">Number of days to closure(Closed)</h4>

<table class="mt-3">
    <thead>
    <tr>
            <th rowspan="2" class="no-border"></th>
            <th colspan="6" class="row-label bold">Number of days to closure</th>           
        </tr>
        <tr>        
            <th class="row-label bold">0 - 3 days</th>
            <th class="row-label bold">4 - 7 days</th>
            <th class="row-label bold">7 - 14 days</th>
            <th class="row-label bold">14 - 30 days</th>
            <th class="row-label bold">&gt; 30 days</th>
            <th class="row-label bold">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="row-label bold">Number</td>
            <td><?php echo isset($overallData['close_3_days_count']) ? $overallData['close_3_days_count'] : 0; ?></td>
            <td><?php echo isset($overallData['close_4to7_days_count']) ? $overallData['close_4to7_days_count'] : 0; ?></td>
            <td><?php echo isset($overallData['close_7to14_days_count']) ? $overallData['close_7to14_days_count'] : 0; ?></td>
            <td><?php echo isset($overallData['close_14to30_days_count']) ? $overallData['close_14to30_days_count'] : 0; ?></td>
            <td><?php echo isset($overallData['close_more_than_30_days_count']) ? $overallData['close_more_than_30_days_count'] : 0; ?></td>
            <td class="row-label bold"><?php echo isset($overallData['num_close_days_count']) ? $overallData['num_close_days_count'] : 0; ?></td>
        </tr>
        <tr>
            <td class="row-label bold">Percentage</td>
            <td><?php echo isset($overallData['close_3_days_percentage']) ? $overallData['close_3_days_percentage'] . "%" : "0.00%"; ?></td>
            <td><?php echo isset($overallData['close_4to7_days_percentage']) ? $overallData['close_4to7_days_percentage'] . "%" : "0.00%"; ?></td>
            <td><?php echo isset($overallData['close_7to14_days_percentage']) ? $overallData['close_7to14_days_percentage'] . "%" : "0.00%"; ?></td>
            <td><?php echo isset($overallData['close_14to30_days_percentage']) ? $overallData['close_14to30_days_percentage'] . "%" : "0.00%"; ?></td>
            <td><?php echo isset($overallData['close_more_than_30_days_percentage']) ? $overallData['close_more_than_30_days_percentage'] . "%" : "0.00%"; ?></td>
            <td class="row-label bold"><?= $overallData['close_days_percentage']; ?></td>
        </tr>
    </tbody>
</table>
<br>
<br>
<h4 class="bold">Observations pending by month</h4>
<!-- Report Table: Observations Pending by Month -->
<table  class="mt-3" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th rowspan="2" class="no-border"></th>
            <th colspan="14" class="row-label bold">Observations pending by month</th>
            
        </tr>
        <tr>
            <th class="row-label bold">Jan</th>
            <th class="row-label bold">Feb</th>
            <th class="row-label bold">Mar</th>
            <th class="row-label bold">Apr</th>
            <th class="row-label bold">May</th>
            <th class="row-label bold">Jun</th>
            <th class="row-label bold">Jul</th>
            <th class="row-label bold">Aug</th>
            <th class="row-label bold">Sep</th>
            <th class="row-label bold">Oct</th>
            <th class="row-label bold">Nov</th>
            <th class="row-label bold">Dec</th>
            <th class="row-label bold">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="row-label bold">Number</td>
            <td><?php echo $overallData['jan_open_count']; ?></td>
            <td><?php echo $overallData['feb_open_count']; ?></td>
            <td><?php echo $overallData['mar_open_count']; ?></td>
            <td><?php echo $overallData['apr_open_count']; ?></td>
            <td><?php echo $overallData['may_open_count']; ?></td>
            <td><?php echo $overallData['jun_open_count']; ?></td>
            <td><?php echo $overallData['jul_open_count']; ?></td>
            <td><?php echo $overallData['aug_open_count']; ?></td>
            <td><?php echo $overallData['sep_open_count']; ?></td>
            <td><?php echo $overallData['oct_open_count']; ?></td>
            <td><?php echo $overallData['nov_open_count']; ?></td>
            <td><?php echo $overallData['dec_open_count']; ?></td>
            <td><?php echo $overallData['total_open_count']; ?></td>
        </tr>
        <tr>
            <td class="row-label bold">Percentage</td>
            <!-- Loop through months and calculate the percentage -->
           
            <td><?php echo $overallData['jan_open_count'] > 0 ? round(($overallData['jan_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['feb_open_count'] > 0 ? round(($overallData['feb_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['mar_open_count'] > 0 ? round(($overallData['mar_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['apr_open_count'] > 0 ? round(($overallData['apr_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['may_open_count'] > 0 ? round(($overallData['may_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['jun_open_count'] > 0 ? round(($overallData['jun_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['jul_open_count'] > 0 ? round(($overallData['jul_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['aug_open_count'] > 0 ? round(($overallData['aug_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['sep_open_count'] > 0 ? round(($overallData['sep_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['oct_open_count'] > 0 ? round(($overallData['oct_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['nov_open_count'] > 0 ? round(($overallData['nov_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo $overallData['dec_open_count'] > 0 ? round(($overallData['dec_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" : '0.00%'; ?></td>
            <td><?php echo ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
                ? round(($overallData['total_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . "%" 
                : '0.00%'; ?>
            </td>

        </tr>
    </tbody>
</table>


    </div>
</body>

</html>
