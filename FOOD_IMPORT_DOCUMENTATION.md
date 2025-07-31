# Food Excel Bulk Import Feature Documentation

## Overview

The Food Excel Bulk Import feature allows administrators to import multiple food items into the system using an Excel file. This feature has been enhanced with **name-to-ID lookup functionality**, making it much more user-friendly by allowing users to specify vendor and category names instead of requiring exact Firestore document IDs.

## Key Features

### ✅ Enhanced User Experience
- **Name-to-ID Lookup**: Use vendor names and category names instead of IDs
- **Dual Support**: Works with both IDs and names for maximum flexibility
- **Professional Template**: Enhanced Excel template with clear instructions
- **Comprehensive Validation**: Robust error handling and data validation
- **User-Friendly UI**: Clean interface with helpful tips and guidance

### ✅ Technical Robustness
- **Data Integrity**: Validates all required fields and data types
- **Referential Integrity**: Ensures vendors and categories exist before import
- **Error Handling**: Detailed error reporting for troubleshooting
- **Type Safety**: Proper type casting for all data fields
- **Transaction Safety**: Individual row processing with partial success support

## Workflow

1. **Download Template**: Click "Download Template" to get the enhanced Excel file
2. **Prepare Data**: Fill the template with food data (can use names or IDs)
3. **Upload File**: Select the filled Excel file and click "Import Foods"
4. **Processing**: System validates and imports data with detailed feedback
5. **Results**: Success/error messages displayed with import statistics

## Excel Template Structure

The enhanced template (`foods_import_template.xlsx`) contains:

| Column | Field | Required | Type | Description | Example |
|--------|-------|----------|------|-------------|---------|
| A | name | Yes | Text | Food item name | "Chicken Burger" |
| B | price | Yes | Number | Base price | 12.99 |
| C | description | No | Text | Food description | "Delicious chicken burger" |
| D | vendorID | Yes | Text | **Vendor ID or Name** | "Restaurant ABC" or "vendor_123" |
| E | categoryID | Yes | Text | **Category ID or Name** | "Fast Food" or "category_456" |
| F | disPrice | No | Number | Discount price | 10.99 |
| G | publish | No | Boolean | Published status | true/false |
| H | nonveg | No | Boolean | Non-vegetarian flag | true/false |
| I | isAvailable | No | Boolean | Availability status | true/false |
| J | photo | No | URL | Main photo URL | https://example.com/food.jpg |

**Auto-Generated Fields (set by system):**
| quantity | Auto | Number | Stock quantity (Default: -1) | -1 |
| calories | Auto | Number | Calorie content (Default: 0) | 0 |
| grams | Auto | Number | Weight in grams (Default: 0) | 0 |
| proteins | Auto | Number | Protein content (Default: 0) | 0 |
| fats | Auto | Number | Fat content (Default: 0) | 0 |
| takeawayOption | Auto | Boolean | Takeaway availability (Default: false) | false |

## Name-to-ID Lookup Feature

### How It Works

The system implements intelligent lookup for vendors and categories:

1. **Primary Check**: First tries to match the input as a direct Firestore document ID
2. **Fallback Lookup**: If not found as ID, searches by name in the respective collections
3. **Validation**: Ensures the resolved ID exists before proceeding with import

### Supported Input Formats

**Vendor Field:**
- ✅ Vendor ID: `vendor_123abc`
- ✅ Vendor Name: `Restaurant ABC`
- ✅ Case-insensitive name matching

**Category Field:**
- ✅ Category ID: `category_456def`
- ✅ Category Name: `Fast Food`
- ✅ Case-insensitive name matching

### Example Usage

```excel
| name          | vendorID        | categoryID    |
|---------------|-----------------|---------------|
| Chicken Burger| Restaurant ABC  | Fast Food     |
| Veg Pizza     | Pizza Palace    | Italian Food  |
| Sushi Roll    | vendor_123      | category_456  |
```

## Data Validation Rules

### Required Fields
- **name**: Must not be empty
- **price**: Must be a valid number > 0
- **vendorID**: Must exist as ID or name in `vendors` collection
- **categoryID**: Must exist as ID or name in `vendor_categories` collection

### Optional Fields with Defaults
- **description**: Defaults to empty string
- **disPrice**: Defaults to empty string (no discount)
- **publish**: Defaults to `true`
- **nonveg**: Defaults to `false`
- **isAvailable**: Defaults to `true`
- **photo**: Defaults to empty string

### Auto-Generated Fields (set by system)
- **quantity**: Always set to `-1` (unlimited)
- **calories**: Always set to `0`
- **grams**: Always set to `0`
- **proteins**: Always set to `0`
- **fats**: Always set to `0`
- **takeawayOption**: Always set to `false`

### Boolean Field Support
The system accepts multiple formats for boolean fields:
- ✅ `true`, `false`
- ✅ `1`, `0`
- ✅ `yes`, `no`
- ✅ `on`, `off`

