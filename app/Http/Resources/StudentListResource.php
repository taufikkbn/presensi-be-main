<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nipd' => $this->nipd,
            'name' => $this->name,
            'classroom' => $this->classroom->class_name . ' - ' . $this->classroom->type,
            'email' => $this->user->email,
            'photo' => url(Storage::url('photo/' . $this->nipd . '/' . $this->photo)),
            'gender' => $this->gender === 'L' ? 'Laki-Laki' : 'Perempuan',
            'dob' => $this->pob . ', ' . Carbon::parse($this->dob)->format('d M Y'),
            'address' => $this->address,
            'residence_type' => $this->residence_type
        ];
    }
}
