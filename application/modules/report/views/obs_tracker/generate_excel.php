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
$worksheet = $spreadsheet->getActiveSheet()->setTitle('Observation Tracker');

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
$worksheet->setCellValue('E2', 'Doc. No. : MP01 REG 07 Observation Register');
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
$worksheet->getColumnDimension('E')->setWidth(30);
$worksheet->getColumnDimension('L')->setWidth(10);
$worksheet->getColumnDimension('M')->setWidth(10);
$worksheet->getColumnDimension('N')->setWidth(10);


$tableHeadings = [
    'No.',
    'Report No',
    'Year',
    'Month',
    'Observation Date',
    'HSE Category',
    'Description of Observation',
    'Risk Level',
    'Rectification',
    'Type of Observation or Incident',
    // 'Injury Mechanism (FAC Only)',
    'Actionee',
    'Planned Closeout Date',
    'Actual Closeout Date',
    'Status',
    'Raised By PC / EPC/CCS Sub-Con',
    'Obs. Raised By',
    'Number of Days Pending',
    'Number of Days to Closure',
    'Delay (Days)'
];

$startRow = 6; // Start row for table data

// Add Table Headings
$column = 'A';

foreach ($tableHeadings as $heading) {
    $cell = $column . $startRow;
    $worksheet->setCellValue($cell, $heading);
    $style = $worksheet->getStyle($cell);
    $style->getFont()->setBold(true)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $style->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('16365C');
    $worksheet->getColumnDimension($column)->setAutoSize(true);

    $column++;
}

