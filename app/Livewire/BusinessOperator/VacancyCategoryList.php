<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\VacancyCategory;
use App\Models\IndustryType;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VacancyCategoryList extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $name = '';
    public $description = '';
    public $editingCategoryId = null;
    public $showDeleteModal = false;
    public $deletingCategoryId = null;
    public $showForm = false;
    public $companycount = 0;

    protected $rules = [
        'name' => 'required|string|max:255|unique:vacancy_categories,name',
        'description' => 'nullable|string',
    ];

    public function render()
    {
        $categories = VacancyCategory::query()
            ->withCount(['industryTypes as companies_count' => function ($query) {
                $query->withCount('companies')->has('companies');
            }])
            ->when($this->search, fn ($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->type, fn ($query) => $query->where('type', $this->type))
            ->paginate(5);

        return view('livewire.business-operator.vacancy-category-list', [
            'categories' => $categories,
        ]);
    }

    public function createCategory()
    {
        $this->validate();

        DB::transaction(function () {
            $category = VacancyCategory::create([
                'name' => Str::upper($this->name),
                'type' => 'special_skilled',
                'description' => $this->description,
            ]);

            $this->syncIndustryType($category);
        });

        $this->reset(['name', 'type', 'description', 'showForm']);
        flash()->success('Category created successfully.');
    }

    public function editCategory($id)
    {
        $this->editingCategoryId = $id;
        $category = VacancyCategory::findOrFail($id);
        $this->name = $category->name;
        $this->description = $category->description;
        $this->showForm = true;
    }

    public function updateCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:vacancy_categories,name,' . $this->editingCategoryId,
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () {
            $category = VacancyCategory::findOrFail($this->editingCategoryId);
            $category->update([
                'name' => Str::upper($this->name),
                'description' => $this->description,
            ]);

            $this->syncIndustryType($category);
        });

        $this->reset(['editingCategoryId', 'name', 'type', 'description', 'showForm']);
        flash()->success('Category updated successfully.');
    }

    public function confirmDelete($id)
    {
        $this->deletingCategoryId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteCategory()
    {
        DB::transaction(function () {
            $category = VacancyCategory::findOrFail($this->deletingCategoryId);

            // Delete associated IndustryTypes
            IndustryType::where('vacancy_category_id', $this->deletingCategoryId)->delete();

            // Delete the VacancyCategory
            $category->delete();
        });

        $this->reset(['showDeleteModal', 'deletingCategoryId']);
        flash()->success('Category deleted successfully.');
    }

    private function syncIndustryType($category)
    {
        // Update or create a single IndustryType with the same name as the VacancyCategory
        IndustryType::updateOrCreate(
            ['vacancy_category_id' => $category->id],
            ['name' => $category->name]
        );
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->reset(['editingCategoryId', 'name', 'type', 'description']);
        }
    }
}
