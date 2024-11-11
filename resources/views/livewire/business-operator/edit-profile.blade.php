<div class="pb-16">
    <div class="container max-w-2xl mx-auto mt-8">
        <h2 class="pb-5 mb-4 text-2xl font-bold text-center">Edit Business Operator Profile</h2>
        @if(!$isEditing)
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="flex justify-center col-span-2 mb-4">
                    <img src="{{ $profileImage ? Storage::disk('s3')->url($profileImage) : asset('placeholder.png') }}" alt="Profile Picture" class="object-cover w-32 h-32 rounded-full">
                </div>
                <div class="font-semibold text-right">User ID:</div>
                <div>{{ $user->username }}</div>
                <div class="font-semibold text-right">User Name (English):</div>
                <div>{{ $name }}</div>
                <div class="font-semibold text-right">User Name (Kanji):</div>
                <div>{{ $nameKanji }}</div>
                <div class="font-semibold text-right">User Name (Katakana):</div>
                <div>{{ $nameKatakana }}</div>
                <div class="font-semibold text-right">Email Address:</div>
                <div>{{ $email }}</div>
                <div class="font-semibold text-right">Contact Phone Number:</div>
                <div>{{ $contactPhoneNumber }}</div>
                <div class="flex justify-center col-span-2 mt-4">
                    <button wire:click="startEditing" wire:loading.remove wire:target="startEditing" class="px-4 py-2 text-white bg-blue-500 rounded"><i class="fas fa-edit"></i> Edit</button>
                    <div wire:loading wire:target="startEditing">
                        <span class="font-bold text-blue-500"><i class="fa fa-spinner fa-spin"></i> Opening Editing Form...</span>
                    </div>
                </div>
            </div>
        @else
            <form wire:submit.prevent="confirmUpdate">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-right">
                        <label for="userId" class="block my-2">User ID</label>
                    </div>
                    <div>
                        <input type="text" id="userId" value="{{ $user->username }}" class="w-full px-3 py-2 border rounded disabled:opacity-75 disabled:cursor-not-allowed" disabled>
                    </div>
                    <div class="text-right">
                        <label for="name" class="block my-2">User Name (English)</label>
                    </div>
                    <div>
                        <input type="text" id="name" wire:model.lazy="name" class="w-full px-3 py-2 border rounded">
                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-right">
                        <label for="nameKanji" class="block my-2">User Name (Kanji)</label>
                    </div>
                    <div>
                        <input type="text" id="nameKanji" wire:model.lazy="nameKanji" class="w-full px-3 py-2 border rounded">
                        @error('nameKanji') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-right">
                        <label for="nameKatakana" class="block my-2">User Name (Katakana)</label>
                    </div>
                    <div>
                        <input type="text" id="nameKatakana" wire:model.lazy="nameKatakana" class="w-full px-3 py-2 border rounded">
                        @error('nameKatakana') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-right">
                        <label for="email" class="block my-2">E-mail Address</label>
                    </div>
                    <div>
                        <input type="email" id="email" wire:model.lazy="email" class="w-full px-3 py-2 border rounded">
                        @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-right">
                        <label for="contactPhoneNumber" class="block my-2">Contact Phone Number</label>
                    </div>
                    <div>
                        <input type="text" id="contactPhoneNumber" wire:model.lazy="contactPhoneNumber" class="w-full px-3 py-2 border rounded">
                        @error('contactPhoneNumber') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-right">
                        <label for="newPassword" class="block my-2">New Password</label>
                    </div>
                    <div class="relative">
                        <input type="password" id="newPassword" wire:model.lazy="newPassword" class="w-full px-3 py-2 border rounded">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 px-3 py-2">
                            <i id="toggle-password-icon" class="fas fa-eye"></i>
                        </button>
                        @error('newPassword') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-right">
                        <label for="newPasswordConfirmation" class="block my-2">Confirm Password</label>
                    </div>
                    <div>
                        <input type="password" id="newPasswordConfirmation" wire:model.lazy="newPasswordConfirmation" class="w-full px-3 py-2 border rounded">
                        @error('newPasswordConfirmation') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4 text-right">
                        <label for="tempProfileImage" class="block my-2">Profile Picture</label>
                    </div>
                    <div class="relative w-32 h-32">
                        @if ($tempProfileImage)
                            <img src="{{ $tempProfileImage->temporaryUrl() }}" alt="Temp Profile Picture" class="object-contain w-full h-full border border-gray-300 rounded shadow-sm">
                        @else
                            <img src="{{ $profileImage ? Storage::disk('s3')->url($profileImage) : asset('placeholder.png') }}" alt="Profile Picture" class="object-contain w-full h-full border border-gray-300 rounded shadow-sm">
                        @endif

                        <label for="tempProfileImage" class="absolute top-0 right-0 p-1 text-white bg-black bg-opacity-50 rounded-full cursor-pointer hover:bg-opacity-75">
                            <i class="fas fa-camera"></i>
                            <input type="file" id="tempProfileImage" wire:model.lazy="tempProfileImage" accept="image/*" class="hidden">
                        </label>
                    </div>
                    @error('tempProfileImage') <span class="mt-2 text-red-500">{{ $message }}</span> @enderror
                </div>                <div class="flex justify-center mt-4 space-x-5">
                    <button type="button" wire:click="cancelEditing" class="px-4 py-2 text-white bg-red-500 rounded"><i class="fa-solid fa-xmark"></i> Cancel</button>

                    <button type="submit" wire:loading.remove wire:target="confirmUpdate" class="px-4 py-2 text-white bg-green-500 rounded"><i class="fa-regular fa-floppy-disk"></i> Update</button>
                    <div wire:loading wire:target="confirmUpdate" class="mt-1">
                        <span class="font-bold text-green-500"><i class="fa fa-spinner fa-spin"></i> Updating...</span>
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
                <button wire:click="updateProfile" wire:loading.remove wire:target="updateProfile" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Confirm Update</button>
                <div wire:loading wire:                target="updateProfile">
                    <span class="font-bold text-blue-500"><i class="fa fa-spinner fa-spin"></i> Updating...</span>
                </div>
            </div>
        </div>
    </x-popup>

    {{-- <div x-data="{ progress: 0 }" x-on:livewire-upload-start="progress = 0" x-on:livewire-upload-finish="progress = 100" x-on:livewire-upload-error="progress = 0" x-on:livewire-upload-progress="progress = $event.detail.progress">
        <div x-show="progress > 0" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-4">
            <div class="bg-blue-600 h-2.5 rounded-full" :style="`width: ${progress}%`"></div>
        </div>
    </div> --}}

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
