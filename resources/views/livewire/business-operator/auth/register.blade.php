<div>
    <main class="flex items-center justify-center px-4 py-8 bg-white">
        <section class="w-full max-w-2xl">
            <h1 class="mb-8 text-xl font-semibold text-center text-black">Business Operator Registration</h1>
            <form wire:submit.prevent="register" class="space-y-6">
                @foreach (['username', 'name', 'nameKanji', 'nameKatakana', 'email', 'contactPhoneNumber', 'password', 'password_confirmation'] as $field)
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <label for="{{ $field }}" class="mb-2 mr-4 text-sm text-right sm:w-1/3 sm:mb-0">{{ ucfirst($field) }}</label>
                        <div class="flex-grow">
                            @if (in_array($field, ['password', 'password_confirmation']))
                                <input id="{{ $field }}" type="password" wire:model.lazy="{{ $field }}"
                                    class="w-full p-2 border border-gray-300 rounded" aria-label="{{ ucfirst($field) }}">
                            @else
                                <input id="{{ $field }}" type="text" wire:model.lazy="{{ $field }}"
                                    class="w-full p-2 border border-gray-300 rounded" aria-label="{{ ucfirst($field) }}">
                            @endif
                            @error($field)
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endforeach
                <div class="flex flex-col items-center mt-8">
                    <button type="button" class="px-4 py-2 text-sm border border-gray-300 rounded">Terms of Use</button>
                    <div class="flex items-center mt-4">
                        <input type="checkbox" id="agreeTerms" wire:model="agreeTerms" class="mr-2">
                        <label for="agreeTerms" class="text-sm">I agree to the Terms of Use.</label>
                    </div>
                    @error('agreeTerms')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-center space-x-4 mt-8">
                    <button wire:loading.remove wire:target="register" wire:loading.attr="disabled"
                        class="px-6 py-3 font-bold text-black bg-white border border-black rounded shadow-md focus:outline-none focus:shadow-outline"
                        type="submit">
                        Register
                    </button>
                    <div wire:loading wire:target="register" class="inline-flex items-center justify-center">
                        <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>
                                Registering...</span></span>
                    </div>
                </div>
            </form>
            <div class="flex justify-center mt-12">
                <a href="{{ route('home') }}"
                    class="px-6 py-3 font-bold text-black transition duration-300 bg-white border border-black rounded shadow-md">
                    Back to Home
                </a>
            </div>
        </section>
    </main>
</div>
