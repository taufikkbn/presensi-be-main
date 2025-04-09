<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Classroom;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $gender = [
            'L' => 'Laki-Laki',
            'P' => 'Perempuan',
        ];

        $religion = [
            'islam' => 'Islam',
            'katolik' => 'Katolik',
            'protestan' => 'Protestan',
            'hindu' => 'Hindu',
            'budha' => 'Budha',
            'konghucu' => 'Konghucu',
        ];

        return [
            'nipd' => $this->nipd,
            'email' => $this->user->email,
            'name' => $this->name,
            'classroom' => $this->classroom->class_name . ' ' . $this->classroom->type,
            'classroom_detail' => [
                'classroom_name' => $this->classroom->class_name,
                'classroom_type' => $this->classroom->type,
                'classroom_id' => $this->classroom->id,
                'other_classroom' => Classroom::select('id', 'class_name', 'type')->get()
            ],
            'gender' => $this->gender === "L" ? 'Laki-Laki' : 'Perempuan',
            'gender_value' => $this->gender,
            'gender_data' => $gender,
            'pob' => $this->pob,
            'dob' => $this->dob,
            'pob_dob' => $this->pob . ', ' . Carbon::parse($this->dob)->format('d M Y'),
            'religion' => $this->religion,
            'religion_detail' => $religion,
            'address' => $this->address,
            'residence_type' => $this->residence_type,
            'photo' => Storage::url('photo/' . $this->nipd . '/' . $this->photo)
        ];
    }
}
