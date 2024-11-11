<?php

namespace App\Enum;

enum InterviewStatus: string
{
    case UNOFFICIAL_OFFER = 'Unofficial offer';
    case INTERVIEW_CONFIRMED = 'Interview confirmed';
    case APPLICATION_COMPLETED = 'Application completed';
    case INTERVIEW_CANCELLED = 'Interview cancelled';
    case INTERVIEW_CONDUCTED = 'Interview conducted';
    case SCOUTED = 'Scouted';
    case INTERVIEW_FAILED = 'Interview failed';
    case APPLICATION_FROM_STUDENTS = 'Application from students';
    case EMPLOYMENT_APPLICATION = 'Employment application';
    case CANCELLATION_REFUSAL = 'Cancellation(Refusal) of interview';
    case INTERVIEW_UNSUCCESSFUL = 'Interview unsuccessful';
    case ONBOARDING_IN_PROGRESS = 'Onboarding in progress';
    case HIRED = 'Hired';
    case OFFER_DECLINED_BY_CANDIDATE = 'Offer declined by candidate';
    case APPLICATION_WITHDRAWN = 'Application withdrawn';
    case OFFER_WITHDRAWN = 'Offer withdrawn';
    case ARCHIVED = 'Archived';
    case OFFER_ACCEPTED = 'Offer accepted';
    case OFFER_DECLINED = 'Offer declined';

    public function getDisplayText(): string
    {
        return match($this) {
            self::SCOUTED => 'Interview request from company',
            self::EMPLOYMENT_APPLICATION => 'Empoyment Application Submitted',
            self::APPLICATION_FROM_STUDENTS => 'Interview Request Sent',
            default => $this->value,
        };
    }
}
