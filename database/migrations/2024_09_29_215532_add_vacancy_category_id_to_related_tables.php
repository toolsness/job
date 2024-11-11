<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        Schema::table('industry_types', function (Blueprint $table) {
            $table->unsignedBigInteger('vacancy_category_id')->nullable();
            $table->foreign('vacancy_category_id', 'fk_ind_vac_cat_id')
                ->references('id')
                ->on('vacancy_categories')
                ->onDelete('set null');
        });
    }

    public function down()
    {

        Schema::table('industry_types', function (Blueprint $table) {
            $table->dropForeign('fk_ind_vac_cat_id');
            $table->dropColumn('vacancy_category_id');
        });
    }
};
