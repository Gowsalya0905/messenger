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
$worksheet = $spreadsheet->getActiveSheet()->setTitle('OBS');

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
$worksheet->setCellValue('I2', 'OBS Category-Weekly Report');
$worksheet->getStyle('I2')->getFont()->setSize(12); // Smaller Font
$worksheet->getStyle('I2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Set Logo2 (Spanning L1:N1)
$logoPath2 = PDF_IMG_PATH . 'company_logo/Logo_without_comp.png';
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



// Week Heading Start
$count = 1;
$old_week = 0;
$currentColumn = 'A';


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
            $endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 4
            );
            $worksheet->mergeCells($currentColumn . '5:' . $endColumn . '5')->setCellValue($currentColumn . '5', 'Week ' . $count . ' Number');
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

            if ($old_week != $week) {
                $count++;
            }
            $old_week = $week;
        }
    }
}
// Week Heading Start


// Headings Start sr.no/category/total
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

$stylePinkleft = [
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
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$count = 1;
$old_week = 0;
$currentColumn = 'A';

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

            $worksheet->setCellValue($currentColumn . '6', 'Sr.No');
            $worksheet->getStyle($currentColumn . '6')->applyFromArray($stylePink);
            $currentColumn++;

            $endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 2
            );
            $worksheet->mergeCells("$currentColumn" . '6:' . "$endColumn" . '6');
            $worksheet->setCellValue($currentColumn . '6', 'Observation Category');
            $worksheet->getStyle("$currentColumn" . '6:' . "$endColumn" . '6')->applyFromArray($stylePink);
            $currentColumn = $endColumn;
            $currentColumn++;

            $worksheet->setCellValue($currentColumn . '6', 'Number');
            $worksheet->getStyle($currentColumn . '6')->applyFromArray($styletotalblue);
            $currentColumn++;

            // Add blank column
            $worksheet->getColumnDimension($currentColumn)->setWidth(2); // Set width to 2
            $currentColumn++;

            if ($old_week != $week) {
                $count++;
            }
            $old_week = $week;
        }
    }
}
// Headings End sr.no/category/total

// tbody Starts

$currentColumn = 'A';
$rowcount = 7;
$counter = 1;
foreach ($data as $key => $row):
    $year_month_wise = $row['year_month_wise'];
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
                $total = isset($year_month_wise[$week]['total'])
                    ? $year_month_wise[$week]['total']
                    : 0;

                $worksheet->setCellValue($currentColumn . $rowcount, $counter);
                $worksheet->getStyle($currentColumn . $rowcount)->applyFromArray($stylePink);
                $currentColumn++;


                $endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($currentColumn) + 2
                );
                $worksheet->mergeCells("$currentColumn" . $rowcount . ':' . "$endColumn" . $rowcount);
                $worksheet->setCellValue($currentColumn . $rowcount, $key);
                $worksheet->getStyle("$currentColumn" . $rowcount . ':' . "$endColumn" . $rowcount)->applyFromArray($stylePinkleft);
                $currentColumn = $endColumn;
                $currentColumn++;

                $worksheet->setCellValue($currentColumn . $rowcount, $total);
                $worksheet->getStyle($currentColumn . $rowcount)->applyFromArray($styletotalblue);
                $currentColumn++;

                $worksheet->getColumnDimension($currentColumn)->setWidth(2); // Set width to 2
                $currentColumn++;
            }
        }
    }
    $currentColumn = 'A';
    $rowcount++;
    $counter++;
endforeach;

// tbody ends


//OBS Category Week Chart Starts

$count = 1;
$old_week = 0;
$left = true;
$rowcount_start = $rowcount;
$rowcount_start += 4;
$rowcount_end = $rowcount_start + 15;
$title_cell = 'A';
$datacount = 'E';


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

            if ($left == true) {
                $column_start = 'A';
                $column_end = 'P';
            } else {
                $column_start = 'S';
                $column_end = 'AH';
            }

            // log_message('debug','startWeek'.print_r($startWeek,true).'endWeek'.print_r($endWeek,true));
            // log_message('debug','column_start'.print_r($column_start,true).'column_end'.print_r($column_end,true));
            // log_message('debug','rowcount_start'.print_r($rowcount_start,true).'rowcount_end'.print_r($rowcount_end,true));


            $total_count_end = $counter + 6;
            $dataSeriesLabels = array(
                new DataSeriesValues('String', 'OBS!$'.$title_cell.'$5', NULL, 1),
            );

            $xAxisTickValues = array(
                new DataSeriesValues('String', 'OBS!$B$7:$B$' . $total_count_end, null, $total_count_end), // Category labels
            );
            $dataSeriesValues = array(
                new DataSeriesValues('Number', 'OBS!'.$datacount.'$7:$'.$datacount.'$' . $total_count_end, null, $total_count_end), // Value 1
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
            $title = new Title(' OBS Category Week ' . $count);

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
            $chart->setTopLeftPosition($column_start . $rowcount_start);
            $chart->setBottomRightPosition($column_end . $rowcount_end);


            try {
                $worksheet->addChart($chart);
            } catch (Exception $e) {
                log_message('debug','errors'.print_r($e,true));
                error_log("Chart creation error: " . $e->getMessage());
            }


            if ($old_week != $week) {
                $count++;
            }
            $old_week = $week;

            $datacount = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($datacount) + 6
            );
            $title_cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($title_cell) + 6
            );

            if ($left == true) {
                $left = false;
            } else {
                $left = true;
                $rowcount_start = $rowcount_end + 4;
                $rowcount_end = $rowcount_start + 15;
            }
        }
    }
}
//OBS Category Week Chart Ends



// Output the File
$file_name = 'OBS-Category Week Report';
date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date("d-m-Y H-i-s");
$outputFilename = $file_name . '-' . $currentDateTime . '.xlsx';


$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->setIncludeCharts(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $outputFilename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
