<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryPresenceTodayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = 'belum hadir';
        if ($this->presencesToday) {
            $status = 'hadir';
        } elseif ($this->absencesToday) {
            $status = $this->absencesToday->absence_type;
        }

        $presence_in = null;
        if ($status === 'hadir') {
            $presence_in = Carbon::parse($this->presencesToday->presence_in)->toTimeString();
        }

        // $absence = null;
        // if ($this->absencesOnDate) {
        //     $absence = $this->absencesOnDate->absence_date;
        // }
        return [
            'status' => $status,
            'name' => $this->name,
            'nipd' => $this->nipd,
            'presence_in' => $presence_in,
            'photo' => Storage::url('photo/' . $this->nipd . '/' . $this->photo)
        ];
    }
}
