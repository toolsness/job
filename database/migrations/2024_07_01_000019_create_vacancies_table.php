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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('company_representative_id')->nullable()->constrained('company_representatives')->onDelete('cascade');
            $table->enum('publish_category', ['NotPublished', 'Published', 'PublicationStopped']);
            $table->string('image')->nullable();
            $table->foreignId('vr_content_company_introduction_id')->nullable()->constrained('v_r_contents')->onDelete('set null');
            $table->foreignId('vr_content_workplace_tour_id')->nullable()->constrained('v_r_contents')->onDelete('set null');
            $table->foreignId('vacancy_category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('job_title');
            $table->string('monthly_salary');
            $table->string('work_location');
            $table->string('working_hours');
            $table->string('transportation_expenses');
            $table->string('overtime_pay');
            $table->string('salary_increase_and_bonuses');
            $table->string('social_insurance');
            $table->string('japanese_language');
            $table->text('other_details')->nullable();
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
        Schema::dropIfExists('vacancies');
    }
};
