<?php
namespace App\Helpers;

use App\Models\Absence;
use App\Models\Presence;

class PresensiValidation
{
    public static function validatePresensiMasuk($nipd)
    {
        $isPresensiExists = Presence::where('nipd', $nipd)->whereDate('created_at', today())->first();
        if ($isPresensiExists) {
            $errCode = 222;
            return $errCode;
        }

        $isAbsenceExists = Absence::where('nipd', $nipd)->whereDate('absence_date', today())->whereIn('absence_type', ['sakit', 'izin'])->first();
        if ($isAbsenceExists) {
            $errCode = 223;
            return $errCode;
        }

        $isAbsenceWithoutNotes = Absence::where('nipd', $nipd)->whereDate('absence_date', today())->where('absence_type', 'alfa')->first();
        if ($isAbsenceExists) {
            $errCode = 224;
            return $errCode;
        }
    }

    public static function validatePresensiKeluar($nipd)
    {
        $isPresensiExists = Presence::where('nipd', $nipd)->whereDate('created_at', today())->first();
        if (!$isPresensiExists) {
            return false;
        }
        return true;
    }
}


