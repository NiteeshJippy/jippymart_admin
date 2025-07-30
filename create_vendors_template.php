<?php

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = [
    'A1' => 'firstName',
    'B1' => 'lastName',
    'C1' => 'email',
    'D1' => 'password',
    'E1' => 'active',
    'F1' => 'profilePictureURL',
    'G1' => 'zone', // zone name
    'H1' => 'phoneNumber',
    'I1' => 'createdAt',
];

foreach ($headers as $cell => $header) {
    $sheet->setCellValue($cell, $header);
}

$sampleData = [
    [
        'A2' => 'Vendor',
        'B2' => 'One',
        'C2' => 'vendor.one@example.com',
        'D2' => 'VendorPass123!',
        'E2' => 'TRUE',
        'F2' => 'https://images.pexels.com/vendor1.jpg',
        'G2' => 'Downtown',
        'H2' => '+1234567890',
        'I2' => '2024-05-01T10:00:00Z',
    ],
    [
        'A3' => 'Vendor',
        'B3' => 'Two',
        'C3' => 'vendor.two@example.com',
        'D3' => 'VendorPass456!',
        'E3' => 'FALSE',
        'F3' => 'https://images.pexels.com/vendor2.jpg',
        'G3' => 'North Zone',
        'H3' => '+1987654321',
        'I3' => '2024-05-02T12:00:00Z',
    ]
];

foreach ($sampleData as $rowData) {
    foreach ($rowData as $cell => $value) {
        $sheet->setCellValue($cell, $value);
    }
}

foreach (range('A', 'I') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

if (!is_dir('storage/app/templates')) {
    mkdir('storage/app/templates', 0755, true);
}

$writer = new Xlsx($spreadsheet);
$writer->save('storage/app/templates/vendors_import_template.xlsx');

echo "Vendors Excel template created successfully!\n";
echo "File: storage/app/templates/vendors_import_template.xlsx\n";
echo "Headers:\n";
foreach ($headers as $cell => $header) {
    echo "$cell: $header\n";
}
echo "Sample rows: " . count($sampleData) . "\n"; 