@props(['id' => null, 'maxWidth' => null])

@php
$id = $id ?? md5($attributes->wire('model'));
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '4xl' => 'sm:max-w-4xl',
    '7xl' => 'sm:max-w-7xl',
][$maxWidth ?? '7xl'];
@endphp

<div
    x-data="{
        show: @entangle($attributes->wire('model')),
        aspectRatio: '16:9',
        isFullscreen: false,
        iframeSrc: null,
        toggleNav() {
            const nav = document.querySelector('.sticky-nav');
            if (nav) {
                nav.style.display = this.show ? 'none' : '';
            }
        }
    }"
    x-init="
        $watch('show', value => {
            if (value) {
                // Set iframe src and hide nav when opening
                $nextTick(() => {
                    iframeSrc = $wire.currentVRLink;
                });
                toggleNav();
            } else {
                // Clear iframe src and show nav when closing
                iframeSrc = null;
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                }
                toggleNav();
            }
        })
    "
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-[9999] overflow-y-auto"
    style="display: none;"
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
            @click="show = false"
        ></div>

        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            :class="{'fixed inset-0 p-0 m-0': isFullscreen}"
            class="relative w-full transform transition-all"
            x-cloak
        >
            <div class="relative bg-white rounded-lg shadow-xl" :class="{'h-screen': isFullscreen}">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-medium">VR Experience</h3>
                    <div class="flex items-center space-x-2">
                        <!-- Aspect Ratio Selector -->
                        <select x-model="aspectRatio" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="16:9">16:9</option>
                            <option value="4:3">4:3</option>
                            <option value="21:9">21:9</option>
                        </select>

                        <!-- Fullscreen Button -->
                        <button @click="isFullscreen = !isFullscreen" class="p-2 text-gray-400 hover:text-gray-500">
                            <svg x-show="!isFullscreen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0 0l-5-5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                            <svg x-show="isFullscreen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <!-- Close Button -->
                        <button @click="show = false" class="p-2 text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="relative bg-transparent" :class="{
                    'aspect-[16/9]': aspectRatio === '16:9' && !isFullscreen,
                    'aspect-[4/3]': aspectRatio === '4:3' && !isFullscreen,
                    'aspect-[21/9]': aspectRatio === '21:9' && !isFullscreen,
                    'h-[calc(100vh-4rem)]': isFullscreen
                }">
                    <template x-if="iframeSrc">
                        <iframe
                            :src="iframeSrc"
                            class="absolute inset-0 w-full h-full"
                            frameborder="0"
                            allowfullscreen="true"
                            allow="autoplay; fullscreen *; geolocation; microphone; camera; midi; monetization; xr-spatial-tracking; gamepad; gyroscope; accelerometer; xr; cross-origin-isolated; web-share"
                            allowtransparency="true"
                            webkitallowfullscreen="true"
                            mozallowfullscreen="true"
                            msallowfullscreen="true"
                            scrolling="no"
                        ></iframe>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
