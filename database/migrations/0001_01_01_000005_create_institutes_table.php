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
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        
        Schema::create('institute_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->tinyText('name');
            $table->timestamps();
            $table->unique(['institute_id', 'locale']);
        });
        
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        
        Schema::create('campus_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->tinyText('name');
            $table->timestamps();
            $table->unique(['campus_id', 'locale']);
        });

        Schema::create('institute_campus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['institute_id', 'campus_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_campuses');
        Schema::dropIfExists('campus_translations');
        Schema::dropIfExists('campuses');
        Schema::dropIfExists('institute_translations');
        Schema::dropIfExists('institutes');
    }
};
