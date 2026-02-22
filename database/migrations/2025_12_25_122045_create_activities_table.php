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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            // Core identifiers
            $table->string('activity_code')->unique();
            $table->string('activity_type')->nullable();

            // Offering details
            $table->foreignId('campus_id')->constrained('campuses')->onDelete('cascade');

            // Instructor / staff
            $table->string('instructor')->nullable();
            $table->string('responsible_staff')->nullable();

            // Execution period
            $table->date('execution_from');
            $table->date('execution_to');

            // Time slot
            $table->dateTime('time_slot_from')->nullable();
            $table->dateTime('time_slot_to')->nullable();
            $table->decimal('duration_hours', 4, 2)->default(0);

            // Description & attributes
            $table->boolean('swpd_programme')->default(false);

            // Venue
            $table->string('venue')->nullable();
            $table->text('venue_remark')->nullable();

            // Capacity & enrolment
            $table->integer('capacity')->default(0);
            $table->integer('registered')->default(0);

            // Financials
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('included_deposit', 10, 2)->default(0);

            // Attachments
            $table->string('attachment')->nullable();

            $table->timestamps();

        });

        Schema::create('activity_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->tinyText('title');
            $table->mediumText('description')->nullable();
            $table->tinyText('discipline')->nullable();
            $table->tinyText('attribute')->nullable();
            $table->timestamps();
            $table->unique(['activity_id', 'locale']);
            $table->fullText(['title', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_translations');
        Schema::dropIfExists('activities');
    }
};
