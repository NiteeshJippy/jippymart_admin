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
    'F1' => 'role',
    'G1' => 'profilePictureURL',
    'H1' => 'createdAt',
];

foreach ($headers as $cell => $header) {
    $sheet->setCellValue($cell, $header);
}

$sampleData = [
    [
        'A2' => 'John',
        'B2' => 'Doe',
        'C2' => 'john.doe@example.com',
        'D2' => 'Password123!',
        'E2' => 'TRUE',
        'F2' => 'customer',
        'G2' => 'https://images.pexels.com/profile1.jpg',
        'H2' => '2024-05-01T10:00:00Z',
    ],
    [
        'A3' => 'Jane',
        'B3' => 'Smith',
        'C3' => 'jane.smith@example.com',
        'D3' => 'Secret456!',
        'E3' => 'FALSE',
        'F3' => 'admin',
        'G3' => 'https://images.pexels.com/profile2.jpg',
        'H3' => '2024-05-02T12:00:00Z',
    ]
];

foreach ($sampleData as $rowData) {
    foreach ($rowData as $cell => $value) {
        $sheet->setCellValue($cell, $value);
    }
}

foreach (range('A', 'H') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

if (!is_dir('storage/app/templates')) {
    mkdir('storage/app/templates', 0755, true);
}

$writer = new Xlsx($spreadsheet);
$writer->save('storage/app/templates/users_import_template.xlsx');

echo "Users Excel template created successfully!\n";
echo "File: storage/app/templates/users_import_template.xlsx\n";
echo "Headers:\n";
foreach ($headers as $cell => $header) {
    echo "$cell: $header\n";
}
echo "Sample rows: " . count($sampleData) . "\n"; 