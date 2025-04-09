<?php

namespace App\Utils;

use Carbon\Carbon;

class ScheduleValidator
{
    public static function validate($type)
    {
        $now = Carbon::now();
        $currentTime = $now->hour * 60 + $now->minute;
        $allowedStartTime = ($type === 'pagi' ? 8 : 12) * 60 - 20;
        $allowedEndTime = ($type === 'pagi' ? 8 : 12) * 60 + 5;

        if ($currentTime < $allowedStartTime) {
            $errorMessage = 'Minimal 20 menit sebelum jam pelajaran mulai';
            return $errorMessage;
        } elseif ($currentTime > $allowedEndTime) {
            $errorMessage = 'Anda telat memasuki kelas';
            return $errorMessage;
        }

        return true;
    }
}
