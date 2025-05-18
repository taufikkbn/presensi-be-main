<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\CreateStudentAccount;
use App\Events\UpdateStudentAccount;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;

class StudentController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::pluck(\DB::raw("CONCAT(class_name, ' - ', type)"), 'id')->toArray();
        $students = Student::get();
        return view('pages.students.index', compact('classrooms', 'students'));
    }

    public function show(Student $student)
    {
        $data = $student->load('classroom', 'user');
        return response()->json(new StudentResource($data), 200);
    }

    public function store(StudentStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $path = $request->file('photo')->store('public/student_faces');;
            $publicPath = Storage::url($path);

            $data = Student::create($validated);
            $data->photo = $publicPath;
            $data->save();
            $studentAccount = event(new CreateStudentAccount($data, $validated['email']));
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data Siswa Berhasil Ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }
    public function update(StudentUpdateRequest $request, Student $student)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $image = $request->file('photoEdit');
            if ($image) {
                $path = $request->file('photoEdit')->store('public/student_faces');
                $publicPath = Storage::url($path);

                $student->update([
                    'photo' => $publicPath, // Updated the photo field here
                ]);
            }

            $student->update([
                'nipd' => $validated['nipdEdit'],
                'name' => $validated['nameEdit'],
                'class_id' => $validated['class_idEdit'],
                'gender' => $validated['genderEdit'],
                'religion' => $validated['religionEdit'],
                'pob' => $validated['pobEdit'],
                'dob' => $validated['dobEdit'],
                'address' => $validated['addressEdit'],
                'residence_type' => $validated['residence_typeEdit'],
            ]);

            event(new UpdateStudentAccount($student, $validated['emailEdit']));

            DB::commit();
            return redirect()->back()->with('successMsg', 'Data Siswa Berhasil Diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }


    public function destroy(Student $student)
    {
        DB::beginTransaction();
        try {
            $user = User::where('id', $student->user_id)->delete();
            Storage::deleteDirectory('public/photo/'.$student->nipd);
            $student->delete();
            DB::commit();
            return response()->json('success', 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }
}
