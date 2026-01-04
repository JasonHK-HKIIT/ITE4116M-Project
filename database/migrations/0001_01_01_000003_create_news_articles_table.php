<?php

use App\Enums\Language;
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
        Schema::create('news_articles', function (Blueprint $table)
        {
            $table->id();
            $table->tinyText('slug');
            $table->enum('language', Language::values());
            $table->tinyText('title');
            $table->boolean('is_published')->default(false);
            $table->date('published_on')->nullable();
            $table->mediumText('content');
            $table->timestamps();
            $table->unique(['slug', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
