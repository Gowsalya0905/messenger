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
$worksheet = $spreadsheet->getActiveSheet()->setTitle('HSE_Obs_Tracker_Graph');

// Set Logo1 (Spanning A1:C1)
$logoPath1 = PDF_IMG_PATH . 'company_logo/sana_taibha.png';
$worksheet->mergeCells('A1:D3');
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
$worksheet->mergeCells('I1:O1');
$worksheet->setCellValue('I1', 'AHK Solar Independent PV Project');
$worksheet->getStyle('I1')->getFont()->setBold(true)->setSize(18); // Large Font
$worksheet->getStyle('I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('I1')
    ->getFont()
    ->setUnderline(true);

// Merge and Style Subtitle (G2:J2)
$worksheet->mergeCells('I2:O2');
$worksheet->setCellValue('I2', 'HSE Observations');
$worksheet->getStyle('I2')->getFont()->setSize(12); // Smaller Font
$worksheet->getStyle('I2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Set Logo2 (Spanning L1:N1)
$logoPath2 = PDF_IMG_PATH . 'company_logo/Logo.png';
$worksheet->mergeCells('R1:S3');

if (file_exists($logoPath2)) {
    $logo2 = new Drawing();
    $logo2->setName('Logo2');
    $logo2->setDescription('Company Logo 2');
    $logo2->setPath($logoPath2);
    $logo2->setCoordinates('R1');
    $logo2->setHeight(70); // Adjust height as needed
    $logo2->setWorksheet($worksheet);
}


// Heading Start

$styleArrayHeader = [
    'font' => [
        'color' => ['rgb' => '000000'],
        'bold' => true,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFFFF'],
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

$currentColumn = 'A';

$endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 1
);
$worksheet->mergeCells($currentColumn . '5:' . $endColumn . '5')->setCellValue($currentColumn . '5', 'Calendar Week');
$worksheet->getStyle($currentColumn . '5:' . $endColumn . '5')->applyFromArray($styleArrayHeader);
$currentColumn = $endColumn;
$currentColumn++;

$worksheet->setCellValue($currentColumn . '5', 'Obs.');
$worksheet->getStyle($currentColumn . '5')->applyFromArray($styleArrayHeader);
$currentColumn++;

$worksheet->setCellValue($currentColumn . '5', 'Cum. Obs.');
$worksheet->getStyle($currentColumn . '5')->applyFromArray($styleArrayHeader);
$currentColumn++;

$worksheet->setCellValue($currentColumn . '5', 'Closed');
$worksheet->getStyle($currentColumn . '5')->applyFromArray($styleArrayHeader);
$currentColumn++;

$worksheet->setCellValue($currentColumn . '5', 'Cum. Closed');
$worksheet->getStyle($currentColumn . '5')->applyFromArray($styleArrayHeader);
$worksheet->getColumnDimension($currentColumn)->setWidth(15);
$currentColumn++;

// Heading Ends 


// tbody Starts
$styleCell = [
    'font' => [
        'color' => ['rgb' => '000000'],
        'bold' => false,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFFFF'],
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


$count1 = 1;
$old_week = 0;
$currentColumn = 'A';
$rowcount = 5;
$overall_open_count = 0;
$overall_closed_count = 0;
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
            $open_count = 0;
            $closed_count = 0;

            $rowcount++;

            $currentColumn = 'A';

            foreach ($data as $row) {
                $year_month_wise = $row['year_month_wise'];

                $open_count += $year_month_wise[$week]['open_count'] ?? 0;
                $closed_count += $year_month_wise[$week]['closed_count'] ?? 0;

                $overall_open_count += $year_month_wise[$week]['open_count'] ?? 0;
                $overall_closed_count += $year_month_wise[$week]['closed_count'] ?? 0;
            }

            $endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 1
            );
            $worksheet->mergeCells($currentColumn . $rowcount . ':' . $endColumn . $rowcount)->setCellValue($currentColumn . $rowcount, 'CW' . $count1);
            $worksheet->getStyle($currentColumn . $rowcount . ':' . $endColumn . $rowcount)->applyFromArray($styleCell);
            $currentColumn = $endColumn;
            $currentColumn++;

            $worksheet->setCellValue($currentColumn . $rowcount, $open_count);
            $worksheet->getStyle($currentColumn . $rowcount)->applyFromArray($styleCell);
            $currentColumn++;

            $worksheet->setCellValue($currentColumn . $rowcount, $overall_open_count);
            $worksheet->getStyle($currentColumn . $rowcount)->applyFromArray($styleCell);
            $currentColumn++;

            $worksheet->setCellValue($currentColumn . $rowcount, $closed_count);
            $worksheet->getStyle($currentColumn . $rowcount)->applyFromArray($styleCell);
            $currentColumn++;

            $worksheet->setCellValue($currentColumn . $rowcount, $overall_closed_count);
            $worksheet->getStyle($currentColumn . $rowcount)->applyFromArray($styleCell);


            if ($old_week != $week) {
                $count1++;
            }
            $old_week = $week;
        }
    }
}

// tbody ends



//OBS Category Week Chart Starts

$currentColumn = 'I';
$title_cell = 'I';
$barDataSeriesLabels = [
    new DataSeriesValues('String', 'HSE_Obs_Tracker_Graph!$C$5', null, 1), // Obs.
    new DataSeriesValues('String', 'HSE_Obs_Tracker_Graph!$E$5', null, 1), // Closed
];

$barDataSeriesValues = [
    new DataSeriesValues('Number', 'HSE_Obs_Tracker_Graph!$C$6:$C$' . $rowcount, null, 10), // Obs. values
    new DataSeriesValues('Number', 'HSE_Obs_Tracker_Graph!$E$6:$E$' . $rowcount, null, 10), // Closed values
];

// Define DataSeries for Line Chart
$lineDataSeriesLabels = [
    new DataSeriesValues('String', 'HSE_Obs_Tracker_Graph!$D$5', null, 1), // Cum. Obs.
    new DataSeriesValues('String', 'HSE_Obs_Tracker_Graph!$F$5', null, 1), // Cum. Closed
];
$lineDataSeriesValues = [
    new DataSeriesValues('Number', 'HSE_Obs_Tracker_Graph!$D$6:$D$' . $rowcount, null, 10), // Cum. Obs. values
    new DataSeriesValues('Number', 'HSE_Obs_Tracker_Graph!$F$6:$F$' . $rowcount, null, 10), // Cum. Closed values
];

$categories = new DataSeriesValues('String', 'HSE_Obs_Tracker_Graph!$A$6:$A$' . $rowcount, null, 10);



$barSeries = new DataSeries(
    DataSeries::TYPE_BARCHART,
    DataSeries::GROUPING_CLUSTERED,
    range(0, count($barDataSeriesValues) - 1),
    $barDataSeriesLabels,
    [$categories],
    $barDataSeriesValues 
);
$barSeries->setPlotDirection(DataSeries::DIRECTION_COL);

// Create Line DataSeries
$lineSeries = new DataSeries(
    DataSeries::TYPE_LINECHART,
    null,
    range(0, count($lineDataSeriesValues) - 1),
    $lineDataSeriesLabels,
    [$categories],
    $lineDataSeriesValues
);

$layout = new Layout();
$layout->setShowVal(true);

$plotArea = new PlotArea($layout, [$barSeries, $lineSeries]);
$legend = new Legend(Legend::POSITION_TOP, null, false);
$title = new Title('HSE_Obs_Tracker_Graph');
// $plotArea->setShowPercent(true);

$chart = new Chart(
    'HSE_Obs_Tracker_Graph',
    $title,
    $legend,
    $plotArea
);

$endvalue = intval($rowcount - 5);
$endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString('I') + $endvalue
);
$chart->setTopLeftPosition('I' . 5);
$chart->setBottomRightPosition($endColumn . 25);


try {
    $worksheet->addChart($chart);
} catch (Exception $e) {
    log_message('debug', 'Chart creation error:' . print_r($e, true));
}

//OBS Category Week Chart Ends


// Output the File
$file_name = 'HSE_Obs_Tracker_Graph';
date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date("d-m-Y H-i-s");
$outputFilename = $file_name . '-' . $currentDateTime . '.xlsx';


$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->setIncludeCharts(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $outputFilename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
