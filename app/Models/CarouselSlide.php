<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CarouselSlide extends Model implements HasMedia, TranslatableContract
{
    /** @use HasFactory<\Database\Factories\CarouselSlideFactory> */
    use HasFactory, InteractsWithMedia, Translatable;

    protected $fillable = [
        'is_active',
        'position',
        'link_url',
    ];

    public $translatedAttributes = ['title', 'description'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    public function carouselSlideTranslation(): HasMany
    {
        return $this->hasMany(CarouselSlideTranslation::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('image')
            ->useFallbackUrl('/images/placeholder.svg')
            ->singleFile();
    }
}
