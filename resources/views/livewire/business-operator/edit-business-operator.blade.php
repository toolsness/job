<div class="container mx-auto px-4 py-8" x-data="{ showDeleteConfirmation: @entangle('showDeleteConfirmation') }">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-6">Business Operator Details</h2>

        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/3 mb-4 md:mb-0 relative">
                @if ($tempImage)
                    <img src="{{ $tempImage->temporaryUrl() }}" alt="{{ $name }}"
                        class="w-full h-auto object-cover mb-4">
                    @if ($isEditing)
                        <button wire:click="deleteTempImage"
                            class="absolute top-2 right-2 bg-red-500 hover:bg-red-700 text-white p-1 rounded-full"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </span>
                            <span wire:loading wire:target="deleteTempImage">
                                <i class="fa fa-spinner fa-spin"></i> Deleting...
                            </span>
                        </button>
                    @endif
                @elseif ($image)
                    <img src="{{ Storage::url($image) }}" alt="{{ $name }}"
                        class="w-full h-auto object-cover mb-4">
                    @if ($isEditing)
                        <button wire:click="deleteImage"
                            class="absolute top-2 right-2 bg-red-500 hover:bg-red-700 text-white p-1 rounded-full"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </span>
                            <span wire:loading wire:target="deleteImage">
                                <i class="fa fa-spinner fa-spin"></i> Deleting...
                            </span>
                        </button>
                    @endif
                @else
                    <div class="w-full h-48 bg-gray-200 rounded-md"></div>
                @endif

                @if ($isEditing)
                    <div wire:loading wire:target="tempImage" class="mt-1">
                        <span class="font-bold text-green-500"><i class="fa fa-spinner fa-spin"></i> Uploading...</span>
                    </div>
                    <input type="file" wire:model.lazy.lazy="tempImage" id="tempImage"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        wire:loading.attr="disabled">
                    @error('tempImage')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                @endif
            </div>

            <div class="md:w-2/3 md:pl-6">
                @if (!$isEditing)
                    <div class="mb-4 ">
                        <p><strong>User ID:</strong> {{ $businessOperator->user->username }}</p>
                        <p><strong>Name:</strong> {{ $name }}</p>
                        <p><strong>Email:</strong> {{ $email }}</p>
                        <p><strong>Name (Kanji):</strong> {{ $nameKanji }}</p>
                        <p><strong>Name (Katakana):</strong> {{ $nameKatakana }}</p>
                        <p><strong>Contact Phone Number:</strong> {{ $contactPhoneNumber }}</p>
                        <p><strong>Responsiblity:</strong> <span
                                class="capitalize">{{ $businessOperator->tag ? $businessOperator->tag : 'N/A' }}</span>
                        </p>
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
                                Name
                            </label>
                            <input wire:model.lazy="name"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="name" type="text">
                            @error('name')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                                Email
                            </label>
                            <input wire:model.lazy="email"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="email" type="email">
                            @error('email')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nameKanji">
                                Name (Kanji)
                            </label>
                            <input wire:model.lazy="nameKanji"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="nameKanji" type="text">
                            @error('nameKanji')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nameKatakana">
                                Name (Katakana)
                            </label>
                            <input wire:model.lazy="nameKatakana"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="nameKatakana" type="text">
                            @error('nameKatakana')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="contactPhoneNumber">
                                Contact Phone Number
                            </label>
                            <input wire:model.lazy="contactPhoneNumber"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="contactPhoneNumber" type="text">
                            @error('contactPhoneNumber')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="tag">
                                Responsiblity
                            </label>
                            <select wire:model.lazy="tag"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="tag">
                                <option value="">Select a tag</option>
                                <option value="general">General</option>
                                <option value="application">Application</option>
                                <option value="interview">Interview</option>
                                <option value="technical">Technical</option>
                            </select>
                            @error('tag')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="tempImage">
                                Profile Image
                            </label>
                            <input wire:model.lazy="tempImage" type="file"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="tempImage">
                            @error('tempImage')
                                <span class="text-red-500 text-xs italic">{{ $message }}</span>
                            @enderror
                        </div> --}}

                        <div class="flex items-center justify-between mt-4">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">Save Changes</span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </button>
                            <button wire:click="cancelEditing" type="button"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('business-operator.business-operators.index') }}"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Return to Business Operators List
        </a>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('home') }}"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            Return to TOP
        </a>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-popup wire:model="showDeleteConfirmation">
        <div class="p-6">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Business Operator</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete this business operator? This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button wire:click="deleteBusinessOperator"
                        class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="deleteBusinessOperator">Delete</span>
                        <span wire:loading wire:target="deleteBusinessOperator">Deleting...</span>
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
