<div x-data="{ formReset: false }" @form-reset.window="formReset = true; $nextTick(() => { formReset = false; })">

    <x-view-type-redirect
    currentViewType="student"
    studentRoute="{{ route('student.new-member-registration') }}"
    companyRoute="{{ route('company.new-member-registration') }}"
/>
    @include('home.partials.home-status', ['viewType' => 'student'])
    <main class="flex flex-col items-center justify-center">
        <section class="flex flex-col items-center w-full max-w-md px-10 py-10 mt-10 bg-white border-2 border-black rounded-lg md:px-4 md:py-6 md:w-full">
            <h2 class="px-4 py-4 mb-4 text-lg font-bold text-white bg-orange-600 rounded-md shadow-md">New Student Registration</h2>
            <form wire:submit.prevent="sendVerificationEmail" class="w-full">
                <div class="mb-4">
                    <label for="email" class="block mt-4 text-sm text-black">
                        Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        wire:model.lazy="email"
                        x-bind:value="formReset ? '' : $wire.email"
                        class="block w-full px-4 py-2 mt-1 bg-white border border-black rounded-md focus:outline-none focus:ring focus:border-blue-300"
                    />
                    @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="confirm-email" class="block mt-4 text-sm text-black">
                        Confirm Email Address
                    </label>
                    <input
                        type="email"
                        id="confirm-email"
                        wire:model.lazy="confirmEmail"
                        x-bind:value="formReset ? '' : $wire.confirmEmail"
                        class="block w-full px-4 py-2 mt-1 bg-white border border-black rounded-md focus:outline-none focus:ring focus:border-blue-300"
                    />
                    @error('confirmEmail') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <p class="mt-4 text-sm text-gray-600">
                    A registration email will be sent to the provided email address.<br />
                    Please click the URL in the email to complete the registration process.
                </p>
                <div class="flex justify-center space-x-4">
                    <button
                        wire:loading.remove wire:target="sendVerificationEmail"
                        type="submit"
                        class="inline-block px-6 py-3 mt-6 text-sm font-semibold text-black transition-colors duration-300 border-2 border-black rounded-md"
                    >
                    Registration
                    </button>
                    <div wire:loading wire:target="sendVerificationEmail"
                                    class="inline-block px-6 py-3 mt-6 text-sm font-semibold">
                                    <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>
                                            Sending Verification Mail...</span></span>
                    </div>
                    <a
                        href="{{ route('student.login') }}"
                        class="inline-block px-6 py-3 mt-6 text-sm font-semibold text-black transition-colors duration-300 border-2 border-black rounded-md"
                    >
                    Login Page
                    </a>
                </div>
            </form>
        </section>
        <button
            onclick="window.location='{{ url('/') }}'"
            class="inline-block px-6 py-3 mt-6 text-sm font-semibold text-black transition-colors duration-300 border-2 border-black rounded-md"
        >
            Return to TOP page
        </button>
    </main>
</div>
