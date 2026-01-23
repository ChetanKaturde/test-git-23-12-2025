<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;

class SuperAdminBusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::with(['users' => function($q) {
            $q->where('role', 'admin')->limit(1); // Get the admin user
        }]);

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('users', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->where('role', 'admin');
                  });
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $businesses = $query->paginate(15);

        return view('superadmin.business-owners', compact('businesses'));
    }
}
