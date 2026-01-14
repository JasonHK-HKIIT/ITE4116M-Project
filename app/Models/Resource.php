<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Resource extends Model implements TranslatableContract, HasMedia
{
    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory, Translatable, InteractsWithMedia;

    public $translatedAttributes = ['title'];

    public function resourceTranslation(): HasMany
    {
        return $this->hasMany(ResourceTranslation::class);
    }
}
