<div>
    <x-view-type-redirect
    currentViewType="company"
    studentRoute="{{ route('student.login') }}"
    companyRoute="{{ route('company.login') }}"
/>
@include('home.partials.home-status', ['viewType' => 'company'])
    <main class="flex flex-col items-center flex-grow max-w-md py-12 mx-auto md:max-w-lg">
        <h2 class="mb-8 text-2xl font-bold text-gray-800">Company Login</h2>
        <form wire:submit.prevent="login" class="w-full max-w-md">
            @if ($errorMessage)
                <div class="p-4 mb-4 text-red-700 bg-red-100 rounded">
                    {{ $errorMessage }}
                </div>
            @endif
            <div class="mb-6">
                <label class="block mb-2 font-bold text-gray-700" for="email">
                    Email Address
                </label>
                <div class="flex flex-col">
                    <input
                        class="appearance-none border w-full rounded flex-1 py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        wire:model.lazy="email" type="email" id="email" placeholder="Enter your email address" />
                    @error('email')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mb-8">
                <label class="block mb-2 font-bold text-gray-700" for="password">
                    Password
                </label>
                <div class="relative flex flex-col" x-data="{ showPassword: false }">
                    <input
                        class="appearance-none border rounded w-full py-3 px-4 pr-10 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        wire:model="password" x-bind:type="showPassword ? 'text' : 'password'" id="password"
                        placeholder="Enter your password" />
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 px-3 py-2">
                        <i class="fas" :class="{ 'fa-eye': !showPassword, 'fa-eye-slash': showPassword }"></i>
                    </button>
                    @error('password')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="items-center mb-6 text-center">
                <p class="text-gray-700">If you are not yet a member, <a
                        class="text-blue-500 hover:underline hover:uppercase"
                        title="New Company Representative Registration"
                        href="{{ route('company.new-member-registration') }}">click here.</a></p>
            </div>
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input type="checkbox" wire:model="remember" id="remember" class="mr-2"
                        title="Stay signed in for 2 months">
                    <label for="remember" class="text-gray-700" title="Stay signed in for 2 months">Stay signed
                        in</label>
                </div>
                <div>
                    <a href="{{ route('company.password.request') }}" class="text-blue-500 hover:underline hover:uppercase"
                    style="display: inline-block !important;">Forgot Password?</a>
                </div>
            </div>
            <div class="flex justify-center space-x-4">
                <button wire:loading.remove wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="px-6 py-3 font-bold text-black bg-white border border-black rounded shadow-md focus:outline-none focus:shadow-outline"
                    type="submit">
                    Login
                </button>
                <div wire:loading wire:target="login" class="inline-flex items-center justify-center">
                    <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>
                            Logging in...</span></span>
                </div>
            </div>
            <div class="flex justify-center mt-12">
                <a href="{{ route('home') }}"
                    class="px-6 py-3 font-bold text-black transition duration-300 bg-white border border-black rounded shadow-md">
                    Back to Home
                </a>
            </div>
        </form>
    </main>
</div>
