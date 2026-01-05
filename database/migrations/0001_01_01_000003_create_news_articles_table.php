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
            $table->boolean('is_published')->default(false);
            $table->date('published_on')->nullable();
            $table->timestamps();
        });

        Schema::create('news_article_translations', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('news_article_id')->constrained()->onDelete('cascade');
            $table->string('locale', 10);
            $table->tinyText('title');
            $table->mediumText('content');
            $table->unique(['news_article_id', 'locale']);
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
