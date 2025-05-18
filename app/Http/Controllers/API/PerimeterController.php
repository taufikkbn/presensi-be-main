<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Perimeter;

class PerimeterController extends Controller
{
    public function location()
    {
        try {
            $perimeter = Perimeter::first();

            return response()->json([
                'address' => $perimeter->address,
                'latitude' => $perimeter->lat,
                'longitude' => $perimeter->long,
                'radius' => $perimeter->radius,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
