<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PresenceOutController extends Controller
{
    public function check()
    {
        $isExists = Presence::where('nipd', Auth::guard('api')->user()->student->nipd)->whereDate('presence_in', today())->where('presence_out', null)->first();
        if ($isExists) {
            return response()->json([
                'status' => 'success',
                'data' => $isExists
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => $isExists
            ]);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $presenceData = Presence::where('nipd', Auth::guard('api')->user()->student->nipd)->whereDate('presence_in', today())->first();
            $presenceData->presence_out = Carbon::now();
            if ($request->has('override') && $request->query('override') === 'true') {
                $presenceData->presence_out_note = 'Pulang lebih awal';
            }
            $presenceData->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $presenceData
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'data' => $e->getMessage()
            ], 200);
        }
    }
}
