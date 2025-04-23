<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Absence;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Presence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherInfoResource;

class InfoController extends Controller
{
    public function student()
    {
        try {
            $student = Student::where('nipd', Auth::guard('api')->user()->student->nipd)->firstOrFail();
            $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfDay();

            $totalPresence = Presence::where('nipd', $student->nipd)->where('created_at', '>=', $sixMonthsAgo)->count();

            $totalAbsencesDueToPermission = Absence::where('nipd', $student->nipd)->permissions()->semester()->count();
            $totalAbsencesDueToSickness = Absence::where('nipd', $student->nipd)->sickness()->semester()->count();
            $totalAbsencesWithoutNote = Absence::where('nipd', $student->nipd)->withoutNotes()->semester()->count();


            $rekapData = [
                'total_presences' => $totalPresence,
                'total_sickness' => $totalAbsencesDueToSickness,
                'total_due_permissions' => $totalAbsencesDueToPermission,
                'total_without_notes' => $totalAbsencesWithoutNote,
            ];

            return response()->json([
                'account_info' => Auth::guard('api')->user(),
                'student_info' => new StudentResource($student),
                'recap' => $rekapData
            ], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function teacher()
    {
        $data = Teacher::with('user')->where('id', Auth::guard('api')->user()->teacher->id)->first();
        return response()->json([
            'status' => 'success',
            'data' => new TeacherInfoResource($data)
        ], 200);
    }
}
