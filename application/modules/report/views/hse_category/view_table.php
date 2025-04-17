<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HSE Category Report</title>
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
// Initialize totals
$ua_total = $uc_total = $safe_total = $to_total = 0;
$total_open = $total_closed = 0;

?>

<body>
    <div class="table-wrapper">
        <table>
            <thead>
                <!-- First Row: Years -->
                <tr>
                    <th rowspan="3">No.</th>
                    <th rowspan="3">Category</th>
                    <th rowspan="3">Open</th>
                    <th rowspan="3">Closed</th>
                    <th rowspan="3">TOTAL</th>
                    <th rowspan="3"></th> <!-- Blank Column -->
                    <?php
                    for ($year = $start_year; $year <= $end_year; $year++) {
                        $month_start = ($year == $start_year) ? $start_month : 1;
                        $month_end = ($year == $end_year) ? $end_month : 12;
                        $month_count = $month_end - $month_start + 1;
                        echo "<th colspan='" . ($month_count * 5) . "'>$year</th>";
                    }
                    ?>
                </tr>

                <!-- Second Row: Months -->
                <tr>
                    <?php
                    for ($year = $start_year; $year <= $end_year; $year++) {
                        $month_start = ($year == $start_year) ? $start_month : 1;
                        $month_end = ($year == $end_year) ? $end_month : 12;

                        for ($month = $month_start; $month <= $month_end; $month++) {
                            $month_name = strtoupper(date('F', mktime(0, 0, 0, $month, 1)));
                            echo "<th colspan='4'>$month_name</th>
                            <th rowspan='3'></th>";
                        }
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    $col_val = 1;
                    for ($year = $start_year; $year <= $end_year; $year++) {
                        $month_start = ($year == $start_year) ? $start_month : 1;
                        $month_end = ($year == $end_year) ? $end_month : 12;

                        for ($month = $month_start; $month <= $month_end; $month++) { ?>
                            <th class="colno_<?= $col_val; ?>">UA</th>
                            <th class="colno_<?= $col_val; ?>">UC</th>
                            <th class="colno_<?= $col_val; ?>">SAFE</th>
                            <th class="colno_<?= $col_val; ?>">TO</th>

                    <?php
                            $col_val++;
                        }
                    }
                    ?>
                </tr>
            </thead>

            <tbody>
                <?php
                $totalOpen = $totalClosed = $totalOverall = 0;
                $ua_total = $uc_total = $safe_total = $to_total = 0;
                $counter = 1;

                foreach ($data as $key => $row):
                    $year_month_wise = $row['year_month_wise'];
                    // echo "<pre>";
                    // print_r($year_month_wise);
                    // exit;
                ?>
                    <tr>
                        <td class="pinkrow"><?= $counter; ?></td>
                        <td class="pinkrow" style="text-align: left;"><?= $key; ?></td>
                        <td class="pinkrow"><?= $row['overall_open_count']; ?></td>
                        <td class="pinkrow"><?= $row['overall_closed_count']; ?></td>
                        <td class="liteorange"><?= $row['overall_open_count'] + $row['overall_closed_count']; ?></td>
                        <td></td> <!-- Blank Column -->
                        <?php
                        $color_class = 'pinkrow';
                        $col_val = 1;

                        for ($year = $start_year; $year <= $end_year; $year++) {
                            $month_start = ($year == $start_year) ? $start_month : 1;
                            $month_end = ($year == $end_year) ? $end_month : 12;

                            for ($month = $month_start; $month <= $month_end; $month++):

                                // Initialize counts
                                $ua = 0;
                                $uc = 0;
                                $safe = 0;
                                $to = 0;

                                // Check if the year and month exist in the year_month_wise array
                                if (isset($year_month_wise[$year][$month])) {
                                    $ua = $year_month_wise[$year][$month]['ua_count'] ?? 0;
                                    $uc = $year_month_wise[$year][$month]['uc_count'] ?? 0;
                                    $safe = $year_month_wise[$year][$month]['safe_count'] ?? 0;
                                    $to = $ua + $uc + $safe;
                                }

                        ?>
                                <td class="<?= $color_class; ?> ua_count_<?= $col_val; ?> "><?= $ua; ?></td>
                                <td class="<?= $color_class; ?> uc_count_<?= $col_val; ?> "><?= $uc; ?></td>
                                <td class="<?= $color_class; ?> safe_count_<?= $col_val; ?> "><?= $safe; ?></td>
                                <td class="total_blue to_count_<?= $col_val; ?>"><?= $to; ?></td> <!-- TO -->
                                <td></td> <!-- Blank Column 2-->
                        <?php
                                $color_class = ($color_class === 'pinkrow') ? 'liteorange' : 'pinkrow';
                                $col_val++;
                            endfor;
                        }
                        ?>
                    </tr>
                <?php

                    $totalOpen += $row['overall_open_count'];
                    $totalClosed += $row['overall_closed_count'];
                    $totalOverall += ($row['overall_open_count'] + $row['overall_closed_count']);
                    $counter++;
                endforeach; ?>
            </tbody>

            <!-- Table Footer -->
            <tfoot>
                <!-- Total Row -->
                <tr class=" totals-row">
                    <td class="liteorange bold" colspan="2">Total Observations</td>
                    <td class="liteorange bold"><?= $totalOpen; ?></td>
                    <td class="liteorange bold"><?= $totalClosed; ?></td>
                    <td class="liteorange bold"><?= $totalOverall; ?></td>
                    <td></td> <!-- Blank Column -->

                    <?php
                    for ($year = $start_year; $year <= $end_year; $year++) {
                        $month_start = ($year == $start_year) ? $start_month : 1;
                        $month_end = ($year == $end_year) ? $end_month : 12;

                        for ($month = $month_start; $month <= $month_end; $month++): ?>
                            <td class="red">UA</td>
                            <td class="red">UC</td>
                            <td class="red">SAFE</td>
                            <td></td>
                            <td></td> <!-- Blank Column 2-->
                    <?php endfor;
                    }
                    ?>
                </tr>

                <tr class="totals-row">
                    <td rowspan="4" colspan="5"></td>
                    <td></td> <!-- Blank Column -->

                    <?php
                    $col_val = 1;
                    for ($year = $start_year; $year <= $end_year; $year++) {
                        $month_start = ($year == $start_year) ? $start_month : 1;
                        $month_end = ($year == $end_year) ? $end_month : 12;

                        for ($month = $month_start; $month <= $month_end; $month++): ?>
                            <td class="total_blue sum_ua_<?= $col_val; ?>"><?= $ua_total; ?></td>
                            <td class="total_blue sum_uc_<?= $col_val; ?>"><?= $uc_total; ?></td>
                            <td class="total_blue sum_safe_<?= $col_val; ?>"><?= $safe_total; ?></td>
                            <td></td>
                            <td></td> <!-- Blank Column 2-->
                    <?php
                            $col_val++;
                        endfor;
                    }
                    ?>
                </tr>

                <!-- Grand Total Row -->
                <tr class="totals-row">
                    <td></td> <!-- Blank Column -->
                    <?php
                    $col_val = 1;
                    for ($year = $start_year; $year <= $end_year; $year++) {
                        $month_start = ($year == $start_year) ? $start_month : 1;
                        $month_end = ($year == $end_year) ? $end_month : 12;

                        for ($month = $month_start; $month <= $month_end; $month++): ?>
                            <td colspan="3" class="total_blue bold final_total_<?= $col_val; ?>">
                                <?= $ua_total + $uc_total + $safe_total + $to_total; ?>
                            <td></td>
                            <td></td> <!-- Blank Column -->
                            </td>
                    <?php
                            $col_val++;
                        endfor;
                    }
                    ?>
                </tr>
            </tfoot>
        </table>
        </table>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {
        // Iterate through each column
        let maxCols = <?= $col_val - 1; ?>; // Adjust to total columns dynamically
        for (let col = 1; col <= maxCols; col++) {
            let uaSum = 0;
            let ucSum = 0;
            let safeSum = 0;

            // Sum up values for the current column
            $(`.ua_count_${col}`).each(function() {
                let val = parseInt($(this).text()) || 0; // Convert to number or default to 0
                uaSum += val;
            });

            $(`.uc_count_${col}`).each(function() {
                let val = parseInt($(this).text()) || 0;
                ucSum += val;
            });

            $(`.safe_count_${col}`).each(function() {
                let val = parseInt($(this).text()) || 0;
                safeSum += val;
            });

            // Update the footer cells for UA, UC, and SAFE columns
            $(`.sum_ua_${col}`).text(uaSum);
            $(`.sum_uc_${col}`).text(ucSum);
            $(`.sum_safe_${col}`).text(safeSum);

            // Calculate and update the final total column (UA + UC + SAFE)
            let finalTotal = uaSum + ucSum + safeSum;
            $(`.final_total_${col}`).text(finalTotal);
        }
    });
</script>