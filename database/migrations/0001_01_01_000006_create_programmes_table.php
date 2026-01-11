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
            $table->tinyText('name_en');
            $table->tinyText('name_zh_HK');
            $table->tinyText('name_zh_CN');
            $table->timestamps();
            $table->unique(['institute_id', 'programme_code']);
        });
        
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id');
            $table->tinyText('module_code');
            $table->timestamps();
            $table->unique(['institute_id', 'module_code']);
        });
        
        Schema::create('programme_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id');
            $table->foreignId('module_id');
            $table->timestamps();
            $table->unique(['programme_id', 'module_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programme_modules');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('programmes');
    }
};
