<?php

namespace App\Livewire\Student\Auth;

use App\Mail\AccountRegistrationSuccessful;
use App\Models\Student;
use App\Models\User;
use App\Rules\ComplexPassword;
use App\Rules\EnglishName;
use App\Rules\JapaneseName;
use App\Rules\JapanesePhoneNumber;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class UserRegistration extends Component
{
    public $token;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $nameJapanese;
    public $contactPhoneNumber;
    public $agreeTerms = false;
    public $showPassword = false;
    public $showConfirmPassword = false;
    public $username;

    protected $validationAttributes = [
        'password_confirmation' => 'password confirmation',
    ];

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:35', new EnglishName],
            'email' => 'required|email|max:70|unique:users,email',
            'password' => ['required', 'min:8', new ComplexPassword],
            'password_confirmation' => 'required|same:password',
            'nameJapanese' => ['required', 'string', 'max:255', new JapaneseName],
            'contactPhoneNumber' => ['required', 'string', 'max:255', new JapanesePhoneNumber],
            'agreeTerms' => 'accepted',
        ];
    }

    public function getLabel($field)
    {
        $labels = [
            'username' => 'User ID',
            'name' => 'Name (English)',
            'nameJapanese' => 'Name (Japanese)',
            'email' => 'E-mail Address',
            'contactPhoneNumber' => 'Contact Phone Number',
            'password' => 'Password',
            'password_confirmation' => 'Password (for confirmation)',
        ];

        return $labels[$field] ?? ucfirst($field);
    }

    public function getPlaceholder($field)
    {
        $placeholders = [
            'username' => '(Auto-generated)',
            'name' => 'Enter your name in English',
            'nameJapanese' => '日本語名',
        ];

        return $placeholders[$field] ?? '';
    }

    public function mount()
    {
        $this->email = session()->get('email');
        $this->token = session()->get('token');

        if (!$this->email || !$this->token) {
            return redirect()->route('student.new-member-registration');
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        if ($propertyName === 'name') {
            $this->generateUsername();
        }
    }

    public function generateUsername()
    {
        $date = now()->format('Ymd');
        $baseUsername = 'S' . $date;
        $suffix = 1;

        do {
            $username = $baseUsername . str_pad($suffix, 3, '0', STR_PAD_LEFT);
            $exists = User::where('username', $username)->exists();
            $suffix++;
        } while ($exists);

        $this->username = $username;
    }

    public function checkErrorsAndSubmit()
    {
        $this->validate();

        if ($this->getErrorBag()->isEmpty()) {
            $this->register();
        } else {
            flash()->error('Please correct the errors before submitting.');
        }
    }

    public function register()
    {
        $otp = new Otp;
        $validation = $otp->validate($this->email, $this->token);
        if (!$validation->status) {
            flash()->error($validation->message);
            return redirect()->route('student.new-member-registration');
        }

        DB::transaction(function () use ($otp) {
            $user = User::create([
                'username' => $this->username,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'user_type' => 'Student',
                'login_permission_category' => 'Allowed',
                'remember_token' => Hash::make(Str::random(60)),
                'email_verified_at' => now(),
            ]);

            Student::create([
                'user_id' => $user->id,
                'name_japanese' => $this->nameJapanese,
                'contact_phone_number' => $this->contactPhoneNumber,
            ]);
        });

        flash()->success('Your account has been created successfully!');
        Mail::to($this->email)->send(new AccountRegistrationSuccessful);

        return redirect()->route('student.login');
    }

    public function render()
    {
        return view('livewire.student.auth.user-registration');
    }
}
