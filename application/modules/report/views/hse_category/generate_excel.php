<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Chart\DataTable;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\RichText\Run;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$spreadsheet = new Spreadsheet();

// Set Active Sheet
$worksheet = $spreadsheet->getActiveSheet()->setTitle('HSE');

// Set Logo1 (Spanning A1:C1)
$logoPath1 = PDF_IMG_PATH . 'company_logo/sana_taibha.png';
if (file_exists($logoPath1)) {
    $logo1 = new Drawing();
    $logo1->setName('Logo1');
    $logo1->setDescription('Company Logo 1');
    $logo1->setPath($logoPath1);
    $logo1->setCoordinates('A1');
    $logo1->setHeight(70); // Adjust height as needed
    $logo1->setWorksheet($worksheet);
}

// Merge and Style Title (G1:K1)
$worksheet->mergeCells('E1:J1');
$worksheet->setCellValue('E1', 'AHK Solar Independent PV Project');
$worksheet->getStyle('E1')->getFont()->setBold(true)->setSize(18); // Large Font
$worksheet->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('E1')
    ->getFont()
    ->setUnderline(true);

// Merge and Style Subtitle (G2:J2)
$worksheet->mergeCells('E2:J2');
$worksheet->setCellValue('E2', 'HSE-Category Report');
$worksheet->getStyle('E2')->getFont()->setSize(12); // Smaller Font
$worksheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Set Logo2 (Spanning L1:N1)
$logoPath2 = PDF_IMG_PATH . 'company_logo/Logo_without_comp.png';
if (file_exists($logoPath2)) {
    $logo2 = new Drawing();
    $logo2->setName('Logo2');
    $logo2->setDescription('Company Logo 2');
    $logo2->setPath($logoPath2);
    $logo2->setCoordinates('L1');
    $logo2->setHeight(70); // Adjust height as needed
    $logo2->setWorksheet($worksheet);
}

// Adjust Column Widths
$worksheet->getColumnDimension('A')->setWidth(10);
$worksheet->getColumnDimension('B')->setWidth(10);
$worksheet->getColumnDimension('C')->setWidth(10);
$worksheet->getColumnDimension('E')->setWidth(10);
$worksheet->getColumnDimension('L')->setWidth(10);
$worksheet->getColumnDimension('M')->setWidth(10);
$worksheet->getColumnDimension('N')->setWidth(10);

// Header start
$worksheet->mergeCells('A4:A6')->setCellValue('A4', 'No.');
$worksheet->mergeCells('B4:B6')->setCellValue('B4', 'Category');
$worksheet->mergeCells('C4:C6')->setCellValue('C4', 'Open');
$worksheet->mergeCells('D4:D6')->setCellValue('D4', 'Closed');
$worksheet->mergeCells('E4:E6')->setCellValue('E4', 'TOTAL');
$worksheet->mergeCells('F4:F6')->setCellValue('F4', ''); // Empty column for spacing

$styleArrayHeader = [
    'font' => [
        'color' => ['rgb' => 'FFFFFF'],
        'bold' => true,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '0F243E'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$worksheet->getStyle('A4:E6')->applyFromArray($styleArrayHeader);
$currentColumn = 'G';
for ($year = $start_year; $year <= $end_year; $year++) {
    $month_start = ($year == $start_year) ? $start_month : 1;
    $month_end = ($year == $end_year) ? $end_month : 12;
    $month_count = $month_end - $month_start + 1;
    $month_width = $month_count * 5;

    $endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + $month_width - 1
    );
    $worksheet->mergeCells($currentColumn . '4:' . $endColumn . '4')->setCellValue($currentColumn . '4', $year);
    $worksheet->getStyle($currentColumn . '4:' . $endColumn . '4')
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($currentColumn . '4:' . $endColumn . '4')->applyFromArray($styleArrayHeader)->getFont()
        ->setSize(22);

    $currentColumn = $endColumn;
    $currentColumn++;
}

$currentColumn = 'G';
for ($year = $start_year; $year <= $end_year; $year++) {
    $month_start = ($year == $start_year) ? $start_month : 1;
    $month_end = ($year == $end_year) ? $end_month : 12;

    for ($month = $month_start; $month <= $month_end; $month++) {
        $month_name = strtoupper(date('F', mktime(0, 0, 0, $month, 1)));
        $endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 3
        );
        $worksheet->mergeCells($currentColumn . '5:' . $endColumn . '5')->setCellValue($currentColumn . '5', $month_name);
        $worksheet->getStyle($currentColumn . '5')
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $worksheet->getStyle($currentColumn . '5:' . $endColumn . '5')->applyFromArray($styleArrayHeader);
        $currentColumn = $endColumn;
        $currentColumn++;
        // Add blank column
        $worksheet->getColumnDimension($currentColumn)->setWidth(2); // Set width to 2
        $currentColumn++;
    }
}

