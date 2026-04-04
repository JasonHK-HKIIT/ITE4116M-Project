<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Astrotomic\Translatable\Translatable;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory, Translatable;

    protected $table = 'activities';

    public array $translatedAttributes = ['title', 'description', 'venue_remark'];



    protected $fillable = [
        'activity_type',
        'activity_code',
        'campus_id',
        'instructor',
        'responsible_staff',
        'execution_from',
        'execution_to',
        'time_slot_from_date',
        'time_slot_from_time',
        'time_slot_to_date',
        'time_slot_to_time',
        'duration_hours',
        'swpd_programme',
        'venue',
        'capacity',
        'registered',
        'total_amount',
        'included_deposit',
        'attachment',
        'discipline',
        'attribute',
    ];

    protected $casts = [
        'execution_from'    => 'date:Y-m-d',
        'execution_to'      => 'date:Y-m-d',
        'time_slot_from_date' => 'date:Y-m-d',
        'time_slot_from_time' => 'datetime:H:i',
        'time_slot_to_date'   => 'date:Y-m-d',
        'time_slot_to_time'   => 'datetime:H:i',
        'duration_hours'    => 'decimal:2',
        'swpd_programme'    => 'boolean',
        'total_amount'      => 'decimal:2',
        'included_deposit'  => 'decimal:2',
        'discipline'        => \App\Enums\Activity\Disciplines::class,
        'attribute'  => \App\Enums\Activity\Attributes::class,
    ];

    /**
     * Get the campus associated with the activity.
     */
    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function activityTranslation(): HasMany
    {
        return $this->hasMany(ActivityTranslation::class);
    }

    public function getHasVacancyAttribute()
    {
        return $this->registered < $this->capacity;
    }

}
