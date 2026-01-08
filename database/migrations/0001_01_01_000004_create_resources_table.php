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
        Schema::create('resources', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
        });

        Schema::create('resource_translations', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('resource_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->tinyText('title');
            $table->unique(['resource_id', 'locale']);
            $table->fullText(['title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_translations');
        Schema::dropIfExists('resources');
    }
};
