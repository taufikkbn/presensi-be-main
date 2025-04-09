<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index()
    {
        $data = Teacher::get();
        $classrooms = Classroom::pluck(\DB::raw("CONCAT(class_name, ' - ', type)"), 'id')->toArray();
        return view('pages.teachers.index', compact('data', 'classrooms'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'name' => 'required',
                'class_id' => 'required|exists:classrooms,id',
                'email' => 'required|email|unique:users,email',
                'photo' => 'required|image|max:3048'
            ]);

            $image = $request->file('photo');
            $filename = Str::random(8) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/photo/teacher/', $filename);

            $createTeacher = Teacher::create($validated);
            $createUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('welcometeacher'),
                'isDefault' => true,
                'role' => 'teacher'
            ]);
            $createTeacher->user_id = $createUser->id;
            $createTeacher->photo = $filename;
            $createTeacher->save();

            DB::commit();
            return redirect()->back()->with('successMsg', 'Data Siswa Berhasil Diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function destroy(Teacher $teacher)
    {
        DB::beginTransaction();
        try {
            $deleteUser = User::where('id', $teacher->user_id)->delete();
            Storage::delete('public/photo/teacher/'.$teacher->photo);
            $deleteTeacher = $teacher->delete();
            DB::commit();
            return response()->json('success', 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }
}
