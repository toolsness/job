@props(['type' => 'info'])

@php
$bgColor = [
    'info' => 'bg-blue-100 border-blue-500 text-blue-700',
    'success' => 'bg-green-100 border-green-500 text-green-700',
    'error' => 'bg-red-100 border-red-500 text-red-700',
    'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
][$type];

$icon = [
    'info' => 'fa-info-circle',
    'success' => 'fa-check-circle',
    'error' => 'fa-times-circle',
    'warning' => 'fa-exclamation-circle',
][$type];
@endphp

<div x-data="{ show: false, message: '' }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-90"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-90"
     @show-notification.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
     class="fixed top-4 right-4 z-50">
    <div class="rounded-lg p-4 {{ $bgColor }} border shadow-lg max-w-xs w-full flex items-center">
        <div class="flex-shrink-0">
            <i class="fas {{ $icon }} fa-lg"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium" x-text="message"></p>
        </div>
        <button @click="show = false" class="ml-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 focus:ring-offset-2 focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