$currentColumn = 'G';
for ($year = $start_year; $year <= $end_year; $year++) {
    $month_start = ($year == $start_year) ? $start_month : 1;
    $month_end = ($year == $end_year) ? $end_month : 12;

    for ($month = $month_start; $month <= $month_end; $month++) {
        $worksheet->setCellValue($currentColumn . '6', 'UA');
        $worksheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 1) . '6', 'UC');
        $worksheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 2) . '6', 'SAFE');
        $worksheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 3) . '6', 'TO');

        $worksheet->getStyle($currentColumn . '6:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 3) . '6')->applyFromArray($styleArrayHeader);
        $currentColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 4);

        // Add blank column
        $worksheet->getColumnDimension($currentColumn)->setWidth(2); // Set width to 2
        $currentColumn++;
    }
}

// Header End
//tbody start
$currentRow = 'A';
$currentRowNO = '7';

$totalOpen = $totalClosed = $totalOverall = 0;
$ua_total = $uc_total = $safe_total = $to_total = 0;
$counter = 1;
$styleArray_hse_cat = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E6B8B7'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
];
$styleArray_hsecat_total = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FABF8F'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$worksheet->getColumnDimension('B')->setAutoSize(true);
foreach ($data as $key => $row):
    $worksheet->setCellValue($currentRow . $currentRowNO, $counter);
    $worksheet->getStyle($currentRow . $currentRowNO)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($currentRow . $currentRowNO)->applyFromArray($styleArray_hse_cat);
    $currentRow++;
    $worksheet->setCellValue($currentRow . $currentRowNO, $key);
    $worksheet->getStyle($currentRow . $currentRowNO)->applyFromArray($styleArray_hse_cat);
    $currentRow++;
    $worksheet->setCellValue($currentRow . $currentRowNO, $row['overall_open_count']);
    $worksheet->getStyle($currentRow . $currentRowNO)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($currentRow . $currentRowNO)->applyFromArray($styleArray_hse_cat);
    $currentRow++;
    $worksheet->setCellValue($currentRow . $currentRowNO, $row['overall_closed_count']);
    $worksheet->getStyle($currentRow . $currentRowNO)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($currentRow . $currentRowNO)->applyFromArray($styleArray_hse_cat);
    $currentRow++;
    $worksheet->setCellValue($currentRow . $currentRowNO, $row['overall_open_count'] + $row['overall_closed_count']);
    $worksheet->getStyle($currentRow . $currentRowNO)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($currentRow . $currentRowNO)->applyFromArray($styleArray_hsecat_total);
    $currentRow++;

    // Add blank column
    $worksheet->getColumnDimension($currentRow)->setWidth(2); // Set width to 2
    $currentRow++;

    $stylePink = [
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E6B8B7'],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];

    $styleOrange = [
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'FABF8F'],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];

    $styletotalblue = [
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '8DB4E2'],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];

    $total_ua = $total_uc = $total_safe = $total_to = 0;
    $year_month_wise = $row['year_month_wise'];
    $colorStyle = $stylePink; // Alternating colors

    // Loop for years and months
    for ($year = $start_year; $year <= $end_year; $year++) {
        $month_start = ($year == $start_year) ? $start_month : 1;
        $month_end = ($year == $end_year) ? $end_month : 12;

        for ($month = $month_start; $month <= $month_end; $month++) {
            // Set default values
            $ua = 0;
            $uc = 0;
            $safe = 0;
            $to = 0;

            // Match current month and year in the data
            if (isset($year_month_wise[$year][$month])) {
                $ua = $year_month_wise[$year][$month]['ua_count'] ?? 0;
                $uc = $year_month_wise[$year][$month]['uc_count'] ?? 0;
                $safe = $year_month_wise[$year][$month]['safe_count'] ?? 0;
                $to = $ua + $uc + $safe;
            }

            // Write UA count
            $worksheet->setCellValue("{$currentRow}{$currentRowNO}", $ua);
            $worksheet->getStyle("{$currentRow}{$currentRowNO}")->applyFromArray($colorStyle);
            $currentRow++;

            // Write UC count
            $worksheet->setCellValue("{$currentRow}{$currentRowNO}", $uc);
            $worksheet->getStyle("{$currentRow}{$currentRowNO}")->applyFromArray($colorStyle);
            $currentRow++;

            // Write SAFE count
            $worksheet->setCellValue("{$currentRow}{$currentRowNO}", $safe);
            $worksheet->getStyle("{$currentRow}{$currentRowNO}")->applyFromArray($colorStyle);
            $currentRow++;

            // Write TO count
            $worksheet->setCellValue("{$currentRow}{$currentRowNO}", $to);
            $worksheet->getStyle("{$currentRow}{$currentRowNO}")->applyFromArray($styletotalblue);
            $currentRow++;

            // Add blank column
            $worksheet->getColumnDimension($currentRow)->setWidth(2); // Set width to 2
            $currentRow++;


            // Alternating row colors
            $colorStyle = ($colorStyle === $stylePink) ? $styleOrange : $stylePink;

            // Update totals
            $total_ua += $ua;
            $total_uc += $uc;
            $total_safe += $safe;
            $total_to += $to;
        }
    }

    $totalOpen += $row['overall_open_count'];
    $totalClosed += $row['overall_closed_count'];
    $totalOverall += ($row['overall_open_count'] + $row['overall_closed_count']);
    $counter++;
    $currentRow = 'A';
    $currentRowNO++;

