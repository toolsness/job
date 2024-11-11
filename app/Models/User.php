<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'email',
        'password',
        'username',
        'email_verified_at',
        'remember_token',
        'user_type',
        'login_permission_category',
        'reason_for_denial_of_login_permission_category',
        'login_permitted_category_disallowed_start_time',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'login_permitted_category_disallowed_start_time' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($user) {
            if ($user->isDirty('name')) {
                // Update student's candidate name if exists
                if ($user->student && $user->student->candidate) {
                    $user->student->candidate->update(['name' => $user->name]);
                }
            }
        });
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function companyRepresentative()
    {
        return $this->hasOne(CompanyRepresentative::class);
    }

    public function businessOperator()
    {
        return $this->hasOne(BusinessOperator::class);
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }

    public function companyAdmin()
    {
        return $this->hasOne(CompanyAdmin::class);
    }

    public function interviewTimeSlots()
    {
        return $this->hasMany(InterviewTimeSlot::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_user_id');
    }

    public function createdRecords()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function updatedRecords()
    {
        return $this->hasMany(User::class, 'updated_by');
    }
}
