<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ResourceTranslation extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ResourceTranslationFactory> */
    use HasFactory, InteractsWithMedia;

    public $timestamps = false;

    protected $fillable = ['title'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('resources');
    }
}
