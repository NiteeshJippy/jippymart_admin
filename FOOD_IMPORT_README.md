# Food Excel Bulk Import Feature

## Overview
This feature allows administrators to bulk import food items from Excel files into the system. The import functionality validates data, checks for existing vendors and categories, and provides detailed error reporting.

## Features Implemented

### 1. Controller Methods (`app/Http/Controllers/FoodController.php`)
- **`import(Request $request)`**: Handles Excel file upload and data import
- **`downloadTemplate()`**: Provides downloadable Excel template

### 2. Routes (`routes/web.php`)
- `POST /foods/import` - Handle file upload and import
- `GET /foods/download-template` - Download Excel template

### 3. UI Components (`resources/views/foods/index.blade.php`)
- Import button in the food listing page
- Download template button
- Modal dialog for file upload
- Success/error message display

### 4. Translation Keys (`resources/lang/en/lang.php`)
Added new translation keys for import functionality:
- `import`
- `download_template`
- `import_foods`
- `select_file`
- `import_file_help`
- `import_instructions`
- `import_instruction_1` through `import_instruction_4`

## Excel Template Structure

The template includes the following columns:
- **name** (required): Food item name
- **price** (required): Base price
- **description**: Food description
- **vendorID** (required): Vendor/restaurant ID (must exist in system)
- **categoryID** (required): Category ID (must exist in system)
- **disPrice**: Discounted price
- **publish**: Published status (true/false)
- **nonveg**: Non-vegetarian flag (true/false)
- **isAvailable**: Availability status (true/false)
- **quantity**: Item quantity (-1 for unlimited)
- **calories**: Calorie content
- **grams**: Weight in grams
- **proteins**: Protein content
- **fats**: Fat content

## Validation Rules

### Required Fields
- name
- price
- vendorID
- categoryID

### Data Validation
- Vendor ID must exist in the `vendors` collection
- Category ID must exist in the `vendor_categories` collection
- Price and discount price must be numeric
- Boolean fields (publish, nonveg, isAvailable) must be "true" or "false"
- Numeric fields (calories, grams, proteins, fats) are converted to integers

## Error Handling

The import process provides detailed error reporting:
- Missing required fields
- Invalid vendor or category IDs
- Data type validation errors
- Row-specific error messages with row numbers

## Usage Instructions

1. **Download Template**: Click the "Download Template" button to get the Excel template
2. **Prepare Data**: Fill in the template with food data, ensuring all required fields are completed
3. **Upload File**: Click the "Import" button and select the prepared Excel file
4. **Review Results**: Check the success/error messages displayed after import

## Technical Implementation

### Dependencies
- `phpoffice/phpspreadsheet` - Excel file processing
- `google/cloud-firestore` - Firestore database operations

### Database Operations
- Creates new documents in the `vendor_products` collection
- Validates against existing `vendors` and `vendor_categories` collections
- Sets proper timestamps and metadata

### Security
- File type validation (.xlsx, .xls only)
- CSRF protection on upload form
- Authentication middleware on all routes

## File Locations

- Controller: `app/Http/Controllers/FoodController.php`
- Routes: `routes/web.php`
- View: `resources/views/foods/index.blade.php`
- Template: `storage/app/templates/foods_import_template.xlsx`
- Translations: `resources/lang/en/lang.php`

## Future Enhancements

Potential improvements for the future:
1. Batch processing for large files
2. Progress indicators for long imports
3. Preview functionality before import
4. Support for image URLs in Excel
5. Export functionality for existing foods
6. Import validation preview 