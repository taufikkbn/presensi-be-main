<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'classroom' => $this->classroom->class_name . '-' . $this->classroom->type,
            'email' => $this->user->email,
            'photo' => Storage::url('photo/teacher/' . $this->photo),
            'isDefault' => $this->user->isDefault
        ];
    }
}