## Firestore Collections Used

### Primary Collection
- **`vendor_products`**: Stores the imported food items

### Validation Collections
- **`vendors`**: Validates vendor existence and performs name lookups
- **`vendor_categories`**: Validates category existence and performs name lookups

### Data Structure
```javascript
{
  name: "Food Name",
  price: 12.99,
  description: "Food description",
  vendorID: "resolved_vendor_id",
  categoryID: "resolved_category_id",
  disPrice: 10.99,
  publish: true,
  nonveg: false,
  veg: true, // Auto-generated (opposite of nonveg)
  isAvailable: true,
  quantity: -1, // Auto-generated (unlimited)
  calories: 0, // Auto-generated
  grams: 0, // Auto-generated
  proteins: 0, // Auto-generated
  fats: 0, // Auto-generated
  takeawayOption: false, // Auto-generated
  photo: "",
  photos: [],
  addOnsTitle: [], // Fixed spelling
  addOnsPrice: [], // Fixed spelling
  sizeTitle: [],
  sizePrice: [],
  attributes: [],
  variants: [],
  product_specification: null,
  item_attribute: null,
  reviewAttributes: null,
  reviewsCount: 0,
  reviewsSum: 0,
  migratedBy: "excel_import",
  createdAt: Timestamp, // Fixed naming
  updated_at: Timestamp,
  id: "auto_generated_document_id"
}
```

## Error Handling

### Validation Errors
- Missing required fields
- Invalid data types
- Non-existent vendors/categories
- Empty or malformed data

### Error Reporting
- Row-specific error messages
- Detailed error descriptions
- Import statistics (successful vs failed rows)
- User-friendly error display

### Example Error Messages
```
"Row 3: Vendor 'Non-existent Restaurant' not found (neither as ID nor name)"
"Row 5: Missing required fields (name, price, vendorID, categoryID)"
"Row 7: Category 'Invalid Category' not found (neither as ID nor name)"
```

## Performance Considerations

### Current Implementation
- **O(n) Firestore calls** per import
- Individual row processing
- Real-time validation for each row

### Optimization Opportunities
- **Batch validation**: Pre-load all vendors/categories
- **Batch writes**: Use Firestore batch operations
- **Caching**: Cache vendor/category lookups

## Security Features

### Input Validation
- File type validation (.xlsx, .xls only)
- Authentication middleware protection
- Input sanitization and type casting
- SQL injection prevention (Firestore)

### Data Integrity
- Referential integrity checks
- Type safety enforcement
- Error logging and monitoring

## Usage Instructions

### For Administrators
1. Navigate to the Foods page
2. Click "Download Template" to get the enhanced Excel file
3. Open the template and review the "Instructions" sheet
4. Fill in your food data (use names or IDs for vendors/categories)
5. Remove sample data rows before importing
6. Save the file and upload via the import form
7. Review success/error messages

### Best Practices
- **Use names** for vendors and categories when possible (more user-friendly)
- **Test with small datasets** first
- **Backup existing data** before large imports
- **Review error messages** carefully for data quality issues
- **Validate data** in the template before importing

## Technical Implementation

### Key Methods
- `import()`: Main import processing method
- `findVendorByName()`: Vendor name lookup
- `findCategoryByName()`: Category name lookup
- `resolveVendorID()`: Vendor ID resolution (ID or name)
- `resolveCategoryID()`: Category ID resolution (ID or name)

### Dependencies
- **PHPSpreadsheet**: Excel file processing
- **Google Cloud Firestore**: Database operations
- **Laravel Framework**: Web framework and validation

## Future Enhancements

### Planned Features
- **Duplicate detection**: Prevent importing same food multiple times
- **Batch processing**: Improve performance for large imports
- **Advanced validation**: More comprehensive data format validation
- **Import history**: Track and review previous imports
- **Template customization**: Allow custom field mappings

### Potential Improvements
- **Real-time validation**: Validate data as user types in template
- **Bulk operations**: Support for update/delete operations
- **Data transformation**: Automatic data formatting and cleaning
- **Import scheduling**: Background processing for large files

## Troubleshooting

### Common Issues
1. **"Vendor not found"**: Ensure vendor exists in system or check spelling
2. **"Category not found"**: Ensure category exists in system or check spelling
3. **"Missing required fields"**: Check all required columns are filled
4. **"Invalid data type"**: Ensure numbers are numeric, booleans are true/false

### Debug Steps
1. Check error messages for specific row numbers
2. Verify vendor and category names/IDs exist in system
3. Review data types in Excel template
4. Test with minimal data first

## Support

For technical support or feature requests, please refer to the development team or create an issue in the project repository.

---

**Version**: 2.0 (Enhanced with Name-to-ID Lookup)  
**Last Updated**: December 2024  
**Compatibility**: Laravel 8+, PHP 7.4+, Google Cloud Firestore 