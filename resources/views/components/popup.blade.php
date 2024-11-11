@props(['id' => null, 'maxWidth' => null])

@php
$id = $id ?? md5($attributes->wire('model'));
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth ?? '2xl'];
@endphp

<style>
    #stickyNav {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    #stickyNav.hiding {
        opacity: 0;
        transform: translateY(-100%);
    }
    .popup-overlay {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
</style>

<div
    x-data="{
        show: @entangle($attributes->wire('model')).live,
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)].filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1 },
        scrollPosition: 0
    }"
    x-init="
        $watch('show', value => {
            const stickyNav = document.getElementById('stickyNav');
            if (value) {
                scrollPosition = window.pageYOffset;
                document.cookie = `scrollPosition=${scrollPosition}`;
                document.body.style.overflow = 'hidden';
                document.body.style.height = '100vh';
                document.body.style.position = 'fixed';
                document.body.style.width = '100%';
                document.getElementById('pageContent').scrollIntoView({ behavior: 'smooth', block: 'start' });
                stickyNav.classList.add('hiding');
                setTimeout(() => {
                    stickyNav.style.display = 'none';
                    $el.querySelector('.popup-content').focus();
                }, 300);
            } else {
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('height');
                document.body.style.removeProperty('position');
                document.body.style.removeProperty('width');
                stickyNav.style.display = '';
                stickyNav.style.zIndex = '999'; // Ensure z-index is set back to 999
                setTimeout(() => {
                    stickyNav.classList.remove('hiding');
                }, 50);
                let savedPosition = parseInt(document.cookie.replace(/(?:(?:^|.*;\s*)scrollPosition\s*\=\s*([^;]*).*$)|^.*$/, '$1'));
                if (!isNaN(savedPosition)) {
                    window.scrollTo({ top: savedPosition, behavior: 'smooth' });
                }
            }
        })
    "
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    id="{{ $id }}"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <div
        x-show="show"
        class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
    >
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 popup-overlay"
            @click.self="show = false"
            aria-hidden="true"
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $maxWidth }} popup-content"
            @click.away="show = false"
        >
            {{ $slot }}
        </div>
    </div>
</div>
