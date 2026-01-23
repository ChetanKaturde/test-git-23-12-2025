<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\ContactRequest;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|regex:/^[0-9]{10}$/',
            'business_type' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ]);

        try {
            // Save contact request to database
            ContactRequest::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'business_type' => $request->business_type,
                'message' => $request->message,
            ]);

            // Log the contact form submission
            Log::info('Contact form submission saved', [
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'business_type' => $request->business_type,
                'message' => $request->message,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            // In a real application, you would send an email here
            // Mail::to('contact@monitorbizz.com')->send(new ContactFormMail($request->all()));

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message! We will get back to you within 24 hours.'
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again.'
            ], 500);
        }
    }
}