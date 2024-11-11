<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('business_operators', function (Blueprint $table) {
            $table->enum('tag', ['general', 'application', 'interview', 'technical'])->nullable();
        });
    }

    public function down()
    {
        Schema::table('business_operators', function (Blueprint $table) {
            $table->dropColumn('tag');
        });
    }
};
