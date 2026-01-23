<?php

namespace App\Http\Controllers;

use App\Models\SalesRepresentative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuperAdminSalesRepresentativeController extends Controller
{
    public function __construct()
    {
        // Middleware handled at route level with superadmin.auth
    }

    public function index()
    {
        $query = SalesRepresentative::query();

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('representative_id', 'LIKE', "%{$search}%");
            });
        }

        $representatives = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('superadmin.sales-representatives.index', compact('representatives'));
    }

    public function create()
    {
        return view('superadmin.sales-representatives.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sales_representatives,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'company_name' => 'required|string|max:255',
            'territory_region' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive,On Leave',
            'current_address' => 'required|string',
            'languages_spoken' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        SalesRepresentative::create($request->all());

        return redirect()->route('superadmin.sales-representatives.index')->with('success', 'Sales Representative created successfully.');
    }

    public function show($id)
    {
        $representative = SalesRepresentative::findOrFail($id);
        $businesses = $representative->businesses()->with('users')->paginate(10);
        return view('superadmin.sales-representatives.show', compact('representative', 'businesses'));
    }

    public function edit($id)
    {
        $representative = SalesRepresentative::findOrFail($id);
        return view('superadmin.sales-representatives.edit', compact('representative'));
    }

    public function update(Request $request, $id)
    {
        $representative = SalesRepresentative::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sales_representatives,email,' . $id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'company_name' => 'required|string|max:255',
            'territory_region' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive,On Leave',
            'current_address' => 'required|string',
            'languages_spoken' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Prevent updating representative_id
        $data = $request->except('representative_id');
        $representative->update($data);

        return redirect()->route('superadmin.sales-representatives.index')->with('success', 'Sales Representative updated successfully.');
    }

    public function toggleStatus($id)
    {
        $representative = SalesRepresentative::findOrFail($id);

        $newStatus = $representative->status === 'Active' ? 'Inactive' : 'Active';
        $representative->update(['status' => $newStatus]);

        $message = $newStatus === 'Active' ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Sales Representative {$message} successfully.");
    }
}
