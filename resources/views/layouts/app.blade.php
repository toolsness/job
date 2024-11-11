<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Toastr Toast -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }

        .fl-wrapper {
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            z-index: 9999 !important;
            width: 40% !important;
            max-width: 400px !important;
            pointer-events: none !important;
        }

        .fl-wrapper>* {
            pointer-events: auto !important;
        }

        .fl-wrapper .alert {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .sticky-nav {
            position: sticky;
            top: 0;
            background-color: white;
            width: 100%;
            transition: all 0.3s;
            z-index: 999;
        }

        body.modal-open::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 40;
        }

        .popup-content {
            z-index: 50;
        }

        .sticky-nav.scrolling {
            z-index: 999;
        }

        .profile-nav {
            transition: transform 0.3s ease-in-out;
        }

        .profile-nav.hidden {
            transform: translateY(-100%);
        }

        body {
            padding-top: 0;
        }

        body.modal-open {
            overflow: hidden;
        }

        #flash-message-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            pointer-events: none;
        }

        #flash-message-container>* {
            pointer-events: auto;
        }
    </style>
    @livewireStyles
</head>

<body class="font-sans antialiased flex flex-col min-h-screen" x-data="navigationControl">
    @include('flash::message')

    <div class="bg-white flex flex-col min-h-screen">
        <header
            class="flex flex-col md:flex-row justify-between items-center py-4 px-4 bg-cover bg-center @guest shadow-md shadow-black/40 @endguest"
            style="background-image: url('https://cdn.builder.io/api/v1/image/assets/TEMP/e6cb9a905fd3d194c8116c7b798dd14b715865b55980a6125aaba4212c44cc03?apiKey=bc17dd780d42423db91092514bd68f87&');">
            <div class="bg-white bg-opacity-80 rounded-lg p-4 mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-blue-700 hover:text-blue-600">
                        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/8c486ba04494e022a0829a4c66116c483090d16e1baeeb8483c4ad804e52cd30?apiKey=bc17dd780d42423db91092514bd68f87&"
                            alt="Company logo" class="h-10 mr-4" />
                    </a>
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-800">Metaverse Employment Support</h1>
                        <p class="text-sm md:text-base text-gray-600">We support employment in Japan with specific
                            skills</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sticky navigation menu wrapper -->
        <div class="sticky-nav shadow-md" id="stickyNav">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @auth('web')
                    @if (Auth::user()->user_type === 'Student')
                        @include('home.partials.student-nav')
                    @elseif (Auth::user()->user_type === 'BusinessOperator')
                        @include('home.partials.admin-nav')
                    @elseif (Auth::user()->user_type === 'CompanyRepresentative')
                        @include('home.partials.company-nav')
                    @elseif (Auth::user()->user_type === 'CompanyAdmin')
                        @include('home.partials.company-nav')
                    @elseif (Auth::user()->user_type === 'Candidate')
                        @include('home.partials.student-nav')
                    @endif
                @endauth
            </div>
        </div>

        <div style="position: relative; z-index: 1;">
            <!-- Profile navigation (right aligned) -->
            <div class="profile-nav" id="profileNav">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
                    <div class="flex justify-end">
                        @auth('web')
                            {{-- @include('home.partials.profile-nav') --}}
                            <livewire:home.partials.profile-nav />
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-grow" id="pageContent">
                {{ $slot }}
            </main>

        </div>
    </div>
    <footer class="mt-auto w-full bg-zinc-300 min-h-[129px] max-md:flex-wrap max-md:mt-10 max-md:max-w-full">

    </footer>

    @livewireScripts
    @stack('scripts')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navigationControl', () => ({
                showModal: false,
                scrollPosition: 0,
                init() {
                    const stickyNav = document.querySelector('.sticky-nav');
                    const pageContent = document.getElementById('pageContent');

                    this.$watch('showModal', value => {
                        if (stickyNav) {
                            if (value) {
                                stickyNav.classList.add('popup-active');
                                this.scrollPosition = window.pageYOffset;
                                pageContent.scrollIntoView({
                                    behavior: 'smooth'
                                });
                            } else {
                                stickyNav.classList.remove('popup-active');
                                setTimeout(() => {
                                    window.scrollTo({
                                        top: this.scrollPosition,
                                        behavior: 'smooth'
                                    });
                                }, 0);
                            }
                        }

                        if (value) {
                            // Modal is opening
                            document.body.classList.add('modal-open');
                        } else {
                            // Modal is closing
                            document.body.classList.remove('modal-open');
                        }
                    });

                    Livewire.on('showModal', value => {
                        this.showModal = value;
                    });
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const stickyNav = document.querySelector('.sticky-nav');
            const profileNav = document.getElementById('profileNav');
            let isScrolling;
            let lastScrollTop = 0;

            // Prevent scrolling to top when clicking nav items
            const navLinks = document.querySelectorAll('.sticky-nav nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const href = this.getAttribute('href');
                    if (href && href.startsWith('#')) {
                        const targetElement = document.querySelector(href);
                        if (targetElement) {
                            targetElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    } else {
                        window.location.href = href;
                    }
                });
            });

            // Handle scrolling
            window.addEventListener('scroll', () => {
                if (document.body.classList.contains('modal-open')) {
                    return; // Don't handle scroll events when modal is open
                }

                let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                // Add high z-index when scrolling
                stickyNav.classList.add('scrolling');
                stickyNav.style.zIndex = '999'; // Changed from 1000 to 999

                // Clear the timeout
                window.clearTimeout(isScrolling);

                // Set a timeout to run after scrolling ends
                isScrolling = setTimeout(() => {
                    // Keep high z-index even when scrolling stops
                    stickyNav.classList.remove('scrolling');
                    stickyNav.style.zIndex = '999'; // Changed from 1000 to 999
                }, 100);
            }, false);
        });
        document.addEventListener('livewire:load', function() {
            Livewire.on('popup-opened', function() {
                document.getElementById('pageContent').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
        // Ensure flash messages are always on top
        document.addEventListener('DOMContentLoaded', function() {
            const flWrapper = document.querySelector('.fl-wrapper');
            if (flWrapper) {
                document.body.appendChild(flWrapper);
            }
        });
    </script>
</body>

</html>
