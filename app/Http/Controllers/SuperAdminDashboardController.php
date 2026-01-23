<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactRequest;
use App\Models\Business;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $contactMessagesCount = ContactRequest::count();
        $businessOwnersCount = Business::count();
        $activeBusinessesCount = Business::where('is_active', true)->count();
        $recentMessagesCount = ContactRequest::where('created_at', '>=', now()->subHours(24))->count();

        return view('superadmin.dashboard', compact(
            'contactMessagesCount',
            'businessOwnersCount',
            'activeBusinessesCount',
            'recentMessagesCount'
        ));
    }
}
