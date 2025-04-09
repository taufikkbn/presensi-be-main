<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Presence;
use App\Models\Absence;
use App\Helpers\PresensiValidation;

class OfflinePresenceController extends Controller
{
    public function store(Request $request)
    {
        $student = Student::where('nipd', Auth::guard('api')->user()->student->nipd)->firstOrFail();
        $validatePresensi = PresensiValidation::validatePresensiMasuk($student->nipd);
        $presence_in = Carbon::now();

        if ($validatePresensi === 222) {
            return response()->json([
                'status' => 222,
                'message' => 'Anda sudah melakukan presensi'
            ], 200);
        } elseif ($validatePresensi === 223) {
            if ($request->has('override') && $request->query('override') === 'true') {
                DB::beginTransaction();
                try {
                    $presence = Presence::create([
                        'nipd' => $student->nipd,
                        'learning_type' => 'offline',
                        'presence_in' => $presence_in
                    ]);
                    Absence::where('nipd', $student->nipd)->whereDate('absence_date', Carbon::today())->delete();
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'data' => $presence,
                    ], 201);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json($e->getMessage(), 500);
                }
            } else {
                $absenceType = Absence::where('nipd', $student->nipd)
                    ->whereDate('absence_date', Carbon::today())
                    ->value('absence_type');
                return response()->json([
                    'status' => 223,
                    'message' => "Anda tercatat sudah melaporkan $absenceType"
                ], 200);
            }
        } elseif ($validatePresensi === 224) {
            return response()->json([
                'status' => 224,
                'message' => 'Anda sudah tercatat absen kelas karena terlambat tanpa keterangan'
            ], 200);
        }

        DB::beginTransaction();
        try {
            $presence = Presence::create([
                'nipd' => $student->nipd,
                'learning_type' => 'offline',
                'presence_in' => $presence_in,
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $presence,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }
}