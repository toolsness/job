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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained('students')->onDelete('cascade');
            $table->enum('publish_category', ['NotPublished', 'Published', 'PublicationStopped']);
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->foreignId('nationality')->nullable()->constrained('countries')->onDelete('set null');
            $table->string('last_education')->nullable();
            $table->text('work_history')->nullable();
            $table->foreignId('qualification')->nullable()->constrained('qualifications')->onDelete('set null');
            $table->text('self_presentation')->nullable();
            $table->text('personal_preference')->nullable();
            $table->string('profile_picture_link')->nullable();
            $table->string('self_introduction_video_link')->nullable();
            $table->string('cv_link')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
