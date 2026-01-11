<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id');
            $table->tinyText('programme_code')->unique();
            $table->timestamps();
            $table->unique(['institute_id', 'programme_code']);
        });
        
        Schema::create('programme_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->tinyText('name');
            $table->timestamps();
            $table->unique(['programme_id', 'locale']);
        });
        
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id');
            $table->tinyText('module_code');
            $table->timestamps();
            $table->unique(['institute_id', 'module_code']);
        });
        
        Schema::create('module_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->tinyText('name');
            $table->timestamps();
            $table->unique(['module_id', 'locale']);
        });
        
        Schema::create('programme_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id');
            $table->foreignId('module_id');
            $table->timestamps();
            $table->unique(['programme_id', 'module_id']);
        });
        
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->year('academic_year');
            $table->foreignId('institute_campus_id')->constrained()->onDelete('cascade');
            $table->foreignId('programme_id')->constrained()->onDelete('cascade');
            $table->string('class_code', 10);
            $table->timestamps();
            $table->unique(['academic_year', 'institute_campus_id', 'module_id', 'class_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programme_modules');
        Schema::dropIfExists('module_translations');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('programme_translations');
        Schema::dropIfExists('programmes');
    }
};
