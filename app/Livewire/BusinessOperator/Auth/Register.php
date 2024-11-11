<?php

namespace App\Livewire\BusinessOperator\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\BusinessOperator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Rules\ComplexPassword;
use App\Rules\EnglishName;
use App\Rules\JapaneseKanjiName;
use App\Rules\JapaneseKatakanaName;

class Register extends Component
{
    public $username;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $nameKanji;
    public $nameKatakana;
    public $contactPhoneNumber;
    public $agreeTerms = false;

    protected function rules()
    {
        return [
            'username' => 'required|unique:users,username',
            'name' => ['required', 'string', 'max:35', new EnglishName],
            'email' => 'required|email|max:70|unique:users,email',
            'password' => ['required', 'min:8', new ComplexPassword],
            'password_confirmation' => 'required|same:password',
            'nameKanji' => ['required', 'string', 'max:255', new JapaneseKanjiName],
            'nameKatakana' => ['required', 'string', 'max:255', new JapaneseKatakanaName],
            'contactPhoneNumber' => 'required|string|max:255',
            'agreeTerms' => 'accepted',
        ];
    }

    public function register()
    {
        $this->validate();

        DB::transaction(function () {
            $user = User::create([
                'username' => $this->username,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'user_type' => 'BusinessOperator',
                'login_permission_category' => 'Allowed',
                'remember_token' => Str::random(60),
            ]);

            BusinessOperator::create([
                'user_id' => $user->id,
                'name_kanji' => $this->nameKanji,
                'name_katakana' => $this->nameKatakana,
                'contact_phone_number' => $this->contactPhoneNumber,
            ]);
        });

        session()->flash('message', 'Registration successful.');
        return redirect()->route('home');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.business-operator.auth.register');
    }
}
