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
$worksheet->setCellValue('E2', 'Obseravation Status');
$worksheet->getStyle('E2')->getFont()->setSize(12); // Smaller Font
$worksheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Set Logo2 (Spanning L1:N1)
$logoPath2 = PDF_IMG_PATH . 'company_logo/Logo.png';
if (file_exists($logoPath2)) {
    $logo2 = new Drawing();
    $logo2->setName('Logo2');
    $logo2->setDescription('Company Logo 2');
    $logo2->setPath($logoPath2);
    $logo2->setCoordinates('L1');
    $logo2->setHeight(70); // Adjust height as needed
    $logo2->setWorksheet($worksheet);
}


// Adjust Column Widths (A to L)
$worksheet->getColumnDimension('A')->setWidth(25);
$worksheet->getColumnDimension('B')->setWidth(15);
$worksheet->getColumnDimension('C')->setWidth(15);
$worksheet->getColumnDimension('D')->setWidth(15);
$worksheet->getColumnDimension('E')->setWidth(15);
$worksheet->getColumnDimension('F')->setWidth(15);
$worksheet->getColumnDimension('G')->setWidth(15);
$worksheet->getColumnDimension('H')->setWidth(15);
$worksheet->getColumnDimension('I')->setWidth(15);
$worksheet->getColumnDimension('J')->setWidth(15);
$worksheet->getColumnDimension('K')->setWidth(15);
$worksheet->getColumnDimension('L')->setWidth(15);

// Add "Observation Overall Count" Header
// Set Cell Values and Styles for Observation Overall Count
$worksheet->setCellValue('B6', 'Observation Overall Count');
$worksheet->getStyle('B6')->getFont()->setBold(true)->setSize(14);

// Table Headers (Open, Closed, Total)
$worksheet->setCellValue('B8', '');
$worksheet->setCellValue('C8', 'Open');
$worksheet->setCellValue('D8', 'Closed');
$worksheet->setCellValue('E8', 'Total');

// Data Rows
$worksheet->setCellValue('B9', 'Number');
$worksheet->setCellValue('C9', $overallData['open_count']);
$worksheet->setCellValue('D9', $overallData['closed_count']);
$worksheet->setCellValue('E9', $overallData['total_count']);

$worksheet->setCellValue('B10', 'Percentage');
$worksheet->setCellValue('C10', $overallData['open_percentage']);
$worksheet->setCellValue('D10', $overallData['closed_percentage']);
$worksheet->setCellValue('E10', $overallData['total_percentage']);

// Apply Bold Styling for Row Labels and Total
$worksheet->getStyle('C8:E8')->getFont()->setBold(true); // Table Headers


$worksheet->getStyle('B9:B10')->getFill()->setFillType(Fill::FILL_SOLID);
$worksheet->getStyle('B9:B10')->getFill()->getStartColor()->setRGB('D3D3D3'); // Gray color
$worksheet->getStyle('C8')->getFill()->setFillType(Fill::FILL_SOLID);
$worksheet->getStyle('C8')->getFill()->getStartColor()->setRGB('ca7c97'); 
$worksheet->getStyle('D8')->getFill()->setFillType(Fill::FILL_SOLID);
$worksheet->getStyle('D8')->getFill()->getStartColor()->setRGB('9ecc6d'); // Orange color

$worksheet->getStyle('E8:E10')->getFill()->setFillType(Fill::FILL_SOLID);
$worksheet->getStyle('E8:E10')->getFill()->getStartColor()->setRGB('D3D3D3'); // Gray color

// Center Alignment for the Data
$worksheet->getStyle('B8:E10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Add Borders to Cells (B8 to E10) excluding B8
$worksheet->getStyle('C8:E10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Optional: Apply borders to specific sides (e.g., top, bottom, left, right)
$worksheet->getStyle('C8:E9')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B9:E10')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B9:E10')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B8:E10')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('E8:E10')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
// Apply specific borders to B9 and B10
$worksheet->getStyle('B9')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B9')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B9')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B9')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

$worksheet->getStyle('B10')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B10')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B10')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B10')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);


