<!-- resources/views/livewire/business-operator/qualification-manager.blade.php -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Qualification Manager</h1>

    <!-- Category Management -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Qualification Categories</h2>
        <form wire:submit.prevent="{{ $editingCategoryId ? 'updateCategory' : 'createCategory' }}" class="mb-4">
            <div class="flex">
                <input wire:model="categoryName" type="text" placeholder="Category Name"
                    class="w-full px-4 py-2 rounded-l border">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-r">{{ $editingCategoryId ? 'Update' : 'Add' }}
                    Category</button>
            </div>
            @error('categoryName')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </form>
        <ul>
            @foreach ($categories as $category)
                <li class="flex justify-between items-center mb-2">
                    {{ $category->name }}
                    <div>
                        <button wire:click="editCategory({{ $category->id }})"
                            class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                        <button wire:click="confirmDelete({{ $category->id }}, 'category')"
                            class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Qualification Management -->
    <div>
        <h2 class="text-xl font-semibold mb-4">Qualifications</h2>
        <form wire:submit.prevent="{{ $editingQualificationId ? 'updateQualification' : 'createQualification' }}"
            class="mb-4">
            <div class="flex mb-2">
                <input wire:model="qualificationName" type="text" placeholder="Qualification Name"
                    class="w-full px-4 py-2 rounded-l border">
                <select wire:model="selectedCategory" class="px-4 py-2 border">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded-r">{{ $editingQualificationId ? 'Update' : 'Add' }}
                    Qualification</button>
            </div>
            @error('qualificationName')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
            @error('selectedCategory')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </form>

        <div class="mb-4">
            <input wire:model.live="search" type="text" placeholder="Search qualifications..."
                class="w-full px-4 py-2 rounded border">
        </div>

        <table class="w-full bg-white shadow-md rounded mb-4">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Category</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($qualifications as $qualification)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $qualification->qualification_name }}</td>
                        <td class="py-3 px-6 text-left">{{ $qualification->qualificationCategory->name }}</td>
                        <td class="py-3 px-6 text-center">
                            <button wire:click="editQualification({{ $qualification->id }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded">Edit</button>
                            <button wire:click="confirmDelete({{ $qualification->id }}, 'qualification')"
                                class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $qualifications->links() }}
    </div>

    <!-- Footer Navigation -->
    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="bg-white text-black border border-black hover:bg-green-700 hover:text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Back</a>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Confirm Delete
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete this category? This action cannot be undone.
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteCategory" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button wire:click="$set('showDeleteModal', false)" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
