<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model implements TranslatableContract
{
    /** @use HasFactory<\Database\Factories\ProgrammeFactory> */
    use HasFactory, Translatable;

    protected $fillable = [
        'institute_id',
        'programme_code',
    ];

    public $translatedAttributes = ['name'];

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'programme_modules');
    }

    public function programmeTranslation(): HasMany
    {
        return $this->hasMany(ProgrammeTranslation::class);
    }
}
