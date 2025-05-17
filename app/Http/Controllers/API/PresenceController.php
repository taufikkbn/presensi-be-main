<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PresenceController extends Controller
{

    public function presence(Request $request)
    {
        try {
            // TODO : add some request
            $request->validate([
                'isMatch' => 'required|boolean',
                'isCheckIn' => 'required|boolean',
            ]);

            $userId = Auth::guard('api')->user()->id;

            $student = DB::table('students')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->where('user_id', $userId)
                ->first();

            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found'
                ], 404);
            }


            return response()->json([
                'status' => true,
                'message' => 'Presence recorded successfully',
                'data' => [
                    'isMatch' => $request->isMatch,
                    'isCheckIn' => $request->isCheckIn,
                    'time' => now(),
                    'user' => [
                        'id' => $student->user_id,
                        'name' => $student->name,
                        'email' => $student->email,
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to presence',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registerFaceUser(Request $request)
    {
        try {
            // Validate the request - ensure a photo file is uploaded
            $request->validate([
                'face' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            ]);

            $userId = Auth::guard('api')->user()->id;

            $student = DB::table('students')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->where('user_id', $userId)
                ->first();

            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            if ($request->hasFile('face')) {
                // Delete old photo if exists
                if ($student->face) {
                    Storage::delete($student->face);
                }

                // Store the new photo
                $path = $request->file('face')->store('public/student_faces');

                // If you want to save a publicly accessible URL instead
                $publicPath = Storage::url($path);

                // Update student record with the photo path
                DB::table('students')
                    ->where('user_id', $userId)
                    ->update([
                        'face' => $publicPath,
                    ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Face photo uploaded successfully',
                    'data' => [
                        'face' => $publicPath,
                        'user' => [
                            'id' => $student->user_id,
                            'name' => $student->name,
                            'email' => $student->email,
                        ],
                    ],
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to register face',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFaceUser()
    {
        try {
            $userId = Auth::guard('api')->user()->id;

            $student = DB::table('students')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->where('students.user_id', $userId)
                ->first();

            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found'
                ], 404);
            }


            return response()->json([
                'status' => true,
                'message' => 'Student data retrieved successfully',
                'data' => [
                    'face' => $student->face,
                    'user' => [
                        'id' => $student->user_id,
                        'name' => $student->name,
                        'email' => $student->email,
                    ],
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve student data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
