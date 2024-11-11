<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->enum('for', ['student', 'company', 'public'])->after('content');
        });

        Schema::table('notices', function (Blueprint $table) {
            $table->enum('for', ['student', 'company', 'public'])->after('content');
        });
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('for');
        });

        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn('for');
        });
    }
};