endforeach;
//tbody end
//Footer start
$currentColumn = 'B';
$worksheet->setCellValue($currentColumn . $currentRowNO, 'Total Observations');
$worksheet->getStyle($currentColumn . $currentRowNO)->applyFromArray($styleArray_hsecat_total);
$currentColumn++;
// Total Open
$worksheet->setCellValue($currentColumn . $currentRowNO, $totalOpen);
$worksheet->getStyle($currentColumn . $currentRowNO)->applyFromArray($styleArray_hsecat_total);
$currentColumn++;
// Total Closed
$worksheet->setCellValue($currentColumn . $currentRowNO, $totalClosed);
$worksheet->getStyle($currentColumn . $currentRowNO)->applyFromArray($styleArray_hsecat_total);
$currentColumn++;
// Total Overall
$worksheet->setCellValue($currentColumn . $currentRowNO, $totalOverall);
$worksheet->getStyle($currentColumn . $currentRowNO)->applyFromArray($styleArray_hsecat_total);
$currentColumn++;
// Add blank column
$worksheet->setCellValue($currentColumn . $currentRowNO, '');
$currentColumn++;

$style_red = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'C00000'],
    ],
    'font' => [
        'color' => ['rgb' => 'FFFFFF'],
        'bold' => true,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$previousRowNO = $currentRowNO - 1;
$nextRowNO = $currentRowNO + 1;
$finalRowNO = $nextRowNO + 1;

for ($year = $start_year; $year <= $end_year; $year++) {
    $month_start = ($year == $start_year) ? $start_month : 1;
    $month_end = ($year == $end_year) ? $end_month : 12;

    for ($month = $month_start; $month <= $month_end; $month++) {
        $startcolumn = $currentColumn;
        $worksheet->setCellValue($currentColumn . $currentRowNO, 'UA');
        $worksheet->getStyle($currentColumn . $currentRowNO)->applyFromArray($style_red);
        $worksheet->setCellValue($currentColumn . $nextRowNO, '=SUM(' . $currentColumn . '7:' . $currentColumn . $previousRowNO . ')');
        $worksheet->getStyle($currentColumn . $nextRowNO)->applyFromArray($styletotalblue);
        $currentColumn++;

        $worksheet->setCellValue($currentColumn . $currentRowNO, 'UC');
        $worksheet->getStyle($currentColumn . $currentRowNO)->applyFromArray($style_red);
        $worksheet->setCellValue($currentColumn . $nextRowNO, '=SUM(' . $currentColumn . '7:' . $currentColumn . $previousRowNO . ')');
        $worksheet->getStyle($currentColumn . $nextRowNO)->applyFromArray($styletotalblue);
        $currentColumn++;

        $worksheet->setCellValue($currentColumn . $currentRowNO, 'SAFE');
        $worksheet->getStyle($currentColumn . $currentRowNO)->applyFromArray($style_red);
        $worksheet->setCellValue($currentColumn . $nextRowNO, '=SUM(' . $currentColumn . '7:' . $currentColumn . $previousRowNO . ')');
        $worksheet->getStyle($currentColumn . $nextRowNO)->applyFromArray($styletotalblue);

        $worksheet->mergeCells($startcolumn . $finalRowNO . ':' . $currentColumn . $finalRowNO)
            ->setCellValue($startcolumn . $finalRowNO, '=SUM(' . $startcolumn . $nextRowNO . ':' . $currentColumn . $nextRowNO . ')');
        $worksheet->getStyle($startcolumn . $finalRowNO . ':' . $currentColumn . $finalRowNO)->applyFromArray($styletotalblue);

        $currentColumn++;
        $worksheet->setCellValue($currentColumn . $currentRowNO, ''); // Blank column
        $currentColumn++;
        $worksheet->setCellValue($currentColumn . $currentRowNO, ''); // Blank column
        $currentColumn++;
    }
}

//Footer end

//Total HSE observations Chart
$total_count_end = count($data) + 6;
$dataSeriesLabels = array(
    new DataSeriesValues('String', 'HSE!$E$4', NULL, 1),
);
$xAxisTickValues = array(
    new DataSeriesValues('String', 'HSE!$B$7:$B$' . $total_count_end, null, $total_count_end), // Category labels
);
$dataSeriesValues = array(
    new DataSeriesValues('Number', 'HSE!E$7:$E$' . $total_count_end, null, $total_count_end), // Value 1
);

// Add the data series to the chart
$series = new DataSeries(
    DataSeries::TYPE_BARCHART, // Type
    DataSeries::GROUPING_STANDARD, // Plot Direction  //pie chart is null and bar chart DataSeries::GROUPING_STANDARD
    range(0, count($dataSeriesValues) - 1), // Plot Order
    $dataSeriesLabels, // DataSeriesLabels
    $xAxisTickValues,
    $dataSeriesValues, // DataSeriesValues
);

$layout = new Layout();
$layout->setShowPercent(true);


$plotArea = new PlotArea($layout, array($series));
$legend = new Legend(Legend::POSITION_RIGHT, null, false);
$title = new Title('Total HSE observations');

$chart = new Chart(
    'chart',       // name
    $title,         // title
    $legend,        // legend
    $plotArea,      // plotArea
    true,           // plotVisibleOnly
    'gap',              // displayBlanksAs
    null,           // xAxisLabel
    null,           // yAxisLabel
);
$finalRowNO += 5;
$chart->setTopLeftPosition('A' . $finalRowNO);
$finalRowNO_end = $finalRowNO + 15;
$chart->setBottomRightPosition('M' . $finalRowNO_end);

$worksheet->addChart($chart);
//Total HSE observations Chart end


//New Hidden worksheet
$worksheet_2 = $spreadsheet->createSheet()->setTitle('MONTH');
$tableHeadings = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
];
$column_2 = 'B';
$row_2 = 2;
foreach ($tableHeadings as $heading) {
    $worksheet_2->setCellValue($column_2 . $row_2, $heading);
    $worksheet_2->getColumnDimension($column_2)->setAutoSize(true);
    $row_2++;
}
$column_2 = 'C';
$row_2 = 1;
foreach ($data as $category_name => $category) {
    $worksheet_2->setCellValue($column_2 . $row_2, $category_name);
    $worksheet_2->getColumnDimension($column_2)->setAutoSize(true);
    $column_2++;
}
$worksheet_2->setCellValue($column_2 . '2', 'Overall');

