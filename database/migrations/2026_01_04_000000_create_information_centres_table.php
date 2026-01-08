<?php

use App\Enums\InformationCentreStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('information_centres', function (Blueprint $table) {
            $table->id();
            $table->string('title'); //eg. "Student Handbook 2025-26"
            $table->string('subtitle'); //eg. "English Version", "Chinese Version"
            $table->string('filename');
            $table->enum('status', InformationCentreStatus::values())->default(InformationCentreStatus::Draft);
            $table->date('published_on')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('information_centres');
    }
};
