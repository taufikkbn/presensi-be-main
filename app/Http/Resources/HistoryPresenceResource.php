<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryPresenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $status = 'belum hadir';
        if ($this->presence_in) {
            $status = 'hadir';
        } elseif ($this->absence_date) {
            $status = $this->absence_type;
        }
        return [
            'status' => $status,
            'name' => $this->name,
            'nipd' => $this->nipd,
            'presence_in' => $this->presence_in,
            'absence_date' => $this->absence_date,
            'photo' => Storage::url('photo/' . $this->nipd . '/' . $this->photo)
        ];
    }
}
