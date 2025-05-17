<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perimeter;

class PerimeterController extends Controller
{
    public function index()
    {
        $perimeter = Perimeter::first();

        // Default coordinates (can be loaded from DB or config)
        $defaultLat = 40.7128; // New York as default
        $defaultLng = -74.0060;
        $defaultRadius = 1000; // meters

        return view('pages.perimeters.index', compact('perimeter', 'defaultLat', 'defaultLng', 'defaultRadius'));
    }

    public function store(Request $request)
    {
        try {
            $perimeter = Perimeter::first();
            $perimeter->radius = $request->radius;
            $perimeter->save();
            return response()->json('success', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);

        }
    }
}
