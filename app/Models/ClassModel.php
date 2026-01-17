<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;
    // "classes" is a reserved word, so the class name cannot be "Class"
    protected $table = 'classes';

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_classes', 'class_id', 'student_id');
    }
}
