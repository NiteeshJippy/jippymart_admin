<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DynamicEmail;

class RestaurantController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

	  public function index()
    {

        return view("restaurants.index");
    }

    public function vendors()
    {
        return view("vendors.index");
    }


    public function edit($id)
    {
    	    return view('restaurants.edit')->with('id',$id);
    }

    public function vendorEdit($id)
    {
    	    return view('vendors.edit')->with('id',$id);
    }

    public function vendorSubscriptionPlanHistory($id='')
    {
    	    return view('subscription_plans.history')->with('id',$id);
    }

    public function view($id)
    {
        return view('restaurants.view')->with('id',$id);
    }

    public function plan($id)
    {

        return view("restaurants.plan")->with('id',$id);
    }

    public function payout($id)
    {
        return view('restaurants.payout')->with('id',$id);
    }

    public function foods($id)
    {
        return view('restaurants.foods')->with('id',$id);
    }

    public function orders($id)
    {
        return view('restaurants.orders')->with('id',$id);
    }

    public function reviews($id)
    {
        return view('restaurants.reviews')->with('id',$id);
    }

    public function promos($id)
    {
        return view('restaurants.promos')->with('id',$id);
    }

    public function vendorCreate(){
        return view('vendors.create');
    }

    public function create(){
        return view('restaurants.create');
    }

    public function DocumentList($id){
        return view("vendors.document_list")->with('id',$id);
    }

    public function DocumentUpload($vendorId, $id)
    {
        return view("vendors.document_upload", compact('vendorId', 'id'));
    }
    public function currentSubscriberList($id)
    {
        return view("subscription_plans.current_subscriber", compact( 'id'));
    }

    public function importVendors(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $spreadsheet = IOFactory::load($request->file('file'));
        $rows = $spreadsheet->getActiveSheet()->toArray();

        if (empty($rows) || count($rows) < 2) {
            return back()->withErrors(['file' => 'The uploaded file is empty or missing data.']);
        }

        $headers = array_map('trim', array_shift($rows));
        $firestore = new FirestoreClient([
            'projectId' => config('firestore.project_id'),
            'keyFilePath' => config('firestore.credentials'),
        ]);
        $collection = $firestore->collection('users');
        $zoneCollection = $firestore->collection('zone');
        $imported = 0;
        $errors = [];
        foreach ($rows as $rowIndex => $row) {
            $rowNum = $rowIndex + 2; // Excel row number
            $data = array_combine($headers, $row);
            // Required fields
            if (empty($data['firstName']) || empty($data['lastName']) || empty($data['email']) || empty($data['password'])) {
                $errors[] = "Row $rowNum: Missing required fields.";
                continue;
            }
            // Email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row $rowNum: Invalid email format.";
                continue;
            }
            // Duplicate email check
            $existing = $collection->where('email', '=', $data['email'])->limit(1)->documents();
            if (!$existing->isEmpty()) {
                $errors[] = "Row $rowNum: Email already exists.";
                continue;
            }
            // Phone number (basic)
            if (!empty($data['phoneNumber']) && !preg_match('/^[+0-9\- ]{7,20}$/', $data['phoneNumber'])) {
                $errors[] = "Row $rowNum: Invalid phone number format.";
                continue;
            }
            // zone name to zoneId lookup
            $zoneId = '';
            if (!empty($data['zone'])) {
                $zoneDocs = $zoneCollection->where('name', '=', $data['zone'])->limit(1)->documents();
                if ($zoneDocs->isEmpty()) {
                    $errors[] = "Row $rowNum: zone '{$data['zone']}' does not exist.";
                    continue;
                } else {
                    $zoneId = $zoneDocs->rows()[0]['id'];
                }
            }
            $vendorData = [
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'active' => strtolower($data['active'] ?? '') === 'true',
                'role' => 'vendor',
                'profilePictureURL' => $data['profilePictureURL'] ?? '',
                'zoneId' => $zoneId,
                'phoneNumber' => $data['phoneNumber'] ?? '',
                'migratedBy' => 'migrate:vendors',
            ];
            if (!empty($data['createdAt'])) {
                try {
                    $vendorData['createdAt'] = new \Google\Cloud\Core\Timestamp(Carbon::parse($data['createdAt']));
                } catch (\Exception $e) {
                    $vendorData['createdAt'] = new \Google\Cloud\Core\Timestamp(now());
                }
            } else {
                $vendorData['createdAt'] = new \Google\Cloud\Core\Timestamp(now());
            }
            $docRef = $collection->add($vendorData);
            $docRef->set(['id' => $docRef->id()], ['merge' => true]);
            $imported++;
            // Send welcome email
            try {
                Mail::to($data['email'])->send(new DynamicEmail([
                    'subject' => 'Welcome to JippyMart!',
                    'body' => "Hi {$data['firstName']},<br><br>Welcome to JippyMart! Your account has been created.<br><br>Email: {$data['email']}<br>Password: (the password you provided)<br><br>Login at: <a href='" . url('/') . "'>JippyMart Admin</a><br><br>Thank you!"
                ]));
            } catch (\Exception $e) {
                $errors[] = "Row $rowNum: Failed to send email (" . $e->getMessage() . ")";
            }
        }
        $msg = "Vendors imported successfully! ($imported rows)";
        if (!empty($errors)) {
            $msg .= "<br>Some issues occurred:<br>" . implode('<br>', $errors);
        }
        if ($imported === 0) {
            return back()->withErrors(['file' => $msg]);
        }
        return back()->with('success', $msg);
    }

    public function downloadVendorsTemplate()
    {
        $filePath = storage_path('app/templates/vendors_import_template.xlsx');
        if (!file_exists($filePath)) {
            abort(404, 'Template file not found');
        }
        return response()->download($filePath, 'vendors_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="vendors_import_template.xlsx"'
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        
        // Get options for handling invalid rows
        $skipInvalidRows = $request->input('skip_invalid_rows', false);
        
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('file'));
        $rows = $spreadsheet->getActiveSheet()->toArray();
        
        if (empty($rows) || count($rows) < 2) {
            return back()->withErrors(['file' => 'The uploaded file is empty or missing data.']);
        }
        
        $headers = array_map('trim', array_shift($rows));
        
        // Validate headers
        $requiredHeaders = ['title', 'description', 'latitude', 'longitude', 'location', 'phonenumber', 'countryCode'];
        $missingHeaders = array_diff($requiredHeaders, $headers);
        
        if (!empty($missingHeaders)) {
            return back()->withErrors(['file' => 'Missing required columns: ' . implode(', ', $missingHeaders) . 
                '. Please use the template provided by the "Download Template" button.']);
        }
        
        $firestore = new \Google\Cloud\Firestore\FirestoreClient([
            'projectId' => config('firestore.project_id'),
            'keyFilePath' => config('firestore.credentials'),
        ]);
        $collection = $firestore->collection('vendors');
        
        // Batch processing configuration
        $batchSize = 50; // Process 50 rows at a time
        $totalRows = count($rows);
        $batches = array_chunk($rows, $batchSize);
        
        $created = 0;
        $updated = 0;
        $failed = 0;
        $skippedRows = 0;
        $processedRows = 0;
        
        // Pre-load lookup data for better performance
        $lookupData = $this->preloadLookupData($firestore);
        
        foreach ($batches as $batchIndex => $batch) {
            $batchCreated = 0;
            $batchUpdated = 0;
            $batchFailed = 0;
            
            foreach ($batch as $rowIndex => $row) {
                $globalRowIndex = $batchIndex * $batchSize + $rowIndex;
                $rowNum = $globalRowIndex + 2; // Excel row number
                $data = array_combine($headers, $row);
                
                // Skip completely empty rows
                if ($this->isEmptyRow($row)) {
                    continue;
                }
                
                try {
                    $result = $this->processRestaurantRow($data, $rowNum, $firestore, $collection, $lookupData, $skipInvalidRows);
                    
                    if ($result['success']) {
                        if ($result['action'] === 'created') {
                            $batchCreated++;
                        } else {
                            $batchUpdated++;
                        }
                    } else {
                        if ($result['action'] === 'skipped') {
                            $skippedRows++;
                        } else {
                            $batchFailed++;
                        }
                    }
                } catch (\Exception $e) {
                    $batchFailed++;
                }
                
                $processedRows++;
            }
            
            // Commit batch results
            $created += $batchCreated;
            $updated += $batchUpdated;
            $failed += $batchFailed;
            
            // Log progress for large datasets
            if ($totalRows > 100) {
                \Log::info("Bulk import progress: {$processedRows}/{$totalRows} rows processed");
            }
        }
        
        $msg = "Restaurant created: $created, updated: $updated, failed: $failed";
        if ($skippedRows > 0) {
            $msg .= ", skipped: $skippedRows";
        }
        
        if ($failed > 0) {
            return back()->withErrors(['file' => $msg]);
        }
        return back()->with('success', $msg);
    }
    
    /**
     * Preload lookup data to avoid repeated queries
     */
    private function preloadLookupData($firestore)
    {
        $lookupData = [
            'users' => [],
            'categories' => [],
            'cuisines' => [],
            'zones' => [],
            'existing_restaurants' => [] // Add existing restaurants for duplicate detection
        ];
        
        try {
            // Preload users (limit to avoid memory issues)
            $userDocs = $firestore->collection('users')->limit(1000)->documents();
            foreach ($userDocs as $userDoc) {
                $user = $userDoc->data();
                $lookupData['users'][$userDoc->id()] = $user;
                // Index by email and name for faster lookup
                if (isset($user['email'])) {
                    $lookupData['users']['email_' . strtolower($user['email'])] = $userDoc->id();
                }
                if (isset($user['firstName']) && isset($user['lastName'])) {
                    $lookupData['users']['name_' . strtolower($user['firstName'] . ' ' . $user['lastName'])] = $userDoc->id();
                }
            }
            
            // Preload categories
            $catDocs = $firestore->collection('vendor_categories')->documents();
            foreach ($catDocs as $catDoc) {
                $cat = $catDoc->data();
                $lookupData['categories'][strtolower($cat['title'] ?? '')] = $catDoc->id();
            }
            
            // Preload zones
            $zoneDocs = $firestore->collection('zone')->documents();
            foreach ($zoneDocs as $zoneDoc) {
                $zone = $zoneDoc->data();
                if (isset($zone['name'])) {
                    $lookupData['zones'][strtolower(trim($zone['name']))] = $zoneDoc->id();
                }
            }
            \Log::info("Preloaded " . count($lookupData['zones']) . " zones: " . implode(', ', array_keys($lookupData['zones'])));
            
            // Preload cuisines
            $cuisineDocs = $firestore->collection('vendor_cuisines')->documents();
            foreach ($cuisineDocs as $cuisineDoc) {
                $cuisine = $cuisineDoc->data();
                if (isset($cuisine['title'])) {
                    $lookupData['cuisines'][strtolower(trim($cuisine['title']))] = $cuisineDoc->id();
                }
            }
            \Log::info("Preloaded " . count($lookupData['cuisines']) . " cuisines: " . implode(', ', array_keys($lookupData['cuisines'])));
            
            // Preload existing restaurants for duplicate detection (limit to recent ones)
            $restaurantDocs = $firestore->collection('vendors')
                ->orderBy('createdAt', 'desc')
                ->limit(5000)->documents();
            foreach ($restaurantDocs as $restaurantDoc) {
                $restaurant = $restaurantDoc->data();
                if (isset($restaurant['title']) && isset($restaurant['location'])) {
                    $key = strtolower(trim($restaurant['title'])) . '|' . strtolower(trim($restaurant['location']));
                    $lookupData['existing_restaurants'][$key] = $restaurantDoc->id();
                }
            }
            
        } catch (\Exception $e) {
            \Log::error("Error preloading lookup data: " . $e->getMessage());
        }
        
        return $lookupData;
    }
    
    /**
     * Process a single restaurant row with optimized lookups
     */
    private function processRestaurantRow($data, $rowNum, $firestore, $collection, $lookupData, $skipInvalidRows = false)
    {
        // --- Data Validation ---
        $validationErrors = $this->validateRestaurantData($data, $rowNum);
        if (!empty($validationErrors)) {
            if ($skipInvalidRows) {
                return [
                    'success' => false,
                    'action' => 'skipped',
                    'error' => implode('; ', $validationErrors)
                ];
            } else {
                return [
                    'success' => false,
                    'error' => implode('; ', $validationErrors)
                ];
            }
        }
        
        // --- Duplicate Detection ---
        $duplicateCheck = $this->checkDuplicateRestaurant($data, $lookupData, $rowNum);
        if ($duplicateCheck['isDuplicate']) {
            return [
                'success' => false,
                'error' => $duplicateCheck['error']
            ];
        }
        
        // --- Optimized Author lookup using preloaded data ---
        if (empty($data['author'])) {
            $authorFound = false;
            
            // 1. Lookup by email if authorEmail is provided
            if (!empty($data['authorEmail'])) {
                $emailKey = 'email_' . strtolower(trim($data['authorEmail']));
                if (isset($lookupData['users'][$emailKey])) {
                    $data['author'] = $lookupData['users'][$emailKey];
                    $authorFound = true;
                }
            }
            
            // 2. Lookup by exact authorName
            if (!$authorFound && !empty($data['authorName'])) {
                $nameKey = 'name_' . strtolower(trim($data['authorName']));
                if (isset($lookupData['users'][$nameKey])) {
                    $data['author'] = $lookupData['users'][$nameKey];
                    $authorFound = true;
                }
            }
            
            // 3. Fallback to fuzzy match only if necessary
            if (!$authorFound && !empty($data['authorName'])) {
                $authorFound = $this->fuzzyAuthorLookup($data, $firestore);
            }
            
            if (!$authorFound && (!empty($data['authorName']) || !empty($data['authorEmail']))) {
                return [
                    'success' => false,
                    'error' => "Row $rowNum: author lookup failed for authorName '{$data['authorName']}' or authorEmail '{$data['authorEmail']}'."
                ];
            }
        }
        
        // --- Optimized Category lookup ---
            if (!empty($data['categoryTitle']) && empty($data['categoryID'])) {
                $titles = json_decode($data['categoryTitle'], true);
                if (!is_array($titles)) $titles = explode(',', $data['categoryTitle']);
                $categoryIDs = [];
            
                foreach ($titles as $title) {
                $titleLower = strtolower(trim($title));
                if (isset($lookupData['categories'][$titleLower])) {
                    $categoryIDs[] = $lookupData['categories'][$titleLower];
                } else {
                    // Fallback to fuzzy match
                    $found = $this->fuzzyCategoryLookup($title, $lookupData['categories']);
                    if ($found) {
                        $categoryIDs[] = $found;
                    } else {
                        return [
                            'success' => false,
                            'error' => "Row $rowNum: categoryTitle '$title' not found in vendor_categories."
                        ];
                    }
                }
            }
            $data['categoryID'] = $categoryIDs;
        }
        
        // --- Optimized Zone lookup ---
        if (!empty($data['zoneName']) && empty($data['zoneId'])) {
            $zoneNameLower = strtolower(trim($data['zoneName']));
            if (isset($lookupData['zones'][$zoneNameLower])) {
                $data['zoneId'] = $lookupData['zones'][$zoneNameLower];
            } else {
                // Fallback to fuzzy match
                $found = $this->fuzzyZoneLookup($data['zoneName'], $lookupData['zones']);
                if ($found) {
                    $data['zoneId'] = $found;
                } else {
                    // Debug: Log available zones for troubleshooting
                    $availableZones = array_keys($lookupData['zones']);
                    \Log::warning("Zone lookup failed for '{$data['zoneName']}'. Available zones: " . implode(', ', $availableZones));
                    
                    return [
                        'success' => false,
                        'error' => "Row $rowNum: zoneName '{$data['zoneName']}' not found in zone collection. Available zones: " . implode(', ', array_slice($availableZones, 0, 10))
                    ];
                }
            }
        }
        
        // Validate zoneId if provided directly
        if (!empty($data['zoneId']) && !in_array($data['zoneId'], array_values($lookupData['zones']))) {
            $availableZoneIds = array_values($lookupData['zones']);
            $availableZoneNames = array_keys($lookupData['zones']);
            
            // Check if the value looks like a zone name instead of an ID
            $providedValue = $data['zoneId'];
            $zoneNameLower = strtolower(trim($providedValue));
            
            if (isset($lookupData['zones'][$zoneNameLower])) {
                // The user provided a zone name in the zoneId column - convert it
                $data['zoneId'] = $lookupData['zones'][$zoneNameLower];
            } else {
                return [
                    'success' => false,
                    'error' => "Row $rowNum: zoneId '{$providedValue}' not found in zone collection. " .
                               "If you meant to provide a zone name, use column 'zoneName' instead of 'zoneId'. " .
                               "Available zone names: " . implode(', ', array_slice($availableZoneNames, 0, 10)) . ". " .
                               "Available zone IDs: " . implode(', ', array_slice($availableZoneIds, 0, 5))
                ];
            }
        }
        
        // --- Optimized Vendor Cuisine lookup ---
        if (!empty($data['vendorCuisineTitle']) && empty($data['vendorCuisineID'])) {
            $titleLower = strtolower(trim($data['vendorCuisineTitle']));
            if (isset($lookupData['cuisines'][$titleLower])) {
                $data['vendorCuisineID'] = $lookupData['cuisines'][$titleLower];
            } else {
                // Fallback to fuzzy match
                $found = $this->fuzzyCuisineLookup($data['vendorCuisineTitle'], $lookupData['cuisines']);
                if ($found) {
                    $data['vendorCuisineID'] = $found;
                } else {
                    // Debug: Log available cuisines for troubleshooting
                    $availableCuisines = array_keys($lookupData['cuisines']);
                    \Log::warning("Cuisine lookup failed for '{$data['vendorCuisineTitle']}'. Available cuisines: " . implode(', ', $availableCuisines));
                    
                    return [
                        'success' => false,
                        'error' => "Row $rowNum: vendorCuisineTitle '{$data['vendorCuisineTitle']}' not found in vendor_cuisines. Available cuisines: " . implode(', ', array_slice($availableCuisines, 0, 10))
                    ];
                }
            }
        }
        
        // Validate vendorCuisineID if provided directly
        if (!empty($data['vendorCuisineID']) && !in_array($data['vendorCuisineID'], array_values($lookupData['cuisines']))) {
            $availableCuisineIds = array_values($lookupData['cuisines']);
            $availableCuisineNames = array_keys($lookupData['cuisines']);
            
            // Check if the value looks like a cuisine name instead of an ID
            $providedValue = $data['vendorCuisineID'];
            $cuisineNameLower = strtolower(trim($providedValue));
            
            if (isset($lookupData['cuisines'][$cuisineNameLower])) {
                // The user provided a cuisine name in the vendorCuisineID column - convert it
                $data['vendorCuisineID'] = $lookupData['cuisines'][$cuisineNameLower];
            } else {
                return [
                    'success' => false,
                    'error' => "Row $rowNum: vendorCuisineID '{$providedValue}' not found in vendor_cuisines collection. " .
                               "If you meant to provide a cuisine name, use column 'vendorCuisineTitle' instead of 'vendorCuisineID'. " .
                               "Available cuisine names: " . implode(', ', array_slice($availableCuisineNames, 0, 10)) . ". " .
                               "Available cuisine IDs: " . implode(', ', array_slice($availableCuisineIds, 0, 5))
                ];
            }
        }
        
        // --- Data Type Conversions and Structure Fixes ---
        $data = $this->processDataTypes($data);
        
        // --- Create or Update with Retry Mechanism ---
            if (!empty($data['id'])) {
                // Update
            try {
                return $this->retryFirestoreOperation(function() use ($collection, $data, $rowNum) {
                $docRef = $collection->document($data['id']);
                $snapshot = $docRef->snapshot();
                if (!$snapshot->exists()) {
                        return [
                            'success' => false,
                            'error' => "Row $rowNum: Restaurant with ID {$data['id']} not found."
                        ];
                }
                $updateData = $data;
                unset($updateData['id']);
                
                // Filter out empty keys to prevent "empty field paths" error
                $updateData = array_filter($updateData, function($value, $key) {
                    return !empty($key) && $value !== null && $value !== '';
                }, ARRAY_FILTER_USE_BOTH);
                
                if (!empty($updateData)) {
                    $docRef->update(array_map(
                        fn($k, $v) => ['path' => $k, 'value' => $v],
                        array_keys($updateData), $updateData
                    ));
                }
                    return ['success' => true, 'action' => 'updated'];
                });
                } catch (\Exception $e) {
                return [
                    'success' => false,
                    'error' => "Row $rowNum: Update failed after retries ({$e->getMessage()})"
                ];
                }
            } else {
                // Create (auto Firestore ID)
                try {
                return $this->retryFirestoreOperation(function() use ($collection, $data) {
                    // Filter out empty values to prevent issues
                    $createData = array_filter($data, function($value, $key) {
                        return !empty($key) && $value !== null && $value !== '';
                    }, ARRAY_FILTER_USE_BOTH);
                    
                    $docRef = $collection->add($createData);
                    $docRef->set(['id' => $docRef->id()], ['merge' => true]);
                    return ['success' => true, 'action' => 'created'];
                });
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'error' => "Row $rowNum: Create failed after retries ({$e->getMessage()})"
                ];
            }
        }
    }
    
    /**
     * Validate restaurant data before processing
     */
    private function validateRestaurantData($data, $rowNum)
    {
        $errors = [];
        
        // Clean and trim data first
        $data = array_map(function($value) {
            return is_string($value) ? trim($value) : $value;
        }, $data);
        
        // Required field validation - collect all missing fields for this row
        $requiredFields = ['title', 'description', 'latitude', 'longitude', 'location', 'phonenumber', 'countryCode'];
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missingFields[] = $field;
            }
        }
        
        // If there are missing required fields, create a simple error message
        if (!empty($missingFields)) {
            $errors[] = "Row $rowNum: Missing required fields: " . implode(', ', $missingFields);
            return $errors; // Return early since missing required fields is a critical error
        }
        
        // Email validation
        if (!empty($data['authorEmail']) && !filter_var($data['authorEmail'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Row $rowNum: Invalid email format for authorEmail";
        }
        
        // Phone number validation
        if (!empty($data['phonenumber']) && !preg_match('/^[+0-9\- ]{7,20}$/', $data['phonenumber'])) {
            $errors[] = "Row $rowNum: Invalid phone number format (should be 7-20 digits, can include +, -, spaces)";
        }
        
        // URL validation for photo
        if (!empty($data['photo']) && !filter_var($data['photo'], FILTER_VALIDATE_URL)) {
            $errors[] = "Row $rowNum: Invalid photo URL format";
        }
        
        // Coordinate validation
        if (!empty($data['latitude'])) {
            $lat = (float)$data['latitude'];
            if ($lat < -90 || $lat > 90) {
                $errors[] = "Row $rowNum: Latitude must be between -90 and 90 degrees";
            }
        }
        
        if (!empty($data['longitude'])) {
            $lng = (float)$data['longitude'];
            if ($lng < -180 || $lng > 180) {
                $errors[] = "Row $rowNum: Longitude must be between -180 and 180 degrees";
            }
        }
        
        // Boolean field validation
        $booleanFields = ['isOpen', 'enabledDiveInFuture', 'hidephotos', 'specialDiscountEnable'];
        foreach ($booleanFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $value = strtolower($data[$field]);
                if (!in_array($value, ['true', 'false', '1', '0', 'yes', 'no'])) {
                    $errors[] = "Row $rowNum: Invalid boolean value for '$field' (use true/false, 1/0, yes/no)";
                }
            }
        }
        
        // Numeric field validation
        $numericFields = ['restaurantCost'];
        foreach ($numericFields as $field) {
            if (!empty($data[$field]) && !is_numeric($data[$field])) {
                $errors[] = "Row $rowNum: Invalid numeric value for '$field'";
            }
        }
        
        // Time format validation
        if (!empty($data['openDineTime']) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['openDineTime'])) {
            $errors[] = "Row $rowNum: Invalid time format for openDineTime (use HH:MM format)";
        }
        
        if (!empty($data['closeDineTime']) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['closeDineTime'])) {
            $errors[] = "Row $rowNum: Invalid time format for closeDineTime (use HH:MM format)";
        }
        
        return $errors;
    }
    
    /**
     * Provide helpful guidance for common validation errors
     */
    private function getValidationGuidance($missingFields)
    {
        $guidance = [];
        
        foreach ($missingFields as $field) {
            switch ($field) {
                case 'title':
                    $guidance[] = "Restaurant name is required";
                    break;
                case 'description':
                    $guidance[] = "Restaurant description is required";
                    break;
                case 'latitude':
                    $guidance[] = "Latitude coordinate is required (use Google Maps to get coordinates)";
                    break;
                case 'longitude':
                    $guidance[] = "Longitude coordinate is required (use Google Maps to get coordinates)";
                    break;
                case 'location':
                    $guidance[] = "Full address is required";
                    break;
                case 'phonenumber':
                    $guidance[] = "Phone number is required (7-20 digits, can include +, -, spaces)";
                    break;
                case 'countryCode':
                    $guidance[] = "Country code is required (e.g., IN for India, US for United States)";
                    break;
            }
        }
        
        return $guidance;
    }
    
    /**
     * Check if a row is completely empty
     */
    private function isEmptyRow($row)
    {
        foreach ($row as $cell) {
            if (!empty(trim($cell))) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Check for duplicate restaurants
     */
    private function checkDuplicateRestaurant($data, $lookupData, $rowNum)
    {
        if (empty($data['title']) || empty($data['location'])) {
            return false; // Can't check duplicates without title and location
        }
        
        $key = strtolower(trim($data['title'])) . '|' . strtolower(trim($data['location']));
        
        if (isset($lookupData['existing_restaurants'][$key])) {
            return [
                'isDuplicate' => true,
                'existingId' => $lookupData['existing_restaurants'][$key],
                'error' => "Row $rowNum: Restaurant with title '{$data['title']}' and location '{$data['location']}' already exists (ID: {$lookupData['existing_restaurants'][$key]})"
            ];
        }
        
        return ['isDuplicate' => false];
    }
    
    /**
     * Retry mechanism for Firestore operations
     */
    private function retryFirestoreOperation($operation, $maxRetries = 3, $delay = 1000)
    {
        $attempts = 0;
        $lastException = null;
        
        while ($attempts < $maxRetries) {
            try {
                return $operation();
                } catch (\Exception $e) {
                $lastException = $e;
                $attempts++;
                
                if ($attempts < $maxRetries) {
                    \Log::warning("Firestore operation failed (attempt $attempts/$maxRetries): " . $e->getMessage());
                    usleep($delay * 1000); // Convert to microseconds
                    $delay *= 2; // Exponential backoff
                }
            }
        }
        
        throw $lastException;
    }
    
    /**
     * Optimized fuzzy author lookup
     */
    private function fuzzyAuthorLookup($data, $firestore)
    {
        // Use Firestore query instead of full scan
        $searchTerm = strtolower($data['authorName']);
        $userDocs = $firestore->collection('users')
            ->where('firstName', '>=', $searchTerm)
            ->where('firstName', '<=', $searchTerm . '\uf8ff')
            ->limit(10)->documents();
            
        foreach ($userDocs as $userDoc) {
            $user = $userDoc->data();
            if (
                (isset($user['firstName']) && stripos($user['firstName'], $searchTerm) !== false) ||
                (isset($user['lastName']) && stripos($user['lastName'], $searchTerm) !== false)
            ) {
                $data['author'] = $userDoc->id();
                return true;
            }
        }
        return false;
    }
    
    /**
     * Optimized fuzzy category lookup
     */
    private function fuzzyCategoryLookup($title, $categories)
    {
        $titleLower = strtolower(trim($title));
        foreach ($categories as $catTitle => $catId) {
            if (stripos($catTitle, $titleLower) !== false) {
                return $catId;
            }
        }
        return false;
    }
    
    /**
     * Optimized fuzzy cuisine lookup
     */
    private function fuzzyCuisineLookup($title, $cuisines)
    {
        $titleLower = strtolower(trim($title));
        foreach ($cuisines as $cuisineTitle => $cuisineId) {
            if (stripos($cuisineTitle, $titleLower) !== false) {
                return $cuisineId;
            }
        }
        return false;
    }
    
    /**
     * Optimized fuzzy zone lookup
     */
    private function fuzzyZoneLookup($zoneName, $zones)
    {
        $zoneNameLower = strtolower(trim($zoneName));
        foreach ($zones as $zoneTitle => $zoneId) {
            if (stripos($zoneTitle, $zoneNameLower) !== false) {
                return $zoneId;
            }
        }
        return false;
    }
    
    /**
     * Process data type conversions
     */
    private function processDataTypes($data)
    {
        // Fix categoryID - ensure it's an array
        if (isset($data['categoryID'])) {
            if (is_string($data['categoryID'])) {
                $data['categoryID'] = json_decode($data['categoryID'], true) ?: explode(',', $data['categoryID']);
            }
            if (!is_array($data['categoryID'])) {
                $data['categoryID'] = [$data['categoryID']];
            }
        }
        
        // Fix categoryTitle - ensure it's an array
        if (isset($data['categoryTitle'])) {
            if (is_string($data['categoryTitle'])) {
                $data['categoryTitle'] = json_decode($data['categoryTitle'], true) ?: explode(',', $data['categoryTitle']);
            }
            if (!is_array($data['categoryTitle'])) {
                $data['categoryTitle'] = [$data['categoryTitle']];
            }
        }
        
        // Fix adminCommission - ensure it's an object with proper structure
        if (isset($data['adminCommission'])) {
            if (is_string($data['adminCommission'])) {
                $adminCommission = json_decode($data['adminCommission'], true);
                if ($adminCommission) {
                    $data['adminCommission'] = $adminCommission;
                } else {
                    $data['adminCommission'] = [
                        'commissionType' => 'Percent',
                        'fix_commission' => (int)($data['adminCommission'] ?? 10),
                        'isEnabled' => true
                    ];
                }
            }
            // Ensure required fields exist
            if (!isset($data['adminCommission']['commissionType'])) {
                $data['adminCommission']['commissionType'] = 'Percent';
            }
            if (!isset($data['adminCommission']['fix_commission'])) {
                $data['adminCommission']['fix_commission'] = 10;
            }
            if (!isset($data['adminCommission']['isEnabled'])) {
                $data['adminCommission']['isEnabled'] = true;
            }
        }
        
        // Fix coordinates - create GeoPoint if latitude and longitude are provided
        if (isset($data['latitude']) && isset($data['longitude']) && 
            is_numeric($data['latitude']) && is_numeric($data['longitude'])) {
            $data['coordinates'] = new \Google\Cloud\Core\GeoPoint(
                (float)$data['latitude'], 
                (float)$data['longitude']
            );
        }
        
        // Fix boolean fields
        $booleanFields = ['isOpen', 'enabledDiveInFuture', 'hidephotos', 'specialDiscountEnable'];
        foreach ($booleanFields as $field) {
            if (isset($data[$field])) {
                if (is_string($data[$field])) {
                    $data[$field] = strtolower($data[$field]) === 'true';
                }
            }
        }
        
        // Fix numeric fields
        $numericFields = ['latitude', 'longitude', 'restaurantCost'];
        foreach ($numericFields as $field) {
            if (isset($data[$field]) && is_numeric($data[$field])) {
                $data[$field] = (float)$data[$field];
            }
        }
        
        // Add missing required fields with defaults
        $defaultFields = [
            'hidephotos' => false,
            'createdAt' => new \Google\Cloud\Core\Timestamp(now()),
            'filters' => [
                'Free Wi-Fi' => 'No',
                'Good for Breakfast' => 'No',
                'Good for Dinner' => 'No',
                'Good for Lunch' => 'No',
                'Live Music' => 'No',
                'Outdoor Seating' => 'No',
                'Takes Reservations' => 'No',
                'Vegetarian Friendly' => 'No'
            ],
            'workingHours' => [
                [
                    'day' => 'Monday',
                    'timeslot' => [
                        [
                            'from' => '09:30',
                            'to' => '22:00'
                        ]
                    ]
                ],
                [
                    'day' => 'Tuesday',
                    'timeslot' => [
                        [
                            'from' => '09:30',
                            'to' => '22:00'
                        ]
                    ]
                ],
                [
                    'day' => 'Wednesday',
                    'timeslot' => [
                        [
                            'from' => '09:30',
                            'to' => '22:00'
                        ]
                    ]
                ],
                [
                    'day' => 'Thursday',
                    'timeslot' => [
                        [
                            'from' => '09:30',
                            'to' => '22:00'
                        ]
                    ]
                ],
                [
                    'day' => 'Friday',
                    'timeslot' => [
                        [
                            'from' => '09:30',
                            'to' => '22:00'
                        ]
                    ]
                ],
                [
                    'day' => 'Saturday',
                    'timeslot' => [
                        [
                            'from' => '09:30',
                            'to' => '22:00'
                        ]
                    ]
                ],
                [
                    'day' => 'Sunday',
                    'timeslot' => [
                        [
                            'from' => '09:30',
                            'to' => '22:00'
                        ]
                    ]
                ]
            ],
            'specialDiscount' => [],
            'photos' => [],
            'restaurantMenuPhotos' => []
        ];
        
        foreach ($defaultFields as $field => $defaultValue) {
            if (!isset($data[$field])) {
                $data[$field] = $defaultValue;
            }
        }
        
        return $data;
    }

    public function downloadBulkUpdateTemplate()
    {
        try {
            // Create a new Spreadsheet
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set up headers with proper column names
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
            
            // Add sample data row
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
                '{"commissionType":"Fixed","fix_commission":12,"isEnabled":true}', // adminCommission
                'true',                             // isOpen
                'false',                            // enabledDiveInFuture
                '250',                              // restaurantCost
                '09:30',                            // openDineTime
                '22:00',                            // closeDineTime
                'https://example.com/restaurant-photo.jpg', // photo
                'false',                            // hidephotos
                'false'                             // specialDiscountEnable
            ];
        
        foreach ($sampleData as $colIndex => $value) {
            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
            $sheet->setCellValue($column . '2', $value);
        }
        
        // Add instructions row
        $instructions = [
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
        
        // Add available zones and cuisines info
        try {
            $firestore = new \Google\Cloud\Firestore\FirestoreClient([
                'projectId' => config('firestore.project_id'),
                'keyFilePath' => config('firestore.credentials'),
            ]);
            
            // Get available zones
            $zoneDocs = $firestore->collection('zone')->documents();
            $zones = [];
            foreach ($zoneDocs as $zoneDoc) {
                $zone = $zoneDoc->data();
                if (isset($zone['name'])) {
                    $zones[] = $zone['name'];
                }
            }
            
            // Get available cuisines
            $cuisineDocs = $firestore->collection('vendor_cuisines')->documents();
            $cuisines = [];
            foreach ($cuisineDocs as $cuisineDoc) {
                $cuisine = $cuisineDoc->data();
                if (isset($cuisine['title'])) {
                    $cuisines[] = $cuisine['title'];
                }
            }
            
            // Add available options to the sheet
            $sheet->setCellValue('A5', 'Available Zones:');
            $sheet->setCellValue('A6', implode(', ', $zones));
            $sheet->getStyle('A5')->getFont()->setBold(true);
            $sheet->getStyle('A6')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0066CC'));
            
            $sheet->setCellValue('A8', 'Available Cuisines:');
            $sheet->setCellValue('A9', implode(', ', $cuisines));
            $sheet->getStyle('A8')->getFont()->setBold(true);
            $sheet->getStyle('A9')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0066CC'));
            
        } catch (Exception $e) {
            $sheet->setCellValue('A5', 'Note: Could not load available zones and cuisines');
        }
        
        // Auto-size columns
        foreach (range('A', 'V') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Add data validation for boolean fields
        $booleanFields = ['M', 'N', 'O', 'U', 'V']; // isOpen, enabledDiveInFuture, hidephotos, specialDiscountEnable
        foreach ($booleanFields as $column) {
            for ($row = 2; $row <= 1000; $row++) {
                $validation = $sheet->getDataValidation($column . $row);
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setFormula1('"true,false"');
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setPromptTitle('Boolean Value');
                $validation->setPrompt('Please select true or false');
                $validation->setShowErrorMessage(true);
                $validation->setErrorTitle('Invalid Value');
                $validation->setError('Please select true or false only');
            }
        }
        
        // Add data validation for country code
        for ($row = 2; $row <= 1000; $row++) {
            $validation = $sheet->getDataValidation('G' . $row);
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setFormula1('"IN,US,UK,CA,AU,DE,FR,IT,ES,JP,CN,KR,BR,MX,AR,CL,CO,PE,VE,EC,BO,PY,UY,GY,SR,GF,FG,BR,AR,CL,CO,PE,VE,EC,BO,PY,UY,GY,SR,GF,FG"');
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setPromptTitle('Country Code');
            $validation->setPrompt('Please select a country code');
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Invalid Country Code');
            $validation->setError('Please select a valid country code');
        }
        
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
            
            // Add available zones and cuisines info (with error handling)
            try {
                $firestore = new \Google\Cloud\Firestore\FirestoreClient([
                    'projectId' => config('firestore.project_id'),
                    'keyFilePath' => config('firestore.credentials'),
                ]);
                
                // Get available zones
                $zoneDocs = $firestore->collection('zone')->documents();
                $zones = [];
                foreach ($zoneDocs as $zoneDoc) {
                    $zone = $zoneDoc->data();
                    if (isset($zone['name'])) {
                        $zones[] = $zone['name'];
                    }
                }
                
                // Get available cuisines
                $cuisineDocs = $firestore->collection('vendor_cuisines')->documents();
                $cuisines = [];
                foreach ($cuisineDocs as $cuisineDoc) {
                    $cuisine = $cuisineDoc->data();
                    if (isset($cuisine['title'])) {
                        $cuisines[] = $cuisine['title'];
                    }
                }
                
                // Add available options to the sheet
                $sheet->setCellValue('A5', 'Available Zones:');
                $sheet->setCellValue('A6', implode(', ', $zones));
                $sheet->getStyle('A5')->getFont()->setBold(true);
                $sheet->getStyle('A6')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0066CC'));
                
                $sheet->setCellValue('A8', 'Available Cuisines:');
                $sheet->setCellValue('A9', implode(', ', $cuisines));
                $sheet->getStyle('A8')->getFont()->setBold(true);
                $sheet->getStyle('A9')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0066CC'));
                
            } catch (\Exception $e) {
                $sheet->setCellValue('A5', 'Note: Could not load available zones and cuisines');
                \Log::error('Error loading zones/cuisines for template: ' . $e->getMessage());
            }
            
            // Auto-size columns
            foreach (range('A', 'W') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            // Add data validation for boolean fields
            $booleanFields = ['O', 'P', 'U', 'V']; // isOpen, enabledDiveInFuture, hidephotos, specialDiscountEnable
            foreach ($booleanFields as $column) {
                for ($row = 2; $row <= 1000; $row++) {
                    $validation = $sheet->getDataValidation($column . $row);
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setFormula1('"true,false"');
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setPromptTitle('Boolean Value');
                    $validation->setPrompt('Please select true or false');
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Invalid Value');
                    $validation->setError('Please select true or false only');
                }
            }
            
            // Add data validation for country code
            for ($row = 2; $row <= 1000; $row++) {
                $validation = $sheet->getDataValidation('H' . $row);
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setFormula1('"IN,US,UK,CA,AU,DE,FR,IT,ES,JP,CN,KR,BR,MX,AR,CL,CO,PE,VE,EC,BO,PY,UY,GY,SR,GF,FG"');
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setPromptTitle('Country Code');
                $validation->setPrompt('Please select a country code');
                $validation->setShowErrorMessage(true);
                $validation->setErrorTitle('Invalid Country Code');
                $validation->setError('Please select a valid country code');
            }
            
            // Create the Excel file
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filePath = storage_path('app/templates/restaurants_bulk_update_template.xlsx');
            
            // Ensure directory exists
            $directory = dirname($filePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $writer->save($filePath);
            
            return response()->download($filePath, 'restaurants_bulk_update_template.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="restaurants_bulk_update_template.xlsx"'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error generating restaurant template: ' . $e->getMessage());
            return back()->withErrors(['file' => 'Error generating template: ' . $e->getMessage()]);
        }
    }
}
