<div>
    <x-view-type-redirect
    currentViewType="student"
    studentRoute="{{ route('student.password.request') }}"
    companyRoute="{{ route('company.password.request') }}"
/>
    @include('home.partials.home-status', ['viewType' => 'student'])

    <div class="pt-18 flex items-center justify-center bg-white px-4 sm:px-6 lg:px-8">

        <div class="max-w-md w-full">
            <div>
                <h2 class="mt-6 text-center text-xl font-bold text-gray-900">
                    Forgot Password
                </h2>
            </div>
            <form wire:submit.prevent="sendResetLink" class="mt-8 space-y-4">
                <div class="w-full grid grid-cols-12 gap-2">
                    <label for="email" class="text-sm font-medium text-gray-700 pt-2 col-span-4">E-mail address</label>
                    <div class="col-span-8">
                        <input wire:model.lazy="email" id="email" name="email" type="email" autocomplete="email" required class="appearance-none w-full px-3 border border-gray-300 rounded-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-300 sm:text-sm" placeholder="Email address">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <p class="text-sm text-gray-500 mt-6 pb-3">A password reset invitation will be sent to your registered e-mail address.<br>
                            Please reset your password from the contents of the e-mail.</p>
                    </div>
                </div>

                <div class="mt-4 text-center pb-6">
                    <button type="submit" class="w-40 items-center justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-gray-300">
                        Send
                    </button>
                </div>
                <div class="mt-4 text-center mb-4">
                    <a href="{{ route('home') }}" class="items-center justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-gray-300">
                        Return to TOP
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
