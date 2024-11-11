<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-6 text-center">Industries Management</h1>

    <div class="flex justify-between mb-4">
        <div class="w-1/3 pr-2 relative">
            <input wire:model.live="search" type="text" placeholder="Search categories..." class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
            <i class="fas fa-search text-gray-400 absolute right-3 top-3 pr-2"></i>
        </div>
        <div class="w-2/3 pl-2">
{{--            <select wire:model="type" class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">--}}
{{--                <option value="">All Types</option>--}}
{{--                <option value="functional">Functional</option>--}}
{{--                <option value="industrial">Industrial</option>--}}
{{--                <option value="special_skilled">Special Skilled</option>--}}
{{--            </select>--}}
        </div>
    </div>

    <div class="mb-4">
        <button wire:click="toggleForm" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
            {{ $showForm ? 'Close Form' : 'Create New Category' }}
        </button>
    </div>

    @if ($showForm)
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-lg font-semibold mb-4">{{ $editingCategoryId ? 'Edit' : 'Create' }} Category</h2>
            <form wire:submit.prevent="{{ $editingCategoryId ? 'updateCategory' : 'createCategory' }}">
                <div class="mb-4">
                    <label for="name" class="block mb-2 font-medium text-gray-700">Name</label>
                    <input wire:model="name" type="text" id="name" class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="description" class="block mb-2 font-medium text-gray-700">Description</label>
                    <textarea wire:model="description" id="description" class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                    @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                    {{ $editingCategoryId ? 'Update' : 'Create' }}
                </button>
                <button type="button" wire:click="toggleForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    Cancel
                </button>
            </form>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded mb-4">
            <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Name <span class="text-gray-400">(Companies Under Category)</span></th>
                <th class="py-3 px-6 text-left">Type</th>
                <th class="py-3 px-6 text-left">Description</th>
                <th class="py-3 px-6 text-center">Actions</th>
            </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-sm">
            @foreach ($categories as $category)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        {{ $category->name }} ({{ $category->companies_count }})
                    </td>
                    <td class="py-3 px-6 text-left">{{ ucfirst($category->type) }}</td>
                    <td class="py-3 px-6 text-left">{{ $category->description }}</td>
                    <td class="py-3 px-6 text-center">
                        <button wire:click="editCategory({{ $category->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button wire:click="confirmDelete({{ $category->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{ $categories->links() }}

    <!-- Footer Navigation -->
    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="bg-white text-black border border-black hover:bg-green-700 hover:text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <x-popup wire:model="showDeleteModal">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                <i class="fas fa-exclamation-triangle font-extrabold"></i>Confirm Delete
            </h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete this category?<br><strong class="text-red-500 font-bold"><span class="bg-red-100 text-red-800">This is also Delete All The Companies Under This Category!</span><br>So Be Carefull! This action cannot be undone.</strong>
                </p>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button wire:click="deleteCategory" type="button" class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                Delete
            </button>
            <button wire:click="$set('showDeleteModal', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </button>
        </div>
    </x-popup>
</div>
