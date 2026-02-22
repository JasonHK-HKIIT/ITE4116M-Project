<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Astrotomic\Translatable\Translatable;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory, Translatable;

    protected $table = 'activities';

    public array $translatedAttributes = ['title', 'description', 'discipline', 'attribute'];

    protected $fillable = [
        'activity_code',
        'activity_type',
        'campus_id',
        'instructor',
        'responsible_staff',
        'execution_from',
        'execution_to',
        'time_slot_from',
        'time_slot_to',
        'duration_hours',
        'swpd_programme',
        'venue',
        'venue_remark',
        'capacity',
        'registered',
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
        'total_amount'     => 'decimal:2',
        'included_deposit' => 'decimal:2',
    ];

    /**
     * Get the campus associated with the activity.
     */
    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function getHasVacancyAttribute()
    {
        return $this->registered < $this->capacity;
    }

}
