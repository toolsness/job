<div>
    <main class="flex items-center justify-center px-4 py-8 bg-white">
        <section class="w-full max-w-2xl">
            <h1 class="mb-8 text-xl font-semibold text-center text-black">Student Registration</h1>
            <form wire:submit.prevent="checkErrorsAndSubmit" class="space-y-6">
                @foreach (['username', 'name', 'nameJapanese', 'email', 'contactPhoneNumber'] as $field)
                    <div class="flex flex-col sm:flex-row">
                        <label for="{{ $field }}"
                            class="mb-2 text-sm text-right sm:mb-0 sm:w-1/3 sm:pr-4 sm:pt-2">{{ $this->getLabel($field) }}</label>
                        <div class="flex-grow sm:w-2/3">
                            <input id="{{ $field }}" type="{{ $field === 'email' ? 'email' : 'text' }}"
                                wire:model.lazy="{{ $field }}"
                                class="w-full p-2 border border-gray-300 rounded {{ in_array($field, ['username', 'email']) ? 'bg-gray-100' : '' }}"
                                aria-label="{{ $this->getLabel($field) }}"
                                {{ in_array($field, ['username', 'email']) ? 'readonly' : '' }}
                                placeholder="{{ $this->getPlaceholder($field) }}">
                            @error($field)
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endforeach

                @foreach (['password', 'password_confirmation'] as $field)
                    <div class="flex flex-col sm:flex-row" x-data="{ show{{ ucfirst($field) }}: false }">
                        <label for="{{ $field }}"
                            class="mb-2 text-sm text-right sm:mb-0 sm:w-1/3 sm:pr-4 sm:pt-2">{{ $this->getLabel($field) }}</label>
                        <div class="flex-grow sm:w-2/3">
                            <div class="relative">
                                <input id="{{ $field }}"
                                    x-bind:type="show{{ ucfirst($field) }} ? 'text' : 'password'"
                                    wire:model.lazy="{{ $field }}"
                                    class="w-full p-2 pr-10 border border-gray-300 rounded"
                                    aria-label="{{ $this->getLabel($field) }}">
                                <button type="button" @click="show{{ ucfirst($field) }} = !show{{ ucfirst($field) }}"
                                    class="absolute inset-y-0 right-0 px-3 py-2">
                                    <i class="fas"
                                        :class="{ 'fa-eye': !
                                            show{{ ucfirst($field) }}, 'fa-eye-slash': show{{ ucfirst($field) }} }"></i>
                                </button>
                            </div>
                            @error($field)
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endforeach

                <div class="flex flex-col items-center mt-8">
                    <button type="button" class="px-4 py-2 text-sm border border-gray-300 rounded">Terms of
                        Use</button>
                    <div class="flex items-center mt-4">
                        <input type="checkbox" id="agreeTerms" wire:model.lazy="agreeTerms" class="mr-2">
                        <label for="agreeTerms" class="text-sm">I agree to the Terms of Use.</label>
                    </div>
                    @error('agreeTerms')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror

                    <div class="flex flex-col justify-between w-full mt-8 sm:flex-row">
                        <button wire:loading.remove wire:target="checkErrorsAndSubmit" type="submit"
                            class="w-full px-6 py-2 mb-4 text-sm border border-gray-300 rounded sm:w-auto sm:mb-0">
                            Member Registration
                        </button>
                        <div wire:loading wire:target="checkErrorsAndSubmit"
                            class="inline-flex items-center justify-center">
                            <span class="font-bold text-blue-700"><i class="fa fa-spinner fa-spin"></i> <span>
                                    Registering...</span></span>
                        </div>
                        <button type="button"
                            onclick="window.location='{{ route('student.new-member-registration') }}'"
                            class="w-full px-6 py-2 text-sm border border-gray-300 rounded sm:w-auto">Cancel</button>
                    </div>
                </div>
            </form>
            <div class="flex justify-center mt-12">
                <a href="{{ url('/') }}" class="px-6 py-2 text-sm border border-gray-300 rounded">Return to
                    TOP</a>
            </div>
        </section>
    </main>
</div>
