<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityTranslation extends Model
{
    protected $table = 'activity_translations';

    protected $fillable = ['title', 'description', 'venue_remark', 'locale'];

    public $timestamps = true;

    public $incrementing = true;
}
