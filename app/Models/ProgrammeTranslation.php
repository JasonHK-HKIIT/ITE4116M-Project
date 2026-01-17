<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['programme_id', 'locale', 'name'];
}
