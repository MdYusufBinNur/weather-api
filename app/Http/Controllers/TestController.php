<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        return response()->json([
            'Message' => 'Weather API from Test Controller'
        ]);
    }
}
