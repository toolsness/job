<x-app-layout>
    @if (true)
        <meta http-equiv="refresh" content="0;url=/" />
    @endif
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div>
        <main class="flex flex-col items-center flex-grow mx-auto max-w-md md:max-w-lg py-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">User Login</h2>
            <form method="POST" action="{{ route('login') }}" class="w-full max-w-md">
                @csrf
                <div class="mb-6 grid grid-cols-[1fr_auto] gap-5 items-center">
                    <label class="block text-gray-700 font-bold mb-2" for="email">
                        Email Address
                    </label>
                    <div>
                        <input
                            class="appearance-none border w-[300px] rounded flex-1 py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            name="email" type="email" placeholder="Enter your email address" />
                        {{-- <x-input-error :messages="$errors->get('email')" class="mt-2"/> --}}
                    </div>
                </div>
                <div class="mb-8 grid grid-cols-[1fr_auto] gap-5 items-center">
                    <label class="block text-gray-700 font-bold mb-2" for="password">
                        Password
                    </label>
                    <div>

                        <input
                            class="appearance-none border rounded w-[300px] py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            name="password" type="password" placeholder="Enter your password" />
                        {{-- <x-input-error :messages="$errors->get('password')" class="mt-2"/> --}}
                    </div>
                </div>
                <div class="flex flex-col justify-between items-center gap-4">
                    <button
                        class=" bg-white shadow-md border border-black text-black font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Login
                    </button>
                    <a href="{{ route('home') }}"
                        class=" bg-white shadow-md border border-black text-black font-bold py-3 px-6 rounded transition duration-300">
                        Back to Home
                    </a>
                </div>
            </form>
        </main>
    </div>

    {{--    <form method="POST" action="{{ route('login') }}"> --}}
    {{--        @csrf --}}

    {{--        <!-- Email Address --> --}}
    {{--        <div> --}}
    {{--            <x-input-label for="email" :value="__('Email')" /> --}}
    {{--            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" /> --}}
    {{--            <x-input-error :messages="$errors->get('email')" class="mt-2" /> --}}
    {{--        </div> --}}

    {{--        <!-- Password --> --}}
    {{--        <div class="mt-4"> --}}
    {{--            <x-input-label for="password" :value="__('Password')" /> --}}

    {{--            <x-text-input id="password" class="block mt-1 w-full" --}}
    {{--                            type="password" --}}
    {{--                            name="password" --}}
    {{--                            required autocomplete="current-password" /> --}}

    {{--            <x-input-error :messages="$errors->get('password')" class="mt-2" /> --}}
    {{--        </div> --}}

    {{--        <!-- Remember Me --> --}}
    {{--        <div class="block mt-4"> --}}
    {{--            <label for="remember_me" class="inline-flex items-center"> --}}
    {{--                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember"> --}}
    {{--                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span> --}}
    {{--            </label> --}}
    {{--        </div> --}}

    {{--        <div class="flex items-center justify-end mt-4"> --}}
    {{--            @if (Route::has('password.request')) --}}
    {{--                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}"> --}}
    {{--                    {{ __('Forgot your password?') }} --}}
    {{--                </a> --}}
    {{--            @endif --}}

    {{--            <x-primary-button class="ms-3"> --}}
    {{--                {{ __('Log in') }} --}}
    {{--            </x-primary-button> --}}
    {{--        </div> --}}
    {{--    </form> --}}
</x-app-layout>
