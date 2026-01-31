<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('business_id', auth()->user()->business_id)
            ->latest()
            ->paginate(15);
        
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        // Check if customer feature is enabled
        if (auth()->user()->currentSubscription() && !auth()->user()->currentSubscription()->isFeatureEnabled('customer_management')) {
            return redirect()->route('customers.index')->with('error', 'Customer management feature is not enabled in your current plan. Please upgrade your plan.');
        }

        // Check feature limits
        if (auth()->user()->currentSubscription() && !auth()->user()->currentSubscription()->canUseFeature('customer_management', 1)) {
            return redirect()->route('customers.index')->with('error', 'You have reached your customer limit. Please upgrade your plan to create more customers.');
        }

        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,NULL,id,business_id,' . auth()->user()->business_id,
            'phone' => 'required|string|regex:/^[6-9]\d{9}$/|unique:customers,phone,NULL,id,business_id,' . auth()->user()->business_id,
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|regex:/^[0-9]{6}$/',
            'gstin' => 'nullable|string|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|unique:customers,gstin,NULL,id,business_id,' . auth()->user()->business_id,
            'contact_person' => 'nullable|string|max:255',
            'customer_type' => 'required|in:individual,business',
            'payment_terms' => 'required|in:due_on_receipt,net_7,net_15,net_30',
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
        ], [
            'phone.regex' => 'Phone number must be a valid 10-digit Indian mobile number starting with 6-9.',
            'phone.unique' => 'This phone number is already registered for another customer.',
            'gstin.regex' => 'GSTIN must be in valid format (e.g., 22AAAAA0000A1Z5).',
            'gstin.unique' => 'This GSTIN is already registered for another customer.',
            'pincode.regex' => 'Pincode must be exactly 6 digits.',
        ]);

        $validated['business_id'] = auth()->user()->business_id;
        $validated['is_active'] = true;

        Customer::create($validated);

        // Increment feature usage
        if (auth()->user()->currentSubscription()) {
            auth()->user()->currentSubscription()->incrementFeatureUsage('customer_management');
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer "' . $validated['name'] . '" created successfully!');
    }

    public function show(Customer $customer)
    {
        if ($customer->business_id !== auth()->user()->business_id) {
            abort(404);
        }
        
        $workOrders = $customer->workOrders()->latest()->take(10)->get();
        $invoices = $customer->invoices()->latest()->take(10)->get();
        
        return view('customers.show', compact('customer', 'workOrders', 'invoices'));
    }

    public function edit(Customer $customer)
    {
        if ($customer->business_id !== auth()->user()->business_id) {
            abort(404);
        }
        
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        if ($customer->business_id !== auth()->user()->business_id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id . ',id,business_id,' . auth()->user()->business_id,
            'phone' => 'required|string|regex:/^[6-9]\d{9}$/|unique:customers,phone,' . $customer->id . ',id,business_id,' . auth()->user()->business_id,
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|regex:/^[0-9]{6}$/',
            'gstin' => 'nullable|string|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|unique:customers,gstin,' . $customer->id . ',id,business_id,' . auth()->user()->business_id,
            'contact_person' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'customer_type' => 'required|in:individual,business',
            'payment_terms' => 'required|in:due_on_receipt,net_7,net_15,net_30',
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
        ], [
            'phone.regex' => 'Phone number must be a valid 10-digit Indian mobile number starting with 6-9.',
            'phone.unique' => 'This phone number is already registered for another customer.',
            'gstin.regex' => 'GSTIN must be in valid format (e.g., 22AAAAA0000A1Z5).',
            'gstin.unique' => 'This GSTIN is already registered for another customer.',
            'pincode.regex' => 'Pincode must be exactly 6 digits.',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer "' . $customer->name . '" updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->business_id !== auth()->user()->business_id) {
            abort(404);
        }
        
        if ($customer->workOrders()->count() > 0 || $customer->invoices()->count() > 0) {
            return redirect()->route('customers.index')
                ->with('error', 'Cannot delete customer with existing work orders or invoices.');
        }
        
        $customerName = $customer->name;
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer "' . $customerName . '" deleted successfully!');
    }

    public function toggle(Customer $customer)
    {
        if ($customer->business_id !== auth()->user()->business_id) {
            abort(404);
        }
        
        $customer->update(['is_active' => !$customer->is_active]);
        
        $status = $customer->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', 'Customer "' . $customer->name . '" ' . $status . ' successfully!');
    }
    
    public function addContact(Request $request, Customer $customer)
    {
        if ($customer->business_id !== auth()->user()->business_id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:100',
            'is_primary' => 'boolean'
        ]);
        
        if ($validated['is_primary'] ?? false) {
            $customer->contacts()->update(['is_primary' => false]);
        }
        
        $customer->contacts()->create($validated);
        
        return redirect()->back()->with('success', 'Contact added successfully!');
    }
    
    public function updateContact(Request $request, Customer $customer, $contactId)
    {
        if ($customer->business_id !== auth()->user()->business_id) {
            abort(404);
        }
        
        $contact = $customer->contacts()->findOrFail($contactId);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:100',
            'is_primary' => 'boolean'
        ]);
        
        if ($validated['is_primary'] ?? false) {
            $customer->contacts()->where('id', '!=', $contactId)->update(['is_primary' => false]);
        }
        
        $contact->update($validated);
        
        return redirect()->back()->with('success', 'Contact updated successfully!');
    }
    
    public function deleteContact(Customer $customer, $contactId)
    {
        if ($customer->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        $contact = $customer->contacts()->findOrFail($contactId);
        $contact->delete();

        return redirect()->back()->with('success', 'Contact deleted successfully!');
    }

    public function getStates()
    {
        $states = State::orderBy('name')->get(['id', 'name']);
        return response()->json($states);
    }

    public function getCitiesByState($stateName)
    {
        $state = State::where('name', $stateName)->first();

        if (!$state) {
            return response()->json([]);
        }

        $cities = City::where('state_id', $state->id)
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        return response()->json($cities);
    }
}