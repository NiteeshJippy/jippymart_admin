<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Google\Cloud\Firestore\FirestoreClient;

class FoodController extends Controller
{

   public function __construct()
    {
        $this->middleware('auth');
    }
	 public function index($id='')
    {
   		return view("foods.index")->with('id',$id);   		
    }

      public function edit($id)
    {
    	return view('foods.edit')->with('id',$id);
    }

    public function create($id='')
    {
      return view('foods.create')->with('id',$id);
    }
    public function createfood()
    {
      return view('foods.create');
    }

    /**
     * Find vendor ID by name
     */
    private function findVendorByName($vendorName, $firestore)
    {
        try {
            $vendors = $firestore->collection('vendors')
                ->where('title', '==', trim($vendorName))
                ->limit(1)
                ->documents();
            
            foreach ($vendors as $vendor) {
                return $vendor->id();
            }
        } catch (\Exception $e) {
            // Log error if needed
        }
        return null;
    }

    /**
     * Find category ID by name
     */
    private function findCategoryByName($categoryName, $firestore)
    {
        try {
            $categories = $firestore->collection('vendor_categories')
                ->where('title', '==', trim($categoryName))
                ->limit(1)
                ->documents();
            
            foreach ($categories as $category) {
                return $category->id();
            }
        } catch (\Exception $e) {
            // Log error if needed
        }
        return null;
    }

    /**
     * Resolve vendor ID - try direct ID first, then name lookup
     */
    private function resolveVendorID($vendorInput, $firestore)
    {
        // First try as direct ID
        try {
            $vendorDoc = $firestore->collection('vendors')->document($vendorInput)->snapshot();
            if ($vendorDoc->exists()) {
                return $vendorInput; // Return the ID as-is
            }
        } catch (\Exception $e) {
            // Continue to name lookup
        }

        // If not found as ID, try name lookup
        return $this->findVendorByName($vendorInput, $firestore);
    }

    /**
     * Resolve category ID - try direct ID first, then name lookup
     */
    private function resolveCategoryID($categoryInput, $firestore)
    {
        // First try as direct ID
        try {
            $categoryDoc = $firestore->collection('vendor_categories')->document($categoryInput)->snapshot();
            if ($categoryDoc->exists()) {
                return $categoryInput; // Return the ID as-is
            }
        } catch (\Exception $e) {
            // Continue to name lookup
        }

        // If not found as ID, try name lookup
        return $this->findCategoryByName($categoryInput, $firestore);
    }

    public function import(Request $request)
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
        
        // Initialize Firestore client
        $firestore = new FirestoreClient([
            'projectId' => config('firestore.project_id'),
            'keyFilePath' => config('firestore.credentials'),
        ]);
        
        $collection = $firestore->collection('vendor_products');
        $imported = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because we removed header and arrays are 0-indexed
            $data = array_combine($headers, $row);
            
            // Skip empty rows
            if (empty($data['name'])) {
                continue;
            }

            try {
                // Validate required fields
                if (empty($data['name']) || empty($data['price']) || empty($data['vendorID']) || empty($data['categoryID'])) {
                    $errors[] = "Row $rowNumber: Missing required fields (name, price, vendorID, categoryID)";
                    continue;
                }

                // Resolve vendor ID (supports both ID and name)
                $resolvedVendorID = $this->resolveVendorID($data['vendorID'], $firestore);
                if (!$resolvedVendorID) {
                    $errors[] = "Row $rowNumber: Vendor '{$data['vendorID']}' not found (neither as ID nor name)";
                    continue;
                }

                // Resolve category ID (supports both ID and name)
                $resolvedCategoryID = $this->resolveCategoryID($data['categoryID'], $firestore);
                if (!$resolvedCategoryID) {
                    $errors[] = "Row $rowNumber: Category '{$data['categoryID']}' not found (neither as ID nor name)";
                    continue;
                }

                // Prepare food data
                $foodData = [
                    'name' => trim($data['name']),
                    'price' => (float) $data['price'],
                    'description' => trim($data['description'] ?? ''),
                    'vendorID' => $resolvedVendorID,
                    'categoryID' => $resolvedCategoryID,
                    'disPrice' => !empty($data['disPrice']) ? (float) $data['disPrice'] : '',
                    'publish' => strtolower($data['publish'] ?? 'true') === 'true',
                    'nonveg' => strtolower($data['nonveg'] ?? 'false') === 'true',
                    'veg' => strtolower($data['nonveg'] ?? 'false') === 'true' ? false : true, // Opposite of nonveg
                    'isAvailable' => strtolower($data['isAvailable'] ?? 'true') === 'true',
                    'quantity' => -1, // Auto-generated default
                    'calories' => 0, // Auto-generated default
                    'grams' => 0, // Auto-generated default
                    'proteins' => 0, // Auto-generated default
                    'fats' => 0, // Auto-generated default
                    'photo' => '',
                    'photos' => [],
                    'addOnsTitle' => [], // Fixed spelling to match Firestore
                    'addOnsPrice' => [], // Fixed spelling to match Firestore
                    'sizeTitle' => [],
                    'sizePrice' => [],
                    'attributes' => [],
                    'variants' => [],
                    'product_specification' => null,
                    'item_attribute' => null,
                    'reviewAttributes' => null,
                    'reviewsCount' => 0,
                    'reviewsSum' => 0,
                    'takeawayOption' => false, // Auto-generated default
                    'migratedBy' => 'excel_import',
                    'createdAt' => new \Google\Cloud\Core\Timestamp(new \DateTime()), // Fixed to match Firestore
                    'updated_at' => new \Google\Cloud\Core\Timestamp(new \DateTime()),
                ];

                // Create document with auto-generated ID
                $docRef = $collection->add($foodData);
                
                // Set the internal 'id' field to match the Firestore document ID
                $docRef->set(['id' => $docRef->id()], ['merge' => true]);
                
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row $rowNumber: " . $e->getMessage();
            }
        }

        if ($imported === 0) {
            return back()->withErrors(['file' => 'No valid rows were found to import.']);
        }

        $message = "Foods imported successfully! ($imported rows)";
        if (!empty($errors)) {
            $message .= " Errors: " . implode('; ', $errors);
        }

        return back()->with('success', $message);
    }

    public function downloadTemplate()
    {
        $filePath = storage_path('app/templates/foods_import_template.xlsx');
        
        if (!file_exists($filePath)) {
            abort(404, 'Template file not found');
        }
        
        return response()->download($filePath, 'foods_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="foods_import_template.xlsx"'
        ]);
    }

}
