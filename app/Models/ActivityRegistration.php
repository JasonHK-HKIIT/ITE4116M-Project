<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityRegistration extends Model
{
    protected $table = 'activity_registrations';

    protected $fillable = [
        'activity_id',
        'student_id',
        'status',
    ];

    /**
     * Get the activity associated with this registration
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the student who registered
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope to get participate registrations
     */
    public function scopeParticipate($query)
    {
        return $query->where('status', 'participate');
    }

    /**
     * Scope to get registered registrations
     */
    public function scopeRegistered($query)
    {
        return $query->where('status', 'registered');
    }

    /**
     * Check if is participating
     */
    public function isParticipating(): bool
    {
        return $this->status === 'participate';
    }

    /**
     * Check if is registered
     */
    public function isRegistered(): bool
    {
        return $this->status === 'registered';
    }

    /**
     * Update to registered status
     */
    public function markAsRegistered(): bool
    {
        return $this->update(['status' => 'registered']);
    }

    /**
     * Update to participate status
     */
    public function markAsParticipate(): bool
    {
        return $this->update(['status' => 'participate']);
    }
}
