<div class="pb-16">
    <div class="container max-w-2xl mx-auto mt-8">
        <h2 class="mb-5 text-2xl font-bold text-center">Edit Company Information</h2>
        @if(!$isEditing)
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="font-semibold text-right">Company ID:</div>
                <div>{{ $company->id }}</div>
                <div class="font-semibold text-right">Name of {{ str_replace('Company', '', $userType) }} (Kanji):</div>
                <div>{{ $companyUserNameKanji }}</div>
                <div class="font-semibold text-right">Name of {{ str_replace('Company', '', $userType) }} (Katakana):</div>
                <div>{{ $companyUserNameKatakana }}</div>
                <div class="font-semibold text-right">{{ str_replace('Company', '', $userType) }} Contact Phone:</div>
                <div>{{ $companyUserContactPhone }}</div>
                <div class="font-semibold text-right">Company Name (English):</div>
                <div>{{ $name }}</div>
                <div class="font-semibold text-right">Company Name (Kanji):</div>
                <div>{{ $nameKanji }}</div>
                <div class="font-semibold text-right">Company Name (Katakana):</div>
                <div>{{ $nameKatakana }}</div>
                <div class="font-semibold text-right">Company Address:</div>
                <div>{{ $address }}</div>
                <div class="font-semibold text-right">Website:</div>
                <div>{{ $website }}</div>
                <div class="font-semibold text-right">E-mail Address:</div>
                <div>{{ $contactEmail }}</div>
                <div class="font-semibold text-right">Contact Phone:</div>
                <div>{{ $contactPhone }}</div>
                <div class="flex justify-center col-span-2 mb-4">
                    <img src="{{ $logo ? Storage::url($logo) : asset('placeholder.png') }}" alt="Company Logo" class="object-contain w-32 h-32">
                </div>
                <div class="flex justify-center col-span-2 mt-4">
                    <button wire:click="startEditing" wire:loading.remove wire:target="startEditing" class="px-4 py-2 text-white bg-blue-500 rounded"><i class="fas fa-edit"></i> Edit</button>
                    <div wire:loading wire:target="startEditing">
                        <span class="font-bold text-red-500"><i class="fa fa-spinner fa-spin"></i> processing...</span>
                    </div>
                </div>
            </div>
        @else
            <form wire:submit.prevent="confirmUpdate">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-right">
                        <label for="companyId" class="block my-2">Company ID</label>
                    </div>
                    <div>
                        <input type="text" id="companyId" value="{{ $company->id }}"
                            class="w-full px-3 py-2 border rounded hover:bg-gray-100 disabled:opacity-75 disabled:cursor-not-allowed"
                            disabled>
                        @error('id')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="companyUserNameKanji" class="block my-2">Name of
                            {{ str_replace('Company', '', $userType) }} (Kanji)</label>
                    </div>
                    <div>
                        <input type="text" id="companyUserNameKanji" wire:model.lazy="companyUserNameKanji"
                            class="w-full px-3 py-2 border rounded">
                        @error('companyUserNameKanji')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="companyUserNameKatakana" class="block my-2">Name of
                            {{ str_replace('Company', '', $userType) }} (Katakana)</label>
                    </div>
                    <div>
                        <input type="text" id="companyUserNameKatakana" wire:model.lazy="companyUserNameKatakana"
                            class="w-full px-3 py-2 border rounded">
                        @error('companyUserNameKatakana')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="companyUsercontactPhone"
                            class="block my-2">{{ str_replace('Company', '', $userType) }} Contact Phone</label>
                    </div>
                    <div>
                        <input type="text" id="companyUsercontactPhone" wire:model.lazy="companyUserContactPhone"
                            class="w-full px-3 py-2 border rounded">
                        @error('companyUserContactPhone')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="newPassword" class="block my-2">New Password</label>
                    </div>
                    <div class="relative">
                        <input type="password" id="newPassword" wire:model.lazy="newPassword"
                            class="w-full px-3 py-2 border rounded">
                        <button type="button" onclick="togglePasswordVisibility()"
                            class="absolute inset-y-0 right-0 px-3 py-2">
                            <i id="toggle-password-icon" class="fas fa-eye"></i>
                        </button>
                        @error('newPassword')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="newPasswordConfirmation" class="block my-2">Confirm Password</label>
                    </div>
                    <div>
                        <input type="password" id="newPasswordConfirmation" wire:model.lazy="newPasswordConfirmation"
                            class="w-full px-3 py-2 border rounded">
                        @error('newPasswordConfirmation')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="name" class="block my-2">Company Name (English)</label>
                    </div>
                    <div>
                        <input type="text" id="name" wire:model.lazy="name"
                            class="w-full px-3 py-2 border rounded">
                        @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="nameKanji" class="block my-2">Company Name (Kanji)</label>
                    </div>
                    <div>
                        <input type="text" id="nameKanji" wire:model.lazy="nameKanji"
                            class="w-full px-3 py-2 border rounded">
                        @error('nameKanji')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="nameKatakana" class="block my-2">Company Name (Katakana)</label>
                    </div>
                    <div>
                        <input type="text" id="nameKatakana" wire:model.lazy="nameKatakana"
                            class="w-full px-3 py-2 border rounded">
                        @error('nameKatakana')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="address" class="block my-2">Company Address</label>
                    </div>
                    <div>
                        <input type="text" id="address" wire:model.lazy="address"
                            class="w-full px-3 py-2 border rounded">
                        @error('address')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="website" class="block my-2">Website</label>
                    </div>
                    <div>
                        <input type="url" id="website" wire:model.lazy="website"
                            class="w-full px-3 py-2 border rounded">
                        @error('website')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="contactEmail" class="block my-2">E-mail Address</label>
                    </div>
                    <div>
                        <input type="email" id="contactEmail" wire:model.lazy="contactEmail"
                            class="w-full px-3 py-2 border rounded">
                        @error('contactEmail')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <label for="contactPhone" class="block my-2">Contact Phone</label>
                    </div>
                    <div>
                        <input type="text" id="contactPhone" wire:model.lazy="contactPhone"
                            class="w-full px-3 py-2 border rounded">
                        @error('contactPhone')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4 text-right">
                        <label for="tempLogo" class="block my-2">Company Icon Image</label>
                    </div>
                    <div class="relative w-32 h-32">
                        @if ($tempLogo)
                            <img src="{{ $tempLogo->temporaryUrl() }}" alt="Temp Company Logo"
                                class="object-contain w-full h-full border border-gray-300 rounded shadow-sm">
                        @else
                            <img src="{{ $logo ? Storage::url($logo) : asset('placeholder.png') }}"
                                alt="Company Logo"
                                class="object-contain w-full h-full border border-gray-300 rounded shadow-sm">
                        @endif

                        <label for="tempLogo"
                            class="absolute top-0 right-0 p-1 text-white bg-black bg-opacity-50 rounded-full cursor-pointer hover:bg-opacity-75">
                            <i class="fas fa-camera"></i>
                            <input type="file" id="tempLogo" wire:model.lazy="tempLogo" accept="image/*"
                                class="hidden">
                        </label>
                    </div>
                    @error('tempLogo')
                        <span class="mt-2 text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-center mt-4 space-x-5">
                    <button type="button" wire:click="cancelEditing" class="px-4 py-2 text-white bg-red-500 rounded"><i class="fa-solid fa-xmark"></i> Cancel</button>

                    <button type="submit" wire:loading.remove wire:target="confirmUpdate" class="px-4 py-2 text-white bg-green-500 rounded"><i class="fa-regular fa-floppy-disk"></i> Update</button>
                    <div wire:loading wire:target="confirmUpdate" class="mt-1">
                        <span class="font-bold text-red-500"><i class="fa fa-spinner fa-spin"></i> Processing...</span>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <x-popup wire:model="showPasswordConfirmation">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Password</h3>
            <div class="mb-4">
                <input type="password" wire:model.defer="currentPassword" placeholder="Enter your current password" class="w-full px-3 py-2 border rounded">
                @error('currentPassword') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end">
                <button wire:click="cancelPasswordConfirmation" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mr-2">Cancel</button>
                <button wire:click="updateCompanyInfo" wire:loading.remove wire:target="updateCompanyInfo" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Confirm Update</button>
                <div wire:loading wire:target="updateCompanyInfo">
                    <span class="font-bold text-blue-500"><i class="fa fa-spinner fa-spin"></i> Updating...</span>
                </div>
            </div>
        </div>
    </x-popup>

    <div x-data="{ progress: 0 }" x-on:livewire-upload-start="progress = 0" x-on:livewire-upload-finish="progress = 100" x-on:livewire-upload-error="progress = 0" x-on:livewire-upload-progress="progress = $event.detail.progress">
        <div x-show="progress > 0" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-4">
            <div class="bg-blue-600 h-2.5 rounded-full" :style="`width: ${progress}%`"></div>
        </div>
    </div>

    <div class="text-center mt-6 sm:mt-12 w-full max-w-[506px] mx-auto px-2 sm:px-0">
        <a href="{{ url('/') }}" class="w-full px-4 py-2 text-xs bg-white border border-black rounded-md sm:px-6 sm:py-3 sm:text-sm sm:w-auto">
            Return to TOP
        </a>
    </div>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('newPassword');
            const confirmPasswordField = document.getElementById('newPasswordConfirmation');
            const icon = document.getElementById('toggle-password-icon');

            if (passwordField.type === "password") {
                passwordField.type = "text";
                confirmPasswordField.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                confirmPasswordField.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</div>
