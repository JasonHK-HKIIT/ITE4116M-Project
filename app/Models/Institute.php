<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institute extends Model implements TranslatableContract
{
    /** @use HasFactory<\Database\Factories\InstituteFactory> */
    use HasFactory, Translatable;

    public $translatedAttributes = ['name'];

    public function instituteTranslation(): HasMany
    {
        return $this->hasMany(InstituteTranslation::class);
    }
}
