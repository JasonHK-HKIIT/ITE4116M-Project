<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    /** @use HasFactory<\Database\Factories\ProgrammeFactory> */
    use HasFactory;

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'programme_modules');
    }

    public function programmeTranslation()
    {
        return $this->hasMany(ProgrammeTranslation::class);
    }
}
