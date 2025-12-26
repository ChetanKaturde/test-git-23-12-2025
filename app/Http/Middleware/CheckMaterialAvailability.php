<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Material;

class CheckMaterialAvailability
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only check for POST requests that contain items (purchase orders, etc.)
        if ($request->isMethod('post') && $request->has('items')) {
            foreach ($request->input('items', []) as $index => $item) {
                if (isset($item['material_id'])) {
                    $material = Material::find($item['material_id']);
                    
                    if (!$material || !$material->isAvailable()) {
                        $materialName = $material ? $material->name : 'Unknown material';
                        
                        return back()
                            ->withInput()
                            ->withErrors([
                                "items.{$index}.material_id" => "Material '{$materialName}' is not available for ordering."
                            ])
                            ->with('error', 'One or more selected materials are not available.');
                    }
                }
            }
        }
        
        return $next($request);
    }
}