// Remove border for B8 specifically
$worksheet->getStyle('B8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);

// Set Cell Values and Styles for Observation UA/UC Count
$worksheet->setCellValue('H6', 'Observation UA/UC Count');
$worksheet->getStyle('H6')->getFont()->setBold(true)->setSize(14);

// Table Headers (UA, UC, Total)
$worksheet->setCellValue('H8', '');
$worksheet->setCellValue('I8', 'UA');
$worksheet->setCellValue('J8', 'UC');
$worksheet->setCellValue('K8', 'Total');

// Data Rows
$worksheet->setCellValue('H9', 'Number');
$worksheet->setCellValue('I9', $overallData['ua_count']);
$worksheet->setCellValue('J9', $overallData['uc_count']);
$worksheet->setCellValue('K9', $overallData['ua_count'] + $overallData['uc_count']);

$worksheet->setCellValue('H10', 'Percentage');
$worksheet->setCellValue('I10', $overallData['ua_percentage']);
$worksheet->setCellValue('J10', $overallData['uc_percentage']);
$worksheet->setCellValue('K10', ($overallData['ua_percentage'] + $overallData['uc_percentage']) . '%');

// Apply Bold Styling for Row Labels and Total
$worksheet->getStyle('I8:K8')->getFont()->setBold(true); // Table Headers

$worksheet->getStyle('H9:H10')->getFill()->setFillType(Fill::FILL_SOLID);
$worksheet->getStyle('H9:H10')->getFill()->getStartColor()->setRGB('D3D3D3'); // Gray color

$worksheet->getStyle('I8')->getFill()->setFillType(Fill::FILL_SOLID);
$worksheet->getStyle('I8')->getFill()->getStartColor()->setRGB('ca7c97'); // Orange color
$worksheet->getStyle('J8')->getFill()->setFillType(Fill::FILL_SOLID);
$worksheet->getStyle('J8')->getFill()->getStartColor()->setRGB('9ecc6d'); // Green color

$worksheet->getStyle('K8:K10')->getFill()->setFillType(Fill::FILL_SOLID);
$worksheet->getStyle('K8:K10')->getFill()->getStartColor()->setRGB('D3D3D3'); // Gray color

// Center Alignment for the Data
$worksheet->getStyle('H8:K10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Add Borders to Cells (H8 to K10) excluding H8
$worksheet->getStyle('I8:K10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Optional: Apply borders to specific sides (e.g., top, bottom, left, right)
$worksheet->getStyle('H8:K8')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H9:K10')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H9:K10')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H8:K10')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H8:K10')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H9')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H9')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H9')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H9')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

$worksheet->getStyle('H10')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H10')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H10')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('H10')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

// Remove border for H8 specifically
$worksheet->getStyle('H8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);



// Header for "Number of days pending"
$worksheet->setCellValue('B13', 'Number of days pending(Open)');
$worksheet->getStyle('B13')->getFont()->setBold(true)->setSize(14);

// Merge Cells for Header and Set Header Text
$worksheet->mergeCells('C15:H15');
$worksheet->setCellValue('C15', 'Number of days pending');

// Style the Merged Header
$worksheet->getStyle('C15:H15')->getFont()->setBold(true)->setSize(14);
$worksheet->getStyle('C15:H15')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('C15:H15')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$worksheet->getStyle('C15:H15')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Add Column Headers
$worksheet->setCellValue('B6', ''); // Empty for Row Labels
$worksheet->setCellValue('C16', '0 - 3 days');
$worksheet->setCellValue('D16', '4 - 7 days');
$worksheet->setCellValue('E16', '7 - 14 days');
$worksheet->setCellValue('F16', '14 - 30 days');
$worksheet->setCellValue('G16', '> 30 days');
$worksheet->setCellValue('H16', 'Total');

// Style the Column Headers
$worksheet->getStyle('B16:H16')->getFont()->setBold(true);
$worksheet->getStyle('B16:H16')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('B16:H16')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B16:H16')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3'); // Light Gray Background
$worksheet->getStyle('C15:H15')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3'); // Light Gray Background



