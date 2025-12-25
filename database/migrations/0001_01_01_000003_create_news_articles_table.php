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
        Schema::create('news_articles', function (Blueprint $table)
        {
            $table->id();
            $table->boolean('is_published')->default(false);
            $table->date('published_on')->nullable();
            $table->timestamps();
        });

        Schema::create('news_article_contents', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('news_article_id');
            $table->enum('language', ['en', 'zh_HK', 'zh_CN']);
            $table->tinyText('title');
            $table->tinyText('thumbnail');
            $table->mediumText('content');
            $table->unique(['news_article_id', 'language']);
            $table->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_article_contents');
        Schema::dropIfExists('news_articles');
    }
};
