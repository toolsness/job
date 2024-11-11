<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacancyCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'description'];

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }

    public function qualificationCategories()
{
    return $this->belongsToMany(QualificationCategory::class);
}

    public function industryTypes()
    {
        return $this->hasMany(IndustryType::class);
    }
}
