<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Manage News and Notices</h1>

    <div class="mb-4">
        <button wire:click="openPopup('news')" class="bg-blue-500 text-white px-4 py-2 rounded">Add News</button>
        <button wire:click="openPopup('notice')" class="bg-green-500 text-white px-4 py-2 rounded ml-2">Add Notice</button>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">News</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Content</th>
                    <th class="border border-gray-300 px-4 py-2">For</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($news as $item)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ Str::limit($item->content, 50) }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ ucfirst($item->for) }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <button wire:click="openPopup('news', {{ $item->id }})" class="text-blue-500">Edit</button>
                            <button wire:click="delete({{ $item->id }})" class="text-red-500 ml-2">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $news->links() }}
    </div>

    <div>
        <h2 class="text-xl font-semibold mb-4">Notices</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Content</th>
                    <th class="border border-gray-300 px-4 py-2">For</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notices as $item)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ Str::limit($item->content, 50) }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ ucfirst($item->for) }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <button wire:click="openPopup('notice', {{ $item->id }})" class="text-blue-500">Edit</button>
                            <button wire:click="delete({{ $item->id }})" class="text-red-500 ml-2">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $notices->links() }}
    </div>

    <x-popup wire:model="showPopup">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $editMode ? 'Edit' : 'Create' }} {{ ucfirst($itemType) }}</h3>
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content:</label>
                    <textarea wire:model="content" id="content" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                    @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="for" class="block text-gray-700 text-sm font-bold mb-2">For:</label>
                    <select wire:model="for" id="for" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="student">Student</option>
                        <option value="company">Company</option>
                        <option value="public">Public</option>
                    </select>
                    @error('for') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end">
                    <button type="button" wire:click="closePopup" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mr-2">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </x-popup>
</div>
