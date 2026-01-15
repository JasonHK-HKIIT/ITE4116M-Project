<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model implements TranslatableContract
{
    /** @use HasFactory<\Database\Factories\CampusFactory> */
    use HasFactory, Translatable;

    public $translatedAttributes = ['name'];

    public function campusTranslation(): HasMany
    {
        return $this->hasMany(CampusTranslation::class);
    }
}