// Add Row Labels and Data
// Row 1: Number
$worksheet->setCellValue('B17', 'Number');
$worksheet->setCellValue('C17', $overallData['open_3_days_count'] ?? 0);
$worksheet->setCellValue('D17', $overallData['open_4to7_days_count'] ?? 0);
$worksheet->setCellValue('E17', $overallData['open_7to14_days_count'] ?? 0);
$worksheet->setCellValue('F17', $overallData['open_14to30_days_count'] ?? 0);
$worksheet->setCellValue('G17', $overallData['open_more_than_30_days_count'] ?? 0);
$worksheet->setCellValue('H17', $overallData['num_pending_days_count'] ?? 0);

// Row 2: Percentage
$worksheet->setCellValue('B18', 'Percentage');
$worksheet->setCellValue('C18', isset($overallData['open_3_days_percentage']) ? $overallData['open_3_days_percentage'] . "%" : "0.00%");
$worksheet->setCellValue('D18', isset($overallData['open_4to7_days_percentage']) ? $overallData['open_4to7_days_percentage'] . "%" : "0.00%");
$worksheet->setCellValue('E18', isset($overallData['open_7to14_days_percentage']) ? $overallData['open_7to14_days_percentage'] . "%" : "0.00%");
$worksheet->setCellValue('F18', isset($overallData['open_14to30_days_percentage']) ? $overallData['open_14to30_days_percentage'] . "%" : "0.00%");
$worksheet->setCellValue('G18', isset($overallData['open_more_than_30_days_percentage']) ? $overallData['open_more_than_30_days_percentage'] . "%" : "0.00%");
$worksheet->setCellValue('H18', isset($overallData['pending_days_percentage']) ? $overallData['pending_days_percentage'] . "%" : "0.00%");

// Style Row Data
$worksheet->getStyle('B17:H18')->getFont()->setBold(false);
$worksheet->getStyle('B17:H18')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('B17:H18')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Highlight the Total Column
$worksheet->getStyle('H16:H18')->getFont()->setBold(true);
$worksheet->getStyle('H16:H18')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3'); // Light Yellow Background

// Optional: Add Borders to the Entire Table
$worksheet->getStyle('B16:H18')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
 // Gray background




// Header for "Number of days Closure"
$worksheet->setCellValue('B21', 'Number of days Closure(Closed)');
$worksheet->getStyle('B21')->getFont()->setBold(true)->setSize(14);

// Merge Cells for Header and Set Header Text
$worksheet->mergeCells('C23:H23');
$worksheet->setCellValue('C23', 'Number of days to closure');

// Style the Merged Header
$worksheet->getStyle('C23:H23')->getFont()->setBold(true)->setSize(14);
$worksheet->getStyle('C23:H23')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('C23:H23')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$worksheet->getStyle('C23:H23')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Add Column Headers
$worksheet->setCellValue('B24', ''); // Empty for Row Labels
$worksheet->setCellValue('C24', '0 - 3 days');
$worksheet->setCellValue('D24', '4 - 7 days');
$worksheet->setCellValue('E24', '7 - 14 days');
$worksheet->setCellValue('F24', '14 - 30 days');
$worksheet->setCellValue('G24', '> 30 days');
$worksheet->setCellValue('H24', 'Total');

// Style the Column Headers
$worksheet->getStyle('B24:H24')->getFont()->setBold(true);
$worksheet->getStyle('B24:H24')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('B24:H24')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$worksheet->getStyle('B24:H24')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3'); // Light Gray Background
$worksheet->getStyle('C23:H23')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3'); // Light Gray Background

// Add Row Labels and Data
// Row 1: Number
$worksheet->setCellValue('B25', 'Number');
$worksheet->setCellValue('C25', $overallData['close_3_days_count'] ?? 0);
$worksheet->setCellValue('D25', $overallData['close_4to7_days_count'] ?? 0);
$worksheet->setCellValue('E25', $overallData['close_7to14_days_count'] ?? 0);
$worksheet->setCellValue('F25', $overallData['close_14to30_days_count'] ?? 0);
$worksheet->setCellValue('G25', $overallData['close_more_than_30_days_count'] ?? 0);
$worksheet->setCellValue('H25', $overallData['num_close_days_count'] ?? 0);

