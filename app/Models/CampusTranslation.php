<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['name'];
}
