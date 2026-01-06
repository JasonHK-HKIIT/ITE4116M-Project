<?php

use App\Enums\Language;
use App\Enums\NewsArticleStatus;
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
            $table->string('slug', 127);
            $table->enum('status', NewsArticleStatus::values())->default(NewsArticleStatus::Draft);
            $table->date('published_on')->nullable();
            $table->timestamps();
        });

        Schema::create('news_article_translations', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('news_article_id')->constrained()->onDelete('cascade');
            $table->string('locale', 10)->index();
            $table->tinyText('title');
            $table->mediumText('content');
            $table->unique(['news_article_id', 'locale']);
            $table->fullText(['title', 'content']);
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
