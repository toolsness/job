<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-8 text-center">New Company User Registration</h1>

    <form wire:submit.prevent="save" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            {{-- <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Profile Image</label>
            <div class="mt-1 flex items-center">
                @if ($image)
                    <div class="relative w-24 h-24">
                        <img src="{{ $image->temporaryUrl() }}" alt="Profile Image"
                            class="w-24 h-24 object-cover rounded-full">
                        <button type="button" wire:click="$set('image', null)"
                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif
                <input type="file" wire:model="image" id="image" class="hidden" accept="image/*"
                    wire:loading.attr="disabled">
                <label for="image"
                    class="ml-4 cursor-pointer bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <span wire:target="image" wire:loading.remove>Choose Image</span><span wire:loading
                        wire:target="image">Loading...</span><span wire:loading wire:target="save">Uploading...</span>
                </label>
            </div> --}}
        </div>
        @error('image')
            <span class="text-red-500 text-xs italic">{{ $message }}</span>
        @enderror

        <div class="mb-4">
            <label for="username" class="block text-gray-700 text-sm font-bold mb-2">User ID</label>
            <input disabled type="text" wire:model.lazy="username" id="username" placeholder="(Auto Generated)"
                class="bg-gray-200 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('username')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name (English) <i class="text-red-500">*</i></label>
            <input type="text" wire:model.lazy="name" id="name"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Please enter your name in English. E.g. John">
            @error('name')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="nameKanji" class="block text-gray-700 text-sm font-bold mb-2">Name <i class="text-red-500">*</i></label>
            <input type="text" wire:model.lazy="nameKanji" id="nameKanji"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Please enter your name in any language. E.g. 田中 / John">
            @error('nameKanji')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="nameKatakana" class="block text-gray-700 text-sm font-bold mb-2">Name (Katakana) <i class="text-red-500">*</i></label>
            <input type="text" wire:model.lazy="nameKatakana" id="nameKatakana"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Please enter your name in Katakana. E.g. タナカ">
            @error('nameKatakana')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email <i class="text-red-500">*</i></label>
            <input type="email" wire:model.lazy="email" id="email"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Please enter your email. E.g. 1V7H2@example.com">
            @error('email')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4" x-data="{ showPassword: false, showConfirmPassword: false }">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password <i class="text-red-500">*</i></label>
            <div class="relative">
                <input :type="showPassword ? 'text' : 'password'" wire:model.lazy="password" id="password"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Please enter your password">
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 px-3 py-2">
                    <i class="fas" :class="{ 'fa-eye': !showPassword, 'fa-eye-slash': showPassword }"></i>
                </button>
            </div>
            @error('password')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4" x-data="{ showPassword: false, showConfirmPassword: false }">
            <label for="passwordconfirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm
                Password <i class="text-red-500">*</i></label>
            <div class="relative">
                <input :type="showConfirmPassword ? 'text' : 'password'" wire:model.lazy="passwordconfirmation" id="passwordconfirmation"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Please enter your password again.">
                <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                    class="absolute inset-y-0 right-0 px-3 py-2">
                    <i class="fas" :class="{ 'fa-eye': !showConfirmPassword, 'fa-eye-slash': showConfirmPassword }"></i>
                </button>
            </div>
            @error('passwordconfirmation')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="userType" class="block text-gray-700 text-sm font-bold mb-2">User Type <i class="text-red-500">*</i></label>
            <select wire:model="userType" id="userType"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select user type</option>
                <option value="CompanyAdmin">Company Admin</option>
                <option value="CompanyRepresentative">Company Representative</option>
            </select>
            @error('userType')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="companyId" class="block text-gray-700 text-sm font-bold mb-2">Company <i class="text-red-500">*</i></label>
            <select wire:model="companyId" id="companyId"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select company</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
            @error('companyId')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="contactPhoneNumber" class="block text-gray-700 text-sm font-bold mb-2">Contact Phone
                Number <i class="text-red-500">*</i></label>
            <input type="text" wire:model.lazy="contactPhoneNumber" id="contactPhoneNumber"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Please enter your contact phone number in english or japanese numaric.">
            @error('contactPhoneNumber')
                <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" wire:target="save"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Register User</span>
                <span wire:loading>Processing...</span>
            </button>
            <a href="{{ route('business-operator.company-users') }}"
                class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>
