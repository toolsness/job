<div class="container mx-auto px-4 py-8" x-data="{ showDeleteConfirmation: @entangle('showDeleteConfirmation') }">
    <div class="mb-8">
        <div class="relative w-full max-w-md mx-auto">
            @if ($company->image || $newImage)
                <img src="{{ $newImage ? $newImage->temporaryUrl() : Storage::url($company->image) }}"
                    alt="{{ $company->name }}" class="w-full h-64 object-cover rounded-lg">
                @if ($isEditing)
                    <button wire:click="deleteImage"
                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-700 text-white p-1 rounded-full"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="deleteImage">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </span>
                        <span wire:loading wire:target="deleteImage">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                    </button>
                @endif
            @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                    <span class="text-gray-500">No Image</span>
                </div>
            @endif
            @if ($isEditing)
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="newImage">
                    Company Image
                </label>
                <input wire:model.lazy="newImage"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="newImage" type="file" accept="image/*">
                <div wire:loading wire:target="newImage" class="mt-1">
                    <span class="font-bold text-green-500"><i class="fa fa-spinner fa-spin"></i>
                        Uploading...</span>
                </div>
                @error('newImage')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>
        @endif

        </div>
    </div>

    <div class="max-w-2xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-6">Company Details</h2>

        @if (!$isEditing)
            <!-- Display mode -->
            <div class="mb-4">
                <p><strong>Corporate ID:</strong> {{ $company->id }}</p>
                <p><strong>Company Name (English):</strong> {{ $company->name }}</p>
                <p><strong>Company Name:</strong> {{ $company->name_kanji }}</p>
                <p><strong>Company Name (Katakana):</strong> {{ $company->name_katakana }}</p>
                <p><strong>Type of Industry:</strong> {{ $company->industryType->name }}</p>
                <p><strong>Company Phone Number:</strong> {{ $company->contact_phone }}</p>
                <p><strong>Company Email Address:</strong> {{ $company->contact_email }}</p>
                <p><strong>Company Location:</strong> {{ $company->address }}</p>
                <p><strong>Company Website:</strong> <a href="{{ $company->website }}" target="_blank"
                        class="text-blue-600 hover:underline">{{ $company->website }}</a></p>
            </div>

            <div class="flex justify-between">
                <button wire:click="startEditing"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </button>
                <button wire:click="confirmDelete"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            </div>
        @else
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Company Name
                    </label>
                    <input wire:model.lazy="company.name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name" type="text">
                    @error('company.name')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name_kanji">
                        Company Name (Kanji)
                    </label>
                    <input wire:model.lazy="company.name_kanji"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name_kanji" type="text">
                    @error('company.name_kanji')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name_katakana">
                        Company Name (Katakana)
                    </label>
                    <input wire:model.lazy="company.name_katakana"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name_katakana" type="text">
                    @error('company.name_katakana')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="industry_type_id">
                        Type of Industry
                    </label>
                    <select wire:model.lazy="company.industry_type_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="industry_type_id">
                        @foreach ($industryTypes as $industryType)
                            <option value="{{ $industryType->id }}">{{ $industryType->name }}</option>
                        @endforeach
                    </select>
                    @error('company.industry_type_id')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="contact_phone">
                        Company Phone Number
                    </label>
                    <input wire:model.lazy="company.contact_phone"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="contact_phone" type="text">
                    @error('company.contact_phone')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="contact_email">
                        Company Email Address
                    </label>
                    <input wire:model.lazy="company.contact_email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="contact_email" type="text">
                    @error('company.contact_email')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="website">
                        Company Website
                    </label>
                    <input wire:model.lazy="company.website"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="website" type="text">
                    @error('company.website')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                        Company Location
                    </label>
                    <input wire:model.lazy="company.address"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="address" type="text">
                    @error('company.address')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save Changes
                    </button>
                    <button wire:click="cancelEditing" type="button"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancel
                    </button>
                </div>
            </form>
        @endif
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('business-operator.companies') }}"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Return to list of registered companies
        </a>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('home') }}"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            Return to TOP
        </a>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-popup wire:model.lazy="showDeleteConfirmation">
        <div class="p-6">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Company</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete this company? This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button wire:click="deleteCompany"
                        class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Delete
                    </button>
                    <button wire:click="cancelDelete"
                        class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </x-popup>
</div>