$column_2 = 'C';
foreach ($data as $categoryName => $category) {
    $year_month_wise = $category['year_month_wise'];
    $row_2 = 2;
    for ($monthIndex = 1; $monthIndex <= 12; $monthIndex++) {
        $year = date('Y');
        if (isset($year_month_wise[$year][$monthIndex])) {
            $total = $year_month_wise[$year][$monthIndex]['total'];
        } else {
            $total = 0;
        }
        $worksheet_2->setCellValue($column_2 . $row_2, $total);
        $row_2++;
    }
    $prev = $column_2;
    $column_2++;
}

for ($row = 2; $row <= 13; $row++) {
    $sumFormula = "=SUM(C$row:$prev$row)";
    $worksheet_2->setCellValue($column_2 . $row, $sumFormula);
}

$worksheet_2->getStyle('B2:' . $worksheet_2->getHighestColumn() . $worksheet_2->getHighestRow())
    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$worksheet_2->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
//Total HSE observations by month


$dataSeriesLabels = array(
    new DataSeriesValues('String', 'HSE!$E$4', NULL, 1),
);
$xAxisTickValues = array(
    new DataSeriesValues('String', 'MONTH!$B$2:$B$13', null, 12),
);

$dataSeriesValues = array(
    new DataSeriesValues('Number', 'MONTH!$' . $column_2 . '$2:$' . $column_2 . '$13', null, 12),
);


