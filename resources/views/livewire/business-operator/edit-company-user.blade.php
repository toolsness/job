<div class="container mx-auto px-4 py-8" x-data="{ showDeleteConfirmation: @entangle('showDeleteConfirmation'), showConversionWarning: @entangle('showConversionWarning') }">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-6">Company User Details</h2>

        @if (!$isEditing)
            <div class="mb-4">
                {{-- <div class="w-32 h-32 mb-4">
                    @if ($user->image)
                        <img src="{{ Storage::url($user->image) }}" alt="{{ $name }}"
                            class="w-full h-full object-cover rounded-full">
                    @else
                        <div class="w-full h-full bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                </div> --}}
                <p><strong>User ID:</strong> {{ $user->username }}</p>
                <p><strong>Name:</strong> {{ $name }}</p>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Name (Kanji):</strong> {{ $nameKanji }}</p>
                <p><strong>Name (Katakana):</strong> {{ $nameKatakana }}</p>
                <p><strong>User Type:</strong> {{ $userType }}</p>
                <p><strong>Contact Phone Number:</strong> {{ $contactPhoneNumber }}</p>
                <p><strong>Company:</strong> {{ $companyName }}</p>
                <p><strong>Industry:</strong> {{ $companyIndustry }}</p>
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
                {{-- @if ($userType === 'CompanyAdmin')
                    <button wire:click="confirmConversion('CompanyRepresentative')"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Convert to Representative
                    </button>
                @else
                    <button wire:click="confirmConversion('CompanyAdmin')"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Convert to Admin
                    </button>
                @endif --}}
            </div>
        @else
            <form wire:submit.prevent="save">
                {{-- <div class="mb-4">
                    <label for="tempImage" class="block text-gray-700 text-sm font-bold mb-2">Profile Image</label>
                    <div class="mt-1 flex items-center">
                       <div class="relative w-32 h-32">
                            @if ($tempImage)
                                <img src="{{ $tempImage->temporaryUrl() }}" alt="Temp Image"
                                    class="w-full h-full object-cover rounded-full">
                            @elseif ($user->image)
                                <img src="{{ Storage::url($user->image) }}" alt="{{ $name }}"
                                    class="w-full h-full object-cover rounded-full">
                            @else
                                <div class="w-full h-full bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                            @if ($tempImage || $user->image)
                                <button type="button" wire:click="deleteImage"
                                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <input wire:loading.attr="disabled" type="file" wire:model="tempImage" id="tempImage" class="hidden" accept="image/*">
                        <label for="tempImage"
                            class="ml-4 cursor-pointer bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <span wire:target="tempImage" wire:loading.remove>Choose Image</span><span wire:loading wire:target="tempImage">Loading...</span><span wire:loading wire:target="save">Uploading...</span>
                        </label>
                    </div>
                    @error('tempImage')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div> --}}

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input wire:model="name" type="text" id="name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('name')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input wire:model="email" type="email" id="email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('email')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nameKanji" class="block text-gray-700 text-sm font-bold mb-2">Name (Kanji)</label>
                    <input wire:model="nameKanji" type="text" id="nameKanji"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('nameKanji')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nameKatakana" class="block text-gray-700 text-sm font-bold mb-2">Name (Katakana)</label>
                    <input wire:model="nameKatakana" type="text" id="nameKatakana"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('nameKatakana')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="contactPhoneNumber" class="block text-gray-700 text-sm font-bold mb-2">Contact Phone
                        Number</label>
                    <input wire:model="contactPhoneNumber" type="text" id="contactPhoneNumber"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('contactPhoneNumber')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Company Information</label>
                    <input value="{{ $companyName }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="text" readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Industry</label>
                    <input value="{{ $companyIndustry }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="text" readonly>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button type="submit" wire:target="save"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Save Changes</span>
                        <span wire:loading>Processing...</span>
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
        <a href="{{ route('business-operator.company-users') }}"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Return to Company Users List
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
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Company User</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete this company user? This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button wire:click="deleteUser"
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

    <!-- Conversion Warning Modal -->
    <x-popup wire:model="showConversionWarning">
        <div class="p-6">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Convert User Type</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to convert this user to {{ $newUserType }}? This action will change the
                        user's permissions.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button wire:click="convertUserType"
                        class="px-4 py-2 bg-yellow-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                        Convert
                    </button>
                    <button wire:click="$set('showConversionWarning', false)"
                        class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </x-popup>
</div>
