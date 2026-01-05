<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsArticleTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\NewsArticleTranslationFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title', 'content'];
}
