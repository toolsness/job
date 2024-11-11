<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_kanji',
        'name_katakana',
        'industry_type_id',
        'address',
        'website',
        'contact_email',
        'contact_phone',
        'image'
    ];

    public function industryType()
    {
        return $this->belongsTo(IndustryType::class);
    }

    public function companyRepresentatives()
    {
        return $this->hasMany(CompanyRepresentative::class);
    }

    public function companyAdmins()
    {
        return $this->hasMany(CompanyAdmin::class);
    }

    public function interviewTimeSlots()
    {
        return $this->hasMany(InterviewTimeSlot::class);
    }

    public function vrContents()
    {
        return $this->hasMany(VRContent::class);
    }

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }

}
