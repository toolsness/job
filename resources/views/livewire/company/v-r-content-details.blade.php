<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">VR Content Details</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        @if ($isEditing)
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label for="newContentName" class="block text-sm font-medium text-gray-700">Content Name</label>
                    <input type="text" id="newContentName" wire:model="newContentName"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
    focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('newContentName')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="newContentLink" class="block text-sm font-medium text-gray-700">Content Link</label>
                    <input type="text" id="newContentLink" disabled wire:model="newContentLink"
                        class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm
    focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('newContentLink')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="newRemarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                    <textarea id="newRemarks" wire:model="newRemarks" rows="3" disabled
                        class="bg-gray-100 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:borderindigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    @error('newRemarks')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="newStatus" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="newStatus" wire:model="newStatus"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300
    focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @foreach (['Public', 'Private', 'Draft'] as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('newStatus')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="newImage" class="block text-sm font-medium text-gray-700">Image</label>
                    <input type="file" id="newImage" wire:model="newImage" class="mt-1 block w-full">
                    @error('newImage')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save</button>
                    <button type="button" wire:click="toggleEdit"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                </div>
            </form>
        @else
            <div class="space-y-4">
                <img src="{{ $vrContent->image ? Storage::url($vrContent->image) : asset('placeholder.png') }}"
                    alt="{{ $vrContent->content_name }}" class="wfull h-64 object-cover rounded-lg">
                <p><strong>Content Name:</strong> {{ $vrContent->content_name }}</p>
                <p><strong>Content Link:</strong> <a href="{{ $vrContent->content_link }}" target="_blank"
                        class="text-blue-500 hover:underline">{{ $vrContent->content_link }}</a></p>
                <p><strong>Remarks:</strong> {{ $vrContent->remarks }}</p>
                <p><strong>Status:</strong> {{ $vrContent->status }}</p>
                <p><strong>Category:</strong> {{ $vrContent->content_category_name }}</p>
                @if (Auth::user()->user_type === 'CompanyAdmin')
                    <button wire:click="toggleEdit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Edit</button>
                @endif
            </div>
        @endif
    </div>

    <div class="text-center my-6">
    <a href="{{ route('company.vr-contents.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back
        to list</a>
    </div>
</div>
