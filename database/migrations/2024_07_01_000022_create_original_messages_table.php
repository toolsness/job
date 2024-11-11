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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('sender_user_type', ['Student', 'CompanyRepresentative', 'BusinessOperator', 'Candidate', 'CompanyAdmin']);
            $table->foreignId('receiver_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('receiver_user_type', ['Student', 'CompanyRepresentative', 'BusinessOperator', 'Candidate', 'CompanyAdmin']);
            $table->dateTime('sent_at');
            $table->string('title');
            $table->text('content');
            $table->enum('message_category', ['Received', 'Sent', 'Saved', 'Deleted']);
            $table->dateTime('read_at')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('messages')->onDelete('set null');
            $table->string('inquiry_type')->nullable();
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
        Schema::dropIfExists('messages');
    }
};