// Add Data Rows
$row = $startRow + 1;
$s_no = 1;
foreach ($obs_tracker as $ltVal) {
    $obs_risk_id = $ltVal->obs_risk_id ?? '';
    $obs_type_id = $ltVal->obs_type_id ?? '';
    $obs_risk = $risk_rating[$obs_risk_id] ?? '-';
    $obs_type = $obs_type_list[$obs_type_id] ?? '-';
    $obs_year = $obs_month = '';

    if (!empty($ltVal->obs_date)) {
        $obs_date_y_m = new DateTime($ltVal->obs_date);
        $obs_year = $obs_date_y_m->format('Y');
        $obs_month = $obs_date_y_m->format('M');
    }

    $obs_open_closed_sts = $ltVal->is_closed ? 'Closed' : 'Open';
    $pending_days = !$ltVal->is_closed && !empty($ltVal->obs_date) ? ceil((strtotime(date('Y-m-d')) - strtotime($ltVal->obs_date)) / (60 * 60 * 24)) : 0;

    $closure_day = (!empty($ltVal->obs_date) && !empty($ltVal->obs_assigner_target_date))
        ? ceil((strtotime($ltVal->obs_assigner_target_date) - strtotime($ltVal->obs_date)) / (60 * 60 * 24))
        : 0;

    $delay = (!empty($ltVal->obs_assigner_target_date) && !empty($ltVal->closed_date))
        ? ceil((strtotime($ltVal->closed_date) - strtotime($ltVal->obs_assigner_target_date)) / (60 * 60 * 24))
        : 0;

    // Populate each cell
    $column = 'A';

    $worksheet->setCellValue($column . $row, $s_no++);
    $worksheet->getStyle($column++ . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->setCellValue($column . $row, $ltVal->obs_id);
    $worksheet->getStyle($column . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)
        ->getFont()
        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE))
        ->setUnderline(true);
    $column++;

    $worksheet->setCellValue($column . $row, ucfirst($obs_year));
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

    $worksheet->getStyle($column . $row)
        ->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB('CCC0DA');

    $column++;

    $worksheet->setCellValue($column . $row, ucfirst($obs_month));
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('CCC0DA');
    $column++;

    $worksheet->setCellValue($column . $row, !empty($ltVal->obs_date) ? date('d-M-Y', strtotime($ltVal->obs_date)) : '-');
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE48F');
    $worksheet->getStyle($column . $row)
        ->getFont()
        ->setBold(true);
    $column++;

    $worksheet->setCellValue($column . $row, ucfirst($ltVal->hse_cat_name));
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('CCC0DA');
    $column++;
    $obsDesc = strip_tags($ltVal->obs_desc);
    $obsDesc = trim(preg_replace('/\s+/', ' ', $obsDesc));
    $words = explode(' ', $obsDesc);
    $chunks = array_chunk($words, 6);
    $formattedText = implode("\n", array_map(function ($chunk) {
        return implode(' ', $chunk);
    }, $chunks));
    $richText = new RichText();
    $richText->createText($formattedText);
    $cellCoordinate = $column++ . $row;
    $worksheet->setCellValue($cellCoordinate, $richText);
    $worksheet->getStyle($cellCoordinate)->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($cellCoordinate)->getFont()->setSize(9); // Reduce font size as needed
    $numLines = count($chunks);


    $worksheet->setCellValue($column . $row, $obs_risk);
    $riskColor = $obs_risk_id == 1 ? 'CEFAD7' : ($obs_risk_id == 2 ? 'FFE48F' : ($obs_risk_id == 3 ? 'FCC9B2' : 'FFFFFF'));
    $fontColor = $obs_risk_id == 1 ? '006100' : ($obs_risk_id == 2 ? '9C6500' : ($obs_risk_id == 3 ? '9C0006' : 'FFFFFF'));
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB($riskColor);
    $worksheet->getStyle($column . $row)
        ->getFont()
        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($fontColor));
    $column++;

    $obsSupDesc = strip_tags($ltVal->obs_supervisor_desc);
    $obsSupDesc = trim(preg_replace('/\s+/', ' ', $obsSupDesc));
    $words_sup = explode(' ', $obsSupDesc);
    $chunks_sup = array_chunk($words_sup, 6);
    $formattedTextsup = implode("\n", array_map(function ($chunks_sup) {
        return implode(' ', $chunks_sup);
    }, $chunks_sup));

    $richText = new RichText();
    $richText->createText($formattedTextsup);
    $cellCoordinate = $column++ . $row;
    $worksheet->setCellValue($cellCoordinate, $richText);
    $worksheet->getStyle($cellCoordinate)->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($cellCoordinate)->getFont()->setSize(9); // Reduce font size as needed
    $numLines_sup = count($chunks_sup);
    $final_rownumLines = ($numLines + $numLines_sup) / 2;
    $worksheet->getRowDimension($row)->setRowHeight(20 * $final_rownumLines);

    $worksheet->setCellValue($column . $row, $obs_type);
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('CCC0DA');
    $column++;

    // $worksheet->setCellValue($column . $row, '');
    // $worksheet->getStyle($column . $row)
    //     ->getAlignment()
    //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    // $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //     ->getStartColor()->setARGB('CCC0DA');
    // $column++;

    $worksheet->setCellValue($column . $row, !empty($ltVal->Hod) ? ucfirst($ltVal->Hod) : '-');
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $column++;

    $worksheet->setCellValue($column . $row, !empty($ltVal->obs_assigner_target_date) ? date('d-M-Y', strtotime($ltVal->obs_assigner_target_date)) : '-');
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE48F');
    $worksheet->getStyle($column . $row)
        ->getFont()
        ->setBold(true);
    $column++;

    $worksheet->setCellValue($column . $row, !empty($ltVal->closed_date) ? date('d-M-Y', strtotime($ltVal->closed_date)) : '-');
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE48F');
    $worksheet->getStyle($column . $row)
        ->getFont()
        ->setBold(true);
    $column++;

    $worksheet->setCellValue($column . $row, ucfirst($obs_open_closed_sts));

    $statusColor = $ltVal->is_closed ? 'C4D79B' : 'FCC9B2';
    $fontColor = $ltVal->is_closed ? '4F6228' : '632523';
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)
        ->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB($statusColor);
    $worksheet->getStyle($column . $row)
        ->getFont()
        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($fontColor));

    $column++;

    $worksheet->setCellValue($column . $row, $ltVal->reporter_desig);
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)
        ->getFont()
        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE))
        ->setUnderline(true);
    $column++;

    $worksheet->setCellValue($column . $row, $ltVal->reporter_name);
    $worksheet->getStyle($column . $row)
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $worksheet->getStyle($column . $row)
        ->getFont()
        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE))
        ->setUnderline(true);
    $column++;
    $worksheet->setCellValue($column . $row, $pending_days);
    $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('EEECE1');
    $column++;

    // Fill and style the 'Closure Day' cell
    $worksheet->setCellValue($column . $row, $closure_day);
    $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('EEECE1');
    $column++;

    // Fill and style the 'Delay' cell
    $worksheet->setCellValue($column . $row, $delay);
    $worksheet->getStyle($column . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('EEECE1');
    $column++;
    $row++;
}

// Apply borders to the table
$lastColumn = chr(ord('A') + count($tableHeadings) - 1);
$tableRange = "A{$startRow}:{$lastColumn}" . ($row - 1);
$worksheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Output the File
$file_name = 'Observation Tracker';
date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date("d-m-Y H-i-s");
$outputFilename = $file_name . '-' . $currentDateTime . '.xlsx';

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $outputFilename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
