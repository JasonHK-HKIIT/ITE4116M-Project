<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\NewsArticleStatus;

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
            $table->string('activity_type')->nullable();
            $table->string('activity_code')->nullable()->unique();

            // Offering details
            $table->foreignId('campus_id')->constrained('campuses')->onDelete('cascade');

            // Instructor / staff
            $table->string('instructor')->nullable();
            $table->string('responsible_staff')->nullable();

            // Execution period
            $table->date('execution_from');
            $table->date('execution_to');

            // Time slot (can span multiple days)
            $table->date('time_slot_from_date')->nullable();
            $table->time('time_slot_from_time')->nullable();
            $table->date('time_slot_to_date')->nullable();
            $table->time('time_slot_to_time')->nullable();
            $table->decimal('duration_hours', 4, 2)->default(0);

            // Description & attributes
            $table->boolean('swpd_programme')->default(false);

            // Venue
            $table->string('venue')->nullable();

            // Capacity & enrolment
            $table->integer('capacity')->default(0);
            $table->integer('registered')->default(0);

            // Financials
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('included_deposit', 10, 2)->default(0);

            // Attachments
            $table->string('attachment')->nullable();

            // Classification
            $table->string('discipline')->nullable();
            $table->string('attribute')->nullable();


            $table->enum('status', NewsArticleStatus::values())->default(NewsArticleStatus::Draft);
            $table->timestamps();
            
            $table->fullText(['instructor']);

        });

        Schema::create('activity_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->tinyText('title');
            $table->mediumText('description')->nullable();
            $table->text('venue_remark')->nullable();
            $table->timestamps();
            $table->unique(['activity_id', 'locale']);
            $table->fullText(['title', 'description']);
        });

        Schema::create('activity_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Simple status: participate or registered
            $table->enum('status', ['participate', 'registered'])->default('participate');
            
            $table->timestamps();
            
            // Prevent duplicate registrations
            $table->unique(['activity_id', 'student_id']);
            $table->index(['student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_registrations');
        Schema::dropIfExists('activity_translations');
        Schema::dropIfExists('activities');
    }
};
