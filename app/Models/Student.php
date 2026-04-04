<?php

namespace App\Models;

use App\Models\InstituteCampus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = [
        'gender',
        'date_of_birth',
        'nationality',
        'mother_tongue',
        'tel_no',
        'mobile_no',
        'address',
        'institute_id',
        'campus_id',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function modules()
    {
        return $this->belongsToMany(Module::class, 'student_modules');
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'student_classes', 'student_id', 'class_id');
    }

    public function programmes()
    {
        return $this->classes()->with('programme')->get()->pluck('programme')->unique('id');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    protected function studentId(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->user->username,
            set: fn(string $value) => ($this->user->username = $value)
        );
    }
}
