<div>
    <h2 class="text-2xl font-semibold mb-4">Create New Student</h2>
    <form wire:submit.prevent="createStudent" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" wire:model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('name') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" wire:model="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('email') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
            <input type="password" id="password" wire:model="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('password') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="name_kanji" class="block text-gray-700 text-sm font-bold mb-2">Name (Kanji):</label>
            <input type="text" id="name_kanji" wire:model="name_kanji" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('name_kanji') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="name_katakana" class="block text-gray-700 text-sm font-bold mb-2">Name (Katakana):</label>
            <input type="text" id="name_katakana" wire:model="name_katakana" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('name_katakana') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="name_japanese" class="block text-gray-700 text-sm font-bold mb-2">Name (Japanese):</label>
            <input type="text" id="name_japanese" wire:model="name_japanese" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('name_japanese') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="contact_phone_number" class="block text-gray-700 text-sm font-bold mb-2">Contact Phone Number:</label>
            <input type="text" id="contact_phone_number" wire:model="contact_phone_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('contact_phone_number') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="profileImage" class="block text-gray-700 text-sm font-bold mb-2">Profile Image:</label>
            <div class="relative w-32 h-32">
                @if ($profileImage)
                    <img src="{{ $profileImage->temporaryUrl() }}" alt="Temp Profile Image" class="object-cover w-full h-full border border-gray-300 rounded shadow-sm">
                @else
                    <img src="{{ asset('placeholder.png') }}" alt="Profile Image Placeholder" class="object-cover w-full h-full border border-gray-300 rounded shadow-sm">
                @endif

                <label for="profileImage" class="absolute top-0 right-0 p-1 text-white bg-black bg-opacity-50 rounded-full cursor-pointer hover:bg-opacity-75">
                    <i class="fas fa-camera"></i>
                    <input type="file" id="profileImage" wire:model="profileImage" accept="image/*" class="hidden">
                </label>
            </div>
            @error('profileImage') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create Student
            </button>
        </div>
    </form>

    <div x-data="{ progress: 0 }" x-on:livewire-upload-start="progress = 0" x-on:livewire-upload-finish="progress = 100" x-on:livewire-upload-error="progress = 0" x-on:livewire-upload-progress="progress = $event.detail.progress">
        <div x-show="progress > 0" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-4">
            <div class="bg-blue-600 h-2.5 rounded-full" :style="`width: ${progress}%`"></div>
        </div>
    </div>
</div>
