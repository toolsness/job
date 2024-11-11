<div>
    <main class="flex flex-col items-center flex-grow max-w-md py-12 mx-auto md:max-w-lg">
        <h2 class="mb-8 text-2xl font-bold text-gray-800">Reset Password</h2>
        <form wire:submit.prevent="sendResetLink" class="w-full max-w-md">
            @if (session('status'))
                <div class="p-4 mb-4 text-green-700 bg-green-100 rounded">
                    {{ session('status') }}
                </div>
            @endif
            <div class="mb-6">
                <label class="block mb-2 font-bold text-gray-700" for="email">
                    Email Address
                </label>
                <input
                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                    wire:model.lazy="email" type="email" placeholder="Enter your email address" required autofocus />
                @error('email')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-center space-x-4">
                <button
                    wire:loading.remove wire:target="sendResetLink"
                    class="px-6 py-3 font-bold text-black bg-white border border-black rounded shadow-md focus:outline-none focus:shadow-outline"
                    type="submit">
                    Send Password Reset Link
                </button>
                <div wire:loading wire:target="sendResetLink"
                    class="inline-flex items-center justify-center">
                    <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>
                        Sending...</span></span>
                </div>
            </div>
        </form>
        <div class="flex justify-center mt-12">
            <a href="{{ route('business-operator.login') }}"
                class="px-6 py-3 font-bold text-black transition duration-300 bg-white border border-black rounded shadow-md">
                Back to Login
            </a>
        </div>
    </main>
</div>
