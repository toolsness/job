<div x-data="{ isOpen: false }">
    <!-- Admin Navigation Menu -->
    <nav class="w-full">
        <div class="w-full mx-auto">
            <button @click="isOpen = !isOpen" type="button"
                class="inline-flex items-center justify-center w-10 h-10 p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                aria-controls="navbar-multi-level" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="flex flex-col p-2 bg-white md:flex-row md:items-center" id="mainNav">
                <div class="w-full py-2 md:flex md:justify-center" :class="{ 'hidden': !isOpen }"
                    id="navbar-multi-level">
                    <ul x-cloak
                        class="flex flex-col items-center justify-center p-4 mt-4 space-y-4 font-medium border rounded-lg md:flex-row md:space-y-0 md:space-x-0 md:p-0 bg-gray-50 md:mt-0 md:border-0 md:bg-white">

                        <li class="hidden w-px h-6 mx-4 bg-black md:block"></li>

                        <li class="relative px-4 py-2 group md:py-0 md:border-r md:border-black"
                            x-data="{ open: false }">
                            <a href="#" @click="open = !open"
                                class="flex items-center justify-between text-sm text-gray-900 hover:text-blue-700">
                                Main menu <span x-show="!open">
                                    @livewire('unread-message-count')
                                </span>
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </a>
                            <ul x-show="open" @click.away="open = false"
                                class="absolute left-0 z-10 mt-2 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <li><a href="/#notice-news" class="block px-4 py-2 hover:bg-gray-100">Notice/News</a>
                                </li>
                                <li><a href="{{ route('messages') }}"
                                        class="block px-4 py-2 hover:bg-gray-100">Message @livewire('unread-message-count')</a></li>
                            </ul>
                        </li>

                        <li class="relative px-4 py-2 group md:py-0 md:border-r md:border-black"
                            x-data="{ open: false }">
                            <a href="#" @click="open = !open"
                                class="flex items-center justify-between text-sm text-gray-900 hover:text-blue-700">
                                Japanese Learning
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </a>
                            <ul x-show="open" @click.away="open = false"
                                class="absolute left-0 z-10 mt-2 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <li><a href="https://ccr-edu.com/" class="block px-4 py-2 hover:bg-gray-100">Japanese learning</a>
                                </li>
                            </ul>
                        </li>

                        @if (Auth::user()->student->candidate)
                        <li class="relative px-4 py-2 group md:py-0 md:border-r md:border-black"
                            x-data="{ open: false }">
                            <a href="#" @click="open = !open"
                                class="flex items-center justify-between text-sm text-gray-900 hover:text-blue-700">
                                Recruiting Companies
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </a>
                            <ul x-show="open" @click.away="open = false"
                                class="absolute left-0 z-10 mt-2 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <li><a href="{{ route('job-list.search') }}" class="block px-4 py-2 hover:bg-gray-100">Recruiting Company
                                        Search</a></li>
                                <li><a href="{{ route('candidate.vacancies.favorite-list') }}" class="block px-4 py-2 hover:bg-gray-100">List of Favorite
                                        Vacancies</a></li>
                            </ul>
                        </li>
                        @endif

                        @if (Auth::user()->student->candidate)
                        <li class="relative px-4 py-2 group md:py-0 md:border-r md:border-black"
                            x-data="{ open: false }">
                            <a href="#" @click="open = !open"
                                class="flex items-center justify-between text-sm text-gray-900 hover:text-blue-700">
                                Interview Preparation Study
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </a>
                            <ul x-show="open" @click.away="open = false"
                                class="absolute left-0 z-10 mt-2 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <li><a href="{{route('interview-preparation-study-plan')}}" class="block px-4 py-2 hover:bg-gray-100">Interview preparation
                                        study plan</a></li>
                                <li><a href="{{ route('interview-preparation-study-orientation') }}" class="block px-4 py-2 hover:bg-gray-100">Orientation</a></li>
                                <li><a href="{{ route('cv.creation.profile') }}" class="block px-4 py-2 hover:bg-gray-100">CV Creation</a></li>
                                <li><a href="{{ route('cv.creation.self-promotion') }}" class="block px-4 py-2 hover:bg-gray-100">Create Self Introduction for CV</a></li>
                                <li><a href="{{ route('interview-answer.writing') }}" class="block px-4 py-2 hover:bg-gray-100">Interview Answer Writing</a></li>
                                <li><a href="{{ route('interview-practice.voice') }}" class="block px-4 py-2 hover:bg-gray-100">Practice Interview Answer</a></li>
                                {{-- <li><a href="{{ route('interview-answer.practice') }}" class="block px-4 py-2 hover:bg-gray-100">Interview Practice</a></li> --}}
                                <li><a href="{{ route('interview-answer.evaluation') }}" class="block px-4 py-2 hover:bg-gray-100">Results</a></li>
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Mock interview</a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <li class="relative px-4 py-2 group md:py-0 md:border-r md:border-black"
                            x-data="{ open: false }">
                            <a href="#" @click="open = !open"
                                class="flex items-center justify-between text-sm text-gray-900 hover:text-blue-700">
                                Interview Test
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </a>
                            <ul x-show="open" @click.away="open = false"
                                class="absolute left-0 z-10 mt-2 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <li><a href="{{ route('job-interviews') }}"
                                        class="block px-4 py-2 hover:bg-gray-100">List of Interview
                                        Status</a></li>
                            </ul>
                        </li>

                        {{-- <li class="relative px-4 py-2 group md:py-0 md:border-r md:border-black"
                            x-data="{ open: false }">
                            <a href="#" @click="open = !open"
                                class="flex items-center justify-between text-sm text-gray-900 hover:text-blue-700">
                                Employment Contract
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </a>
                            <ul x-show="open" @click.away="open = false"
                                class="absolute left-0 z-10 mt-2 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Employment procedure
                                        application</a></li>
                            </ul>
                        </li> --}}

                        <li class="relative px-4 py-2 group md:py-0" x-data="{ open: false }">
                            <a href="#" @click="open = !open"
                                class="flex items-center justify-between text-sm text-gray-900 hover:text-blue-700">
                                Setting
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </a>
                            <ul x-show="open" @click.away="open = false"
                                class="absolute left-0 z-10 mt-2 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <li><a href="{{ route('student.profile.edit') }}"
                                        class="block px-4 py-2 hover:bg-gray-100">Edit Profile</a>
                                </li>
                                <li><a href="{{ route('student.candidate-details') }}" class="block px-4 py-2 hover:bg-gray-100">My Profile (Edit CV)</a>
                                </li>
                                {{-- @auth
                                    @if (Auth::user()->user_type === 'CompanyAdmin')
                                        <li>
                                            <a href="{{ route('company.approve-representatives') }}"
                                                class="block px-4 py-2 hover:bg-gray-100">
                                                Pending Account
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Other authenticated user menu items -->
                                @endauth --}}
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full px-4 py-2 text-left hover:bg-gray-100">Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                        <li class="hidden w-px h-6 mx-4 bg-black md:block"></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    {{-- <div class="flex items-center justify-between p-5 mx-auto max-w-7xl">
        <div></div>
        @include('home.partials.profile-nav')
    </div> --}}
</div>
