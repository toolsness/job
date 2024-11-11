<div class="container mx-auto px-4 py-8" x-data="{ showDeleteConfirmation: @entangle('showDeleteConfirmation') }">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-6">VR Content Details</h2>

        @if (!$isEditing)
            <div class="mb-4">
                @if ($image)
                    <img src="{{ Storage::url($image) }}" alt="{{ $content_name }}" class="w-32 h-32 object-cover mb-4">
                @endif
                <p><strong>Content Name:</strong> {{ $content_name }}</p>
                <p><strong>Content Category:</strong> {{ $vrContent->content_category_name }}</p>
                <p><strong>Content Link:</strong> <a href="{{ $content_link }}" target="_blank"
                        class="text-blue-500 hover:underline">{{ $content_link }}</a></p>
                <p><strong>Company:</strong> {{ $vrContent->company ? $vrContent->company->name : 'N/A' }}</p>
                <p><strong>Remark:</strong> {{ $remarks ? $remarks : 'N/A' }}</p>
                <p><strong>Status:</strong> {{ $status }}</p>
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
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="content_name">
                        Content Name
                    </label>
                    <input wire:model="content_name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="content_name" type="text">
                    @error('content_name')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="content_category">
                        Content Category
                    </label>
                    <select wire:model="content_category"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="content_category">
                        <option value="">-- Select --</option>
                        <option value="CompanyIntroduction">Company Introduction</option>
                        <option value="WorkplaceTour">Workplace Tour</option>
                    </select>
                    @error('content_category')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="content_link">
                        Content Link
                    </label>
                    <input wire:model="content_link"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="content_link" type="url">
                    @error('content_link')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tempImage">
                        Image
                    </label>
                    @if ($image)
                        <img src="{{ Storage::url($image) }}" alt="{{ $content_name }}"
                            class="w-32 h-32 object-cover mb-2">
                        <button type="button" wire:click="deleteImage" class="text-red-500 hover:text-red-700">
                            Delete Image
                        </button>
                    @endif
                    <input wire:model="tempImage"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="tempImage" type="file">
                    @error('tempImage')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="company_id">
                        Company
                    </label>
                    <select wire:model="company_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="company_id">
                        <option value="">Select a company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="remarks">
                        Remarks
                    </label>
                    <input wire:model="remarks"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="remarks" type="text">
                    @error('remarks')
                        <span class="text-red-500 text-xs italic">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Status
                    </label>
                    <select wire:model="status"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="status">
                        <option value="">Select a status</option>
                        <option value="Public">Public</option>
                        <option value="Private">Private</option>
                        <option value="Draft">Draft</option>
                    </select>
                    @error('status')
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
        <a href="{{ route('business-operator.vr-contents.index') }}"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Return to VR Contents List
        </a>
    </div>

<!-- Delete Confirmation Modal -->
<x-popup wire:model="showDeleteConfirmation">
    <div class="p-6">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete VR Content</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete this VR content? This action cannot be undone.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button wire:click="deleteVRContent"
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
