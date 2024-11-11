<?php

namespace App\Livewire\BusinessOperator;

use App\Models\BusinessOperator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Rules\EnglishName;
use App\Rules\JapaneseKanjiName;
use App\Rules\JapaneseKatakanaName;
use Illuminate\Support\Facades\Auth;

class EditBusinessOperator extends Component
{
    use WithFileUploads;

    public BusinessOperator $businessOperator;
    public $name;
    public $email;
    public $nameKanji;
    public $nameKatakana;
    public $contactPhoneNumber;
    public $image;
    public $tempImage;
    public $isEditing = false;
    public $showDeleteConfirmation = false;
    public $tag;

    protected function rules()
    {
        return [
        'name' =>  ['required', 'string', 'max:35', new EnglishName],
        'email' => 'required|email|max:255',
        'nameKanji' => ['required', 'string', 'max:255', new JapaneseKanjiName],
        'nameKatakana' => ['required', 'string', 'max:255', new JapaneseKatakanaName],
        'contactPhoneNumber' => 'required|string|max:20',
        'tempImage' => 'nullable|image|max:1024',
        'tag' => 'nullable|in:general,application,interview,technical',
    ];
    }

    public function mount(BusinessOperator $businessOperator)
    {
        $this->businessOperator = $businessOperator;
        $this->loadBusinessOperatorData();
    }

    public function loadBusinessOperatorData()
    {
        $this->name = $this->businessOperator->user->name;
        $this->email = $this->businessOperator->user->email;
        $this->nameKanji = $this->businessOperator->name_kanji;
        $this->nameKatakana = $this->businessOperator->name_katakana;
        $this->contactPhoneNumber = $this->businessOperator->contact_phone_number;
        $this->image = $this->businessOperator->user->image;
        $this->tag = $this->businessOperator->tag;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function startEditing()
    {
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->loadBusinessOperatorData();
        $this->tempImage = null;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $this->businessOperator->user->update([
                'name' => $this->name,
                'email' => $this->email,
                'updated_by' => Auth::id(),
            ]);

            if ($this->tempImage) {
                if ($this->businessOperator->user->image) {
                    Storage::disk('s3')->delete($this->businessOperator->user->image);
                }
                $this->businessOperator->user->image = $this->tempImage->store('business-operator-images', 's3');

                $this->businessOperator->user->save();
            }

            $this->businessOperator->update([
                'name_kanji' => $this->nameKanji,
                'name_katakana' => $this->nameKatakana,
                'contact_phone_number' => $this->contactPhoneNumber,
                'updated_by' => Auth::id(),
                'tag' => $this->tag,
            ]);
        });

        $this->isEditing = false;
        $this->loadBusinessOperatorData();
        flash()->success('Business Operator updated successfully.');
    }

    public function deleteImage()
    {
        if ($this->businessOperator->user->image) {
            Storage::disk('s3')->delete($this->businessOperator->user->image);
            $this->businessOperator->user->image = null;
            $this->businessOperator->user->save();
            $this->image = null;
        }
    }

    public function deleteTempImage()
    {
        $this->tempImage = null;
    }

    public function confirmDelete()
    {
        $this->showDeleteConfirmation = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
    }

    public function deleteBusinessOperator()
    {
        DB::transaction(function () {
            if ($this->businessOperator->user->image) {
                Storage::disk('s3')->delete($this->businessOperator->user->image);
            }
            $this->businessOperator->user->delete();
            $this->businessOperator->delete();
        });

        flash()->success('Business Operator deleted successfully.');
        return redirect()->route('business-operator.business-operators.index');
    }

    public function render()
    {
        return view('livewire.business-operator.edit-business-operator');
    }
}
