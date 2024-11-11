<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('vacancy_categories', function (Blueprint $table) {
        $table->enum('type', ['special_skilled'])->default('special_skilled');
        $table->text('description')->nullable();
    });
}

public function down()
{
    Schema::table('vacancy_categories', function (Blueprint $table) {
        $table->dropColumn(['type', 'description']);
    });
}
};
