<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify messages table
        Schema::table('messages', function (Blueprint $table) {
            // Add thread_id column if it doesn't exist
            if (!Schema::hasColumn('messages', 'thread_id')) {
                $table->foreignId('thread_id')->after('id')->constrained('message_threads')->onDelete('cascade');
            }

            // Remove columns that are now in the message_threads table
            if (Schema::hasColumn('messages', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('messages', 'inquiry_type')) {
                $table->dropColumn('inquiry_type');
            }

            // Make content nullable (in case we want to support system messages or other types in the future)
            $table->text('content')->nullable()->change();
        });

        // Migrate existing data
        $this->migrateExistingData();
    }

    public function down(): void
    {
        // Revert changes to messages table
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['thread_id']);
            $table->dropColumn('thread_id');
            $table->string('title');
            $table->string('inquiry_type')->nullable();
            $table->text('content')->change();
        });
    }

    private function migrateExistingData(): void
    {
        $messages = DB::table('messages')->whereNull('thread_id')->get();

        foreach ($messages as $message) {
            $threadId = DB::table('message_threads')->insertGetId([
                'title' => $message->title ?? 'No Title',
                'inquiry_type' => $message->inquiry_type ?? 'general',
                'created_at' => $message->created_at,
                'updated_at' => $message->updated_at,
            ]);

            DB::table('messages')
                ->where('id', $message->id)
                ->update(['thread_id' => $threadId]);
        }
    }
};
