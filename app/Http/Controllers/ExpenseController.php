<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $businessId = auth()->user()->business_id;

             // Check if user can view expenses
             if (!auth()->user()->hasPermission('view_expense') && !auth()->user()->hasPermission('add_expense')) {
                 return redirect()->route('dashboard')
                     ->with('error', 'You do not have permission to view expenses.');
             }

            $query = Expense::where('business_id', $businessId);

            // Filter to own expenses if no view_all_expenses permission
            if (!auth()->user()->hasPermission('view_all_expenses')) {
                $query->where('created_by', auth()->id());
            }

            // Apply filters
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            if ($request->filled('month') && $request->filled('year')) {
                $query->whereYear('expense_date', $request->year)
                      ->whereMonth('expense_date', $request->month);
            } elseif ($request->filled('year')) {
                $query->whereYear('expense_date', $request->year);
            }

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('expense_date', [$request->from_date, $request->to_date]);
            }

            $expenses = $query->with('createdBy')->latest('expense_date')->paginate(15);

            // Get filter options
            $categories = [
                'staff_payroll' => 'Staff Payroll',
                'rent_property_expense' => 'Rent / Property Expense',
                'electricity_utilities' => 'Electricity / Utilities',
                'equipment_assets' => 'Equipment / Assets',
                'miscellaneous' => 'Miscellaneous',
            ];

            return view('expenses.index', compact('expenses', 'categories'));
        } catch (\Exception $e) {
            Log::error('Expense index error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load expenses.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user can create expenses
        if (!auth()->user()->hasPermission('add_expense')) {
            return redirect()->route('expenses.index')
                ->with('error', 'You do not have permission to create expenses.');
        }

        $categories = [
            'staff_payroll' => 'Staff Payroll',
            'rent_property_expense' => 'Rent / Property Expense',
            'electricity_utilities' => 'Electricity / Utilities',
            'equipment_assets' => 'Equipment / Assets',
            'miscellaneous' => 'Miscellaneous',
        ];

        $paymentModes = [
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'upi' => 'UPI',
            'card' => 'Card',
            'cheque' => 'Cheque',
            'other' => 'Other',
        ];

        return view('expenses.create', compact('categories', 'paymentModes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Check if user can create expenses
            if (!auth()->user()->hasPermission('add_expense')) {
                return redirect()->route('expenses.index')
                    ->with('error', 'You do not have permission to create expenses.');
            }

            $validated = $request->validate([
                'category' => 'required|in:staff_payroll,rent_property_expense,electricity_utilities,equipment_assets,miscellaneous',
                'amount' => 'required|numeric|min:0',
                'expense_date' => 'required|date|before_or_equal:today',
                'description' => 'nullable|string|max:1000',
                'payment_mode' => 'required|in:cash,bank_transfer,upi,card,cheque,other',
                'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            ]);

            $validated['business_id'] = auth()->user()->business_id;
            $validated['created_by'] = auth()->id();

            // Handle file upload
            if ($request->hasFile('proof_file')) {
                $file = $request->file('proof_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('expense_proofs'), $filename);
                $validated['proof_file_path'] = 'expense_proofs/' . $filename;
            }

            $expense = Expense::create($validated);

            return redirect()->route('expenses.index')
                ->with('success', 'Expense created successfully!');
        } catch (\Exception $e) {
            Log::error('Expense creation error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create expense.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        // Check business ownership
        if ($expense->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        // Check permissions
         if (!auth()->user()->hasPermission('view_expense') && !auth()->user()->hasPermission('add_expense')) {
             return redirect()->route('expenses.index')
                 ->with('error', 'You do not have permission to view this expense.');
         }

         // If no view_all_expenses, check ownership
         if (!auth()->user()->hasPermission('view_all_expenses') && $expense->created_by !== auth()->id()) {
             return redirect()->route('expenses.index')
                 ->with('error', 'You do not have permission to view this expense.');
         }

        $expense->load('createdBy');
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        // Check business ownership
        if ($expense->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        // Check permissions
         if (!auth()->user()->hasPermission('add_expense')) {
             return redirect()->route('expenses.index')
                 ->with('error', 'You do not have permission to edit expenses.');
         }

         // If no view_all_expenses, check ownership
         if (!auth()->user()->hasPermission('view_all_expenses') && $expense->created_by !== auth()->id()) {
             return redirect()->route('expenses.index')
                 ->with('error', 'You do not have permission to edit this expense.');
         }

        $categories = [
            'staff_payroll' => 'Staff Payroll',
            'rent_property_expense' => 'Rent / Property Expense',
            'electricity_utilities' => 'Electricity / Utilities',
            'equipment_assets' => 'Equipment / Assets',
            'miscellaneous' => 'Miscellaneous',
        ];

        $paymentModes = [
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'upi' => 'UPI',
            'card' => 'Card',
            'cheque' => 'Cheque',
            'other' => 'Other',
        ];

        return view('expenses.edit', compact('expense', 'categories', 'paymentModes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Check business ownership
        if ($expense->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        // Check permissions
         if (!auth()->user()->hasPermission('add_expense')) {
             return redirect()->route('expenses.index')
                 ->with('error', 'You do not have permission to edit expenses.');
         }

         // If no view_all_expenses, check ownership
         if (!auth()->user()->hasPermission('view_all_expenses') && $expense->created_by !== auth()->id()) {
             return redirect()->route('expenses.index')
                 ->with('error', 'You do not have permission to edit this expense.');
         }

        try {
            $validated = $request->validate([
                'category' => 'required|in:staff_payroll,rent_property_expense,electricity_utilities,equipment_assets,miscellaneous',
                'amount' => 'required|numeric|min:0',
                'expense_date' => 'required|date|before_or_equal:today',
                'description' => 'nullable|string|max:1000',
                'payment_mode' => 'required|in:cash,bank_transfer,upi,card,cheque,other',
                'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            // Handle file upload
            if ($request->hasFile('proof_file')) {
                // Delete old file if exists
                if ($expense->proof_file_path && file_exists(public_path($expense->proof_file_path))) {
                    unlink(public_path($expense->proof_file_path));
                }

                $file = $request->file('proof_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('expense_proofs'), $filename);
                $validated['proof_file_path'] = 'expense_proofs/' . $filename;
            }

            $expense->update($validated);

            return redirect()->route('expenses.show', $expense)
                ->with('success', 'Expense updated successfully!');
        } catch (\Exception $e) {
            Log::error('Expense update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update expense.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        // Check business ownership
        if ($expense->business_id !== auth()->user()->business_id) {
            abort(404);
        }

        // Check permissions
         if (!auth()->user()->hasPermission('add_expense')) {
             return redirect()->route('expenses.index')
                 ->with('error', 'You do not have permission to delete expenses.');
         }

         // If no view_all_expenses, check ownership
         if (!auth()->user()->hasPermission('view_all_expenses') && $expense->created_by !== auth()->id()) {
             return redirect()->route('expenses.index')
                 ->with('error', 'You do not have permission to delete this expense.');
         }

        try {
            // Delete associated file if exists
            if ($expense->proof_file_path && file_exists(public_path($expense->proof_file_path))) {
                unlink(public_path($expense->proof_file_path));
            }

            $expense->delete();

            return redirect()->route('expenses.index')
                ->with('success', 'Expense deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Expense deletion error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete expense.');
        }
    }
}
