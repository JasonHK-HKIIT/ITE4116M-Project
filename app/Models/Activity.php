<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = [
        'activity_code',
        'activity_type',
        'title',
        'campus',
        'discipline',
        'instructor',
        'responsible_staff',
        'execution_from',
        'execution_to',
        'time_slot_from',
        'time_slot_to',
        'duration_hours',
        'description',
        'attribute',
        'swpd_programme',
        'venue',
        'venue_remark',
        'capacity',
        'registered',
        'has_vacancy',
        'total_amount',
        'included_deposit',
        'attachment',
    ];

    protected $casts = [
        'execution_from'   => 'date:Y-m-d',
        'execution_to'     => 'date:Y-m-d',
        'time_slot_from'   => 'datetime:Y-m-d H:i',
        'time_slot_to'     => 'datetime:Y-m-d H:i',
        'duration_hours'   => 'decimal:2',
        'swpd_programme'   => 'boolean',
        'has_vacancy'      => 'boolean',
        'total_amount'     => 'decimal:2',
        'included_deposit' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function ($activity) {
            $activity->has_vacancy = $activity->registered < $activity->capacity;
        });
    }

}
