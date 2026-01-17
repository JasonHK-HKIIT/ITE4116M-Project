<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory;

    public function programmes()
    {
        return $this->belongsToMany(Programme::class, 'programme_modules');
    }
    public function moduleTranslation()
    {
        return $this->hasMany(ModuleTranslation::class);
    }
}
