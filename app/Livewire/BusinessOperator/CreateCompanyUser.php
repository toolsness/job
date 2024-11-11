<?php

namespace App\Livewire\BusinessOperator;

use App\Models\Company;
use App\Models\CompanyAdmin;
use App\Models\CompanyRepresentative;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Rules\EnglishName;
use App\Rules\JapaneseKatakanaName;
use App\Rules\JapanesePhoneNumber;
use App\Rules\ComplexPassword;
use App\Rules\PasswordConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyAdminRegistration;
use App\Mail\CompanyRepresentativeRegistration;

class CreateCompanyUser extends Component
{
    use WithFileUploads;

    public $username;
    public $name;
    public $nameKanji;
    public $nameKatakana;
    public $email;
    public $password;
    public $passwordconfirmation;
    public $userType;
    public $companyId;
    public $contactPhoneNumber;
    public $image;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', new EnglishName],
            'nameKanji' => 'required|string|max:255',
            'nameKatakana' => ['required', 'string', 'max:255', new JapaneseKatakanaName],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'min:8', new ComplexPassword],
            'passwordconfirmation' => ['required', new PasswordConfirmation($this->password, $this->passwordconfirmation)],
            'userType' => 'required|in:CompanyAdmin,CompanyRepresentative',
            'companyId' => 'required|exists:companies,id',
            'contactPhoneNumber' => ['required', 'string', 'max:20', new JapanesePhoneNumber],
            'image' => 'nullable|image|max:1024',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName === 'password') {

            if (!empty($this->password) && !empty($this->passwordconfirmation) && $this->password !== $this->passwordconfirmation) {
                $this->addError('passwordconfirmation', 'The passwords do not match.');
            } else {
                $this->resetErrorBag('passwordconfirmation');
            }

            if (!empty($this->password)) {
                $this->resetErrorBag('password');
            }

        }

        if ($propertyName === 'passwordconfirmation') {

            if (!empty($this->password) && empty($this->passwordconfirmation)) {
                $this->addError('passwordconfirmation', 'The password confirmation field cannot be empty.');
            }

            if (!empty($this->password) && !empty($this->passwordconfirmation) && $this->password !== $this->passwordconfirmation) {
                $this->addError('passwordconfirmation', 'The passwords do not match.');
            } else {
                $this->resetErrorBag('passwordconfirmation');
            }


            if (empty($this->password) && !empty($this->passwordconfirmation)) {
                $this->addError('password', 'The password field cannot be empty.');
            } else {
                $this->resetErrorBag('password');
            }
        }

        if ($propertyName === 'name') {
            $this->generateUsername();
        }
    }

    public function generateUsername()
    {
        $prefix = $this->userType === 'CompanyAdmin' ? 'C' : 'C';
        $date = now()->format('Ymd');
        $baseUsername = $prefix . $date;
        $suffix = 1;

        do {
            $username = $baseUsername . str_pad($suffix, 3, '0', STR_PAD_LEFT);
            $exists = User::where('username', $username)->exists();
            $suffix++;
        } while ($exists);

        $this->username = $username;
    }

    public function save()
{
    $this->validate();

    $plainPassword = $this->password; // Store the plain password for email
    $company = Company::find($this->companyId);

    $user = User::create([
        'username' => $this->username,
        'name' => $this->name,
        'email' => $this->email,
        'password' => Hash::make($this->password),
        'user_type' => $this->userType,
        'login_permission_category' => $this->userType === 'CompanyAdmin' ? 'Allowed' : 'Pending',
        'remember_token' => Str::random(60),
    ]);

    if ($this->image) {
        $imagePath = $this->image->store('user-images', 's3');
        $user->image = $imagePath;
        $user->save();
    }

    $userTypeModel = $this->userType === 'CompanyAdmin' ? CompanyAdmin::class : CompanyRepresentative::class;

    $userTypeModel::create([
        'user_id' => $user->id,
        'company_id' => $this->companyId,
        'name_kanji' => $this->nameKanji,
        'name_katakana' => $this->nameKatakana,
        'contact_phone_number' => $this->contactPhoneNumber,
    ]);

    // Send appropriate email based on user type
    if ($this->userType === 'CompanyAdmin') {
        Mail::to($this->email)->send(new CompanyAdminRegistration(
            $this->name,
            $this->email,
            $plainPassword,
            $company->name
        ));
    } else {
        Mail::to($this->email)->send(new CompanyRepresentativeRegistration(
            $this->name,
            $this->email,
            $plainPassword,
            $company->name
        ));
    }

    flash()->success('Company user created successfully.');

    return redirect()->route('business-operator.company-users');
}

    public function render()
    {
        return view('livewire.business-operator.create-company-user', [
            'companies' => Company::orderBy('name')->get(),
        ]);
    }
}
