<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::where('business_id', auth()->user()->business_id)->get();
        return view('machines.index', compact('machines'));
    }

    public function create()
    {
        return view('machines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cnc,lathe,welding,cutting,drilling,milling,other',
            'model' => 'nullable|string|max:100',
            'status' => 'required|in:available,in_use,maintenance,broken',
        ]);

        $validated['business_id'] = auth()->user()->business_id;
        $validated['code'] = $this->generateMachineCode($validated['business_id']);

        Machine::create($validated);

        return redirect()->route('machines.index')->with('success', 'Machine added successfully!');
    }

    public function show(Machine $machine)
    {
        return view('machines.show', compact('machine'));
    }

    public function edit(Machine $machine)
    {
        return view('machines.edit', compact('machine'));
    }

    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cnc,lathe,welding,cutting,drilling,milling,other',
            'model' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:available,in_use,maintenance,broken',
        ]);

        $machine->update($validated);

        return redirect()->route('machines.index')->with('success', 'Machine updated successfully!');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();
        return redirect()->route('machines.index')->with('success', 'Machine deleted successfully!');
    }

    public function updateStatus(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,in_use,maintenance,broken',
        ]);

        // Only operators and admins can update machine status
        if (!in_array(auth()->user()->role, ['operator', 'admin'])) {
            return redirect()->back()->with('error', 'You do not have permission to update machine status.');
        }

        $oldStatus = $machine->status;
        $machine->update(['status' => $validated['status']]);

        $statusMessage = ucfirst(str_replace('_', ' ', $validated['status']));
        
        return redirect()->back()->with('success', "Machine {$machine->name} status updated to {$statusMessage}.");
    }

    private function generateMachineCode($businessId)
    {
        $maxCode = Machine::where('business_id', $businessId)
            ->selectRaw('MAX(CAST(SUBSTRING(code, 2) AS UNSIGNED)) as max_num')
            ->value('max_num') ?? 0;
        
        return 'M' . str_pad($maxCode + 1, 4, '0', STR_PAD_LEFT);
    }
}