<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'vacancy_category_id'];

    
    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function vacancyCategory()
    {
        return $this->belongsTo(VacancyCategory::class);
    }

}
