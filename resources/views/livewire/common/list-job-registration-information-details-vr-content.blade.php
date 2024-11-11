<div>
    <section class="flex justify-center items-center px-16 py-20 text-center text-black bg-white max-md:px-5">
        <div class="flex flex-col items-center mt-16 max-w-full w-[673px] max-md:mt-10">
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="relative w-full">
                @if ($isEditing)
                    <label for="image-upload" class="cursor-pointer block relative">
                        @if ($newImage)
                            <img src="{{ $newImage->temporaryUrl() }}" alt="New VR content image"
                                class="w-full aspect-[1.61] object-cover">
                        @elseif ($vrContent->image)
                            <img src="{{ Storage::disk('s3')->url($vrContent->image) }}" alt="Current VR content image"
                                class="w-full aspect-[1.61] object-cover">
                        @else
                            <div class="w-full aspect-[1.61] bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No image available</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <span class="text-white text-lg">Click to upload new image</span>
                        </div>
                    </label>
                    <input id="image-upload" type="file" wire:model="newImage" class="hidden" accept="image/*">
                @else
                    @if ($vrContent->image)
                        <img loading="lazy" src="{{ Storage::disk('s3')->url($vrContent->image) }}"
                            alt="Visual representation of {{ $contentType }} VR experience"
                            class="w-full aspect-[1.61] object-cover">
                    @else
                        <div class="w-full aspect-[1.61] bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No image available</span>
                        </div>
                    @endif
                @endif
            </div>

            <h2 class="mt-4 text-xl font-semibold">
                {{ $contentType == 'CompanyIntroduction' ? 'Company Introduction VR Content' : 'VR Workplace Tour' }}
            </h2>

            @if ($isEditing)
                <form wire:submit.prevent="save" class="w-full mt-4">
                    <div class="mb-4">
                        <label for="newContentName" class="block text-sm font-medium text-gray-700">Content Name</label>
                        <input type="text" id="newContentName" wire:model="newContentName"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('newContentName')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="newContentLink" class="block text-sm font-medium text-gray-700">Content Link</label>
                        <input type="text" id="newContentLink" wire:model="newContentLink" disabled
                            class="bg-gray-100 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('newContentLink')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="newRemarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                        <textarea id="newRemarks" wire:model="newRemarks" rows="3" disabled
                            class="bg-gray-100 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        @error('newRemarks')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="newStatus" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="newStatus" wire:model="newStatus"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach (['Public', 'Private', 'Draft'] as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('newStatus')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Save Changes
                    </button>
                </form>
            @else
                <div class="text-start">
                    <p class="mt-2"><strong>Content Name:</strong> {{ $vrContent->content_name }}</p>
                    @if (Auth::user()->user_type == 'CompanyAdmin' || Auth::user()->user_type == 'CompanyRepresentative')
                        <p class="mt-2"><strong>Status:</strong> {{ $vrContent->status }}</p>
                        @if ($vrContent->remarks)
                            <p class="mt-2"><strong>Remarks:</strong> {{ $vrContent->remarks }}</p>
                        @endif
                    @endif
                </div>
                <button wire:click="playVR"
                    class="mt-4 px-4 py-2 bg-white text-black border border-black rounded-md hover:bg-blue-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Play VR Experience
                </button>

                <!-- Add the VR Popup -->
                <x-vr-popup wire:model="showVRPopup" maxWidth="7xl"></x-vr-popup>
            @endif

            @if (Auth::user()->user_type == 'CompanyAdmin')
                <button wire:click="toggleEdit"
                    class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    {{ $isEditing ? 'Cancel' : 'Edit' }}
                </button>
            @endif
            <div class='mt-10'>
                <a href="{{ route('job-details', $vacancy->id) }}"
                    class="mt-8 inline-block px-4 py-2 bg-white text-black border border-black rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    Return to Job Details
                </a>
            </div>
        </div>
    </section>
    <style>
        /* Optional: Add smooth transitions for the iframe */
        iframe {
            transition: all 0.3s ease;
        }

        /* Optional: Add custom scrollbar for the popup */
        .vr-popup-content::-webkit-scrollbar {
            width: 8px;
        }

        .vr-popup-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .vr-popup-content::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .vr-popup-content::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</div>
