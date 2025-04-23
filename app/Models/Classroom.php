<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = [
        'class_name',
        'type'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    // Define relationship with students
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id', 'id');
    }

    // Define relationship with teachers
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'class_id', 'id');
    }
}
