<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="mb-8 text-3xl font-bold text-center text-gray-800">New Company Registration</h1>

    <form wire:submit.prevent="save" class="bg-white rounded-lg shadow-md p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="col-span-1 md:col-span-2">
                <!-- Company Logo -->
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Company Logo</label>
                <div class="flex items-center space-x-6">
                    <div class="relative w-40 h-40 rounded-lg overflow-hidden bg-gray-100">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Company Logo" class="w-full h-full object-cover">
                            <button type="button" wire:click="deleteImage" class="absolute top-2 right-2 bg-red-500 hover:bg-red-700 text-white p-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @else
                            <div class="flex items-center justify-center w-full h-full text-gray-500">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <input type="file" id="image" wire:model.lazy.lazy="image" class="hidden" accept="image/*">
                        <label for="image" class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md inline-block transition duration-300 ease-in-out">
                            Choose Logo
                        </label>
                        <div wire:loading wire:target="image" class="mt-2">
                            <span class="text-sm text-blue-500"><i class="fas fa-spinner fa-spin"></i> Uploading...</span>
                        </div>
                        @error('image')
                            <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Company Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Company Name (English)</label>
                <input type="text" id="name" wire:model.lazy="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Write the company name in English">
                @error('name')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Company Name (Kanji) -->
            <div>
                <label for="name_kanji" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                <input type="text" id="name_kanji" wire:model.lazy="name_kanji" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Write the company name in any language">
                @error('name_kanji')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Company Name (Katakana) -->
            <div>
                <label for="name_katakana" class="block text-sm font-medium text-gray-700 mb-2">Company Name (Katakana)</label>
                <input type="text" id="name_katakana" wire:model.lazy="name_katakana" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Write the company name in Katakana">
                @error('name_katakana')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Industry Type -->
            <div>
                <label for="industry_type_id" class="block text-sm font-medium text-gray-700 mb-2">Industry Type</label>
                <select id="industry_type_id" wire:model.lazy="industry_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">Select Industry Type</option>
                    @foreach ($industryTypes as $industryType)
                        <option value="{{ $industryType->id }}">{{ $industryType->name }}</option>
                    @endforeach
                </select>
                @error('industry_type_id')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Address -->
            <div class="col-span-1 md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <input type="text" id="address" wire:model.lazy="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Write the company address">
                @error('address')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Website -->
            <div>
                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                <input type="url" id="website" wire:model.lazy="website" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Write the company website">
                @error('website')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contact Email -->
            <div>
                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                <input type="email" id="contact_email" wire:model.lazy="contact_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Write the company email address">
                @error('contact_email')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contact Phone -->
            <div>
                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                <input type="text" id="contact_phone" wire:model.lazy="contact_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Write the company phone number">
                @error('contact_phone')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-between mt-8">
            <a href="{{ route('business-operator.companies') }}" class="px-4 py-2 font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-300 ease-in-out">
                <i class="fas fa-arrow-left"></i> Back to Company List
            </a>
            <button type="submit" class="px-6 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out">
                <i class="fas fa-save"></i> Register Company
            </button>
        </div>
    </form>
</div>
