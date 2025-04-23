<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    public function index()
    {
        $data = Classroom::orderBy('class_name', 'ASC')->get();
        return view('pages.class.index', compact('data'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'class_name' => 'required',
                'type' => 'required'
            ]);
            $store = Classroom::create([
                'class_name' => $request->class_name,
                'type' => $request->type
            ]);
            // dd($request->all());
            DB::commit();
            return redirect()->back()->with('successMsg', 'Data Kelas Berhasil Ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('errorMsg', $e->getMessage());
        }
    }

    public function edit(Classroom $class)
    {
        return response()->json($class, 200);
    }

    public function update(Request $request, Classroom $class)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'class_name_edit' => 'required',
                'type_class_edit' => 'required'
            ]);
            $class->update([
                'class_name' => $request->class_name_edit,
                'type' => $request->type_class_edit
            ]);
            DB::commit();
            return redirect()->back()->with('successMsg', 'Data Kelas Berhasil Ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('errorMsg', $e->getMessage());
        }
    }

    public function destroy(Classroom $class)
    {
        try {
            if ($class->students()->exists() || $class->teachers()->exists()) {
                return response()->json([
                    'message' => 'Kelas tidak dapat dihapus karena masih ada relasi dari kelas ini yang terdaftar',
                ], 422);
            }

            $class->delete();
            return response()->json('success', 200);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

    }
}
