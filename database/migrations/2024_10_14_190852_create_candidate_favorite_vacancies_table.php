<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateFavoriteVacanciesTable extends Migration
{
    public function up()
    {
        Schema::create('candidate_favorite_vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('vacancy_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['candidate_id', 'vacancy_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidate_favorite_vacancies');
    }
}
