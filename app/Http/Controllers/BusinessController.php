<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Services\PdfService;

class BusinessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        $business = auth()->user()->business;
        
        if (!$business) {
            return redirect()->route('dashboard')->with('error', 'Business profile not found.');
        }
        
        $isProfileComplete = $this->isProfileComplete($business);
        $canLoadSampleData = $this->canLoadSampleData();
        
        return view('business.profile', compact('business', 'isProfileComplete', 'canLoadSampleData'));
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|regex:/^[0-9]{10}$/',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pin_code' => 'nullable|regex:/^[0-9]{6}$/',
            'gstin' => ['nullable', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            'pan' => ['nullable', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'hsn_prefix' => 'nullable|string|max:8',
            'terms_and_conditions' => 'nullable|string|max:3500',
            'currency' => 'nullable|string|max:3',
            'financial_year_start' => 'nullable|date',
            'payment_terms' => 'nullable|integer|min:0|max:365',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'gstin.regex' => 'GSTIN must be in format: 22AAAAA0000A1Z5',
            'pan.regex' => 'PAN must be in format: ABCDE1234F',
            'pin_code.regex' => 'PIN code must be 6 digits',
            'phone.regex' => 'Phone number must be exactly 10 digits',
            'logo.max' => 'Logo must be less than 2MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $business = auth()->user()->business;
        $updateData = $request->only([
            'name', 'legal_name', 'email', 'phone', 'address', 'city', 'state', 'pin_code',
            'gstin', 'pan', 'hsn_prefix', 'terms_and_conditions', 'currency', 
            'financial_year_start', 'payment_terms'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($business->logo_path && file_exists(public_path($business->logo_path))) {
                unlink(public_path($business->logo_path));
            }

            $logoFile = $request->file('logo');
            $filename = 'logo_' . $business->id . '_' . time() . '.' . $logoFile->getClientOriginalExtension();

            $directory = public_path('uploads/business');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $logoFile->move($directory, $filename);
            $updateData['logo_path'] = 'uploads/business/' . $filename;
        }

        $business->update($updateData);

        return back()->with('success', 'Business profile updated successfully.');
    }

    public function loadSampleData(Request $request)
    {
        // Disabled sample data loading to prevent contamination of feature limits
        return back()->with('error', 'Sample data loading is disabled.');

        /*

        /*
        $user = auth()->user();
        $business = $user->business;
        $subscriptionTier = $business->subscription_tier ?? 'full_erp';

        // Check if user can load sample data
        if (!$this->canLoadSampleData()) {
            return back()->with('error', 'Sample data can only be loaded for new businesses with less than 2 customers.');
        }

        try {
            \DB::beginTransaction();

            if ($subscriptionTier === 'billing_sales') {
                // Add sample data for billing_sales tier
                $customer = \App\Models\Customer::create([
                    'business_id' => $business->id,
                    'name' => 'Sample Client Pvt Ltd',
                    'email' => 'client@samplecorp.com',
                    'phone' => '+91 98765 43210',
                    'address' => '123 Business Park, Sample City',
                    'city' => 'Mumbai',
                    'state' => 'Maharashtra',
                    'pincode' => '400001',
                    'gstin' => '27AAAAA0000A1Z5',
                    'is_active' => true,
                ]);
                
                // Create a sample quotation
                $quotation = \App\Models\Quotation::create([
                    'business_id' => $business->id,
                    'customer_id' => $customer->id,
                    'number' => 'QUO-2526-0001',
                    'valid_until' => now()->addDays(30),
                    'subtotal' => 10000,
                    'tax_amount' => 1800,
                    'total' => 11800,
                    'status' => 'sent',
                ]);
                
                \App\Models\QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'description' => 'Consulting Hour',
                    'quantity' => 10,
                    'unit_price' => 1000,
                    'total' => 10000,
                ]);
                
            } else {
                // Add sample data for full_erp tier
                $vendor = \App\Models\Vendor::create([
                    'business_id' => $business->id,
                    'name' => 'Metal Supplier Co.',
                    'email' => 'orders@metalsupplier.com',
                    'phone' => '+91 98765 12345',
                    'address' => '456 Industrial Area, Steel City',
                    'city' => 'Pune',
                    'state' => 'Maharashtra',
                    'pincode' => '411001',
                    'gstin' => '27BBBBB1111B2Z6',
                    'is_active' => true,
                ]);
                
                $material1 = \App\Models\Material::create([
                    'business_id' => $business->id,
                    'name' => 'Steel Rod',
                    'description' => 'Mild Steel Rod 12mm',
                    'category' => 'Raw Material',
                    'unit' => 'kg',
                    'cost_per_unit' => 45.50,
                    'hsn_code' => '7213',
                    'is_active' => true,
                ]);
                
                $material2 = \App\Models\Material::create([
                    'business_id' => $business->id,
                    'name' => 'Aluminum Sheet',
                    'description' => 'Aluminum Sheet 2mm thickness',
                    'category' => 'Raw Material',
                    'unit' => 'sq.ft',
                    'cost_per_unit' => 125.00,
                    'hsn_code' => '7606',
                    'is_active' => true,
                ]);
                
                $customer = \App\Models\Customer::create([
                    'business_id' => $business->id,
                    'name' => 'ABC Manufacturing Ltd',
                    'email' => 'orders@abcmfg.com',
                    'phone' => '+91 98765 54321',
                    'address' => '789 Factory Road, Industrial Zone',
                    'city' => 'Chennai',
                    'state' => 'Tamil Nadu',
                    'pincode' => '600001',
                    'gstin' => '33CCCCC2222C3Z7',
                    'is_active' => true,
                ]);
            }
            
            \DB::commit();
            
            return back()->with('success', 'Sample data added successfully! Now create your first quote.');
            
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to load sample data: ' . $e->getMessage());
        }
        */
    }

    private function isProfileComplete($business)
    {
        if (!$business) {
            return false;
        }
        
        return !empty($business->name) && 
               !empty($business->address) && 
               (!empty($business->gstin) || !empty($business->city));
    }
    
    private function canLoadSampleData()
    {
        $user = auth()->user();
        if (!$user || !$user->business) {
            return false;
        }
        
        $business = $user->business;
        $customerCount = \App\Models\Customer::where('business_id', $business->id)->count();
        return $customerCount < 2;
    }

    public function previewPDF(Request $request)
    {
        try {
            $business = auth()->user()->business;

            if (!$business) {
                return back()->with('error', 'Business profile not found.');
            }

            // Merge current form data with existing business data
            $previewData = array_merge($business->toArray(), $request->only([
                'name', 'legal_name', 'email', 'phone', 'address', 'city', 'state', 'pin_code',
                'gstin', 'pan', 'terms_and_conditions'
            ]));

            $previewBusiness = (object) $previewData;

            $filename = 'business-profile-' . $business->id . '.pdf';
            return (new PdfService())->generateDocumentPdf($previewBusiness, [], null, true)->download($filename);

        } catch (\Exception $e) {
            \Log::error('PDF Download Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Unable to generate PDF. Please try again.');
        }
    }
}