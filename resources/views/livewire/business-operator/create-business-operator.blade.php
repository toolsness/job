<div>
    <h2 class="text-2xl font-semibold mb-4 text-center">Create New Business Operator</h2>
    <div class="grid grid-cols-3 ">
        <div class="col-span-1"></div>
        <form class="col-span-1" wire:submit.prevent="createBusinessOperator" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                <input type="text" id="username" wire:model.lazy="username"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('username')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" wire:model.lazy="name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('name')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" wire:model.lazy="email"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('email')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input type="password" id="password" wire:model.lazy="password"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('password')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password:</label>
                <input type="password" id="password_confirmation" wire:model.lazy="password_confirmation"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('password_confirmation')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nameKanji" class="block text-gray-700 text-sm font-bold mb-2">Name (Kanji):</label>
                <input type="text" id="nameKanji" wire:model.lazy="nameKanji"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('nameKanji')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nameKatakana" class="block text-gray-700 text-sm font-bold mb-2">Name (Katakana):</label>
                <input type="text" id="nameKatakana" wire:model.lazy="nameKatakana"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('nameKatakana')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="contactPhoneNumber" class="block text-gray-700 text-sm font-bold mb-2">Contact Phone Number:</label>
                <input type="text" id="contactPhoneNumber" wire:model.lazy="contactPhoneNumber"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('contactPhoneNumber')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tag" class="block text-gray-700 text-sm font-bold mb-2">Tag:</label>
                <select id="tag" wire:model.lazy="tag"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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

            <div class="mb-4">
                <label for="profileImage" class="block text-gray-700 text-sm font-bold mb-2">Profile Image:</label>
                <input type="file" id="profileImage" wire:model="profileImage"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('profileImage')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
                @if ($profileImage)
                    <div class="mt-2">
                        <img src="{{ $profileImage->temporaryUrl() }}" alt="Profile Image Preview" class="max-w-xs h-auto">
                    </div>
                @else
                    <div class="w-32 mt-2 border border-gray-300">
                        <img src="{{ asset('placeholder.png') }}" alt="Profile Image Placeholder" class="w-24 mx-3 my-3">
                    </div>
                @endif
            </div>

            <div class="mb-4 text-center">
                <label class="items-center">
                    <input type="checkbox" wire:model.lazy="agreeTerms" class="form-checkbox">
                    <span class="ml-2">I agree to the terms and conditions</span>
                </label>
                @error('agreeTerms')
                    <span class="text-red-500 text-xs italic">{{ $message }}</span>
                @enderror
            </div>

            <div class="text-center items-center justify-between">
                <button wire:loading.remove wire:loading.attr="disabled" type="submit"
                    class="bg-white text-black hover:bg-green-700 hover:text-white border border-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline text-center">
                    Create Business Operator
                </button>
                <div wire:loading wire:target="createBusinessOperator">
                    <span class="font-bold text-blue-500"><i class="fa fa-spinner fa-spin"></i> Creating Business Operator...</span>
                </div>
            </div>
        </form>
        <div class="col-span-1"></div>
    </div>
    <div class="mt-8 text-center">
        <a href="{{ route('business-operator.business-operators.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Return to Business Operators List
        </a>
    </div>

    <div class="mt-4 text-center mb-4">
        <a href="{{ route('home') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            Return to TOP
        </a>
    </div>
</div>
