<div class="container mx-auto mt-8 px-4">
    <h2 class="text-2xl font-bold mb-6 text-center">Company Representatives Management</h2>

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
        <div class="mb-4 sm:mb-0">
            <input wire:model.live="search" type="text" placeholder="Search representatives..." class="p-2 border rounded">
        </div>
        <div>
            <select wire:model.live="filter" class="p-2 border rounded">
                <option value="all">All</option>
                <option value="Pending">Pending</option>
                <option value="Allowed">Approved</option>
                <option value="NotAllowed">Rejected</option>
            </select>
        </div>
    </div>

    @if($representatives->isEmpty())
        <p class="text-gray-600 text-center">No representatives found.</p>
    @else
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Phone</th>
                        <th class="px-4 py-2 text-left">Application Date</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions (Login Access)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($representatives as $representative)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-2">{{ $representative->name }}</td>
                            <td class="px-4 py-2">{{ $representative->email }}</td>
                            <td class="px-4 py-2">{{ $representative->companyRepresentative->contact_phone_number }}</td>
                            <td class="px-4 py-2">{{ $representative->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $representative->login_permission_category === 'Allowed' ? 'bg-green-100 text-green-800' :
                                       ($representative->login_permission_category === 'NotAllowed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $representative->login_permission_category }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                @if($representative->login_permission_category !== 'Allowed')
                                    <button wire:click="confirmUpdateLoginAccess({{ $representative->id }}, 'Allowed')" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-sm mr-1">
                                        Approve
                                    </button>
                                @endif
                                @if($representative->login_permission_category !== 'NotAllowed')
                                    <button wire:click="confirmUpdateLoginAccess({{ $representative->id }}, 'NotAllowed')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm mr-1">
                                        Reject
                                    </button>
                                @endif
                                <button wire:click="confirmDeleteRepresentative({{ $representative->id }})" class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-sm">
                                    Delete User
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $representatives->links() }}
        </div>
    @endif

    <div class="mt-8 text-center">
        <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Return to Home
        </a>
    </div>

    <!-- Confirmation Modal -->
    <x-popup wire:model="showConfirmModal">
        <div class="p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Confirmation</h3>
            <p class="mb-4 text-sm text-gray-600">{{ $message }}</p>
            <div class="flex justify-end space-x-3">
                <button wire:click="closeConfirmModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">Cancel</button>
                    <button wire:click="executeConfirmedAction" class="px-4 py-2 bg-blue-600 text-white rounded-md">Confirm</button>
            </div>
        </div>
    </x-popup>
</div>
