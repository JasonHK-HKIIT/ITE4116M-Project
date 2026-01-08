<?php

namespace App\Models;

use App\Enums\InformationCentreStatus;
use Illuminate\Database\Eloquent\Model;

class InformationCentre extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'filename',
        'status',
        'published_on',
    ];

    protected $casts = [
        'status' => InformationCentreStatus::class,
        'published_on' => 'date',
    ];
}
