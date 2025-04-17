<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS Category Week</title>
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 12px;
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
            color: white;
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
    </style>
</head>
<?php

// echo '<pre>' . print_r($data, true) . '</pre>';
// exit;

?>

<body>
    <div class="table-wrapper">

        <div class="row">

            <?php
            $count1 = 1;
            $old_week = 0;
            for ($year = $start_year; $year <= $end_year; $year++) {
                $month_start = ($year == $start_year) ? $start_month : 1;
                $month_end = ($year == $end_year) ? $end_month : 12;

                for ($month = $month_start; $month <= $month_end; $month++) {
                    $startDate = new DateTime("$year-$month-01");
                    $endDate = new DateTime("$year-$month-01");
                    $endDate->modify('last day of this month');

                    $startWeek = (int)$startDate->format('W');
                    $endWeek = (int)$endDate->format('W');

                    if ($month == 12 && $endWeek == 1) {
                        $endWeek = 52;
                    }

                    for ($week = $startWeek; $week <= $endWeek; $week++) {
            ?>
                        <div class="col-md-4">

                            <table>
                                <thead>
                                    <tr>
                                        <th colspan="3">Week <?= $count1 ?></th>
                                    </tr>
                                    <tr>
                                        <td class="pinkrow">Sr.No</td>
                                        <td class="pinkrow">Observation Category</td>
                                        <td class="total_blue">Number</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if ($old_week != $week){
                                        $count1++;
                                    }
                                    $old_week = $week;

                                    $counter = 1;

                                    foreach ($data as $key => $row):
                                        $year_month_wise = $row['year_month_wise'];

                                        $total = isset($year_month_wise[$week]['total'])
                                            ? $year_month_wise[$week]['total']
                                            : 0;
                                    ?>
                                        <tr>
                                            <td class="pinkrow"><?= $counter; ?></td>
                                            <td class="pinkrow" style="text-align: left;"><?= $key; ?></td>

                                            <td class="total_blue"><?= $total; ?></td>

                                        </tr>
                                    <?php
                                        $counter++;
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>

            <?php }
                }
            }
            ?>
        </div>


    </div>
</body>

</html>

<script>
    $(document).ready(function() {

    });
</script>