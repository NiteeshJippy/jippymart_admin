<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers to match exactly what the application expects
$headers = [
    'id',                       // Optional: Restaurant ID (for updates)
    'title',                    // Required: Restaurant name
    'description',              // Required: Restaurant description
    'latitude',                 // Required: Latitude coordinate (-90 to 90)
    'longitude',                // Required: Longitude coordinate (-180 to 180)
    'location',                 // Required: Address
    'phonenumber',              // Required: Phone number
    'countryCode',              // Required: Country code (e.g., "IN")
    'zoneName',                 // Required: Zone name (will be converted to zoneId)
    'authorName',               // Optional: Vendor name (will be converted to author ID)
    'authorEmail',              // Optional: Vendor email (alternative to authorName)
    'categoryTitle',            // Required: Category names (comma-separated or JSON array)
    'vendorCuisineTitle',       // Required: Vendor cuisine name (will be converted to vendorCuisineID)
    'adminCommission',          // Optional: Commission structure (JSON string)
    'isOpen',                   // Optional: Restaurant open status (true/false)
    'enabledDiveInFuture',      // Optional: Dine-in future enabled (true/false)
    'restaurantCost',           // Optional: Restaurant cost (number)
    'openDineTime',             // Optional: Opening time (HH:MM format)
    'closeDineTime',            // Optional: Closing time (HH:MM format)
    'photo',                    // Optional: Main photo URL
    'hidephotos',               // Optional: Hide photos (true/false)
    'specialDiscountEnable'     // Optional: Special discount enabled (true/false)
];

// Set headers
foreach ($headers as $colIndex => $header) {
    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
    $sheet->setCellValue($column . '1', $header);
    
    // Style headers
    $sheet->getStyle($column . '1')->getFont()->setBold(true);
    $sheet->getStyle($column . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $sheet->getStyle($column . '1')->getFill()->getStartColor()->setRGB('E6E6FA');
}

// Sample data that matches the application's expected format
$sampleData = [
    '',                                  // id (leave empty for new restaurants)
    'Sample Restaurant',                 // title
    'A great restaurant with delicious food', // description
    '15.12345',                         // latitude
    '80.12345',                         // longitude
    '123 Main Street, City, State',     // location
    '1234567890',                       // phonenumber
    'IN',                               // countryCode
    'Ongole',                           // zoneName
    'Vendor One',                       // authorName
    'vendor@example.com',               // authorEmail
    'Biryani, Pizza',                   // categoryTitle
    'Indian',                           // vendorCuisineTitle
    '{"commissionType":"Percent","fix_commission":12,"isEnabled":true}', // adminCommission
    'true',                             // isOpen
    'false',                            // enabledDiveInFuture
    '250',                              // restaurantCost
    '09:30',                            // openDineTime
    '22:00',                            // closeDineTime
    'https://example.com/restaurant-photo.jpg', // photo
    'false',                            // hidephotos
    'false'                             // specialDiscountEnable
];

// Add sample data row
foreach ($sampleData as $colIndex => $value) {
    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
    $sheet->setCellValue($column . '2', $value);
}

// Add instructions row
$instructions = [
    'Restaurant ID (optional - leave empty for new restaurants)', // id
    'Restaurant name (required)',           // title
    'Restaurant description (required)',    // description
    'Latitude coordinate -90 to 90 (required)', // latitude
    'Longitude coordinate -180 to 180 (required)', // longitude
    'Full address (required)',              // location
    'Phone number 7-20 digits (required)',  // phonenumber
    'Country code like IN, US (required)',  // countryCode
    'Zone name like Ongole, Hyderabad (required)', // zoneName
    'Vendor name (optional)',               // authorName
    'Vendor email (optional)',              // authorEmail
    'Category names separated by comma (required)', // categoryTitle
    'Cuisine name like Indian, Chinese (required)', // vendorCuisineTitle
    'JSON format commission (optional)',    // adminCommission
    'true/false for open status (optional)', // isOpen
    'true/false for dine-in future (optional)', // enabledDiveInFuture
    'Restaurant cost number (optional)',    // restaurantCost
    'Opening time HH:MM format (optional)', // openDineTime
    'Closing time HH:MM format (optional)', // closeDineTime
    'Photo URL (optional)',                 // photo
    'true/false to hide photos (optional)', // hidephotos
    'true/false for special discount (optional)' // specialDiscountEnable
];

foreach ($instructions as $colIndex => $instruction) {
    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
    $sheet->setCellValue($column . '3', $instruction);
    
    // Style instructions
    $sheet->getStyle($column . '3')->getFont()->setItalic(true);
    $sheet->getStyle($column . '3')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('666666'));
}

// Add a help sheet with validation rules
$helpSheet = $spreadsheet->createSheet();
$helpSheet->setTitle('Validation Help');
$helpSheet->setCellValue('A1', 'Column Validation Rules');
$helpSheet->setCellValue('A2', 'isOpen');
$helpSheet->setCellValue('B2', 'Acceptable values: true, false, 1, 0, yes, no');
$helpSheet->setCellValue('A3', 'enabledDiveInFuture');
$helpSheet->setCellValue('B3', 'Acceptable values: true, false, 1, 0, yes, no');
$helpSheet->setCellValue('A4', 'hidephotos');
$helpSheet->setCellValue('B4', 'Acceptable values: true, false, 1, 0, yes, no');
$helpSheet->setCellValue('A5', 'specialDiscountEnable');
$helpSheet->setCellValue('B5', 'Acceptable values: true, false, 1, 0, yes, no');
$helpSheet->setCellValue('A6', 'adminCommission');
$helpSheet->setCellValue('B6', 'JSON format: {"commissionType":"Percent","fix_commission":10,"isEnabled":true}');
$helpSheet->setCellValue('A7', 'categoryTitle');
$helpSheet->setCellValue('B7', 'Comma-separated: Biryani, Pizza or JSON: ["Biryani","Pizza"]');
$helpSheet->setCellValue('A8', 'phonenumber');
$helpSheet->setCellValue('B8', '7-20 digits, can include +, -, spaces');
$helpSheet->setCellValue('A9', 'authorEmail');
$helpSheet->setCellValue('B9', 'Valid email format: vendor@example.com');
$helpSheet->setCellValue('A10', 'photo');
$helpSheet->setCellValue('B10', 'Valid URL: https://example.com/photo.jpg');
$helpSheet->setCellValue('A11', 'restaurantCost');
$helpSheet->setCellValue('B11', 'Numeric value: 250');
$helpSheet->setCellValue('A12', 'openDineTime/closeDineTime');
$helpSheet->setCellValue('B12', 'HH:MM format: 09:30, 22:00');

// Set the main sheet as active
$spreadsheet->setActiveSheetIndex(0);

$outputPath = __DIR__ . '/storage/app/templates/restaurants_bulk_update_template.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($outputPath);
echo "Template created at $outputPath\n"; echo "This template matches the application's expected format exactly.\n";
echo "All validation errors should be resolved with this template.\n"; 
