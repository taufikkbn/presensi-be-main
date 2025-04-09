<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absence extends Model
{
    use HasFactory;
    protected $table = 'absences';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nipd',
        'absence_type',
        'absence_note',
        'attachment',
        'absence_date',
    ];
    
    // protected $dates = ['absence_date'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'nipd', 'nipd');
    }

    public function scopeSickness($query)
    {
        return $query->where('absence_type', 'sakit');
    }

    public function scopePermissions($query)
    {
        return $query->where('absence_type', 'izin');
    }

    public function scopeWithoutNotes($query)
    {
        return $query->where('absence_type', 'alfa');
    }

    public function scopeSemester($query)
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfDay();
        return $query->where('absence_date', '>=', $sixMonthsAgo);
    }
}
