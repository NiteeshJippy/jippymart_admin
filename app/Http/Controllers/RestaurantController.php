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
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('file'));
        $rows = $spreadsheet->getActiveSheet()->toArray();
        if (empty($rows) || count($rows) < 2) {
            return back()->withErrors(['file' => 'The uploaded file is empty or missing data.']);
        }
        $headers = array_map('trim', array_shift($rows));
        $firestore = new \Google\Cloud\Firestore\FirestoreClient([
            'projectId' => config('firestore.project_id'),
            'keyFilePath' => config('firestore.credentials'),
        ]);
        $collection = $firestore->collection('vendors');
        $created = 0;
        $updated = 0;
        $errors = [];
        foreach ($rows as $rowIndex => $row) {
            $rowNum = $rowIndex + 2;
            $data = array_combine($headers, $row);
            // --- Author lookup ---
            if (empty($data['author'])) {
                $authorFound = false;
                // 1. Lookup by email if authorEmail is provided
                if (!empty($data['authorEmail'])) {
                    $userDocs = $firestore->collection('users')
                        ->where('email', '=', trim($data['authorEmail']))
                        ->limit(1)->documents();
                    if (!$userDocs->isEmpty()) {
                        $data['author'] = $userDocs->rows()[0]['id'];
                        $authorFound = true;
                    }
                }
                // 2. Lookup by exact authorName (firstName + lastName)
                if (!$authorFound && !empty($data['authorName'])) {
                    $nameParts = explode(' ', $data['authorName'], 2);
                    $firstName = $nameParts[0] ?? '';
                    $lastName = $nameParts[1] ?? '';
                    $userDocs = $firestore->collection('users')
                        ->where('firstName', '=', $firstName)
                        ->where('lastName', '=', $lastName)
                        ->limit(1)->documents();
                    if (!$userDocs->isEmpty()) {
                        $data['author'] = $userDocs->rows()[0]['id'];
                        $authorFound = true;
                    }
                }
                // 3. Fuzzy match (case-insensitive, partial match on firstName or lastName)
                if (!$authorFound && !empty($data['authorName'])) {
                    $allUsers = $firestore->collection('users')->documents();
                    $authorNameLower = strtolower($data['authorName']);
                    foreach ($allUsers as $userDoc) {
                        $user = $userDoc->data();
                        if (
                            (isset($user['firstName']) && stripos($user['firstName'], $authorNameLower) !== false) ||
                            (isset($user['lastName']) && stripos($user['lastName'], $authorNameLower) !== false)
                        ) {
                            $data['author'] = $userDoc->id();
                            $authorFound = true;
                            break;
                        }
                    }
                }
                if (!$authorFound && (!empty($data['authorName']) || !empty($data['authorEmail']))) {
                    $errors[] = "Row $rowNum: author lookup failed for authorName '{$data['authorName']}' or authorEmail '{$data['authorEmail']}'.";
                }
            }
            // --- Category lookup ---
            if (!empty($data['categoryTitle']) && empty($data['categoryID'])) {
                $titles = json_decode($data['categoryTitle'], true);
                if (!is_array($titles)) $titles = explode(',', $data['categoryTitle']);
                $categoryIDs = [];
                foreach ($titles as $title) {
                    $catDocs = $firestore->collection('vendor_categories')
                        ->where('title', '=', trim($title))
                        ->limit(1)->documents();
                    if (!$catDocs->isEmpty()) {
                        $categoryIDs[] = $catDocs->rows()[0]['id'];
                    } else {
                        // Fuzzy match (case-insensitive, partial)
                        $allCats = $firestore->collection('vendor_categories')->documents();
                        $titleLower = strtolower(trim($title));
                        $found = false;
                        foreach ($allCats as $catDoc) {
                            $cat = $catDoc->data();
                            if (isset($cat['title']) && stripos(strtolower($cat['title']), $titleLower) !== false) {
                                $categoryIDs[] = $catDoc->id();
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $errors[] = "Row $rowNum: categoryTitle '$title' not found in vendor_categories.";
                        }
                    }
                }
                $data['categoryID'] = $categoryIDs;
            }
            // --- Vendor Cuisine lookup ---
            if (!empty($data['vendorCuisineTitle']) && empty($data['vendorCuisineID'])) {
                $cuisineDocs = $firestore->collection('vendor_cuisines')
                    ->where('title', '=', $data['vendorCuisineTitle'])
                    ->limit(1)->documents();
                if (!$cuisineDocs->isEmpty()) {
                    $data['vendorCuisineID'] = $cuisineDocs->rows()[0]['id'];
                } else {
                    // Fuzzy match (case-insensitive, partial)
                    $allCuisines = $firestore->collection('vendor_cuisines')->documents();
                    $titleLower = strtolower($data['vendorCuisineTitle']);
                    $found = false;
                    foreach ($allCuisines as $cuisineDoc) {
                        $cuisine = $cuisineDoc->data();
                        if (isset($cuisine['title']) && stripos(strtolower($cuisine['title']), $titleLower) !== false) {
                            $data['vendorCuisineID'] = $cuisineDoc->id();
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $errors[] = "Row $rowNum: vendorCuisineTitle '{$data['vendorCuisineTitle']}' not found in vendor_cuisines.";
                    }
                }
            }
            // --- Zone lookup ---
            if (!empty($data['zoneName']) && empty($data['zoneId'])) {
                $zoneDocs = $firestore->collection('zone')
                    ->where('name', '=', $data['zoneName'])
                    ->limit(1)->documents();
                if (!$zoneDocs->isEmpty()) {
                    $data['zoneId'] = $zoneDocs->rows()[0]['id'];
                } else {
                    // Fuzzy match (case-insensitive, partial)
                    $allZones = $firestore->collection('zone')->documents();
                    $zoneNameLower = strtolower($data['zoneName']);
                    $found = false;
                    foreach ($allZones as $zoneDoc) {
                        $zone = $zoneDoc->data();
                        if (isset($zone['name']) && stripos(strtolower($zone['name']), $zoneNameLower) !== false) {
                            $data['zoneId'] = $zoneDoc->id();
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $errors[] = "Row $rowNum: zoneName '{$data['zoneName']}' not found in zone collection.";
                    }
                }
            }
            // --- Create or Update ---
            if (!empty($data['id'])) {
                // Update
                $docRef = $collection->document($data['id']);
                $snapshot = $docRef->snapshot();
                if (!$snapshot->exists()) {
                    $errors[] = "Row $rowNum: Restaurant with ID {$data['id']} not found.";
                    continue;
                }
                $updateData = $data;
                unset($updateData['id']);
                try {
                    $docRef->update(array_map(
                        fn($k, $v) => ['path' => $k, 'value' => $v],
                        array_keys($updateData), $updateData
                    ));
                    $updated++;
                } catch (\Exception $e) {
                    $errors[] = "Row $rowNum: Update failed ({$e->getMessage()})";
                }
            } else {
                // Create (auto Firestore ID)
                try {
                    $docRef = $collection->add($data);
                    $docRef->set(['id' => $docRef->id()], ['merge' => true]);
                    $created++;
                } catch (\Exception $e) {
                    $errors[] = "Row $rowNum: Create failed ({$e->getMessage()})";
                }
            }
        }
        $msg = "Restaurants updated: $updated, created: $created.";
        if (!empty($errors)) {
            $msg .= "<br>Some issues occurred:<br>" . implode('<br>', $errors);
        }
        if ($updated === 0 && $created === 0) {
            return back()->withErrors(['file' => $msg]);
        }
        return back()->with('success', $msg);
    }

    public function downloadBulkUpdateTemplate()
    {
        $filePath = storage_path('app/templates/restaurants_bulk_update_template.xlsx');
        if (!file_exists($filePath)) {
            abort(404, 'Template file not found');
        }
        return response()->download($filePath, 'restaurants_bulk_update_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="restaurants_bulk_update_template.xlsx"'
        ]);
    }
}
