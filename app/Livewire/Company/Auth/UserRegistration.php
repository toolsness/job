<?php

namespace App\Livewire\Company\Auth;

use App\Mail\AccountRegistrationSuccessfulCompany;
use App\Models\Company;
use App\Models\CompanyRepresentative;
use App\Models\User;
use App\Rules\ComplexPassword;
use App\Rules\EnglishName;
use App\Rules\JapaneseName;
use App\Rules\JapaneseKatakanaName;
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
    public $companyId;
    public $nameKanji;
    public $nameKatakana;
    public $contactPhoneNumber;
    public $agreeTerms = false;
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
            'companyId' => 'required|exists:companies,id',
            'nameKanji' => ['required', 'string', 'max:255', new JapaneseName],
            'nameKatakana' => ['required', 'string', 'max:255', new JapaneseKatakanaName],
            'contactPhoneNumber' => ['required', 'string', 'max:255', new JapanesePhoneNumber],
            'agreeTerms' => 'accepted',
        ];
    }

    public function mount()
    {
        $this->email = session()->get('email');
        $this->token = session()->get('token');

        if (!$this->email || !$this->token) {
            return redirect()->route('company.new-member-registration');
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
        $baseUsername = 'C' . $date;
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
            return redirect()->route('company.new-member-registration');
        }

        DB::transaction(function () use ($otp) {
            $user = User::create([
                'username' => $this->username,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'user_type' => 'CompanyRepresentative',
                'login_permission_category' => 'Pending',
                'email_verified_at' => now(),
                'remember_token' => Str::random(60),
            ]);

            CompanyRepresentative::create([
                'user_id' => $user->id,
                'company_id' => $this->companyId,
                'name_kanji' => $this->nameKanji,
                'name_katakana' => $this->nameKatakana,
                'contact_phone_number' => $this->contactPhoneNumber,
            ]);
        });

        flash()->success('Your account has been created successfully! Please wait for admin approval.');
        Mail::to($this->email)->send(new AccountRegistrationSuccessfulCompany);

        return redirect()->route('company.login');
    }

    public function getLabel($field)
    {
        $labels = [
            'username' => 'User ID',
            'companyId' => 'Company Name',
            'name' => 'Name (English)',
            'nameKanji' => 'Name (Japanese)',
            'nameKatakana' => 'Name (Katakana)',
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
            'nameKanji' => '漢字で名前を入力してください',
            'nameKatakana' => 'カタカナで名前を入力してください',
        ];

        return $placeholders[$field] ?? '';
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();
        return view('livewire.company.auth.user-registration', compact('companies'));
    }
}
