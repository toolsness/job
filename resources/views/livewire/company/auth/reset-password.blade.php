<div class="mt-18 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Reset Password
            </h2>
        </div>
        <form wire:submit.prevent="resetPassword" class="mt-8 space-y-6">
            <input type="hidden" name="remember" value="true">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input wire:model="email" id="email" name="email" type="email" autocomplete="email" required class="text-center appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address" readonly>
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input wire:model.lazy="password" id="password" name="password" type="password" autocomplete="new-password" required class="text-center appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only">Confirm Password</label>
                    <input wire:model.lazy="password_confirmation" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="text-center appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
                </div>
            </div>

            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <div class="text-center">
                <button type="submit" class="group relative w-40 justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gray-500 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Reset Password
                </button>
            </div>
        </form>
        <div class="mt-4 text-center mb-4">
            <a href="{{ route('home') }}" class="items-center justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-gray-300">
                Return to TOP
            </a>
        </div>
    </div>
</div>
