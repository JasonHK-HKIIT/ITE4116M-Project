<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;

class NewsArticleTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\NewsArticleTranslationFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title', 'content'];

    protected function casts()
    {
        return [
            'content' => PurifyHtmlOnGet::class,
        ];
    }
}
