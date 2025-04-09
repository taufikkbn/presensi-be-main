<?php

namespace App\Http\Controllers;

use App\Models\Perimeter;
use Illuminate\Http\Request;

class LBSController extends Controller
{
    public function checkPerimeter(Request $request)
    {
        $lat = $request->query('lat');
        $long = $request->query('long');

        // Ambil semua data perimeter dari database
        $perimeter = Perimeter::first();

        $perimeterLat = $perimeter->lat;
        $perimeterLong = $perimeter->long;
        $radius = $perimeter->radius;

        // Hitung jarak antara koordinat pengguna dan pusat perimeter menggunakan rumus haversine
        $distance = $this->haversineDistance($lat, $long, $perimeterLat, $perimeterLong);

        // Jika jarak lebih kecil atau sama dengan radius, pengguna berada di dalam perimeter
        if ($distance <= $radius) {
            return response()->json([
                'status' => 'inside',
                'lat' => $lat,
                'long' => $long
            ], 200);
        } else {
            return response()->json([
                'status' => 'outside',
                'lat' => $lat,
                'long' => $long
            ], 200);
        }
    }

    private function haversineDistance($lat1, $long1, $lat2, $long2)
    {
        $earthRadius = 6371; // Radius bumi dalam kilometer

        $latDiff = deg2rad($lat2 - $lat1);
        $longDiff = deg2rad($long2 - $long1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($longDiff / 2) * sin($longDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }

}
