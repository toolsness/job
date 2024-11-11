<!-- navigation menu -->
@auth('web')
    @if (Auth::user()->user_type === 'Student')
        @include('home.partials.home-menu-student')
    @elseif (Auth::user()->user_type === 'BusinessOperator')
        @include('home.partials.home-menu-admin')
    @elseif (Auth::user()->user_type === 'CompanyRepresentative')
        @include('home.partials.home-menu-company')
    @elseif (Auth::user()->user_type === 'CompanyAdmin')
        @include('home.partials.home-menu-company')
    @elseif (Auth::user()->user_type === 'Candidate')
        @include('home.partials.home-menu-student')
    @endif
@else
    @include('home.partials.home-menu-default')
@endauth
