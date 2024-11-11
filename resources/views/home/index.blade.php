<x-app-layout>
    @guest
        @include('home.partials.home-status', ['viewType' => $viewType])
    @endguest
    <div class="flex flex-col items-center gap-8 py-12">
        <main class="flex flex-col items-center w-full max-w-4xl gap-8 px-4 text-center">
            @guest
                @include('home.partials.login-section', ['viewType' => $viewType])
            @endguest

            {{-- if auth usertype is student or candidate will shpow this --}}
            @auth('web')
                @if (Auth::user()->user_type === 'Student' || Auth::user()->user_type === 'Candidate')
                    @livewire('student.dashboard')
                @endif
            @endauth

            @livewire('common.notice-news')

            @include('home.partials.home-menu')
        </main>
    </div>
</x-app-layout>
