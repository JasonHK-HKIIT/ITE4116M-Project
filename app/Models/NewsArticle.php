<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class NewsArticle extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\NewsArticleFactory> */
    use HasFactory, InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('cover')
            ->useFallbackUrl('/images/placeholder.svg')
            ->singleFile();
    }

    protected $fillable = [
        'slug',
        'language',
        'title',
        'is_published',
        'published_on',
        'content',
    ];
}
