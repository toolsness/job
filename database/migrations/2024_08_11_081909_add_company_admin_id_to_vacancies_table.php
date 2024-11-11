<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->unsignedBigInteger('company_admin_id')->nullable()->after('company_representative_id');
            $table->foreign('company_admin_id')->references('id')->on('company_admins')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropForeign(['company_admin_id']);
            $table->dropColumn('company_admin_id');
        });
    }
};
