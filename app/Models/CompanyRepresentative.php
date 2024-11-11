<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyRepresentative extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_id', 'name_kanji', 'name_katakana', 'contact_phone_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }
}
