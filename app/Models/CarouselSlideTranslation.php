<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselSlideTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\CarouselSlideTranslationFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title', 'description'];
}
