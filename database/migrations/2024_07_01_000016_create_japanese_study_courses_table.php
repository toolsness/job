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
        Schema::create('japanese_study_courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_name');
            $table->string('course_category');
            $table->enum('publish_category', ['NotPublished', 'Published', 'PublicationStopped']);
            $table->integer('monthly_amount');
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('japanese_study_courses');
    }
};