// Row 2: Percentage
$worksheet->setCellValue('B26', 'Percentage');
$worksheet->setCellValue('C26', isset($overallData['close_3_days_percentage']) ? $overallData['close_3_days_percentage'] . "%" : "0.00%");
$worksheet->setCellValue('D26', isset($overallData['close_4to7_days_percentage']) ? $overallData['close_4to7_days_percentage'] . "%" : "0.00%");
$worksheet->setCellValue('E26', isset($overallData['close_7to14_days_percentage']) ? $overallData['close_7to14_days_percentage'] . "%" : "0.00%");
$worksheet->setCellValue('F26', isset($overallData['close_14to30_days_percentage']) ? $overallData['close_14to30_days_percentage'] . "%" : "0.00%");
$worksheet->setCellValue('G26', isset($overallData['close_more_than_30_days_percentage']) ? $overallData['close_more_than_30_days_percentage'] . "%" : "0.00%");
$worksheet->setCellValue('H26', isset($overallData['close_days_percentage']) ? $overallData['close_days_percentage'] . "%" : "0.00%");

// Style Row Data
$worksheet->getStyle('B25:H26')->getFont()->setBold(false);
$worksheet->getStyle('B25:H26')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('B25:H26')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);


$worksheet->getStyle('H24:H26')->getFill()->setFillType(Fill::FILL_SOLID);
$worksheet->getStyle('H24:H26')->getFill()->getStartColor()->setRGB('D3D3D3'); // Gray color
// Optional: Add Borders to the Entire Table
$worksheet->getStyle('B24:H26')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);




// Add the title for the section
$worksheet->setCellValue('B31', 'Observations pending by month');
$worksheet->getStyle('B31')->getFont()->setBold(true)->setSize(14);

// Merge cells for the title row
$worksheet->mergeCells('B31:O31');
$worksheet->getStyle('B31:O31')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('B31:O31')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Add the headers
$headers = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Total'];
$worksheet->mergeCells('C32:O32'); // Merge headers across months
$worksheet->setCellValue('B32', ''); // Empty rowspan header
$worksheet->setCellValue('C32', 'Observations pending by month');

// Sub-header for individual months and total
$worksheet->fromArray(array_merge([''], $headers), null, 'B33');
$worksheet->getStyle('B32:O33')->getFont()->setBold(true);

// Set data for "Number" row
$worksheet->setCellValue('B34', 'Number');
$dataRow1 = [
    $overallData['jan_open_count'] ?? 0,
    $overallData['feb_open_count'] ?? 0,
    $overallData['mar_open_count'] ?? 0,
    $overallData['apr_open_count'] ?? 0,
    $overallData['may_open_count'] ?? 0,
    $overallData['jun_open_count'] ?? 0,
    $overallData['jul_open_count'] ?? 0,
    $overallData['aug_open_count'] ?? 0,
    $overallData['sep_open_count'] ?? 0,
    $overallData['oct_open_count'] ?? 0,
    $overallData['nov_open_count'] ?? 0,
    $overallData['dec_open_count'] ?? 0,
    $overallData['total_open_count'] ?? 0
];

$worksheet->fromArray(array_merge(['Number'], $dataRow1), null, 'B34');

