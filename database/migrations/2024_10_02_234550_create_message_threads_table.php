<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_threads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('inquiry_type', ['general', 'interview', 'application', 'technical']);
            $table->timestamps();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('thread_id')->after('id')->constrained('message_threads')->onDelete('cascade');
            $table->dropColumn('title');
            $table->dropColumn('inquiry_type');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['thread_id']);
            $table->dropColumn('thread_id');
            $table->string('title');
            $table->string('inquiry_type')->nullable();
        });

        Schema::dropIfExists('message_threads');
    }
};
