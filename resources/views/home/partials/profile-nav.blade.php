<div class="justify-self-end">
    <div class="flex items-center gap-2"">
        <div>
            @if (Auth::user()->user_type === 'CompanyRepresentative')
                <img src="{{ Auth::user()->CompanyRepresentative->Company->image ? Storage::url(Auth::user()->CompanyRepresentative->Company->image) : asset('placeholder.png') }}"
                    alt="Profile Picture" class="bg-gray-100 rounded-full w-[40px] h-[40px]">
            @elseif (Auth::user()->user_type === 'CompanyAdmin')
                <img src="{{ Auth::user()->CompanyAdmin->Company->image ? Storage::url(Auth::user()->CompanyAdmin->Company->image) : asset('placeholder.png') }}"
                    alt="Profile Picture" class="bg-gray-100 rounded-full w-[40px] h-[40px]">
            @else
                <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : asset('placeholder.png') }}"
                    alt="Profile Picture" class="bg-gray-100 rounded-full w-[40px] h-[40px]">
            @endif
        </div>
        <div class="flex flex-col">
            @if (Auth::user()->user_type === 'Student')
                @if (Auth::user()->student)
                    <span class="text-sm font-semibold">{{ Auth::user()->name }}</span>
                @endif
            @elseif (Auth::user()->user_type === 'CompanyRepresentative')
                @if (Auth::user()->companyRepresentative)
                    <span class="text-sm font-semibold">{{ Auth::user()->name }}</span>
                    <span class="text-xs text-gray-900">Representative</span>
                    @if (Auth::user()->companyRepresentative->company)
                        <span class="text-xs text-gray-600">{{ Auth::user()->companyRepresentative->company->name }}</span>
                    @endif
                @endif
            @elseif (Auth::user()->user_type === 'CompanyAdmin')
                @if (Auth::user()->companyAdmin)
                    <span class="text-sm font-semibold">{{ Auth::user()->name }}</span>
                    <span class="text-xs text-gray-900">Admin</span>
                    @if (Auth::user()->companyAdmin->company)
                        <span class="text-xs text-gray-600">{{ Auth::user()->companyAdmin->company->name }}</span>
                    @endif
                @endif
            @elseif (Auth::user()->user_type === 'BusinessOperator')
                @if (Auth::user()->businessOperator)
                    <span class="text-sm font-semibold">{{ Auth::user()->name }}</span>
                    <span class="text-xs text-gray-900">Business Operator</span>
                @endif
            @elseif (Auth::user()->user_type === 'Candidate')
                @if (Auth::user()->student)
                    <span class="text-sm font-semibold">{{ Auth::user()->name }}</span>
                @endif
            @endif
        </div>
    </div>
</div>
