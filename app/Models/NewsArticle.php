<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContracts;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class NewsArticle extends Model implements TranslatableContracts, HasMedia
{
    /** @use HasFactory<\Database\Factories\NewsArticleFactory> */
    use HasFactory, Translatable, InteractsWithMedia;

    protected $fillable = [
        'slug',
        'is_published',
        'published_on',
    ];

    public $translatedAttributes = ['title', 'content'];

    public function newsArticleTranslation(): HasMany
    {
        return $this->hasMany(NewsArticleTranslation::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('cover')
            ->useFallbackUrl('/images/placeholder.svg')
            ->singleFile();
    }
}
