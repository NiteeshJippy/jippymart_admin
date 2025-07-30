# 🚀 **BULK IMPORT USER GUIDE**

## 📋 **Table of Contents**
1. [Overview](#overview)
2. [Getting Started](#getting-started)
3. [Template Structure](#template-structure)
4. [Step-by-Step Instructions](#step-by-step-instructions)
5. [Field Descriptions](#field-descriptions)
6. [Examples](#examples)
7. [Troubleshooting](#troubleshooting)
8. [Best Practices](#best-practices)
9. [Performance Tips](#performance-tips)

---

## 🎯 **Overview**

The Bulk Import system allows you to import multiple restaurants at once using an Excel file. The system automatically:

- ✅ Validates all data before import
- ✅ Converts zone names to zone IDs
- ✅ Converts cuisine names to cuisine IDs
- ✅ Converts vendor names to vendor IDs
- ✅ Sets default values for missing fields
- ✅ Detects and prevents duplicates
- ✅ Provides detailed error reporting

---

## 🚀 **Getting Started**

### **Step 1: Access the Bulk Import**
1. Go to **Restaurants** page in your admin panel
2. Click on **"Bulk Import/Update Restaurants"** button
3. You'll see the import interface

### **Step 2: Download Template**
1. Click **"Download Template"** button
2. Save the Excel file to your computer
3. The template includes:
   - ✅ Proper column headers
   - ✅ Sample data row
   - ✅ Instructions for each field
   - ✅ Available zones and cuisines
   - ✅ Data validation rules

### **Step 3: Fill the Template**
1. Open the downloaded Excel file
2. Review the sample data and instructions
3. Replace sample data with your restaurant information
4. Save the file

### **Step 4: Upload and Import**
1. Go back to the bulk import page
2. Click **"Choose File"** and select your Excel file
3. Click **"Bulk Update"** to start the import
4. Monitor the progress and review results

---

## 📊 **Template Structure**

### **Excel Template Layout:**

| Row | Content | Description |
|-----|---------|-------------|
| 1 | Headers | Column names (bold, blue background) |
| 2 | Sample Data | Example data for reference |
| 3 | Instructions | Field descriptions and requirements |
| 5-9 | Available Options | Lists of zones and cuisines |

### **Column Structure:**

| Column | Field Name | Required | Type | Description |
|--------|------------|----------|------|-------------|
| A | title | ✅ | Text | Restaurant name |
| B | description | ✅ | Text | Restaurant description |
| C | latitude | ✅ | Number | Latitude coordinate (-90 to 90) |
| D | longitude | ✅ | Number | Longitude coordinate (-180 to 180) |
| E | location | ✅ | Text | Full address |
| F | phonenumber | ✅ | Text | Phone number (7-20 digits) |
| G | countryCode | ✅ | Dropdown | Country code (IN, US, etc.) |
| H | zoneName | ✅ | Text | Zone name (see available options) |
| I | authorName | ❌ | Text | Vendor name |
| J | authorEmail | ❌ | Email | Vendor email |
| K | categoryTitle | ✅ | Text | Category names (comma-separated) |
| L | vendorCuisineTitle | ✅ | Text | Cuisine name (see available options) |
| M | adminCommission | ❌ | JSON | Commission structure |
| N | isOpen | ❌ | Boolean | Restaurant open status |
| O | enabledDiveInFuture | ❌ | Boolean | Dine-in future enabled |
| P | restaurantCost | ❌ | Number | Restaurant cost |
| Q | openDineTime | ❌ | Time | Opening time (HH:MM) |
| R | closeDineTime | ❌ | Time | Closing time (HH:MM) |
| S | photo | ❌ | URL | Main photo URL |
| T | hidephotos | ❌ | Boolean | Hide photos |
| U | specialDiscountEnable | ❌ | Boolean | Special discount enabled |

---

## 📝 **Step-by-Step Instructions**

### **1. Prepare Your Data**

**Before filling the template:**
- ✅ Gather all restaurant information
- ✅ Verify coordinates are correct
- ✅ Check zone names against available options
- ✅ Verify cuisine names against available options
- ✅ Ensure phone numbers are in correct format
- ✅ Prepare photo URLs (if any)

### **2. Fill the Template**

**Required Fields (Must be filled):**
```excel
title: "Mastan Hotel"
description: "South Indian restaurant with authentic flavors"
latitude: 15.505723
longitude: 80.049919
location: "Grand trunk road, beside zudio, Ongole"
phonenumber: "9912871315"
countryCode: "IN"
zoneName: "Ongole"
categoryTitle: "Biryani, South Indian"
vendorCuisineTitle: "Indian"
```

**Optional Fields (Can be left empty):**
```excel
authorName: "John Doe"
authorEmail: "john@example.com"
adminCommission: '{"commissionType":"Percent","fix_commission":12,"isEnabled":true}'
isOpen: "true"
enabledDiveInFuture: "false"
restaurantCost: "250"
openDineTime: "09:30"
closeDineTime: "22:00"
photo: "https://example.com/restaurant-photo.jpg"
hidephotos: "false"
specialDiscountEnable: "false"
```

### **3. Data Validation**

The template includes built-in validation:
- ✅ **Boolean fields**: Dropdown with true/false options
- ✅ **Country codes**: Dropdown with common country codes
- ✅ **Auto-sizing**: Columns automatically adjust to content
- ✅ **Color coding**: Headers and instructions are styled

### **4. Save and Upload**

1. **Save the file** as `.xlsx` format
2. **Verify file size** (should be under 10MB)
3. **Check for errors** in Excel (red triangles indicate issues)
4. **Upload** to the bulk import system

---

## 📋 **Field Descriptions**

### **Required Fields**

#### **title**
- **Type**: Text
- **Description**: Restaurant name
- **Example**: "Mastan Hotel"
- **Validation**: Cannot be empty

#### **description**
- **Type**: Text
- **Description**: Restaurant description
- **Example**: "South Indian restaurant with authentic flavors"
- **Validation**: Cannot be empty

#### **latitude**
- **Type**: Number
- **Description**: Latitude coordinate
- **Example**: 15.505723
- **Validation**: Must be between -90 and 90

#### **longitude**
- **Type**: Number
- **Description**: Longitude coordinate
- **Example**: 80.049919
- **Validation**: Must be between -180 and 180

#### **location**
- **Type**: Text
- **Description**: Full address
- **Example**: "Grand trunk road, beside zudio, Ongole"
- **Validation**: Cannot be empty

#### **phonenumber**
- **Type**: Text
- **Description**: Phone number
- **Example**: "9912871315"
- **Validation**: 7-20 digits, can include +, -, spaces

#### **countryCode**
- **Type**: Dropdown
- **Description**: Country code
- **Example**: "IN"
- **Validation**: Must be from predefined list

#### **zoneName**
- **Type**: Text
- **Description**: Zone name (will be converted to zoneId)
- **Example**: "Ongole"
- **Validation**: Must match available zones

#### **categoryTitle**
- **Type**: Text
- **Description**: Category names
- **Example**: "Biryani, South Indian"
- **Validation**: Cannot be empty

#### **vendorCuisineTitle**
- **Type**: Text
- **Description**: Cuisine name (will be converted to vendorCuisineID)
- **Example**: "Indian"
- **Validation**: Must match available cuisines

### **Optional Fields**

#### **authorName**
- **Type**: Text
- **Description**: Vendor name
- **Example**: "John Doe"
- **Validation**: Will be looked up in users collection

#### **authorEmail**
- **Type**: Email
- **Description**: Vendor email
- **Example**: "john@example.com"
- **Validation**: Must be valid email format

#### **adminCommission**
- **Type**: JSON
- **Description**: Commission structure
- **Example**: `{"commissionType":"Percent","fix_commission":12,"isEnabled":true}`
- **Validation**: Must be valid JSON

#### **isOpen**
- **Type**: Boolean
- **Description**: Restaurant open status
- **Example**: "true"
- **Validation**: true/false dropdown

#### **enabledDiveInFuture**
- **Type**: Boolean
- **Description**: Dine-in future enabled
- **Example**: "false"
- **Validation**: true/false dropdown

#### **restaurantCost**
- **Type**: Number
- **Description**: Restaurant cost
- **Example**: "250"
- **Validation**: Must be numeric

#### **openDineTime**
- **Type**: Time
- **Description**: Opening time
- **Example**: "09:30"
- **Validation**: HH:MM format

#### **closeDineTime**
- **Type**: Time
- **Description**: Closing time
- **Example**: "22:00"
- **Validation**: HH:MM format

#### **photo**
- **Type**: URL
- **Description**: Main photo URL
- **Example**: "https://example.com/photo.jpg"
- **Validation**: Must be valid URL

#### **hidephotos**
- **Type**: Boolean
- **Description**: Hide photos
- **Example**: "false"
- **Validation**: true/false dropdown

#### **specialDiscountEnable**
- **Type**: Boolean
- **Description**: Special discount enabled
- **Example**: "false"
- **Validation**: true/false dropdown

---

## 📚 **Examples**

### **Example 1: Basic Restaurant**

```excel
title: "Mastan Hotel"
description: "South Indian restaurant with authentic flavors"
latitude: 15.505723
longitude: 80.049919
location: "Grand trunk road, beside zudio, Ongole"
phonenumber: "9912871315"
countryCode: "IN"
zoneName: "Ongole"
categoryTitle: "Biryani, South Indian"
vendorCuisineTitle: "Indian"
isOpen: "true"
enabledDiveInFuture: "false"
```

### **Example 2: Full Restaurant with All Fields**

```excel
title: "Pizza Palace"
description: "Authentic Italian pizza and pasta"
latitude: 15.12345
longitude: 80.12345
location: "123 Main Street, City Center, Ongole"
phonenumber: "+91-9876543210"
countryCode: "IN"
zoneName: "Ongole"
authorName: "Maria Rossi"
authorEmail: "maria@pizzapalace.com"
categoryTitle: "Pizza, Italian, Fast Food"
vendorCuisineTitle: "Italian"
adminCommission: '{"commissionType":"Percent","fix_commission":15,"isEnabled":true}'
isOpen: "true"
enabledDiveInFuture: "true"
restaurantCost: "300"
openDineTime: "10:00"
closeDineTime: "23:00"
photo: "https://example.com/pizza-palace.jpg"
hidephotos: "false"
specialDiscountEnable: "true"
```

### **Example 3: Multiple Categories**

```excel
title: "Global Cuisine"
description: "Multi-cuisine restaurant with international flavors"
latitude: 15.55555
longitude: 80.55555
location: "456 Food Street, Ongole"
phonenumber: "9876543210"
countryCode: "IN"
zoneName: "Ongole"
categoryTitle: "Chinese, Indian, Continental, Fast Food"
vendorCuisineTitle: "Chinese"
isOpen: "true"
enabledDiveInFuture: "false"
```

---

## 🔧 **Troubleshooting**

### **Common Errors and Solutions**

#### **1. Zone Lookup Failed**
```
Error: zoneName 'InvalidZone' not found in zone collection
```
**Solution:**
- Check the available zones in the template (row 6)
- Use exact zone names (case-sensitive)
- Common zones: Ongole, Hyderabad, Mumbai, Delhi, Bangalore

#### **2. Cuisine Lookup Failed**
```
Error: vendorCuisineTitle 'InvalidCuisine' not found in vendor_cuisines
```
**Solution:**
- Check the available cuisines in the template (row 9)
- Use exact cuisine names (case-sensitive)
- Common cuisines: Indian, Chinese, Italian, Mexican, Thai

#### **3. Invalid Coordinates**
```
Error: Latitude must be between -90 and 90 degrees
```
**Solution:**
- Verify coordinates are numeric
- Check latitude is between -90 and 90
- Check longitude is between -180 and 180

#### **4. Invalid Phone Number**
```
Error: Invalid phone number format
```
**Solution:**
- Use 7-20 digits
- Can include +, -, spaces
- Example: "9912871315" or "+91-9912871315"

#### **5. Duplicate Restaurant**
```
Error: Restaurant with title 'Mastan Hotel' and location 'Grand trunk road' already exists
```
**Solution:**
- Change restaurant name or location
- Check if restaurant already exists in system
- Use unique combinations of title and location

#### **6. Invalid Email Format**
```
Error: Invalid email format for authorEmail
```
**Solution:**
- Use valid email format: user@domain.com
- Check for typos and special characters

#### **7. Invalid JSON Format**
```
Error: Invalid adminCommission JSON format
```
**Solution:**
- Use proper JSON format
- Example: `{"commissionType":"Percent","fix_commission":12,"isEnabled":true}`
- Check quotes and brackets

### **Performance Issues**

#### **Large File Processing**
- **Issue**: File takes too long to process
- **Solution**: 
  - Split large files into smaller batches (50-100 rows each)
  - Process during off-peak hours
  - Check server resources

#### **Memory Issues**
- **Issue**: System runs out of memory
- **Solution**:
  - Reduce batch size
  - Close other applications
  - Contact system administrator

---

## ✅ **Best Practices**

### **Data Preparation**
1. **Verify all data** before import
2. **Use consistent formatting** for similar fields
3. **Test with small batches** first
4. **Backup existing data** before large imports
5. **Use exact names** for zones and cuisines

### **Template Usage**
1. **Don't modify headers** - keep exact column names
2. **Use sample data** as reference
3. **Follow instructions** in row 3
4. **Check available options** in rows 5-9
5. **Use data validation** dropdowns when available

### **Import Process**
1. **Start with small files** (5-10 restaurants)
2. **Review error messages** carefully
3. **Fix errors** and re-import
4. **Monitor progress** for large imports
5. **Keep backup** of original files

### **Quality Assurance**
1. **Verify imported data** in admin panel
2. **Check coordinates** are correct
3. **Verify zone assignments** are correct
4. **Test restaurant functionality** after import
5. **Report issues** to support team

---

## ⚡ **Performance Tips**

### **For Small Imports (1-50 restaurants)**
- ✅ Process immediately
- ✅ No special considerations needed
- ✅ Quick feedback on errors

### **For Medium Imports (50-200 restaurants)**
- ✅ Process during normal hours
- ✅ Monitor progress
- ✅ Check logs for any issues

### **For Large Imports (200+ restaurants)**
- ✅ Process during off-peak hours
- ✅ Split into smaller batches
- ✅ Monitor server resources
- ✅ Use progress tracking
- ✅ Plan for potential retries

### **Optimization Tips**
1. **Use exact matches** for zones and cuisines
2. **Provide complete data** to avoid fallback lookups
3. **Use HTTPS URLs** for photos
4. **Validate data** before import
5. **Use consistent formatting**

---

## 📞 **Support**

### **Getting Help**
- **Check this guide** for common issues
- **Review error messages** carefully
- **Test with sample data** first
- **Contact support** for complex issues

### **Useful Information to Provide**
- **Error messages** (exact text)
- **Row numbers** where errors occur
- **Sample data** that causes issues
- **File size** and number of rows
- **Browser and system** information

---

## 🎉 **Success Checklist**

Before starting your bulk import:

- ✅ [ ] Downloaded the latest template
- ✅ [ ] Reviewed available zones and cuisines
- ✅ [ ] Verified all required fields are filled
- ✅ [ ] Checked data formats (coordinates, phone, email)
- ✅ [ ] Tested with a small sample first
- ✅ [ ] Backed up existing data
- ✅ [ ] Prepared for potential errors

After import:

- ✅ [ ] Reviewed success/error messages
- ✅ [ ] Verified imported restaurants in admin panel
- ✅ [ ] Checked coordinates and zone assignments
- ✅ [ ] Tested restaurant functionality
- ✅ [ ] Reported any issues to support

---

**Happy Importing! 🚀**

This guide should help you successfully import restaurants using the bulk import system. If you encounter any issues not covered here, please contact the support team with detailed information about the problem. 