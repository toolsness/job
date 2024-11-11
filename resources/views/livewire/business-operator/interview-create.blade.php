<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Create New Interview</h2>

    <form wire:submit.prevent="createInterview">
        <div class="mb-4">
            <label for="company_id" class="block text-gray-700 text-sm font-bold mb-2">Company:</label>
            <select wire:model.live="company_id" id="company_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select a company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
            @error('company_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="vacancy_id" class="block text-gray-700 text-sm font-bold mb-2">Vacancy:</label>
            <select wire:model.live="vacancy_id" id="vacancy_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select a vacancy</option>
                @foreach($vacancies as $vacancy)
                    <option value="{{ $vacancy->id }}">{{ $vacancy->job_title }}</option>
                @endforeach
            </select>
            @error('vacancy_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="incharge_user_id" class="block text-gray-700 text-sm font-bold mb-2">In-charge User:</label>
            <select wire:model.live="incharge_user_id" id="incharge_user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select an in-charge Person for the Interview</option>
                @foreach($inchargeUsers as $user)
                    <option value="{{ $user['id'] }}">{{ $user['name'] }} ({{ $user['type'] }})</option>
                @endforeach
            </select>
            @error('incharge_user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="candidate_id" class="block text-gray-700 text-sm font-bold mb-2">Candidate:</label>
            <select wire:model.live="candidate_id" id="candidate_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select a candidate</option>
                @foreach($candidates as $candidate)
                    <option value="{{ $candidate->id }}">{{ $candidate->name }}</option>
                @endforeach
            </select>
            @error('candidate_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="implementation_date" class="block text-gray-700 text-sm font-bold mb-2">Interview Date:</label>
            <input wire:model="implementation_date" type="date" id="implementation_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('implementation_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="implementation_start_time" class="block text-gray-700 text-sm font-bold mb-2">Interview Time:</label>
            <input wire:model="implementation_start_time" type="time" id="implementation_start_time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('implementation_start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
            <select wire:model="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select a status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->name }}</option>
                @endforeach
            </select>
            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="zoom_link" class="block text-gray-700 text-sm font-bold mb-2">Zoom Link:</label>
            <input wire:model="zoom_link" type="url" id="zoom_link" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('zoom_link') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="memoContent" class="block text-gray-700 text-sm font-bold mb-2">Memo:</label>
            <textarea wire:model="memoContent" id="memoContent" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            @error('memoContent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create Interview
            </button>
            <a href="{{ route('business-operator.interviews.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>
