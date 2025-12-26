<?php

// app/Http/Controllers/CSRFController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CSRFController extends Controller
{
    public function refresh()
    {
        return response()->json([
            'token' => csrf_token()
        ]);
    }
}