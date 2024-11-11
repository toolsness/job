<div>
    <main class="flex flex-col items-center flex-grow max-w-md py-12 mx-auto md:max-w-lg">
        <h2 class="mb-8 text-2xl font-bold text-gray-800">Reset Password</h2>
        <form wire:submit.prevent="resetPassword" class="w-full max-w-md">
            <input type="hidden" wire:model="token">
            <div class="mb-6">
                <label class="block mb-2 font-bold text-gray-700" for="email">
                    Email Address
                </label>
                <input
                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                    wire:model.lazy="email" type="email" required autofocus />
                @error('email')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block mb-2 font-bold text-gray-700" for="password">
                    New Password
                </label>
                <input
                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                    wire:model.lazy="password" type="password" required />
                @error('password')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block mb-2 font-bold text-gray-700" for="password_confirmation">
                    Confirm New Password
                </label>
                <input
                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                    wire:model.lazy="password_confirmation" type="password" required />
            </div>
            <div class="flex justify-center space-x-4">
                <button
                    wire:loading.remove wire:target="resetPassword"
                    class="px-6 py-3 font-bold text-black bg-white border border-black rounded shadow-md focus:outline-none focus:shadow-outline"
                    type="submit">
                    Reset Password
                </button>
                <div wire:loading wire:target="resetPassword"
                    class="inline-flex items-center justify-center">
                    <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>
                        Resetting...</span></span>
                </div>
            </div>
        </form>
    </main>
</div>
