<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class CreateStudent extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $name_kanji;
    public $name_katakana;
    public $name_japanese;
    public $contact_phone_number;
    public $profileImage;

    public $username;

    protected $rules = [
        'name' => 'required|string|max:55',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'name_kanji' => 'nullable|string|max:255',
        'name_katakana' => 'nullable|string|max:255',
        'name_japanese' => 'nullable|string|max:255',
        'contact_phone_number' => 'nullable|string|max:255',
        'profileImage' => 'nullable|image|max:1024',
    ];

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

    public function createStudent()
    {
        $this->validate();

        $user = User::create([
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'user_type' => 'Student',
        ]);

        if ($this->profileImage) {
            $imagePath = $this->profileImage->store('profile-images', 's3');
            $user->image = $imagePath;
            $user->save();
        }

        Student::create([
            'user_id' => $user->id,
            'name_kanji' => $this->name_kanji,
            'name_katakana' => $this->name_katakana,
            'name_japanese' => $this->name_japanese,
            'contact_phone_number' => $this->contact_phone_number,
            'created_by' => Auth::id(),
        ]);

        flash()->success('Student created successfully.');
        return redirect()->route('business-operator.students.index');
    }

    public function render()
    {
        return view('livewire.business-operator.create-student');
    }
}
