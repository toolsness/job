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
        Schema::create('v_r_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained();
            $table->string('content_name');
            $table->enum('content_category', ['CompanyIntroduction', 'WorkplaceTour'])
                  ->default('CompanyIntroduction');
            $table->string('content_link');
            $table->string('image')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['Public', 'Private', 'Draft'])->default('Draft');
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
        Schema::dropIfExists('v_r_contents');
    }
};
