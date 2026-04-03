<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model implements TranslatableContract
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory, Translatable;

    public $translatedAttributes = ['name'];

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function programmes(): BelongsToMany
    {
        return $this->belongsToMany(Programme::class, 'programme_modules');
    }
    
    public function moduleTranslation(): HasMany
    {
        return $this->hasMany(ModuleTranslation::class);
    }
}
