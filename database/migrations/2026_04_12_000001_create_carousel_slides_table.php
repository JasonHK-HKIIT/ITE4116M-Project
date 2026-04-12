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
        Schema::create('carousel_slides', function (Blueprint $table)
        {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('position')->default(1);
            $table->string('link_url')->nullable();
            $table->timestamps();
        });

        Schema::create('carousel_slide_translations', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('carousel_slide_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->tinyText('title');
            $table->text('description')->nullable();
            $table->unique(['carousel_slide_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousel_slide_translations');
        Schema::dropIfExists('carousel_slides');
    }
};
