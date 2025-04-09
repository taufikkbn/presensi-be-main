<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perimeter;

class PerimeterController extends Controller
{
    public function index()
    {
        $perimeter = Perimeter::first();
        return view('pages.perimeters.index', compact('perimeter'));
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
