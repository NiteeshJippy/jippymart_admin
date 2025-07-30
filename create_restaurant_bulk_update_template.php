<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers (required and main fields)
$headers = [
    'id',
    'title',
    'author',
    'authorName',
    'categoryID',
    'categoryTitle',
    'vendorCuisineID',
    'adminCommission',
    'phonenumber',
    'countryCode',
    'location',
    'zoneId',
    'latitude',
    'longitude',
    'description',
    'isOpen',
    'enabledDiveInFuture',
    'restaurantCost',
    'openDineTime',
    'closeDineTime'
];
foreach ($headers as $col => $header) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
    $sheet->setCellValue($colLetter . '1', $header);
}

// Sample data
$data = [
    [
        'RESTAURANT_ID_1',
        'Sample Restaurant',
        'USER_ID_1',
        'John Doe',
        '["catid1","catid2"]',
        '["Indian","Chinese"]',
        'vendorCuisineId1',
        '{"commissionType":"Percent","fix_commission":10,"isEnabled":true}',
        '+1234567890',
        'IN',
        '123 Main St, City',
        'zoneId1',
        '15.12345',
        '80.12345',
        'A great restaurant.',
        'true',
        'false',
        '250',
        '09:30',
        '22:00'
    ],
    [
        'RESTAURANT_ID_2',
        'Another Restaurant',
        'USER_ID_2',
        'Jane Smith',
        '["catid3"]',
        '["Italian"]',
        'vendorCuisineId2',
        '{"commissionType":"Fixed","fix_commission":15,"isEnabled":true}',
        '+1987654321',
        'US',
        '456 Another Rd, Town',
        'zoneId2',
        '16.54321',
        '81.54321',
        'Another great place.',
        'false',
        'true',
        '300',
        '10:00',
        '23:00'
    ]
];
foreach ($data as $rowIdx => $row) {
    foreach ($row as $colIdx => $value) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1);
        $sheet->setCellValue($colLetter . ($rowIdx + 2), $value);
    }
}

$outputPath = __DIR__ . '/storage/app/templates/restaurants_bulk_update_template.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($outputPath);
echo "Template created at $outputPath\n"; 