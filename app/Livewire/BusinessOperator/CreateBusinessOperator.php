<?php

namespace App\Livewire\BusinessOperator;

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
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class CreateBusinessOperator extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $nameKanji;
    public $nameKatakana;
    public $contactPhoneNumber;
    public $profileImage;
    public $agreeTerms = false;
    public $tag;

    public $username;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:35', new EnglishName],
            'email' => 'required|email|max:70|unique:users,email',
            'password' => ['required', 'min:8', new ComplexPassword],
            'password_confirmation' => 'required|same:password',
            'nameKanji' => ['required', 'string', 'max:255', new JapaneseKanjiName],
            'nameKatakana' => ['required', 'string', 'max:255', new JapaneseKatakanaName],
            'contactPhoneNumber' => 'required|string|max:255',
            'profileImage' => 'nullable|image|max:1024',
            'agreeTerms' => 'accepted',
            'tag' => 'nullable|in:general,application,interview,technical',
        ];
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
        $baseUsername = 'A' . $date;
        $suffix = 1;

        do {
            $username = $baseUsername . str_pad($suffix, 3, '0', STR_PAD_LEFT);
            $exists = User::where('username', $username)->exists();
            $suffix++;
        } while ($exists);

        $this->username = $username;
    }

    public function createBusinessOperator()
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

            if ($this->profileImage) {
                $imagePath = $this->profileImage->store('profile-images', 's3');
                $user->image = $imagePath;
                $user->save();
            }

            BusinessOperator::create([
                'user_id' => $user->id,
                'name_kanji' => $this->nameKanji,
                'name_katakana' => $this->nameKatakana,
                'contact_phone_number' => $this->contactPhoneNumber,
                'created_by' => Auth::id(),
                'tag' => $this->tag,
            ]);
        });

        flash()->success('Business Operator created successfully.');
        return redirect()->route('business-operator.business-operators.index');
    }

    public function render()
    {
        return view('livewire.business-operator.create-business-operator');
    }
}
