<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MaterialMissingAlert;

class MaterialCheckController extends Controller
{
  public function index()
{
    $requests = MaterialRequest::with('requestedBy')
        ->where('resolved', false)
        ->latest()
        ->get();

    return view('admin.material_requests.index', compact('requests'));
}

  
    public function check(Request $request)
    {
        $materialName = trim($request->input('material_name'));

        // Check if material already exists
        $material = Material::where('name', $materialName)->first();

        if ($material) {
            return response()->json([
                'status' => 'found',
                'material' => $material
            ]);
        }

        // Check if an unresolved request already exists
        $exists = MaterialRequest::where('name', $materialName)
                                 ->where('resolved', false)
                                 ->first();

        if (!$exists) {
            // Create new request
            $newRequest = MaterialRequest::create([
                'name' => $materialName,
                'requested_by' => auth()->id(),
            ]);

            // Send email to admin
            Mail::to('admin@inventory.com')->send(new MaterialMissingAlert($materialName, auth()->user()));

            // Optionally: Dispatch job to auto-delete in 10 min
             AutoDeleteMaterialRequest::dispatch($newRequest)->delay(now()->addMinutes(10));
        }

        return response()->json(['status' => 'not_found']);
    }
  public function dismiss($id)
{
    try {
        $request = MaterialRequest::findOrFail($id);
        $request->resolved = true;
        $request->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        \Log::error('Dismiss failed: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to dismiss request'], 500);
    }
}

}
