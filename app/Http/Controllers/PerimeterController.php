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

            if ($perimeter) {
                $perimeter->address = $request->address;
                $perimeter->lat = $request->lat;
                $perimeter->long = $request->long;
                $perimeter->radius = $request->radius;
                $perimeter->save();
            } else {
//                Perimeter::create($request->all());
            }

            return redirect()->back()->with('successMsg', 'Perimeter updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update perimeter: ' . $e->getMessage());
        }
    }
}
