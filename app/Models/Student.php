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
        'institute_campus_id',
        'gender',
        'date_of_birth',
        'nationality',
        'mother_tongue',
        'tel_no',
        'mobile_no',
        'address',
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

    public function instituteCampus(): BelongsTo
    {
        return $this->belongsTo(InstituteCampus::class);
    }
    
    protected function studentId(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->user->username,
            set: fn(string $value) => ($this->user->username = $value));
    }
}
