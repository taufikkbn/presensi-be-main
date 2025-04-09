<?php

namespace App\Http\Controllers\Api;

use Auth;
use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Exports\PresensiExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\HistoryPresenceResource;
use App\Http\Resources\HistoryPresenceTodayResource;

class HistoryPresenceController extends Controller
{
    public function index(Request $request)
    {
        $nipd = Student::where('nipd', Auth::guard('api')->user()->student->nipd)->first();
        $rekapPresensiByNipd = Student::with(['presences', 'absences'])
            ->where('nipd', $nipd->nipd)
            ->select('nipd', 'name')
            ->latest()
            ->get();


        $response = [];

        foreach ($rekapPresensiByNipd as $student) {
            $data = [];
            foreach ($student->presences as $presence) {
                $data[] = [
                    'status' => 'hadir',
                    'learning_type' => $presence->learning_type,
                    'date' => Carbon::parse($presence->presence_in)->format('D, d-M-Y'),
                    'presence_in' => date('H:i:s', strtotime($presence->presence_in)),
                    'presence_out' => $presence->presence_out ? Carbon::parse($presence->presence_out)->toTimeString() : 'Belum Keluar',
                ];
            }
            foreach ($student->absences as $absence) {
                $data[] = [
                    'status' => $absence->absence_type,
                    'absence_note' => $absence->absence_note ? $absence->absence_note : 'Tanpa Catatan',
                    'absence_date' => Carbon::parse($absence->absence_date)->format('D, d-M-Y'),
                    'absence_created_at' => Carbon::parse($absence->created_at)->format('d-M-Y'),
                ];
            }

            $response[] = [
                'nipd' => $student->nipd,
                'name' => $student->name,
                'data' => $data,
            ];
        }
        return response()->json($response, 200);
    }

    public function today(Request $request)
    {
        $rekapPresensi = Student::with(['presencesToday', 'absencesToday'])
            ->where('class_id', Auth::guard('api')->user()->teacher->class_id)
            ->orderBy('name', 'asc')
            ->get();
        if ($request->has('download') && $request->query('download') === 'true') {
            $classroom = Auth::guard('api')->user()->teacher->classroom->class_name;
            $filename = 'Data-Presensi-Siswa-' . $classroom . '-' . Carbon::now()->toDateString() . '.xlsx';
            $downloadUrl = Storage::url('data-presensi/'.$filename);
            $export = Excel::store(new PresensiExport($rekapPresensi), 'public/data-presensi/'.$filename);
            return response()->json([
                'status' => 'success',
                'data' => HistoryPresenceResource::collection($rekapPresensi),
                'download' => url($downloadUrl)
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => HistoryPresenceTodayResource::collection($rekapPresensi)
            ], 200);
        }
    }

    public function presenceOnDate(Request $request)
    {
        if ($request->has('date') && $request->query('date')) {
            $date = $request->query('date');
            $rekapPresensi = DB::table('students')
                ->leftJoin('presences', function ($join) use ($date) {
                    $join->on('students.nipd', '=', 'presences.nipd')
                        ->whereNotNull('presences.presence_in')
                        ->whereDate('presences.presence_in', $date);
                })
                ->leftJoin('absences', function ($join) use ($date) {
                    $join->on('students.nipd', '=', 'absences.nipd')
                        ->whereNotNull('absences.absence_date')
                        ->whereDate('absences.absence_date', $date);
                })
                ->select(
                    'students.user_id',
                    'students.class_id',
                    'students.name',
                    'students.nipd',
                    'students.photo',
                    'students.residence_type',
                    'students.updated_at',
                    'presences.id as presence_id',
                    'presences.presence_in',
                    'absences.id as absence_id',
                    'absences.absence_type',
                    'absences.absence_date',
                    'absences.created_at as absence_created_at',
                    'absences.updated_at as absence_updated_at'
                )
                ->where('students.class_id', Auth::guard('api')->user()->teacher->class_id)
                ->orderBy('students.name', 'asc') // Mengurutkan berdasarkan nama siswa dari A-Z (asc)
                ->get();

            if ($request->has('download') && $request->query('download') === 'true') {
                $classroom = Auth::guard('api')->user()->teacher->classroom->class_name;
                $filename = 'Data-Presensi-Siswa-' . $classroom . '-' . $date . '.xlsx';
                $downloadUrl = Storage::url('data-presensi/'.$filename);
                $export = Excel::store(new PresensiExport($rekapPresensi), 'public/data-presensi/'.$filename);
                return response()->json([
                    'status' => 'success',
                    'data' => HistoryPresenceResource::collection($rekapPresensi),
                    'download' => url($downloadUrl)
                ], 200);
            } else {
                return response()->json([
                    'status' => 'success',
                    'data' => HistoryPresenceResource::collection($rekapPresensi)
                ], 200);
            }

        } else {
            return $this->today($request);
        }
    }
}