$series = new DataSeries(
    DataSeries::TYPE_BARCHART,
    DataSeries::GROUPING_STANDARD,
    range(0, count($dataSeriesValues) - 1),
    $dataSeriesLabels,
    $xAxisTickValues,
    $dataSeriesValues,
);

$layout = new Layout();
$layout->setShowPercent(true);


$plotArea = new PlotArea($layout, array($series));
$legend = new Legend(Legend::POSITION_RIGHT, null, false);
$title = new Title('Total HSE observations by month');

$chart = new Chart(
    'chart',       // name
    $title,         // title
    $legend,        // legend
    $plotArea,      // plotArea
    true,           // plotVisibleOnly
    'gap',              // displayBlanksAs
    null,           // xAxisLabel
    null,           // yAxisLabel
);
$chart->setTopLeftPosition('N' . $finalRowNO);
$finalRowNO += 15;
$chart->setBottomRightPosition('Z' . $finalRowNO);

$worksheet->addChart($chart);
//Total HSE observations by month end
$chart_column_start = 'C';
$odd_even = 1;
foreach ($data as $key => $row):
    $dataSeriesLabels = array(
        new DataSeriesValues('String', 'HSE!$E$4', NULL, 1),
    );
    $xAxisTickValues = array(
        new DataSeriesValues('String', 'MONTH!$B$2:$B$13', null, 12),
    );

    $dataSeriesValues = array(
        new DataSeriesValues('Number', 'MONTH!$' . $chart_column_start . '$2:$' . $chart_column_start . '$13', null, 12),
    );
    $series = new DataSeries(
        DataSeries::TYPE_BARCHART,
        DataSeries::GROUPING_STANDARD,
        range(0, count($dataSeriesValues) - 1),
        $dataSeriesLabels,
        $xAxisTickValues,
        $dataSeriesValues,
    );

    $layout = new Layout();
    $layout->setShowPercent(true);


    $plotArea = new PlotArea($layout, array($series));
    $legend = new Legend(Legend::POSITION_RIGHT, null, false);
    $title = new Title($key);

    $chart = new Chart(
        'chart',       // name
        $title,         // title
        $legend,        // legend
        $plotArea,      // plotArea
        true,           // plotVisibleOnly
        'gap',              // displayBlanksAs
        null,           // xAxisLabel
        null,           // yAxisLabel
    );
    if ($odd_even % 2 == 0) {
        $chart->setTopLeftPosition('N' . $finalRowNO);
        $finalRowNO += 15;
        $chart->setBottomRightPosition('Z' . $finalRowNO);
    } else {

        $finalRowNO += 5;
        $chart->setTopLeftPosition('A' . $finalRowNO);
        $finalRowNO_end = $finalRowNO + 15;
        $chart->setBottomRightPosition('M' . $finalRowNO_end);
    }


    $worksheet->addChart($chart);
    $chart_column_start++;
    $odd_even++;
endforeach;

// Output the File
$file_name = 'HSE-Category Report';
date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date("d-m-Y H-i-s");
$outputFilename = $file_name . '-' . $currentDateTime . '.xlsx';


$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->setIncludeCharts(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $outputFilename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
