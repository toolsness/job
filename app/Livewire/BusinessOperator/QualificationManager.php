<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Qualification;
use App\Models\QualificationCategory;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class QualificationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryName = '';
    public $qualificationName = '';
    public $selectedCategory = '';
    public $editingCategoryId = null;
    public $editingQualificationId = null;
    public $showDeleteModal = false;
    public $deletingItemId = null;
    public $deletingItemType = null;

    protected $rules = [
        'categoryName' => 'required|string|max:255|unique:qualification_categories,name',
        'qualificationName' => 'required|string|max:255|unique:qualifications,qualification_name',
        'selectedCategory' => 'required|exists:qualification_categories,id',
    ];

    protected $categoryRules = [
        'categoryName' => 'required|string|max:255|unique:qualification_categories,name',
    ];

    protected $qualificationRules = [
        'qualificationName' => 'required|string|max:255|unique:qualifications,qualification_name',
        'selectedCategory' => 'required|exists:qualification_categories,id',
    ];

    public function render()
    {
        $categories = QualificationCategory::all();
        $qualifications = Qualification::with('qualificationCategory')
            ->when($this->search, fn ($query) => $query->where('qualification_name', 'like', '%' . $this->search . '%'))
            ->when($this->selectedCategory, fn ($query) => $query->where('qualification_category_id', $this->selectedCategory))
            ->paginate(5);

        return view('livewire.business-operator.qualification-manager', [
            'categories' => $categories,
            'qualifications' => $qualifications,
        ]);
    }

    public function createCategory()
{
    $this->validate($this->categoryRules);

    QualificationCategory::create([
        'name' => Str::upper($this->categoryName),
    ]);

    $this->reset('categoryName');
    session()->flash('message', 'Category created successfully.');
}

public function createQualification()
{
    $this->validate($this->qualificationRules);

    Qualification::create([
        'qualification_name' => Str::upper($this->qualificationName),
        'qualification_category_id' => $this->selectedCategory,
    ]);

    $this->reset(['qualificationName', 'selectedCategory']);
    session()->flash('message', 'Qualification created successfully.');
}

    public function editCategory($id)
    {
        $this->editingCategoryId = $id;
        $category = QualificationCategory::findOrFail($id);
        $this->categoryName = $category->name;
    }

    public function updateCategory()
{
    $this->validate([
        'categoryName' => 'required|string|max:255|unique:qualification_categories,name,' . $this->editingCategoryId,
    ]);

    $category = QualificationCategory::findOrFail($this->editingCategoryId);
    $category->update([
        'name' => Str::upper($this->categoryName),
    ]);

    $this->reset(['editingCategoryId', 'categoryName']);
    session()->flash('message', 'Category updated successfully.');
}

    public function editQualification($id)
    {
        $this->editingQualificationId = $id;
        $qualification = Qualification::findOrFail($id);
        $this->qualificationName = $qualification->qualification_name;
        $this->selectedCategory = $qualification->qualification_category_id;
    }

    public function updateQualification()
{
    $this->validate([
        'qualificationName' => 'required|string|max:255|unique:qualifications,qualification_name,' . $this->editingQualificationId,
        'selectedCategory' => 'required|exists:qualification_categories,id',
    ]);

    $qualification = Qualification::findOrFail($this->editingQualificationId);
    $qualification->update([
        'qualification_name' => Str::upper($this->qualificationName),
        'qualification_category_id' => $this->selectedCategory,
    ]);

    $this->reset(['editingQualificationId', 'qualificationName', 'selectedCategory']);
    session()->flash('message', 'Qualification updated successfully.');
}

    public function confirmDelete($id, $type)
    {
        $this->deletingItemId = $id;
        $this->deletingItemType = $type;
        $this->showDeleteModal = true;
    }

    public function deleteItem()
    {
        if ($this->deletingItemType === 'category') {
            QualificationCategory::destroy($this->deletingItemId);
            $message = 'Category deleted successfully.';
        } else {
            Qualification::destroy($this->deletingItemId);
            $message = 'Qualification deleted successfully.';
        }

        $this->reset(['showDeleteModal', 'deletingItemId', 'deletingItemType']);
        session()->flash('message', $message);
    }

    public function updated($propertyName)
{
    if (in_array($propertyName, ['categoryName'])) {
        $this->validateOnly($propertyName, $this->categoryRules);
    } elseif (in_array($propertyName, ['qualificationName', 'selectedCategory'])) {
        $this->validateOnly($propertyName, $this->qualificationRules);
    }
}
}
