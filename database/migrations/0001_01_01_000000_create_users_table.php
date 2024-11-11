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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name', 55);
            $table->string('image')->nullable();
            $table->string('email', 70)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('user_type', ['Student', 'CompanyRepresentative', 'BusinessOperator', 'Candidate', 'CompanyAdmin'])->nullable();
            $table->enum('login_permission_category', ['Allowed', 'NotAllowed', 'Pending'])->nullable();
            $table->enum('reason_for_denial_of_login_permission_category', ['IncorrectAttempts'])->nullable();
            $table->dateTime('login_permitted_category_disallowed_start_time')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
