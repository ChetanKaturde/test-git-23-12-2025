<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactRequest;
use Illuminate\Support\Facades\Response;

class SuperAdminContactController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactRequest::query();

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $contactRequests = $query->paginate(15);

        return view('superadmin.contact-messages', compact('contactRequests'));
    }

    public function destroy(ContactRequest $contactRequest)
    {
        $contactRequest->delete();
        return redirect()->back()->with('success', 'Contact message deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:contact_requests,id',
        ]);

        ContactRequest::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', 'Selected contact messages deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = ContactRequest::query();

        // Apply same search filter as index
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $contactRequests = $query->orderBy('created_at', 'desc')->get();

        $filename = 'contact_messages_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($contactRequests) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, ['Name', 'Email', 'Phone', 'Message', 'Created Date']);

            // Data rows
            foreach ($contactRequests as $contact) {
                fputcsv($file, [
                    $contact->name,
                    $contact->email,
                    $contact->mobile,
                    $contact->message,
                    $contact->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