// Set data for "Percentage" row
$worksheet->setCellValue('B35', 'Percentage');
$dataRow2 = [
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['jan_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['feb_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['mar_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['apr_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['may_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['jun_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['jul_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['aug_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['sep_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['oct_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['nov_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['dec_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%',
    ($overallData['total_open_count'] + $overallData['total_close_count']) > 0 
        ? round(($overallData['total_open_count'] / ($overallData['total_open_count'] + $overallData['total_close_count'])) * 100, 2) . '%' 
        : '0.00%'
];
$worksheet->fromArray(array_merge(['Percentage'], $dataRow2), null, 'B35');

// Styling
$worksheet->getStyle('B32:O35')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Center alignment
$worksheet->getStyle('B32:O35')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER); // Vertical alignment
$worksheet->getStyle('B32:O33')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3'); // Header background color
$worksheet->getStyle('O33:O35')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3'); // Highlight Total Column
$worksheet->getStyle('B34:B35')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D3D3D3'); // Highlight Total Column
$worksheet->getStyle('B32:O35')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); // Table borders
$worksheet->getStyle('C35:N35')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ca7c97'); // Highlight Total Column

// Remove border and color from B33 and B34
$worksheet->getStyle('B32')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);
$worksheet->getStyle('B32')->getFill()->setFillType(Fill::FILL_NONE);

$worksheet->getStyle('B33')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);
$worksheet->getStyle('B33')->getFill()->setFillType(Fill::FILL_NONE);






// Add custom labels (e.g., "10, 100%")
$customLabels = [
    "{$overallData['open_count']}, {$overallData['open_percentage']}%",
    "{$overallData['closed_count']}, {$overallData['closed_percentage']}%",
];


// Define DataSeries values
$dataSeriesLabels = [
    new DataSeriesValues('String', 'HSE!$A$1', null, 1), // Chart Title
];
$xAxisTickValues = [
    new DataSeriesValues('String', 'HSE!$C$8:$D$8', null, 2), // Custom labels
];
$dataSeriesValues = [
    new DataSeriesValues('Number', 'HSE!$C$9:$D$9', null, 2), // Data values
];

// Create DataSeries for the Pie Chart
$series = new DataSeries(
    DataSeries::TYPE_PIECHART_3D, // 3D Pie Chart
    null, // No grouping for pie charts
    range(0, count($dataSeriesValues) - 1), // Plot order
    $dataSeriesLabels, // Chart Title
    $xAxisTickValues,  // Custom labels
    $dataSeriesValues  // Data values
);


// Set layout for the chart
$layout = new Layout();
$layout->setShowVal(true);     // Show values
$layout->setShowPercent(true); // Show percentages

// Create PlotArea, Legend, and Title
$plotArea = new PlotArea($layout, [$series]);
$legend = new Legend(Legend::POSITION_RIGHT, null, false);
$title = new Title('Observations Status');

// Create Chart
$chart = new Chart(
    'chart1',    // Name
    $title,      // Title
    $legend,     // Legend
    $plotArea,   // Plot area
    true,        // Plot visible only
    'gap',       // Display blanks as
    null,        // X-axis label
    null         // Y-axis label
);

// Position the Chart
$chart->setTopLeftPosition('B43'); // Set the top-left corner to B43
$chart->setBottomRightPosition('H65'); // Adjust the bottom-right corner for alignment and size

// Add Chart to Worksheet
$worksheet->addChart($chart);






$customLabel2 = [
    "{$overallData['ua_count']}, {$overallData['ua_percentage']}%",
    "{$overallData['uc_count']}, {$overallData['uc_percentage']}%",
];

// Define DataSeries values
$dataSeriesLabels2 = [
    new DataSeriesValues('String', 'HSE!$A$1', null, 1), // Chart Title
];
$xAxisTickValues2 = [
    new DataSeriesValues('String', 'HSE!$I$8:$J$8', null, 2), // Custom labels
];
$dataSeriesValues2 = [
    new DataSeriesValues('Number', 'HSE!$I$9:$J$9', null, 2), // Data values
];

// Create DataSeries for the Pie Chart
$series2 = new DataSeries(
    DataSeries::TYPE_PIECHART_3D, // 3D Pie Chart
    null, // No grouping for pie charts
    range(0, count($dataSeriesValues2) - 1), // Plot order
    $dataSeriesLabels2, // Chart Title
    $xAxisTickValues2,  // Custom labels
    $dataSeriesValues2  // Data values
);


// Set layout for the chart
$layout2 = new Layout();
$layout2->setShowVal(true);     // Show values
$layout2->setShowPercent(true); // Show percentages

// Create PlotArea, Legend, and Title
$plotArea2 = new PlotArea($layout2, [$series2]);
$legend2 = new Legend(Legend::POSITION_RIGHT, null, false);
$title2 = new Title('UA,UC Status');

// Create Chart
$chart2 = new Chart(
    'chart1',    // Name
    $title2,      // Title
    $legend2,     // Legend
    $plotArea2,   // Plot area
    true,        // Plot visible only
    'gap',       // Display blanks as
    null,        // X-axis label
    null         // Y-axis label
);

// Position the Chart
$chart2->setTopLeftPosition('I43'); // Set the top-left corner to B43
$chart2->setBottomRightPosition('O65'); // Adjust the bottom-right corner for alignment and size

// Add Chart to Worksheet
$worksheet->addChart($chart2);




// Use worksheet references for DataSeriesValues
$dataSeriesLabels3 = [new DataSeriesValues('String', 'HSE!$H$16', null, 1)]; // Series Label
$xAxisTickValues3 = [new DataSeriesValues('String', 'HSE!$C$16:$G$16', null, 5)]; // Categories
$dataSeriesValues3 = [new DataSeriesValues('Number', 'HSE!$C$17:$G$17', null, 5)]; // Data Values

// Create DataSeries for the bar chart
$series3 = new DataSeries(
    DataSeries::TYPE_BARCHART,      // Chart type
    DataSeries::GROUPING_CLUSTERED, // Chart grouping (e.g., clustered)
    range(0, count($dataSeriesValues3) - 1), // Plot order
    $dataSeriesLabels3,             // Series labels
    $xAxisTickValues3,              // X-Axis values
    $dataSeriesValues3              // Y-Axis values
);

// Set the series to plot as a bar chart with horizontal orientation
$series3->setPlotDirection(DataSeries::DIRECTION_COL);

// Create a layout for the chart
$layout3 = new Layout();
$layout3->setShowVal(true); // Show values on bars

// Create a PlotArea
$plotArea3 = new PlotArea($layout3, [$series3]);

// Create a Legend
$legend3 = new Legend(Legend::POSITION_RIGHT, null, false);

// Add a Title
$title3 = new Title('Number of days pending(Open)');

// Create the Chart
$chart3 = new Chart(
    'PendingDaysChart',  // Chart name
    $title3,              // Title
    $legend3,             // Legend
    $plotArea3,           // PlotArea
    true,                // Plot visible only
    'gap',               // Display blanks as
    null,                // X-Axis label
    null                 // Y-Axis label
);

// Set chart position in the worksheet
$chart3->setTopLeftPosition('B70'); // Adjusted to start at B70
$chart3->setBottomRightPosition('H90'); // Adjusted to ensure the size fits within H90

// Add the chart to the worksheet
$worksheet->addChart($chart3);








// Use worksheet references for DataSeriesValues
$dataSeriesLabels4 = [new DataSeriesValues('String', 'HSE!$H$24', null, 1)]; // Series Label
$xAxisTickValues4 = [new DataSeriesValues('String', 'HSE!$C$24:$G$24', null, 5)]; // Categories
$dataSeriesValues4 = [new DataSeriesValues('Number', 'HSE!$C$25:$G$25', null, 5)]; // Data Values

// Create DataSeries for the bar chart
$series4 = new DataSeries(
    DataSeries::TYPE_BARCHART,      // Chart type
    DataSeries::GROUPING_CLUSTERED, // Chart grouping (e.g., clustered)
    range(0, count($dataSeriesValues4) - 1), // Plot order
    $dataSeriesLabels4,             // Series labels
    $xAxisTickValues4,              // X-Axis values
    $dataSeriesValues4              // Y-Axis values
);

// Set the series to plot as a bar chart with horizontal orientation
$series4->setPlotDirection(DataSeries::DIRECTION_COL);

// Create a layout for the chart
$layout4 = new Layout();
$layout4->setShowVal(true); // Show values on bars

// Create a PlotArea
$plotArea4 = new PlotArea($layout4, [$series4]);

// Create a Legend
$legend4 = new Legend(Legend::POSITION_RIGHT, null, false);

// Add a Title
$title4 = new Title('Number of days to closure(Closed)');

// Create the Chart
$chart4 = new Chart(
    'Number of days to closure',  // Chart name
    $title4,              // Title
    $legend4,             // Legend
    $plotArea4,           // PlotArea
    true,                // Plot visible only
    'gap',               // Display blanks as
    null,                // X-Axis label
    null                 // Y-Axis label
);

// Set chart position in the worksheet
$chart4->setTopLeftPosition('I70'); // Adjusted to start at B70
$chart4->setBottomRightPosition('O90'); // Adjusted to ensure the size fits within H90

// Add the chart to the worksheet
$worksheet->addChart($chart4);



// Output the File
$file_name = 'Observation Status';
date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date("d-m-Y H-i-s");
$outputFilename = $file_name . '-' . $currentDateTime . '.xlsx';


$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->setIncludeCharts(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $outputFilename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
