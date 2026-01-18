<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Module extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory, Translatable;

    public $translatedAttributes = ['name'];

    public function programmes()
    {
        return $this->belongsToMany(Programme::class, 'programme_modules');
    }
    
    public function moduleTranslation()
    {
        return $this->hasMany(ModuleTranslation::class);
    }
}
