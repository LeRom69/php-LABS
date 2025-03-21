<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class rvvController extends Controller
{
    public function testMethod()
    {
        return response()->json(['message' => 'Test method executed successfully']);
    }
}
