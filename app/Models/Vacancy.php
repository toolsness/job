<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'company_representative_id',
        'company_admin_id',
        'publish_category',
        'vr_content_company_introduction_id',
        'vr_content_workplace_tour_id',
        'job_title',
        'monthly_salary',
        'work_location',
        'working_hours',
        'transportation_expenses',
        'overtime_pay',
        'salary_increase_and_bonuses',
        'social_insurance',
        'other_details',
        'image',
        'japanese_language',
        'vacancy_category_id',
    ];

    public function companyRepresentative()
    {
        return $this->belongsTo(CompanyRepresentative::class);
    }

    public function companyAdmin()
    {
        return $this->belongsTo(CompanyAdmin::class);
    }

    public function vrContentCompanyIntroduction()
    {
        return $this->belongsTo(VRContent::class, 'vr_content_company_introduction_id');
    }

    public function vrContentWorkplaceTour()
    {
        return $this->belongsTo(VRContent::class, 'vr_content_workplace_tour_id');
    }

    public function interviewSchedules()
    {
        return $this->hasMany(InterviewSchedule::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function vacancyCategory()
    {
        return $this->belongsTo(VacancyCategory::class);
    }

    public function getPersonInCharge()
    {
        if ($this->companyRepresentative) {
            return $this->companyRepresentative->user->name;
        } elseif ($this->companyAdmin) {
            return $this->companyAdmin->user->name;
        }

        return 'Not assigned';
    }

    public function interviewTimeSlots()
    {
        return $this->hasMany(InterviewTimeSlot::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function favoriteCandidates()
    {
        return $this->belongsToMany(Candidate::class, 'candidate_favorite_vacancies')->withTimestamps();
    }
}
