<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Perimeter;

class PerimeterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perimeter::create([
            'lat' => '-6.9256826',
            'long' => '107.6019216',
            'address' => 'Jl. Kautamaan Istri No.12, Balonggede, Kec. Regol, Kota Bandung, Jawa Barat 40251',
            'radius' => "1"
        ]);
    }
}
